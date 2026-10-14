<?php

namespace App\Http\Controllers;

use App\Models\ChecklistQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ChecklistQuestionController extends Controller
{
    /** Types that always exist, even before any question is saved for them. */
    private const DEFAULT_TYPES = ['PNL', 'TRQ', 'TST'];

    /* ------------------------------------------------------------------
     |  Index / listing
     * ------------------------------------------------------------------ */

    public function showQuestions(Request $request)
    {
        $query = ChecklistQuestion::query();

        $query->when($request->filled('search'), function ($q) use ($request) {
            $q->where('question_text', 'like', '%' . $request->search . '%');
        });

        $query->when($request->filled('type'), function ($q) use ($request) {
            $q->where('type', $request->type);
        });

        $query->when($request->filled('frequency'), function ($q) use ($request) {
            $q->where('frequency', $request->frequency);
        });

        $query->when($request->filled('variant'), function ($q) use ($request) {
            if (in_array($request->variant, ['none', 'null', 'normal'], true)) {
                $q->whereNull('variant');
            } else {
                $q->where('variant', $request->variant);
            }
        });

        $query->when($request->filled('is_active'), function ($q) use ($request) {
            $q->where('is_active', $request->is_active === '1');
        });

        $questions = $query
            ->orderBy('type')
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $stats    = $this->stats();
        $types    = $this->existingTypes();
        $variants = $this->existingVariants();

        if ($request->expectsJson()) {
            return response()->json([
                'questions' => $questions,
                'stats'     => $stats,
                'types'     => $types,
                'variants'  => $variants,
            ]);
        }

        return view('admin.questions.index', compact('questions', 'stats', 'types', 'variants'));
    }

    public function create()
    {
        $stats = $this->stats();

        return view('admin.questions.create', compact('stats'));
    }

    /* ------------------------------------------------------------------
     |  Create Equipment page + save
     * ------------------------------------------------------------------ */

    public function createEquipment()
    {
        $stats    = $this->stats();
        $types    = $this->existingTypes();
        $variants = $this->existingVariants();

        return view('admin.questions.createquipment', compact('stats', 'types', 'variants'));
    }

    public function storeEquipment(Request $request)
    {
        $this->cleanVariant($request);

        $data = $request->validate([
            'type'                      => $this->typeRule(),
            'frequency'                 => ['nullable', 'integer', 'min:1'],
            'variant'                   => $this->variantRule(),
            'is_active'                 => ['required', 'boolean'],
            'questions'                 => ['required', 'array', 'min:1', 'max:500'],
            'questions.*.question_text' => ['required', 'string', 'max:1000'],
            'questions.*.is_active'     => ['sometimes', 'boolean'],
        ], $this->messages() + [
            'questions.required'                 => 'Add at least one question.',
            'questions.*.question_text.required' => 'Every question needs text.',
        ]);

        $frequency = $data['frequency'] ?? null;
        $variant   = $data['variant'] ?? null;

        $count = DB::transaction(function () use ($data, $frequency, $variant) {
            $nextOrder = $this->nextOrder($data['type'], $frequency, $variant);

            $rows = [];

            foreach (array_values($data['questions']) as $i => $q) {
                $rows[] = [
                    'type'          => $data['type'],
                    'frequency'     => $frequency,
                    'variant'       => $variant,
                    'question_text' => trim($q['question_text']),
                    'order'         => $nextOrder + $i,
                    'is_active'     => $q['is_active'] ?? $data['is_active'],
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }

            ChecklistQuestion::insert($rows);

            return count($rows);
        });

        return response()->json([
            'message'  => "Equipment type {$data['type']} saved with {$count} question(s).",
            'created'  => $count,
            'redirect' => route('admin.checklist-questions.index'),
        ], 201);
    }

    /* ------------------------------------------------------------------
     |  Single question CRUD
     * ------------------------------------------------------------------ */

    public function store(Request $request)
    {
        $this->cleanVariant($request);
        $data = $this->normalize($request->validate($this->rules(), $this->messages()));

        if (!isset($data['order'])) {
            $data['order'] = $this->nextOrder($data['type'], $data['frequency'], $data['variant']);
        }

        $question = ChecklistQuestion::create($data);

        return response()->json([
            'message'  => 'Question created successfully.',
            'question' => $question,
        ], 201);
    }

    public function update(Request $request, ChecklistQuestion $question)
    {
        $this->cleanVariant($request);
        $data = $this->normalize($request->validate($this->rules(), $this->messages()));

        $question->update($data);

        return response()->json([
            'message'  => 'Question updated successfully.',
            'question' => $question->fresh(),
        ]);
    }

    public function toggle(ChecklistQuestion $question)
    {
        $question->update([
            'is_active' => !$question->is_active,
        ]);

        return response()->json([
            'message'  => $question->is_active
                ? 'Question activated.'
                : 'Question hidden.',
            'question' => $question->fresh(),
        ]);
    }

    public function destroy(ChecklistQuestion $question)
    {
        if ($question->answers()->exists()) {
            return response()->json([
                'message' => 'This question has saved answers. Hide it instead to preserve checklist history.',
            ], 422);
        }

        $question->delete();

        return response()->json([
            'message' => 'Question deleted.',
        ]);
    }

    /* ------------------------------------------------------------------
     |  Bulk import
     * ------------------------------------------------------------------ */

    public function bulk(Request $request)
    {
        $this->cleanVariant($request);

        $data = $request->validate([
            'type'           => $this->typeRule(),
            'frequency'      => ['nullable', 'integer', 'min:1'],
            'variant'        => $this->variantRule(),
            'mode'           => ['required', Rule::in(['append', 'replace'])],
            'questions_text' => ['required', 'string', 'max:100000'],
            'is_active'      => ['required', 'boolean'],
        ], $this->messages());

        $variant   = $data['variant'] ?: null;
        $frequency = $data['frequency'] ?? null;

        $lines = preg_split('/\r\n|\r|\n/', $data['questions_text']);

        $lines = array_values(array_filter(
            array_map(fn ($line) => trim($line), $lines),
            fn ($line) => $line !== ''
        ));

        if (count($lines) === 0) {
            return response()->json([
                'message' => 'Paste at least one question.',
            ], 422);
        }

        if (count($lines) > 500) {
            return response()->json([
                'message' => 'You can import a maximum of 500 questions at once.',
            ], 422);
        }

        $created = DB::transaction(function () use ($data, $variant, $frequency, $lines) {
            $scope = ChecklistQuestion::query()
                ->where('type', $data['type'])
                ->where('frequency', $frequency)
                ->where('variant', $variant);

            if ($data['mode'] === 'replace') {
                $existing = (clone $scope)->get();

                foreach ($existing as $question) {
                    if ($question->answers()->exists()) {
                        abort(response()->json([
                            'message' =>
                                'Replacement cancelled: existing questions have saved answers. ' .
                                'Hide old questions or use Append to preserve historical checklists.',
                        ], 422));
                    }
                }

                (clone $scope)->delete();
            }

            $nextOrder = $this->nextOrder($data['type'], $frequency, $variant);

            $questions = [];

            foreach ($lines as $index => $line) {
                $questions[] = [
                    'type'          => $data['type'],
                    'frequency'     => $frequency,
                    'variant'       => $variant,
                    'question_text' => $line,
                    'order'         => $nextOrder + $index,
                    'is_active'     => $data['is_active'],
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }

            ChecklistQuestion::insert($questions);

            return count($questions);
        });

        return response()->json([
            'message' => "{$created} question(s) imported successfully.",
            'created' => $created,
        ]);
    }

    /* ------------------------------------------------------------------
     |  Helpers
     * ------------------------------------------------------------------ */

    private function typeRule(): array
    {
        // 2-10 chars: A-Z, 0-9, dash, underscore (e.g. PNL, TRQ, TST, UPS, GEN_2)
        return ['required', 'string', 'regex:/^[A-Z0-9_-]{2,10}$/'];
    }

    private function variantRule(): array
    {
        // optional, 1-10 chars: A-Z, 0-9, dash, underscore (e.g. HV, LV, MV, OUTDOOR)
        return ['nullable', 'string', 'regex:/^[A-Z0-9_-]{1,10}$/'];
    }

    private function rules(): array
    {
        return [
            'type'          => $this->typeRule(),
            'frequency'     => ['nullable', 'integer', 'min:1'],
            'question_text' => ['required', 'string', 'max:1000'],
            'order'         => ['nullable', 'integer', 'min:0'],
            'variant'       => $this->variantRule(),
            'is_active'     => ['sometimes', 'boolean'],
        ];
    }

    private function messages(): array
    {
        return [
            'type.regex'    => 'Type must be 2-10 characters: letters, numbers, dash or underscore.',
            'variant.regex' => 'Variant must be 1-10 characters: letters, numbers, dash or underscore.',
        ];
    }

    private function normalize(array $data): array
    {
        $data['variant']   = $data['variant'] ?: null;
        $data['frequency'] = $data['frequency'] ?? null;
        $data['is_active'] = $data['is_active'] ?? true;

        return $data;
    }

    private function nextOrder(string $type, $frequency, $variant): int
    {
        return ((int) ChecklistQuestion::query()
            ->where('type', $type)
            ->where('frequency', $frequency)
            ->where('variant', $variant)
            ->max('order')) + 1;
    }

    private function cleanVariant(Request $request): void
    {
        $variant = $request->input('variant');

        if ($variant === null || in_array(strtolower(trim((string) $variant)), ['', 'null', 'none', 'normal'], true)) {
            $request->merge(['variant' => null]);
        } else {
            $request->merge(['variant' => strtoupper(trim((string) $variant))]);
        }

        if ($request->filled('type')) {
            $request->merge(['type' => strtoupper(trim((string) $request->input('type')))]);
        }
    }

    private function stats(): array
    {
        return [
            'total'  => ChecklistQuestion::count(),
            'active' => ChecklistQuestion::where('is_active', true)->count(),
            'hidden' => ChecklistQuestion::where('is_active', false)->count(),
        ];
    }

    /** Default types + every type that exists in the database. */
    private function existingTypes()
    {
        return ChecklistQuestion::query()
            ->select('type')
            ->distinct()
            ->pluck('type')
            ->merge(self::DEFAULT_TYPES)
            ->unique()
            ->sort()
            ->values();
    }

    private function existingVariants()
    {
        return ChecklistQuestion::query()
            ->whereNotNull('variant')
            ->select('variant')
            ->distinct()
            ->orderBy('variant')
            ->pluck('variant');
    }
}
