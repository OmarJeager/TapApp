@include('layouts.main')

@section('title', 'PNL Checklist')

@section('content')

    {{-- =========================
         PNL TITLE
    ========================= --}}

    {{-- =========================
         PPM RECORD
    ========================= --}}

    @include('components.ppm-record-card', [
        'record' => $ppmRecord
    ])
<div class="page-header">
</div>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PPM Details</title>
<style>

    .submit-row {
    margin-top: 30px;
    text-align: right;
}

.save-next-btn {
    background: #f28c28;
    color: white;
    border: none;
    padding: 12px 28px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.2s;
}

.save-next-btn:hover {
    background: #d96f0b;
}

.save-next-btn:disabled {
    background: #999;
    cursor: not-allowed;
}
    body {
        font-family: 'Times New Roman', Times, serif;
        color: #111;
        background: #e9e9e9;
        margin: 0;
        padding: 20px;
    }

    .sheet {
        max-width: 850px;
        margin: 0 auto 30px auto;
        background: #fff;
        border: 1px solid #999;
        box-shadow: 0 0 6px rgba(0,0,0,0.2);
        padding: 30px 40px;
    }

    h1.title {
        font-size: 20px;
        margin: 0 0 10px 0;
    }

    .flash-status {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        max-width: min(420px, calc(100vw - 40px));
        padding: 14px 20px;
        color: #155724;
        background: #d4edda;
        border: 1px solid #c3e6cb;
        border-left: 5px solid #28a745;
        border-radius: 6px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, .18);
        font: bold 15px Arial, sans-serif;
        animation: notification-in .25s ease-out;
    }

    @keyframes notification-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .errors {
        color: #b00000;
        border: 1px solid #b00000;
        padding: 10px;
        margin-bottom: 15px;
    }

    .record-info {
        font-size: 14px;
        margin-bottom: 20px;
    }
    .record-info span {
        display: inline-block;
        margin-right: 25px;
    }

    table.checklist {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table.checklist th, table.checklist td {
        border: 1px solid #000;
        padding: 6px 8px;
        font-size: 14px;
        vertical-align: middle;
    }

    table.checklist th {
        background: #f0f0f0;
        text-align: center;
        width: 70px;
    }

    table.checklist td.num {
        width: 35px;
        text-align: center;
    }

    table.checklist td.desc {
        text-align: left;
    }

    table.checklist td.ok, table.checklist td.nok {
        width: 60px;
        text-align: center;
    }

    table.checklist td.comment {
        width: 180px;
        text-align: left;
    }

    table.checklist td.comment input[type=text] {
        width: 100%;
        border: 1px solid #999;
        padding: 4px 6px;
        font-family: inherit;
        font-size: 13px;
        box-sizing: border-box;
    }

    tr.section-header td {
        background: #dcdcdc;
        font-weight: bold;
        text-align: left;
    }

    .notes {
        font-size: 13px;
        margin: 20px 0;
        line-height: 1.5;
    }
    .notes strong {
        display: block;
        margin-bottom: 4px;
    }

    table.dpn-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 25px;
    }

    table.dpn-table th, table.dpn-table td {
        border: 1px solid #000;
        padding: 6px 8px;
        font-size: 14px;
    }

    table.dpn-table th {
        background: #f0f0f0;
    }

    table.dpn-table td.num {
        width: 35px;
        text-align: center;
    }

    table.dpn-table input[type=text] {
        width: 100%;
        border: none;
        font-family: inherit;
        font-size: 14px;
    }

    .maintenance {
        border-top: 2px solid #000;
        padding-top: 15px;
        font-size: 14px;
    }

    .maintenance h2 {
        font-size: 16px;
        margin: 0 0 15px 0;
    }

    .maintenance-row {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        margin-bottom: 12px;
        align-items: center;
    }

    .maintenance-row label {
        font-weight: bold;
        margin-right: 6px;
    }

    .maintenance input[type=text],
    .maintenance input[type=date],
    .maintenance input[type=time],
    .maintenance input[type=number] {
        border: none;
        border-bottom: 1px solid #000;
        font-family: inherit;
        font-size: 14px;
        padding: 2px 4px;
        background: transparent;
    }

    .error-inline {
        color: #b00000;
        font-size: 12px;
        margin-left: 8px;
    }

    .admin-verification-status {
        margin: 16px 0;
        padding: 12px 16px;
        border: 1px solid;
        border-radius: 6px;
        font-weight: bold;
    }

    .admin-verification-status.verified {
        color: #155724;
        background: #d4edda;
        border-color: #c3e6cb;
    }

    .admin-verification-status.pending {
        color: #721c24;
        background: #f8d7da;
        border-color: #f5c6cb;
    }

    .submit-row {
        margin-top: 25px;
        text-align: right;
    }

    .submit-row button {
        font-size: 15px;
        padding: 8px 20px;
    }

    .page-footer {
        text-align: center;
        font-size: 12px;
        color: #555;
        margin-top: 10px;
    }

    @media (max-width: 600px) {
        body {
            padding: 0;
            background: #f5f6f8;
            font-family: Arial, sans-serif;
        }

        .sheet {
            width: 100%;
            box-sizing: border-box;
            margin: 0;
            padding: 18px 12px 24px;
            border: 0;
            box-shadow: none;
            border-radius: 0;
        }

        h1.title {
            font-size: 22px;
            margin-bottom: 16px;
        }

        .record-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            padding: 12px;
            margin-bottom: 16px;
            background: #f0f2f5;
            border-radius: 8px;
            font-size: 13px;
        }

        .record-info span {
            display: block;
            margin: 0;
            overflow-wrap: anywhere;
        }

        .record-info span:last-child {
            grid-column: 1 / -1;
        }

        table.checklist,
        table.dpn-table {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            white-space: nowrap;
        }

        table.checklist th,
        table.checklist td,
        table.dpn-table th,
        table.dpn-table td {
            padding: 10px 8px;
            font-size: 13px;
        }

        table.checklist td.desc,
        table.checklist td.comment {
            white-space: normal;
            min-width: 145px;
        }

        table.checklist td.comment input[type=text],
        table.dpn-table input[type=text] {
            min-height: 34px;
            font-size: 14px;
        }

        input[type="radio"] {
            width: 20px;
            height: 20px;
        }

        .notes {
            font-size: 13px;
            line-height: 1.55;
            padding: 12px;
            background: #fff8ed;
            border-radius: 8px;
        }

        .maintenance {
            padding-top: 18px;
        }

        .maintenance-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 6px;
            margin-bottom: 18px;
        }

        .maintenance-row label {
            margin: 0;
        }

        .maintenance input[type=text],
        .maintenance input[type=date],
        .maintenance input[type=time],
        .maintenance input[type=number] {
            width: 100%;
            min-height: 40px;
            box-sizing: border-box;
            padding: 7px 4px;
            font-size: 16px;
        }

        .error-inline {
            margin-left: 0;
        }

        .submit-row {
            margin-top: 24px;
        }

        .save-next-btn {
            width: 100%;
            min-height: 48px;
            font-size: 16px;
        }

        .flash-status {
            top: 10px;
            right: 10px;
            left: 10px;
            max-width: none;
            box-sizing: border-box;
        }
    }
