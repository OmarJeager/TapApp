<?php

namespace App\Http\Controllers;

use App\Models\PpmChecklist;
use App\Models\PpmChecklistAnswer;
use App\Models\PpmRecord;
use App\Models\ChecklistQuestion;
use App\Models\PpmChecklistQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PnlChecklistController extends Controller
{
    /**
     * Return active PNL questions.
     *
     * GET /ppm-records/{ppmRecord}/pnl-questions
     */
    public function pnlQuestions(PpmRecord $ppmRecord)
    {
        $questions = ChecklistQuestion::where('type', 'PNL')
            ->where('is_active', true)
            ->orderBy('order')
            ->get([
                'id',
                'question_text',
                'order'
            ]);

        return response()->json([
            'ppm_record' => $ppmRecord->only([
                'id',
                'ppm_id',
                'asset_id',
                'asset_description',
                'job_id',
                'week_due'
            ]),
            'questions' => $questions,
        ]);
    }

    /**
     * Show PNL form.
     *
     * GET /ppm-records/{ppmRecord}/pnl-form
     */
    public function createPnlForm(Request $request, PpmRecord $ppmRecord)
    {
        // Make sure this is a PNL asset.
        if (!str_starts_with(strtoupper($ppmRecord->asset_id ?? ''), 'PNL')) {
            return redirect()
                ->route('user.index')
                ->with('error', 'Invalid asset: this ppm_record is not a PNL asset.');
        }

        // Active PNL questions.
        $questions = ChecklistQuestion::where('type', 'PNL')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        // Existing checklist.
        $checklist = PpmChecklist::where('ppm_records_id', $ppmRecord->id)->first();

        // Existing answers.
        $answers = collect();

        if ($checklist) {
            $answers = $checklist
                ->answers()
                ->get()
                ->keyBy('checklist_question_id');
        }

        $editRequest = $checklist
            ? $checklist->editRequests()->latest('id')->first()
            : null;

        $isLocked = $checklist
            && $checklist->isQualityVerified()
            && ($editRequest?->status !== 'approved');

        return view('user.pnl.create', [
            'ppmRecord'   => $ppmRecord,
            'questions'   => $questions,
            'checklist'   => $checklist,
            'answers'     => $answers,
            'editRequest' => $editRequest,
            'isLocked'    => $isLocked,
            'autoShift'   => $request->query('shift'),
            'autoDate'    => $request->query('date'),
        ]);
    }

    /**
     * Save PNL checklist.
     *
     * POST /ppm-checklists/pnl
     */
    public function storePnl(Request $request)
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

            'answers'                          => ['required', 'array', 'min:1'],
            'answers.*.checklist_question_id'  => ['required', 'integer', 'exists:checklist_questions,id'],
            'answers.*.response'               => ['nullable', 'in:ok,not_ok'],
            'answers.*.comment'                => ['nullable', 'string'],
            'answers.*.dpn'                    => ['nullable', 'string'],
            'answers.*.observation'            => ['nullable', 'string'],

            // shift scheduling (carried to the next PNL)
            'shift'         => ['nullable', 'in:' . implode(',', array_keys(config('ppm.shifts')))],
            'schedule_date' => ['nullable', 'date'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Locked checklist check
        |--------------------------------------------------------------------------
        */
        $existingChecklist = PpmChecklist::where('ppm_records_id', $validated['ppm_records_id'])->first();
        $approvedRequest = null;

        if ($existingChecklist && $existingChecklist->isQualityVerified()) {
            $approvedRequest = $existingChecklist->editRequests()
                ->where('status', 'approved')
                ->latest('id')
                ->first();

            if (!$approvedRequest) {
                return back()->with('error', 'This checklist is verified by quality and locked. Request edit access from admin.');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Get PNL question IDs
        |--------------------------------------------------------------------------
        */
        $pnlQuestionIds = ChecklistQuestion::where('type', 'PNL')
            ->where('is_active', true)
            ->pluck('id')
            ->all();

        /*
        |--------------------------------------------------------------------------
        | Current PPM record
        |--------------------------------------------------------------------------
        */
        $currentRecord = PpmRecord::findOrFail($validated['ppm_records_id']);

        /*
        |--------------------------------------------------------------------------
        | Shift plan (which day / date this PNL belongs to)
        |--------------------------------------------------------------------------
        */
        $plan          = null;
        $mine          = null;
        $scheduledDate = null;

        if (!empty($validated['shift'])) {
            $plan          = $this->buildSchedule($currentRecord, $validated['shift'], $validated['schedule_date'] ?? null);
            $mine          = collect($plan['items'])->firstWhere('id', $currentRecord->id);
            $scheduledDate = $mine['iso'] ?? null;
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate time
        |--------------------------------------------------------------------------
        */
        $startTime        = $validated['start_time'] ?? null;
        $endTime          = $validated['end_time'] ?? null;
        $totalTimeMinutes = $validated['total_time_minutes'] ?? null;

        // start given but no end -> end = start + est_resource_time
        if ($startTime && !$endTime) {
            $estMinutes = $this->parseMinutes($currentRecord->est_resource_time);

            if ($estMinutes > 0) {
                $endTime = Carbon::createFromFormat('H:i', $startTime)
                    ->addMinutes($estMinutes)
                    ->format('H:i');
            }
        }

        // total time (handles crossing midnight for the night shift)
        if (is_null($totalTimeMinutes) && $startTime && $endTime) {
            $startMin = $this->timeToMinutes($startTime);
            $endMin   = $this->timeToMinutes($endTime);

            if ($endMin < $startMin) {
                $endMin += 1440;
            }

            $totalTimeMinutes = $endMin - $startMin;
        }

        /*
        |--------------------------------------------------------------------------
        | Save checklist
        |--------------------------------------------------------------------------
        */
        $checklist = DB::transaction(function () use (
            $validated,
            $pnlQuestionIds,
            $startTime,
            $endTime,
            $totalTimeMinutes,
            $scheduledDate
        ) {
            $checklist = PpmChecklist::updateOrCreate(
                [
                    'ppm_records_id' => $validated['ppm_records_id'],
                ],
                [
                    'start_time'         => $startTime,
                    'end_time'           => $endTime,
                    'total_time_minutes' => $totalTimeMinutes,
                    'scheduled_date'     => $scheduledDate,

                    'completed_by_matricule' => $validated['completed_by_matricule'],
                    'completed_at'           => now()->toDateString(),

                    'verified_by_matricule' => $validated['verified_by_matricule'] ?? null,
                    'verified_at'           => $validated['verified_at'] ?? null,

                    'verified_by_quality_matricule' => $validated['verified_by_quality_matricule'] ?? null,
                    'verified_quality_at'           => $validated['verified_quality_at'] ?? null,
                ]
            );

            // PNL question snapshots (only on first creation)
            if ($checklist->wasRecentlyCreated) {
                $pnlQuestions = ChecklistQuestion::where('type', 'PNL')
                    ->where('is_active', true)
                    ->orderBy('order')
                    ->get();

                foreach ($pnlQuestions as $question) {
                    PpmChecklistQuestion::create([
                        'ppm_checklist_id'      => $checklist->id,
                        'checklist_question_id' => $question->id,

                        'type'          => $question->type,
                        'frequency'     => $question->frequency,
                        'question_text' => $question->question_text,
                        'order'         => $question->order,
                        'variant'       => $question->variant,
                    ]);
                }
            }

            // Save answers
            foreach ($validated['answers'] as $answer) {
                $questionId = (int) $answer['checklist_question_id'];

                // Only PNL questions
                if (!in_array($questionId, $pnlQuestionIds, true)) {
                    continue;
                }

                PpmChecklistAnswer::updateOrCreate(
                    [
                        'ppm_checklist_id'      => $checklist->id,
                        'checklist_question_id' => $questionId,
                    ],
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

        // Mark the approved edit request as used
        if ($approvedRequest) {
            $approvedRequest->update(['status' => 'used']);
        }

        /*
        |--------------------------------------------------------------------------
        | JSON response
        |--------------------------------------------------------------------------
        */
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'PNL checklist saved successfully.',
                'data'    => $checklist->load([
                    'answers.question',
                    'ppmRecord',
                ]),
            ], 201);
        }

        /*
        |--------------------------------------------------------------------------
        | Find next PNL record (same week_due)
        |--------------------------------------------------------------------------
        */
        $nextPnlRecord = PpmRecord::where('week_due', $currentRecord->week_due)
            ->where('asset_id', 'like', 'PNL%')
            ->where('id', '>', $currentRecord->id)
            ->orderBy('id', 'asc')
            ->first();

        if ($nextPnlRecord) {
            $message = 'Checklist saved. Moving to the next PNL.';

            // shift finished -> the next PNL starts on the next day
            if ($plan && $mine) {
                $next = collect($plan['items'])->firstWhere('id', $nextPnlRecord->id);

                if ($next && $next['day'] > $mine['day']) {
                    $message = "Checklist saved. Shift finished: next PNL moves to Day {$next['day']} ({$next['date']}) at {$next['start']}.";
                }
            }

            return redirect()
                ->route('ppm-checklists.pnl.create', [
                    'ppmRecord' => $nextPnlRecord,
                    'shift'     => $validated['shift'] ?? null,          // null values are dropped from the URL
                    'date'      => $validated['schedule_date'] ?? null,
                ])
                ->with('status', $message);
        }

        // No more PNL records for this week
        return redirect()
            ->route('user.index')
            ->with('status', 'All PNL checklists for this week have been completed.');
    }

    /**
     * Request edit access for a locked checklist.
     */
    public function requestEdit(Request $request, PpmRecord $ppmRecord)
    {
        $data = $request->validate([
            'request_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $checklist = PpmChecklist::where('ppm_records_id', $ppmRecord->id)->firstOrFail();

        $hasPending = $checklist->editRequests()->where('status', 'pending')->exists();

        if ($hasPending) {
            return back()->with('error', 'You already have a pending request.');
        }

        $checklist->editRequests()->create([
            'requested_by_matricule' => Auth::user()->matricule,
            'request_reason'         => $data['request_reason'] ?? null,
            'status'                 => 'pending',
        ]);

        return back()->with('status', 'Edit request sent to admin.');
    }

    /**
     * Scan an asset and open the right record.
     */
    public function scan(Request $request)
    {
        $data = $request->validate([
            'asset_id' => ['required', 'string', 'max:50'],
        ]);

        $assetId = strtoupper(trim($data['asset_id']));

        // ISO year + week, e.g. 202641 (same format as week_due)
        $currentWeek = (int) now()->format('oW');

        $base = PpmRecord::whereRaw('UPPER(TRIM(asset_id)) = ?', [$assetId]);

        $record =
            // 1) oldest pending (no checklist yet) record that is due now or overdue
            (clone $base)->doesntHave('checklist')
                ->where('week_due', '<=', $currentWeek)
                ->orderBy('week_due')
                ->first()
            // 2) otherwise the next upcoming pending record
            ?? (clone $base)->doesntHave('checklist')
                ->orderBy('week_due')
                ->first()
            // 3) otherwise the most recent one (already done, opens for viewing)
            ?? (clone $base)->orderByDesc('week_due')->first();

        if (!$record) {
            return back()->with('error', "Asset {$assetId} not found.");
        }

        // PNL assets go to the PNL create page, others to the generic form
        return $record->asset_prefix === 'pnl'
            ? redirect()->route('ppm-checklists.pnl.create', $record)
            : redirect()->route('ppm-records.form', $record);
    }

    /**
     * Calculate the shift schedule for all PNL of the same week_due.
     *
     * GET /ppm-checklists/pnl/{ppmRecord}/schedule?shift=shift1&date=2026-10-10
     */
    public function schedule(Request $request, PpmRecord $ppmRecord)
    {
        $data = $request->validate([
            'shift' => ['required', 'in:' . implode(',', array_keys(config('ppm.shifts')))],
            'date'  => ['nullable', 'date'],
        ]);

        return response()->json(
            $this->buildSchedule($ppmRecord, $data['shift'], $data['date'] ?? null)
        );
    }

    /**
     * Build the schedule: PNL after PNL inside the shift.
     * When a PNL does not fit before the shift end, it goes to the next day at shift start.
     */
    private function buildSchedule(PpmRecord $ppmRecord, string $shiftKey, ?string $date): array
    {
        $shift = config('ppm.shifts')[$shiftKey];

        $fmt = fn (int $min) => sprintf('%02d:%02d', intdiv($min % 1440, 60), $min % 60);

        $shiftStart  = $this->timeToMinutes($shift['start']);
        $shiftLength = (($this->timeToMinutes($shift['end']) - $shiftStart) + 1440) % 1440;

        if ($shiftLength === 0) {
            $shiftLength = 1440;
        }

        $firstDay = Carbon::parse($date ?? now()->toDateString());

        // same ordering used by the "next PNL" logic
        $records = PpmRecord::where('week_due', $ppmRecord->week_due)
            ->where('asset_id', 'like', 'PNL%')
            ->orderBy('id')
            ->get();

        $cursor = 0;   // minutes used inside the current day of the shift
        $day    = 0;
        $items  = [];

        foreach ($records as $rec) {
            // est_resource_time is a string column -> parse it
            $duration = $this->parseMinutes($rec->est_resource_time);

            if ($duration <= 0) {
                $duration = (int) config('ppm.default_pnl_minutes', 30);
            }

            $duration = max(1, min($duration, $shiftLength));

            // does not fit before shift end -> next day, shift start
            if ($cursor + $duration > $shiftLength) {
                $day++;
                $cursor = 0;
            }

            $d = $firstDay->copy()->addDays($day);

            $items[] = [
                'id'       => $rec->id,
                'asset_id' => $rec->asset_id,
                'day'      => $day + 1,
                'date'     => $d->format('D d M'),
                'iso'      => $d->toDateString(),
                'start'    => $fmt($shiftStart + $cursor),
                'end'      => $fmt($shiftStart + $cursor + $duration),
                'minutes'  => $duration,
                'current'  => $rec->id === $ppmRecord->id,
            ];

            $cursor += $duration;
        }

        return [
            'shift' => [
                'label' => $shift['label'],
                'start' => $shift['start'],
                'end'   => $shift['end'],
            ],
            'days'  => $day + 1,
            'items' => $items,
        ];
    }

    /**
     * "HH:MM" -> minutes since midnight.
     */
    private function timeToMinutes(string $time): int
    {
        [$h, $m] = array_map('intval', explode(':', $time));

        return $h * 60 + $m;
    }

    /**
     * Convert est_resource_time (string) to minutes.
     *
     * Supports: "30", "30 min", "0:30", "01:30", "01:30:00",
     *           "1h 30m", "1h", "0.5" (hours), "0,5" (hours)
     */
    private function parseMinutes($value): int
    {
        $v = strtolower(trim((string) $value));

        if ($v === '') {
            return 0;
        }

        // 1:30 or 01:30:00 -> hours:minutes
        if (preg_match('/^(\d+):(\d{1,2})(?::\d{1,2})?$/', $v, $m)) {
            return ((int) $m[1] * 60) + (int) $m[2];
        }

        // 1h 30m, 1h, 45m, 45 min, 45 mins, 45 minutes
        if (preg_match('/^(?:(\d+(?:[.,]\d+)?)\s*h(?:ours?|rs?)?)?\s*(?:(\d+)\s*m(?:in(?:ute)?s?)?)?$/', $v, $m)
            && (($m[1] ?? '') !== '' || ($m[2] ?? '') !== '')) {
            $h = (float) str_replace(',', '.', $m[1] ?? 0);

            return (int) round($h * 60) + (int) ($m[2] ?? 0);
        }

        // decimal like 0.5 or 1,5 -> hours
        if (preg_match('/^\d+[.,]\d+$/', $v)) {
            return (int) round((float) str_replace(',', '.', $v) * 60);
        }

        // plain number -> minutes
        if (preg_match('/^\d+$/', $v)) {
            return (int) $v;
        }

        return 0;
    }
}