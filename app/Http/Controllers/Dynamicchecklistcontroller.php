<?php

namespace App\Http\Controllers;

use App\Models\ChecklistQuestion;
use App\Models\PpmChecklist;
use App\Models\PpmChecklistAnswer;
use App\Models\PpmChecklistQuestion;
use App\Models\PpmRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * One checklist page for EVERY equipment type created in Checklist Studio.
 * The type, frequency and variant are read from the PPM record itself,
 * so a new type (e.g. UPS) works without writing a new controller or view.
 */
class DynamicChecklistController extends Controller
{
    /* ------------------------------------------------------------------
     |  Type matching (also used by PpmRecordController::form)
     * ------------------------------------------------------------------ */

    /** Returns the longest known type that the asset id starts with, or null. */
    public static function matchType(?string $assetId): ?string
    {
        $assetId = strtoupper(trim((string) $assetId));

        if ($assetId === '') {
            return null;
        }

        return ChecklistQuestion::query()
            ->select('type')->distinct()->pluck('type')
            ->filter(fn ($type) => str_starts_with($assetId, strtoupper($type)))
            ->sortByDesc(fn ($type) => strlen($type))
            ->first();
    }

    /** Works out type / frequency / variant for a record and checks questions exist. */
    private function resolveGroup(PpmRecord $record): array
    {
        $type = self::matchType($record->asset_id);

        abort_if(!$type, 404, 'Unknown asset type: ' . $record->asset_id);

        $frequency = ($record->frequency === null || $record->frequency === '')
            ? null
            : (int) $record->frequency;

        // Variant: any saved variant (HV, LV...) found as a word in the description
        $description = strtoupper((string) $record->asset_description);
        $variant = null;

        $variants = ChecklistQuestion::where('type', $type)
            ->whereNotNull('variant')->distinct()->pluck('variant');

        foreach ($variants as $candidate) {
            $pattern = '/(?<![A-Z0-9])' . preg_quote(strtoupper($candidate), '/') . '(?![A-Z0-9])/';
            if (preg_match($pattern, $description)) {
                $variant = $candidate;
                break;
            }
        }

        $group = compact('type', 'frequency', 'variant');

        // Fall back to the plain group when the specific one has no questions
        if (!$this->questionsQuery($group)->exists() && $variant !== null) {
            $group['variant'] = null;
        }

        if (!$this->questionsQuery($group)->exists() && $frequency !== null) {
            $group['frequency'] = null;
        }

        abort_if(
            !$this->questionsQuery($group)->exists(),
            404,
            "No active {$type} questions for frequency " . ($frequency ?? 'null') . '. Add them in Checklist Studio.'
        );

        return $group;
    }

    private function questionsQuery(array $g)
    {
        $q = ChecklistQuestion::where('type', $g['type'])->where('is_active', true);

        $g['frequency'] === null ? $q->whereNull('frequency') : $q->where('frequency', $g['frequency']);
        $g['variant']   === null ? $q->whereNull('variant')   : $q->where('variant', $g['variant']);

        return $q->orderBy('order');
    }

    /* ------------------------------------------------------------------
     |  Pages
     * ------------------------------------------------------------------ */