</style>
</head>
<body>
<div class="sheet">

    <h1 class="title">PPM Details</h1>

    <div class="record-info">
        <span><strong>PPM ID:</strong> {{ $ppmRecord->ppm_id }}</span>
        <span><strong>Asset ID:</strong> {{ $ppmRecord->asset_id }}</span>
        <span><strong>Job ID:</strong> {{ $ppmRecord->job_id }}</span>
        <span><strong>Week Due:</strong> {{ $ppmRecord->week_due }}</span><br>
        <span><strong>Asset Description:</strong> {{ $ppmRecord->asset_description }}</span>
    </div>

    @if (session('status'))
        <div class="flash-status" role="status" aria-live="polite">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('ppm-checklists.pnl.store') }}">
        @csrf
        <input type="hidden" name="ppm_records_id" value="{{ $ppmRecord->id }}">

        @php
            $sectionHeaders = [
                1  => 'Equipment',
                4  => 'Print area',
                7  => 'Mechanization area',
                11 => 'Cutter Device',
                12 => 'Test',
            ];
        @endphp

        <table class="checklist">
            <thead>
                <tr>
                    <th style="width:35px;">#</th>
                    <th style="text-align:left;">Description</th>
                    <th>OK</th>
                    <th>NOK</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($questions as $index => $question)
                    @if (isset($sectionHeaders[$question->order]))
                        <tr class="section-header">
                            <td colspan="5">{{ $sectionHeaders[$question->order] }}</td>
                        </tr>
                    @endif
                    @php
                        $existing = $answers->get($question->id);
                        $oldAnswer = old("answers.$index");
                        $currentResponse = $oldAnswer['response'] ?? $existing->response ?? 'ok';
                    @endphp
                    <tr>
                        <td class="num">[{{ $question->order }}]</td>
                        <td class="desc">
                            {{ $question->question_text }}
                            <input type="hidden" name="answers[{{ $index }}][checklist_question_id]" value="{{ $question->id }}">
                        </td>
                        <td class="ok">
                            <input type="radio" name="answers[{{ $index }}][response]" value="ok"
                                @checked($currentResponse === 'ok')>
                        </td>
                        <td class="nok">
                            <input type="radio" name="answers[{{ $index }}][response]" value="not_ok"
                                @checked($currentResponse === 'not_ok')>
                        </td>
                        <td class="comment">
                           <input type="text" name="answers[{{ $index }}][comment]"
                                 value="{{ $oldAnswer['comment'] ?? $existing->comment ?? '' }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="notes">
            <strong>Notes:</strong>
            Always refer the Label Printer manual.<br>
            Always place the battery to recycle.<br>
            The maintenance responsible (MR) needs to validate the maintenance and turn on the equipment.<br>
            If some points are marked as NOK, it is necessary to close them during the maintenance and the Reliability responsible needs to validate them.<br>
            After the maintenance, the equipment needs to be identified with the evidence of the maintenance. The maintenance evidence needs to have filled out the equipment ID, maintenance date, expire maintenance date, signature.
            <br><br>
            <strong>Remarks:</strong>
            Every abnormality must be reported to maintenance department:
            Issue which can affect safety, productivity or product quality must be reported immediately to maintenance team.
            Issues which cannot affect safety, productivity or product quality must be reported to Work Instruction for Manufacturing Feedback (Daily Problem Report).
        </div>

        <table class="dpn-table">
            <thead>
                <tr>
                    <th style="width:60px;">Line #</th>
                    <th>SP Consumed [DPN]</th>
                    <th>Observations</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($questions as $index => $question)
                    @php
                        $existing = $answers->get($question->id);
                        $oldAnswer = old("answers.$index");
                    @endphp
                    <tr>
                        <td class="num">[{{ $question->order }}]</td>
                        <td>
                            <input type="text" name="answers[{{ $index }}][dpn]"
                                   value="{{ $oldAnswer['dpn'] ?? $existing->dpn ?? '' }}">
                        </td>
                        <td>
                            <input type="text" name="answers[{{ $index }}][observation]"
                                   value="{{ $oldAnswer['observation'] ?? $existing->observation ?? '' }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="maintenance">
            <h2>Maintenance</h2>

            <div class="maintenance-row">
                <label>Start Time:</label>
                <input type="time" name="start_time" id="start_time_input"
                       value="{{ old('start_time', $checklist?->start_time?->format('H:i')) }}">

                <label>Finish Time:</label>
                <input type="time" name="end_time" id="end_time_input"
                       value="{{ old('end_time', $checklist?->end_time?->format('H:i')) }}">
                <span class="error-inline" id="end_time_error"></span>
            </div>

            <div class="maintenance-row">
                <label>Total Time (Hours/Mins):</label>
                <input type="number" name="total_time_minutes" id="total_time_input" min="0" readonly
                       value="{{ old('total_time_minutes', $checklist->total_time_minutes ?? '') }}">
            </div>

            <div class="maintenance-row">
                <label>Completed By (Name/No.):</label>
                <input type="text" name="completed_by_matricule"
                      value="{{ old('completed_by_matricule', $checklist->completed_by_matricule ?? auth()->user()?->matricule ?? '') }}">
            </div>

            <div class="maintenance-row">
                <label>Verified By (Name/No.):</label>
                <input type="text" name="verified_by_matricule"
                       value="{{ old('verified_by_matricule', $checklist->verified_by_matricule ?? '') }}">
                <label>Date:</label>
                <input type="date" name="verified_at"
                       value="{{ old('verified_at', $checklist?->verified_at?->format('Y-m-d')) }}">
            </div>
            <div class="maintenance-row">
                <label>If quality check required - Verified By (Name/No.):</label>
                <input type="text" name="verified_by_quality_matricule"
                       value="{{ old('verified_by_quality_matricule', $checklist->verified_by_quality_matricule ?? '') }}">

                <label>Date:</label>
                <input type="date" name="verified_quality_at"
                       value="{{ old('verified_quality_at', $checklist?->verified_quality_at?->format('Y-m-d')) }}">
            </div>

            @php
                $adminVerified = strtolower(trim((string) ($checklist->status_admin ?? ''))) === 'verified';
                $qualityVerified = strtolower(trim((string) ($checklist->quality_status ?? ''))) === 'verified';
                $adminVerifier = trim((string) ($checklist->verified_by_matricule ?? ''));
                $qualityVerifier = trim((string) ($checklist->verified_by_quality_matricule ?? ''));
            @endphp
            <div class="admin-verification-status {{ $adminVerified ? 'verified' : 'pending' }}" role="status" aria-live="polite">
                {{ $adminVerified ? 'Admin verification: Verified by ' . ($adminVerifier !== '' ? $adminVerifier : 'N/A') . '.' : 'Admin verification: Not verified yet.' }}
            </div>
            <div class="admin-verification-status {{ $qualityVerified ? 'verified' : 'pending' }}" role="status" aria-live="polite">
                {{ $qualityVerified ? 'Quality status: Verified by ' . ($qualityVerifier !== '' ? $qualityVerifier : 'N/A') . '.' : 'Quality status: Not verified yet.' }}
            </div>
        </div>

        <div class="submit-row">

    <button type="submit" class="save-next-btn">
        Save & Next
    </button>

