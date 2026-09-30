<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PPM Tickets - {{ $category }} / {{ $week }}</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f1f5f9;
        }

        .sheet-toolbar {
            max-width: 900px;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sheet-toolbar button {
            padding: 8px 18px;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        .sheet-toolbar h2 {
            font-size: 15px;
            color: #334155;
            margin: 0;
        }

        .ticket-grid {
            max-width: 900px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
        }

        .ticket {
            width: calc((100% - 14px) / 2);
            border: 1.5px solid #000;
            padding: 16px 20px;
            background: #fff;
            font-size: 15px;
            page-break-inside: avoid;
        }

        .ticket-row {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
            padding: 6px 0;
        }

        .ticket-row:last-child {
            border-bottom: none;
        }

        .ticket-row .t-label {
            font-weight: 700;
            min-width: 200px;
        }

        .ticket-row .t-value {
            flex: 1;
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }

        .ticket-header .jobid-label {
            font-weight: 700;
            font-size: 16px;
        }

        .ticket-header .jobid-value {
            font-weight: 700;
            font-size: 16px;
            margin-left: 6px;
        }

        .ticket-header .date-block {
            font-weight: 700;
            font-size: 15px;
            text-align: right;
        }

        .ticket-header .date-block .date-line {
            font-weight: 400;
            margin-top: 4px;
        }

        .barcode-row {
            margin: 4px 0 10px 0;
        }

        .barcode-row svg {
            height: 44px;
        }

        .blank-line {
            display: inline-block;
            min-width: 70px;
            border-bottom: 1px solid #999;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .sheet-toolbar {
                display: none;
            }

            .ticket-grid {
                gap: 10px;
            }
        }
    </style>
</head>
<body>

    <div class="sheet-toolbar">
        <h2>{{ $category }} — Week {{ $week }} — {{ $records->count() }} ticket(s)</h2>
        <button onclick="window.print()">Print</button>
    </div>

    <div class="ticket-grid">

        @forelse($records as $record)

            <div class="ticket">

                <div class="ticket-header">
                    <div>
                        <span class="jobid-label">PPM Job ID</span>
                        <span class="jobid-value">{{ $record->job_id }}</span>
                    </div>

                    <div class="date-block">
                        Date
                        <div class="date-line">
                            {{ optional($record->ppmChecklists->sortByDesc('completed_at')->first())->completed_at?->format('d/m/Y') ?? '__ / __ / ____' }}
                        </div>
                    </div>
                </div>

                <div class="barcode-row">
                    @if($record->job_id)
                        {!! DNS1D::getBarcodeSVG($record->job_id, 'C128', 2, 44) !!}
                    @endif
                </div>

                <div class="ticket-row">
                    <span class="t-label">Site</span>
                    <span class="t-value">{{ $record->plant_name ?? 'Morocco III' }}</span>
                </div>

                <div class="ticket-row">
                    <span class="t-label">Asset</span>
                    <span class="t-value">{{ $record->asset_id }}</span>
                </div>

                <div class="ticket-row">
                    <span class="t-label">Asset Description</span>
                    <span class="t-value">{{ $record->asset_description ?? '—' }}</span>
                </div>

                <div class="ticket-row">
                    <span class="t-label">Frequency</span>
                    <span class="t-value">{{ $record->frequency ?? '—' }}</span>
                </div>

                <div class="ticket-row">
                    <span class="t-label">Intervention WK</span>
                    <span class="t-value">{{ $record->intervention_week ?? '—' }}</span>
                </div>

                <div class="ticket-row">
                    <span class="t-label">Signature / ID n&deg;</span>
                    <span class="t-value" style="display:flex; align-items:center; gap:10px;">

                        @if(auth()->user()->signature)
                            <img
                                src="{{ asset('storage/' . auth()->user()->signature) }}"
                                style="height:60px; max-width:180px; object-fit:contain; filter: brightness(0) saturate(100%);"
                            >
                        @endif

                        <span style="font-weight:700;">
                            {{ auth()->user()->matricule ?? '—' }}
                        </span>

                    </span>
                </div>

                <div class="ticket-row">
                    <span class="t-label">Next Preventative<br>Maintenance Week</span>
                    <span class="t-value">{{ $record->next_pm_week ?? '—' }}</span>
                </div>

            </div>

        @empty

            <p>No assets found for this selection.</p>

        @endforelse

    </div>

</body>
</html>
