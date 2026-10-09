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

class TstFreauencyFourHvController extends Controller
{
    /** Active TST / frequency 4 / variant HV questions. */
    private function questionsQuery()
    {
        return ChecklistQuestion::where('type', 'TST')
            ->where('frequency', 4)
            ->where('variant', 'HV')
            ->where('is_active', true)
            ->orderBy('variant')
            ->orderBy('order');
    }

    /**
     * GET /ppm-records/{ppmRecord}/tst-frequency-4-hv
     */
    public function frequency4Hv(PpmRecord $ppmRecord)
    {
        $questions = $this->questionsQuery()->get();

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

        return view('user.tst.Frequency4hv.Frequency4hv', compact(
            'ppmRecord', 'questions', 'checklist', 'answers', 'editRequest', 'isLocked'
        ));
    }

    /**
     * POST /tst-frequency-4-hv
     */
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

            'answers'                           => ['required', 'array', 'min:1'],
            'answers.*.checklist_question_id'   => ['required', 'integer', 'exists:checklist_questions,id'],
            'answers.*.response'                => ['nullable', 'in:ok,not_ok'],
            'answers.*.comment'                 => ['nullable', 'string'],
            'answers.*.dpn'                     => ['nullable', 'string'],
            'answers.*.observation'             => ['nullable', 'string'],
        ]);

        /* ---------- Lock check ---------- */
        $existing = PpmChecklist::where('ppm_records_id', $validated['ppm_records_id'])->first();
        $approvedRequest = null;

        if ($existing && $existing->isQualityVerified()) {
            $approvedRequest = $existing->editRequests()
                ->where('status', 'approved')
                ->latest('id')
                ->first();

            if (!$approvedRequest) {
                return back()->with('error', 'This checklist is verified by quality and locked. Request edit access from admin.');
            }
        }

        $questionIds   = $this->questionsQuery()->pluck('id')->all();
        $currentRecord = PpmRecord::findOrFail($validated['ppm_records_id']);

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
        $checklist = DB::transaction(function () use ($validated, $questionIds, $startTime, $endTime, $total) {

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
                foreach ($this->questionsQuery()->get() as $q) {
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
                    continue; // ignore questions that are not TST freq 4 HV
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
                'message' => 'TST checklist saved successfully.',
                'data'    => $checklist->load(['answers.question', 'ppmRecord']),
            ], 201);
        }

        /* ---------- Next TST HV record, same week ---------- */
        $next = PpmRecord::where('week_due', $currentRecord->week_due)
            ->where('asset_id', 'like', 'TST%')
            ->where('id', '>', $currentRecord->id)
            // only if active TST / freq 4 / HV questions exist in checklist_questions
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('checklist_questions')
                    ->where('type', 'TST')
                    ->where('frequency', 4)
                    ->where('variant', 'HV')
                    ->where('is_active', true);
            })
            ->orderBy('id')
            ->first();

        if ($next) {
            return redirect()
                ->route('ppm-checklists.tst.frequency4hv', $next)
                ->with('status', 'Checklist saved. Moving to the next TST HV.');
        }

        return redirect()
            ->route('user.index')
            ->with('status', 'All TST checklists for this week have been completed.');
    }

    /**
     * POST /ppm-records/{ppmRecord}/tst-frequency-4-hv/edit-request
     */
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