</div>
    </form>

    <div class="page-footer">DPEO MEN-MEC 00.39-07.005 F1</div>
</div>

<script>
(function () {
    var startInput = document.getElementById('start_time_input');
    var endInput = document.getElementById('end_time_input');
    var totalInput = document.getElementById('total_time_input');
    var endError = document.getElementById('end_time_error');
    var form = startInput.closest('form');
    var submitButton = form.querySelector('button[type="submit"]');

    function toMinutes(timeStr) {
        var parts = timeStr.split(':');
        return parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
    }

    function updateTotal() {
        if (!startInput.value || !endInput.value) {
            totalInput.value = '';
            endError.textContent = '';
            submitButton.disabled = false;
            return;
        }

        var startMin = toMinutes(startInput.value);
        var endMin = toMinutes(endInput.value);

        if (endMin < startMin) {
            endError.textContent = 'End time cannot be earlier than start time.';
            totalInput.value = '';
            submitButton.disabled = true;
            return;
        }

        endError.textContent = '';
        submitButton.disabled = false;
        totalInput.value = endMin - startMin;
    }

    startInput.addEventListener('change', updateTotal);
    endInput.addEventListener('change', updateTotal);
    endInput.addEventListener('input', updateTotal);

    updateTotal();
})();
</script>

<script>
(function () {
    var notification = document.querySelector('.flash-status');
    if (!notification) return;

    window.setTimeout(function () {
        notification.style.transition = 'opacity .3s ease';
        notification.style.opacity = '0';
        window.setTimeout(function () { notification.remove(); }, 300);
    }, 4000);
})();
</script>

</body>
</html>
