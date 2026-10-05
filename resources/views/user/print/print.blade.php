<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PPM Tickets - {{ $category }} / {{ $week }}</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 8mm;
        }

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
            max-width: 1100px;
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

        /* ===== 4 tickets per row ===== */
        .ticket-grid {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 3mm;
        }

        .ticket {
            border: 1px solid #000;
            padding: 2mm 2.5mm;
            background: #fff;
            font-size: 8.5px;
            line-height: 1.25;
            min-height: 62mm;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* no separator lines, like the original ticket */
        .ticket-row {
            display: flex;
            padding: 1.5px 0;
        }

        .ticket-row .t-label {
            font-weight: 700;
            width: 22mm;
            flex-shrink: 0;
        }

        .ticket-row .t-value {
            flex: 1;
            word-break: break-word;
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2px;
        }

        .ticket-header .jobid-label,
        .ticket-header .jobid-value {
            font-weight: 700;
            font-size: 9px;
        }

        .ticket-header .jobid-value {
            margin-left: 4px;
        }

        .ticket-header .date-block {
            font-weight: 700;
            font-size: 8.5px;
            text-align: right;
        }

        .ticket-header .date-block .date-line {
            font-weight: 400;
            margin-top: 1px;
            white-space: nowrap;
        }

        .barcode-row {
            margin: 2px 0 4px 0;
            overflow: hidden;
        }

        .barcode-row svg {
            height: 26px;
            max-width: 100%;
        }

        .sig-img {
            height: 28px;
            max-width: 60px;
            object-fit: contain;
            filter: brightness(0) saturate(100%);
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
                max-width: none;
                margin: 0;
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
                        {!! DNS1D::getBarcodeSVG($record->job_id, 'C128', 1, 26) !!}
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
                    <span class="t-value" style="display:flex; align-items:center; gap:4px;">
                        @if(auth()->user()->signature)
                            <img class="sig-img" src="{{ asset('storage/' . auth()->user()->signature) }}">
                        @endif
                        <span style="font-weight:700;">{{ auth()->user()->matricule ?? '—' }}</span>
                    </span>
                </div>

                <div class="ticket-row">
                    <span class="t-label">Next Preventative Maintenance Week</span>
                    <span class="t-value">{{ $record->next_pm_week ?? '—' }}</span>
                </div>

            </div>

        @empty

            <p>No assets found for this selection.</p>

        @endforelse

    </div>

</body>
</html>
