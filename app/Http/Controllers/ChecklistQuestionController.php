<?php

namespace App\Http\Controllers;

use App\Models\ChecklistQuestion;
use Illuminate\Http\Request;

class ChecklistQuestionController extends Controller
{
    /**
     * List questions with filters (search, type, frequency, variant, is_active).
     * The same view is fetched by AJAX when a filter changes, so no extra partial is needed.
     */
    public function showQuestions(Request $request)
    {
        $questions = ChecklistQuestion::query()
            ->when($request->filled('search'), fn ($q) =>
                $q->where('question_text', 'like', '%' . $request->search . '%'))
            ->when($request->filled('type'), fn ($q) =>
                $q->where('type', $request->type))
            ->when($request->filled('frequency'), fn ($q) =>
                $q->where('frequency', $request->frequency))
            ->when($request->filled('variant'), fn ($q) =>
                $q->where('variant', $request->variant))
            ->when($request->filled('is_active'), fn ($q) =>
                $q->where('is_active', $request->is_active === '1'))
            ->orderBy('order')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        // Distinct values used to build the filter dropdowns and form suggestions
        $types       = ChecklistQuestion::whereNotNull('type')->distinct()->orderBy('type')->pluck('type');
        $frequencies = ChecklistQuestion::whereNotNull('frequency')->distinct()->orderBy('frequency')->pluck('frequency');
        $variants    = ChecklistQuestion::whereNotNull('variant')->distinct()->orderBy('variant')->pluck('variant');

        $stats = [
            'total'  => ChecklistQuestion::count(),
            'active' => ChecklistQuestion::where('is_active', true)->count(),
            'hidden' => ChecklistQuestion::where('is_active', false)->count(),
        ];

        return view('admin.questions.index', compact(
            'questions', 'types', 'frequencies', 'variants', 'stats'
        ));
    }

    /** Add a question */
    public function store(Request $request)
    {
        $data = $this->validated($request);

        if (!isset($data['order'])) {
            $data['order'] = (ChecklistQuestion::max('order') ?? 0) + 1;
        }

        $question = ChecklistQuestion::create($data);

        return response()->json([
            'message'  => 'Question added.',
            'question' => $question,
        ], 201);
    }

    /** Edit a question */
    public function update(Request $request, ChecklistQuestion $question)
    {
        $question->update($this->validated($request));

        return response()->json([
            'message'  => 'Question updated.',
            'question' => $question->fresh(),
        ]);
    }

    /** Hide / show a question */
    public function toggle(ChecklistQuestion $question)
    {
        $question->update(['is_active' => !$question->is_active]);

        return response()->json([
            'message'   => $question->is_active ? 'Question is now visible.' : 'Question hidden.',
            'is_active' => $question->is_active,
        ]);
    }

    /** Remove a question */
    public function destroy(ChecklistQuestion $question)
    {
        $question->delete();

        return response()->json(['message' => 'Question removed.']);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'type'          => ['required', 'string', 'max:100'],
           //frequency'     => ['required', 'string', 'max:100'],
            'question_text' => ['required', 'string', 'max:1000'],
            'order'         => ['nullable', 'integer', 'min:0'],
            'variant'       => ['nullable', 'string', 'max:100'],
            'is_active'     => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