    public function create(PpmRecord $ppmRecord)
    {
        $group     = $this->resolveGroup($ppmRecord);
        $questions = $this->questionsQuery($group)->get();

        $checklist = PpmChecklist::where('ppm_records_id', $ppmRecord->id)->first();

        $answers = $checklist
            ? $checklist->answers()->get()->keyBy('checklist_question_id')
            : collect();

        $editRequest = $checklist
            ? $checklist->editRequests()->latest('id')->first()
            : null;

        $isLocked = $checklist
            && $checklist->isQualityVerified()
            && ($editRequest?->status !== 'approved');

        return view('user.dynamic.checklist', compact(
            'ppmRecord', 'group', 'questions', 'checklist', 'answers', 'editRequest', 'isLocked'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ppm_records_id'                => ['required', 'exists:ppm_records,id'],
            'completed_by_matricule'        => ['required', 'exists:users,matricule'],
            'start_time'                    => ['nullable', 'date_format:H:i'],
            'end_time'                      => ['nullable', 'date_format:H:i'],
            'total_time_minutes'            => ['nullable', 'integer', 'min:0'],
            'verified_by_matricule'         => ['nullable', 'exists:users,matricule'],
            'verified_at'                   => ['nullable', 'date'],
            'verified_by_quality_matricule' => ['nullable', 'exists:users,matricule'],
            'verified_quality_at'           => ['nullable', 'date'],

            'answers'                         => ['required', 'array', 'min:1'],
            'answers.*.checklist_question_id' => ['required', 'integer', 'exists:checklist_questions,id'],
            'answers.*.response'              => ['nullable', 'in:ok,not_ok'],
            'answers.*.comment'               => ['nullable', 'string'],
            'answers.*.dpn'                   => ['nullable', 'string'],
            'answers.*.observation'           => ['nullable', 'string'],
        ]);

        $currentRecord = PpmRecord::findOrFail($validated['ppm_records_id']);
        $group         = $this->resolveGroup($currentRecord);

        /* ---------- Lock check ---------- */
        $existing = PpmChecklist::where('ppm_records_id', $currentRecord->id)->first();
        $approvedRequest = null;

        if ($existing && $existing->isQualityVerified()) {
            $approvedRequest = $existing->editRequests()
                ->where('status', 'approved')->latest('id')->first();

            if (!$approvedRequest) {
                return back()->with('error', 'This checklist is verified by quality and locked. Request edit access from admin.');
            }
        }

        $questionIds = $this->questionsQuery($group)->pluck('id')->all();

        /* ---------- Time ---------- */
        $startTime = $validated['start_time'] ?? null;
        $endTime   = $validated['end_time'] ?? null;
        $total     = $validated['total_time_minutes'] ?? null;

        if ($startTime && !$endTime) {
            $est = (int) ($currentRecord->est_resource_time ?? 0);
            if ($est > 0) {
                $endTime = Carbon::createFromFormat('H:i', $startTime)->addMinutes($est)->format('H:i');
            }
        }

        if (is_null($total) && $startTime && $endTime) {
            $total = Carbon::createFromFormat('H:i', $startTime)
                ->diffInMinutes(Carbon::createFromFormat('H:i', $endTime));
        }

        /* ---------- Save ---------- */
        $checklist = DB::transaction(function () use ($validated, $group, $questionIds, $startTime, $endTime, $total) {

            $checklist = PpmChecklist::updateOrCreate(
                ['ppm_records_id' => $validated['ppm_records_id']],
                [
                    'start_time'                    => $startTime,
                    'end_time'                      => $endTime,
                    'total_time_minutes'            => $total,
                    'completed_by_matricule'        => $validated['completed_by_matricule'],
                    'completed_at'                  => now()->toDateString(),
                    'verified_by_matricule'         => $validated['verified_by_matricule'] ?? null,
                    'verified_at'                   => $validated['verified_at'] ?? null,
                    'verified_by_quality_matricule' => $validated['verified_by_quality_matricule'] ?? null,
                    'verified_quality_at'           => $validated['verified_quality_at'] ?? null,
                ]
            );

            // Snapshot the questions the first time only
            if ($checklist->wasRecentlyCreated) {
                foreach ($this->questionsQuery($group)->get() as $q) {
                    PpmChecklistQuestion::create([
                        'ppm_checklist_id'      => $checklist->id,
                        'checklist_question_id' => $q->id,
                        'type'                  => $q->type,
                        'frequency'             => $q->frequency,
                        'question_text'         => $q->question_text,
                        'order'                 => $q->order,
                        'variant'               => $q->variant,
                    ]);
                }
            }

            foreach ($validated['answers'] as $answer) {
                $qid = (int) $answer['checklist_question_id'];

                if (!in_array($qid, $questionIds, true)) {
                    continue; // ignore questions from another group
                }

                PpmChecklistAnswer::updateOrCreate(
                    ['ppm_checklist_id' => $checklist->id, 'checklist_question_id' => $qid],
                    [
                        'response'    => $answer['response'] ?? null,
                        'comment'     => $answer['comment'] ?? null,
                        'dpn'         => $answer['dpn'] ?? null,
                        'observation' => $answer['observation'] ?? null,
                    ]
                );
            }

            return $checklist;
        });

        if ($approvedRequest) {
            $approvedRequest->update(['status' => 'used']);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "{$group['type']} checklist saved successfully.",
                'data'    => $checklist->load(['answers.question', 'ppmRecord']),
            ], 201);
        }

        /* ---------- Next record: same week, same type, same frequency ---------- */
        $next = PpmRecord::where('week_due', $currentRecord->week_due)
            ->where('asset_id', 'like', $group['type'] . '%')
            ->where('frequency', $currentRecord->frequency)
            ->where('id', '>', $currentRecord->id)
            ->orderBy('id')
            ->first();

        if ($next) {
            return redirect()
                ->route('ppm-checklists.dynamic.create', $next)
                ->with('status', "Checklist saved. Moving to the next {$group['type']}.");
        }

        return redirect()
            ->route('user.index')
            ->with('status', "All {$group['type']} checklists for this week have been completed.");
    }

    public function requestEdit(Request $request, PpmRecord $ppmRecord)
    {
        $data = $request->validate(['request_reason' => ['nullable', 'string', 'max:1000']]);

        $checklist = PpmChecklist::where('ppm_records_id', $ppmRecord->id)->firstOrFail();

        if ($checklist->editRequests()->where('status', 'pending')->exists()) {
            return back()->with('error', 'You already have a pending request.');
        }

        $checklist->editRequests()->create([
            'requested_by_matricule' => Auth::user()->matricule,
            'request_reason'         => $data['request_reason'] ?? null,
            'status'                 => 'pending',
        ]);

        return back()->with('status', 'Edit request sent to admin.');
    }
}
