
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PPM Maintenance Ticket</title>

    <style>
        @page {
            size: A4;
            margin: 10mm 11mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #222;
        }

        * { box-sizing: border-box; }

        .top {
            border-bottom: 1px solid #333;
            padding-bottom: 8px;
            margin-bottom: 7px;
        }

        .barcode {
            text-align: left;
            height: 42px;
        }

        .barcode svg {
            height: 35px;
            max-width: 240px;
        }

        .site {
            float: right;
            font-weight: bold;
            margin-top: 10px;
        }

        .jobline {
            font-size: 12px;
            font-weight: bold;
            margin: 4px 0;
        }

        .meta {
            width: 100%;
            border-collapse: collapse;
        }

        .meta td {
            padding: 3px 2px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            white-space: nowrap;
        }

        .separator {
            border-top: 1px solid #333;
            margin: 7px 0;
        }

        .service {
            width: 100%;
            border: 1px solid #555;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .service td {
            padding: 5px;
        }

        .details-title {
            margin-top: 17px;
            margin-bottom: 7px;
            font-weight: bold;
        }

        .questions {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .questions th {
            font-size: 8px;
            text-align: center;
            padding: 4px 2px;
        }

        .questions td {
            padding: 5px 2px;
            vertical-align: top;
        }

        .questions .number {
            width: 7%;
            text-align: left;
        }

        .questions .description {
            width: 68%;
        }

        .questions .answer {
            width: 12.5%;
            text-align: center;
            white-space: nowrap;
        }

        .mark {
            display: inline-block;
            min-width: 35px;
            border-bottom: 1px dashed #777;
            height: 12px;
        }

        .group td {
            padding-top: 8px;
            font-weight: bold;
        }

        .bottom {
            page-break-inside: avoid;
            margin-top: 15px;
        }

        .maintenance {
            border-top: 1px solid #555;
            padding-top: 8px;
        }

        .line {
            display: inline-block;
            border-bottom: 1px dashed #777;
            min-width: 190px;
            height: 15px;
        }

        .signoff {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .signoff td {
            padding: 5px 0;
        }

        .signoff .line-cell {
            border-bottom: 1px dashed #777;
        }

        .job-box {
            width: 265px;
            min-height: 175px;
            border: 1px solid #555;
            padding: 8px;
            margin: 12px 0 0 auto;
            page-break-inside: avoid;
        }

        .job-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .job-box td {
            padding: 3px 1px;
            vertical-align: top;
        }

        .job-box .key {
            width: 42%;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 8px;
        }
    </style>
</head>

<body>

@php
    $jobId = (string) ($record->job_id ?? '');

    $completedBy = $checklist?->completedBy;

    $completedDate = $checklist?->completed_at;

    $startTime = $checklist?->start_time;
    $finishTime = $checklist?->end_time;

    $totalMinutes = $checklist?->total_time_minutes;

    $formatDate = function ($value) {
        if (!$value) {
            return '';
        }

        try {
            return \Carbon\Carbon::parse($value)->format('d/m/Y');
        } catch (\Throwable $e) {
            return (string) $value;
        }
    };

    $formatTime = function ($value) {
        if (!$value) {
            return '';
        }

        try {
            return \Carbon\Carbon::parse($value)->format('H:i');
        } catch (\Throwable $e) {
            return (string) $value;
        }
    };

    $totalDisplay = $totalMinutes !== null
        ? sprintf('%02d Hrs %02d Mins',
            intdiv((int) $totalMinutes, 60),
            (int) $totalMinutes % 60
        )
        : '';

    $answersByQuestion = $answers->keyBy('checklist_question_id');
@endphp

<div class="top">
    <div class="site">Morocco III</div>

    <div class="barcode">
        @if($jobId !== '')
            {!! DNS1D::getBarcodeSVG($jobId, 'C128', 1.5, 35) !!}
        @endif
    </div>

    <div class="jobline">
        PPM Job ID {{ $jobId }}
    </div>

    <div class="separator"></div>

    <table class="meta">
        <tr>
            <td>
                <span class="label">Week Due</span>
                {{ $record->week_due }}
            </td>
            <td>
                <span class="label">PPM ID</span>
                {{ $record->ppm_id ?? $record->id }}
            </td>
            <td>
                <span class="label">Risk ID</span>
                {{ $record->risk_id ?? '' }}
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <span class="label">Brief Description</span>
                {{ $record->brief_description ?? '' }}
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <span class="label">Asset</span>
                {{ $record->asset_id }}
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <span class="label">Asset Description</span>
                {{ $record->asset_description }}
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <span class="label">Sub Assembly</span>
                {{ $record->position_3 ?? '' }}
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <span class="label">Position</span>
                {{ $record->position ?? $record->position_2 ?? '' }}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="label">Plant Group</span>
                {{ $record->plant_group ?? '' }}
            </td>
            <td>
                <span class="label">System</span>
                {{ $record->system ?? '' }}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="label">Serial Number</span>
                {{ $record->manufacturer_serial_number ?? '' }}
            </td>
            <td>
                <span class="label">Created</span>
                {{ $formatDate($record->date_time_created) }}
            </td>
        </tr>
    </table>

    <table class="service">
        <tr>
            <td>
                <strong>Maintenance Service</strong><br>
                {{ $record->trade ?? '' }}
            </td>
            <td>
                <strong>Date Time Created</strong><br>
                {{ $record->date_time_created ?? '' }}
            </td>
        </tr>
        <tr>
            <td>
                <strong>Frequency (Weeks)</strong><br>
                {{ $record->frequency }}
            </td>
            <td>
                <strong>Est. Duration</strong><br>
                {{ $record->est_duration ?? $record->est_resource_time ?? '' }}
            </td>
        </tr>
    </table>
</div>

<div class="details-title">PPM Details</div>

<table class="questions">
    <thead>
    <tr>
        <th class="number"></th>
        <th class="description"></th>
        <th class="answer">OK</th>
        <th class="answer">NOK</th>
    </tr>
    </thead>

    <tbody>
    @forelse($answers as $index => $answer)
        @php
            $response = strtolower(trim((string) $answer->response));

            $isOk = in_array($response, ['ok', 'yes', 'pass', '1'], true);
            $isNotOk = in_array(
                $response,
                ['not_ok', 'not ok', 'nok', 'no', 'fail', '0'],
                true
            );

            $questionText = $answer->question?->question_text
                ?? 'Question #' . ($index + 1);
        @endphp

        <tr>
            <td class="number">[{{ $index + 1 }}]</td>
            <td class="description">
                {{ $questionText }}

                @if($answer->comment)
                    <br><strong>Comment:</strong> {{ $answer->comment }}
                @endif

                @if($answer->dpn)
                    <br><strong>SP Consumed [DPN]:</strong> {{ $answer->dpn }}
                @endif

                @if($answer->observation)
                    <br><strong>Observation:</strong> {{ $answer->observation }}
                @endif
            </td>

            <td class="answer">
                @if($isOk) X @else <span class="mark"></span> @endif
            </td>

            <td class="answer">
                @if($isNotOk) X @else <span class="mark"></span> @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4">
                No checklist answers are registered.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="bottom">
    <div class="maintenance">
        <strong>Maintenance:</strong>

        <table class="signoff">
            <tr>
                <td style="width: 18%"><strong>Start Time</strong></td>
                <td style="width: 32%">
                    {{ $formatTime($startTime) }}
                </td>
                <td style="width: 18%"><strong>Finish Time</strong></td>
                <td>{{ $formatTime($finishTime) }}</td>
            </tr>
            <tr>
                <td><strong>Total Time</strong></td>
                <td colspan="3">
                    {{ $totalDisplay }}
                </td>
            </tr>
            <tr>
                <td><strong>Completed By</strong></td>
                <td colspan="2" class="line-cell">
                    {{ $completedBy?->name ?? '' }}
                    {{ $checklist?->completed_by_matricule
                        ? ' / ' . $checklist->completed_by_matricule
                        : '' }}
                </td>
                <td>
                    {{ $formatDate($completedDate) }}
                </td>
            </tr>
            <tr>
                <td><strong>Verified By</strong></td>
                <td colspan="2" class="line-cell">
                    {{ $checklist?->verifiedBy?->name ?? '' }}
                    {{ $checklist?->verified_by_matricule
                        ? ' / ' . $checklist->verified_by_matricule
                        : '' }}
                </td>
                <td>{{ $formatDate($checklist?->verified_at) }}</td>
            </tr>
            <tr>
                <td colspan="4">
                    <strong>If quality check required:</strong>
                </td>
            </tr>
            <tr>
                <td><strong>Verified By</strong></td>
                <td colspan="2" class="line-cell">
                    {{ $checklist?->verifiedByQuality?->name ?? '' }}
                    {{ $checklist?->verified_by_quality_matricule
                        ? ' / ' . $checklist->verified_by_quality_matricule
                        : '' }}
                </td>
                <td>
                    {{ $formatDate($checklist?->verified_quality_at) }}
                </td>
            </tr>
        </table>
    </div>

    <div class="job-box">
        <strong>PPM Job ID</strong>
        <strong style="float:right">{{ $jobId }}</strong>

        <table>
            <tr>
                <td class="key">Site</td>
                <td>Morocco III</td>
            </tr>
            <tr>
                <td class="key">Asset</td>
                <td>{{ $record->asset_id }}</td>
            </tr>
            <tr>
                <td class="key">Frequency</td>
                <td>
                    {{ $record->frequency }} -
                    {{ $record->frequency_label }}
                </td>
            </tr>
            <tr>
                <td class="key">Date</td>
                <td>{{ $formatDate($completedDate) }}</td>
            </tr>
            <tr>
                <td class="key">Intervention WK</td>
                <td>{{ $record->intervention_week }}</td>
            </tr>
            <tr>
                <td class="key">Signature / ID No.</td>
                <td>
                    {{ $completedBy?->matricule ?? '' }}
                </td>
            </tr>
            <tr>
                <td class="key">Next Preventative Maintenance Week</td>
                <td>{{ $record->next_pm_week }}</td>
            </tr>
        </table>
    </div>
</div>

</body>
</html>
