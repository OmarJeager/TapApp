<?php

namespace App\Http\Controllers;

use App\Models\ChecklistQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ChecklistQuestionController extends Controller
{
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
            $q->where('variant', $request->variant);
        });

        $query->when($request->filled('is_active'), function ($q) use ($request) {
            $q->where('is_active', $request->is_active === '1');
        });

        $questions = $query
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        $stats = [
            'total' => ChecklistQuestion::count(),
            'active' => ChecklistQuestion::where('is_active', true)->count(),
            'hidden' => ChecklistQuestion::where('is_active', false)->count(),
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'questions' => $questions,
                'stats' => $stats,
            ]);
        }

        return view('admin.questions.index', compact('questions', 'stats'));
    }

    private function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(['PNL', 'TRQ', 'TST'])],
            'frequency' => ['nullable', 'integer', 'min:1'],
            'question_text' => ['required', 'string', 'max:1000'],
            'order' => ['nullable', 'integer', 'min:0'],
            'variant' => ['nullable', 'string', Rule::in(['normal', 'HV'])],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    private function normalize(array $data): array
    {
        $data['variant'] = $data['variant'] ?: 'normal';
        $data['frequency'] = $data['frequency'] ?? null;
        $data['is_active'] = $data['is_active'] ?? true;

        return $data;
    }

    public function store(Request $request)
    {
        $data = $this->normalize($request->validate($this->rules()));

        if (!isset($data['order'])) {
            $data['order'] = ((int) ChecklistQuestion::where('type', $data['type'])
                ->where('frequency', $data['frequency'])
                ->where('variant', $data['variant'])
                ->max('order')) + 1;
        }

        $question = ChecklistQuestion::create($data);

        return response()->json([
            'message' => 'Question created successfully.',
            'question' => $question,
        ], 201);
    }

    public function update(Request $request, ChecklistQuestion $question)
    {
        $data = $this->normalize($request->validate($this->rules()));

        $question->update($data);

        return response()->json([
            'message' => 'Question updated successfully.',
            'question' => $question->fresh(),
        ]);
    }

    public function toggle(ChecklistQuestion $question)
    {
        $question->update([
            'is_active' => !$question->is_active,
        ]);

        return response()->json([
            'message' => $question->is_active
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

    public function bulk(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(['PNL', 'TRQ', 'TST'])],
            'frequency' => ['nullable', 'integer', 'min:1'],
            'variant' => ['nullable', Rule::in(['normal', 'HV'])],
            'mode' => ['required', Rule::in(['append', 'replace'])],
            'questions_text' => ['required', 'string', 'max:100000'],
            'is_active' => ['required', 'boolean'],
        ]);

        $variant = $data['variant'] ?: 'normal';

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

        $created = DB::transaction(function () use (
            $data, $variant, $lines
        ) {
            $scope = ChecklistQuestion::query()
                ->where('type', $data['type'])
                ->where('frequency', $data['frequency'])
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

            $nextOrder = ((int) ChecklistQuestion::query()
                ->where('type', $data['type'])
                ->where('frequency', $data['frequency'])
                ->where('variant', $variant)
                ->max('order')) + 1;

            $questions = [];

            foreach ($lines as $index => $line) {
                $questions[] = [
                    'type' => $data['type'],
                    'frequency' => $data['frequency'],
                    'variant' => $variant,
                    'question_text' => $line,
                    'order' => $nextOrder + $index,
                    'is_active' => $data['is_active'],
                    'created_at' => now(),
                    'updated_at' => now(),
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
}
