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
    public function createPnlForm(PpmRecord $ppmRecord)
    {
        //dd($ppmRecord);
        /*
         * Make sure this is a PNL asset.
         */
        if (!str_starts_with(
            strtoupper($ppmRecord->asset_id ?? ''),
            'PNL'
        )) {
            return redirect()
                ->route('user.index')
                ->with(
                    'error',
                    'Invalid asset: this ppm_record is not a PNL asset.'
                );
        }

        /*
         * Get active PNL questions.
         */
        $questions = ChecklistQuestion::where('type', 'PNL')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        /*
         * Find existing checklist.
         */
        $checklist = PpmChecklist::where(
            'ppm_records_id',
            $ppmRecord->id
        )->first();

        /*
         * Get existing answers.
         */
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
]);
        /*
         * IMPORTANT:
         *
         * The variable sent to Blade is $ppmRecord.
         *
         * layouts.main will automatically use this variable
         * to display the PPM record card.
         */
        return view('user.pnl.create', [
            'ppmRecord' => $ppmRecord,
            'questions' => $questions,
            'checklist' => $checklist,
            'answers'   => $answers,
        ]);
    }

    /**
     * Show saved PNL checklist.
     *
     * GET /ppm-records/{ppmRecord}/pnl-checklist
     */
    public function storePnl(Request $request)
{
    $validated = $request->validate([
        'ppm_records_id' => [
            'required',
            'exists:ppm_records,id'
        ],

        'completed_by_matricule' => [
            'required',
            'exists:users,matricule'
        ],

        'start_time' => [
            'nullable',
            'date_format:H:i'
        ],

        'end_time' => [
            'nullable',
            'date_format:H:i'
        ],

        'total_time_minutes' => [
            'nullable',
            'integer',
            'min:0'
        ],

        'verified_by_matricule' => [
            'nullable',
            'exists:users,matricule'
        ],

        'verified_at' => [
            'nullable',
            'date'
        ],

        'verified_by_quality_matricule' => [
            'nullable',
            'exists:users,matricule'
        ],

        'verified_quality_at' => [
            'nullable',
            'date'
        ],

        'answers' => [
            'required',
            'array',
            'min:1'
        ],

        'answers.*.checklist_question_id' => [
            'required',
            'integer',
            'exists:checklist_questions,id'
        ],

        'answers.*.response' => [
            'nullable',
            'in:ok,not_ok'
        ],

        'answers.*.comment' => [
            'nullable',
            'string'
        ],

        'answers.*.dpn' => [
            'nullable',
            'string'
        ],

        'answers.*.observation' => [
            'nullable',
            'string'
        ],
    ]);
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
}    /*
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
    | Get current PPM record
    |--------------------------------------------------------------------------
    */

    $currentRecord = PpmRecord::findOrFail(
        $validated['ppm_records_id']
    );

    /*
    |--------------------------------------------------------------------------
    | Calculate time
    |--------------------------------------------------------------------------
    */

    $startTime = $validated['start_time'] ?? null;
    $endTime = $validated['end_time'] ?? null;
    $totalTimeMinutes = $validated['total_time_minutes'] ?? null;

    if ($startTime && !$endTime) {

        $estMinutes = (int) (
            $currentRecord->est_resource_time ?? 0
        );

        if ($estMinutes > 0) {

            $endTime = Carbon::createFromFormat(
                'H:i',
                $startTime
            )
            ->addMinutes($estMinutes)
            ->format('H:i');
        }
    }

    if (
        is_null($totalTimeMinutes)
        && $startTime
        && $endTime
    ) {

        $start = Carbon::createFromFormat(
            'H:i',
            $startTime
        );

        $end = Carbon::createFromFormat(
            'H:i',
            $endTime
        );

        $totalTimeMinutes = $start->diffInMinutes($end);
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
        $totalTimeMinutes
    ) {

        $checklist = PpmChecklist::updateOrCreate(
            [
                'ppm_records_id' =>
                    $validated['ppm_records_id']
            ],
            [
                'start_time' =>
                    $startTime,

                'end_time' =>
                    $endTime,

                'total_time_minutes' =>
                    $totalTimeMinutes,

                'completed_by_matricule' =>
                    $validated['completed_by_matricule'],

                'completed_at' =>
                    now()->toDateString(),

                'verified_by_matricule' =>
                    $validated['verified_by_matricule'] ?? null,

                'verified_at' =>
                    $validated['verified_at'] ?? null,

                'verified_by_quality_matricule' =>
                    $validated['verified_by_quality_matricule'] ?? null,

                'verified_quality_at' =>
                    $validated['verified_quality_at'] ?? null,
            ]
        );

        /*
|--------------------------------------------------------------------------
| Create PNL question snapshots
|--------------------------------------------------------------------------
*/

if ($checklist->wasRecentlyCreated) {

    $pnlQuestions = ChecklistQuestion::where('type', 'PNL')
        ->where('is_active', true)
        ->orderBy('order')
        ->get();

    foreach ($pnlQuestions as $question) {

        PpmChecklistQuestion::create([
            'ppm_checklist_id' => $checklist->id,
            'checklist_question_id' => $question->id,

            'type' => $question->type,
            'frequency' => $question->frequency,
            'question_text' => $question->question_text,
            'order' => $question->order,
            'variant' => $question->variant,
        ]);
    }
}
        /*
        |--------------------------------------------------------------------------
        | Save answers
        |--------------------------------------------------------------------------
        */

        foreach ($validated['answers'] as $answer) {

            $questionId =
                (int) $answer['checklist_question_id'];

            /*
            | Only PNL questions
            */

            if (!in_array(
                $questionId,
                $pnlQuestionIds,
                true
            )) {
                continue;
            }

            PpmChecklistAnswer::updateOrCreate(
                [
                    'ppm_checklist_id' =>
                        $checklist->id,

                    'checklist_question_id' =>
                        $questionId,
                ],
                [
                    'response' =>
                        $answer['response'] ?? null,

                    'comment' =>
                        $answer['comment'] ?? null,

                    'dpn' =>
                        $answer['dpn'] ?? null,

                    'observation' =>
                        $answer['observation'] ?? null,
                ]
            );
        }

        return $checklist;
    });


    // ✅ PASTE THE "USED" UPDATE HERE
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
            'message' =>
                'PNL checklist saved successfully.',

            'data' =>
                $checklist->load([
                    'answers.question',
                    'ppmRecord',
                ]),
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND NEXT PNL RECORD
    |
    | Same week_due
    |--------------------------------------------------------------------------
    */

    $nextPnlRecord = PpmRecord::where(
        'week_due',
        $currentRecord->week_due
    )
    ->where('asset_id', 'like', 'PNL%')
    ->where('id', '>', $currentRecord->id)
    ->orderBy('id', 'asc')
    ->first();

    /*
    |--------------------------------------------------------------------------
    | NEXT PNL EXISTS
    |--------------------------------------------------------------------------
    */

    if ($nextPnlRecord) {

        return redirect()
            ->route(
                'ppm-checklists.pnl.create',
                $nextPnlRecord
            )
            ->with(
                'status',
                'Checklist saved. Moving to the next PNL.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | NO MORE PNL RECORDS FOR THIS WEEK
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('user.index')
        ->with(
            'status',
            'All PNL checklists for this week have been completed.'
        );
}
public function requestEdit(Request $request, PpmRecord $ppmRecord)
{
    $data = $request->validate(['request_reason' => ['nullable', 'string', 'max:1000']]);

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
}
