
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PPM Maintenance Export</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 9mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1e293b;
            font-size: 9px;
        }

        h1 { font-size: 21px; margin: 0 0 6px; }
        h2 { font-size: 13px; margin: 15px 0 7px; }
        h3 { font-size: 11px; margin: 12px 0 6px; }

        .header {
            padding-bottom: 12px;
            margin-bottom: 15px;
            border-bottom: 3px solid #f97316;
        }

        .muted { color: #64748b; }
        .asset {
            padding: 10px;
            background: #f1f5f9;
            border-left: 4px solid #f97316;
            margin-bottom: 12px;
        }

        .meta {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0 10px;
        }

        .meta td {
            width: 25%;
            padding: 5px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .meta strong {
            display: block;
            color: #64748b;
            font-size: 8px;
            margin-bottom: 3px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0 12px;
            table-layout: fixed;
        }

        .data th {
            background: #1e293b;
            color: white;
            text-align: left;
            font-size: 7px;
        }

        .data th, .data td {
            border: 1px solid #cbd5e1;
            padding: 5px;
            vertical-align: top;
            overflow-wrap: anywhere;
        }

        .data tr:nth-child(even) { background: #f8fafc; }

        .field {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .field td {
            padding: 4px 5px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
            overflow-wrap: anywhere;
        }

        .field td:first-child {
            width: 27%;
            background: #f8fafc;
            font-weight: bold;
        }

        .status {
            padding: 3px 5px;
            font-weight: bold;
        }

        .ok { color: #166534; }
        .not-ok { color: #b91c1c; }

        .checklist {
            page-break-inside: avoid;
            margin-bottom: 15px;
        }

        .page-break {
            page-break-before: always;
        }

        .footer {
            margin-top: 12px;
            color: #64748b;
            font-size: 8px;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>TAP APP — PPM Maintenance Report</h1>
    <div class="muted">
        Generated: {{ $generatedAt->format('Y-m-d H:i:s') }}
        | PPM records: {{ $records->count() }}
    </div>

    @if(collect($filters)->filter(fn($v) => $v !== null && $v !== '')->isNotEmpty())
        <p>
            Filters:
            @foreach($filters as $key => $value)
                @if($value !== null && $value !== '')
                    <strong>{{ strtoupper(str_replace('_', ' ', $key)) }}:</strong>
                    {{ $value }} &nbsp;
                @endif
            @endforeach
        </p>
    @endif
</div>

@forelse($records as $recordIndex => $record)

    @if($recordIndex > 0)
        <div class="page-break"></div>
    @endif

    <div class="asset">
        <strong>PPM: {{ $record->ppm_id ?? $record->id }}</strong>
        &nbsp; | &nbsp; Job: {{ $record->job_id ?? '—' }}
        &nbsp; | &nbsp; Asset: {{ $record->asset_id ?? '—' }}
        <br><br>
        <strong>Description:</strong>
        {{ $record->asset_description ?? '—' }}
        &nbsp; | &nbsp;
        <strong>Week Due:</strong> {{ $record->week_due ?? '—' }}
        &nbsp; | &nbsp;
        <strong>Frequency:</strong> {{ $record->frequency_label ?? $record->frequency ?? '—' }}
    </div>

    <h2>PPM Record Fields</h2>

    <table class="field">
        @foreach($recordColumns as $column)
            <tr>
                <td>{{ $column }}</td>
                <td>{{ $record->getAttribute($column) ?? '—' }}</td>
            </tr>
        @endforeach
    </table>

    @forelse($record->ppmChecklists as $checklistIndex => $checklist)

        <div class="checklist">
            <h2>
                Checklist #{{ $checklist->getKey() }}
                — {{ $checklist->completed_at ?? 'Not completed' }}
            </h2>

            <h3>All Checklist Fields</h3>

            <table class="field">
                @foreach($checklistColumns as $column)
                    <tr>
                        <td>{{ $column }}</td>
                        <td>{{ $checklist->getAttribute($column) ?? '—' }}</td>
                    </tr>
                @endforeach
            </table>

            <h3>Checklist Questions and Answers</h3>

            <table class="data">
                <thead>
                <tr>
                    <th style="width: 5%">Question ID</th>
                    <th style="width: 29%">Question</th>
                    <th style="width: 10%">Response</th>
                    <th style="width: 18%">Comment</th>
                    <th style="width: 12%">DPN</th>
                    <th style="width: 26%">Observation</th>
                </tr>
                </thead>

                <tbody>
                @forelse($checklist->answers as $answer)
                    @php
                        $response = strtolower(trim((string) $answer->response));
                    @endphp
                    <tr>
                        <td>{{ $answer->checklist_question_id ?? '—' }}</td>
                        <td>{{ $answer->question?->question_text ?? 'Question unavailable' }}</td>
                        <td>
                            <span class="status
                                {{ in_array($response, ['ok', 'yes', 'pass']) ? 'ok' : '' }}
                                {{ in_array($response, ['not_ok', 'not ok', 'no', 'fail']) ? 'not-ok' : '' }}">
                                {{ $answer->response ?? '—' }}
                            </span>
                        </td>
                        <td>{{ $answer->comment ?? '—' }}</td>
                        <td>{{ $answer->dpn ?? '—' }}</td>
                        <td>{{ $answer->observation ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No question answers recorded.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    @empty
        <p>No checklist has been created for this PPM record.</p>
    @endforelse

@empty
    <p>No PPM records match the selected filters.</p>
@endforelse

<div class="footer">
    TAP APP | Preventive Maintenance | Confidential internal report
</div>

</body>
</html>
