
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPM Export Center | TAP APP</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --orange: #f97316;
            --dark: #172033;
            --muted: #64748b;
            --border: #e2e8f0;
        }

        body {
            margin: 0;
            background: #f4f6fb;
            color: var(--dark);
            font-family: Inter, "Segoe UI", sans-serif;
        }

        .page {
            max-width: 1450px;
            margin: auto;
            padding: 30px 22px;
        }

        .hero {
            position: relative;
            overflow: hidden;
            padding: 30px;
            border-radius: 22px;
            color: white;
            background: linear-gradient(120deg, #111827, #26344d);
            box-shadow: 0 15px 40px #17203318;
            animation: slideUp .55s ease both;
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 240px;
            height: 240px;
            right: -65px;
            top: -120px;
            border: 35px solid #f9731640;
            border-radius: 50%;
        }

        .hero h1 { margin: 0 0 10px; font-size: 29px; }
        .hero p { color: #cbd5e1; margin: 0; line-height: 1.7; }

        .panel {
            margin-top: 22px;
            padding: 24px;
            border: 1px solid var(--border);
            border-radius: 18px;
            background: white;
            box-shadow: 0 8px 28px #17203308;
            animation: slideUp .6s ease both;
        }

        .filters {
            display: grid;
            grid-template-columns: repeat(3, minmax(150px, 1fr));
            gap: 16px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-size: 12px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        input {
            box-sizing: border-box;
            width: 100%;
            padding: 12px 13px;
            border: 1px solid #dbe2ec;
            border-radius: 10px;
            outline: none;
            background: #fff;
            transition: .2s;
        }

        input:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px #f973161c;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 9px;
            padding: 12px 17px;
            border: 0;
            border-radius: 10px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s, filter .2s;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 18px #17203318;
            filter: brightness(1.04);
        }

        .btn-orange { background: var(--orange); color: white; }
        .btn-green { background: #15803d; color: white; }
        .btn-dark { background: #1e293b; color: white; }
        .btn-light { background: #edf2f7; color: #334155; }

        .ticket-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 11px;
            border-radius: 9px;
            background: #eff6ff;
            color: #1d4ed8;
            text-decoration: none;
            font-size: 12px;
            font-weight: 800;
            transition: transform .2s, background .2s, box-shadow .2s;
        }

        .ticket-btn:hover {
            transform: translateY(-2px);
            background: #dbeafe;
            box-shadow: 0 6px 14px #1d4ed820;
        }

        .ticket-icon {
            display: inline-block;
            animation: ticketPulse 2s ease-in-out infinite;
        }

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 18px;
        }

        .section-heading h2 { margin: 0; font-size: 18px; }
        .count {
            display: inline-block;
            padding: 7px 11px;
            background: #fff1e6;
            color: #c2410c;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 800;
        }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 850px; }
        th {
            background: #f8fafc;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .5px;
            font-size: 11px;
            text-align: left;
        }

        th, td {
            padding: 14px 12px;
            border-bottom: 1px solid #edf0f5;
            font-size: 13px;
        }

        tbody tr { transition: background .2s; }
        tbody tr:hover { background: #fff7ed; }

        .mono { font-family: Consolas, monospace; font-weight: 700; }
        .muted { color: var(--muted); }
        .empty { text-align: center; padding: 38px; color: var(--muted); }

        .pagination { margin-top: 20px; }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes ticketPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.12); }
        }

        @media(max-width: 700px) {
            .page { padding: 15px 12px; }
            .hero { padding: 22px; }
            .hero h1 { font-size: 23px; }
            .panel { padding: 16px; }
            .filters { grid-template-columns: 1fr; }
            .btn { flex: 1 1 100%; }
        }

        @media(prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
</head>
<body>
<div class="page">
    <section class="hero">
        <h1>📊 PPM Export Center</h1>
        <p>
            Preventive maintenance reporting, checklist answers, completion times
            and verification records in one place.
        </p>
    </section>

    <section class="panel">
        <div class="section-heading">
            <h2>🔎 Filter your maintenance data</h2>
        </div>

        <form method="GET" action="{{ route('admin.ppm-exports.index') }}">
            <div class="filters">
                <div>
                    <label for="week_due">Week Due</label>
                    <input
                        id="week_due"
                        name="week_due"
                        value="{{ request('week_due') }}"
                        placeholder="Example: 202640"
                    >
                </div>

                <div>
                    <label for="asset_id">Asset ID</label>
                    <input
                        id="asset_id"
                        name="asset_id"
                        value="{{ request('asset_id') }}"
                        placeholder="Search asset..."
                    >
                </div>

                <div>
                    <label for="job_id">Job ID</label>
                    <input
                        id="job_id"
                        name="job_id"
                        value="{{ request('job_id') }}"
                        placeholder="Search job..."
                    >
                </div>
            </div>

            <div class="actions">
                <button class="btn btn-orange" type="submit">
                    🔍 Apply Filters
                </button>

                <a class="btn btn-light"
                   href="{{ route('admin.ppm-exports.index') }}">
                    ↺ Reset
                </a>

                <button class="btn btn-green" type="submit"
                        formaction="{{ route('admin.ppm-exports.csv') }}"
                        formmethod="GET">
                    ⬇ Export CSV
                </button>

                <button class="btn btn-dark" type="submit"
                        formaction="{{ route('admin.ppm-exports.pdf') }}"
                        formmethod="GET">
                    📄 Export PDF
                </button>
            </div>
        </form>
    </section>

    <section class="panel">
        <div class="section-heading">
            <h2>🛠 PPM Records</h2>
            <span class="count">
                {{ number_format($records->total()) }} matching assets
            </span>
        </div>

        <p class="muted">
            The preview is paginated. Exports include all matching records,
            not just the current page.
        </p>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>PPM ID</th>
                    <th>Job ID</th>
                    <th>Asset ID</th>
                    <th>Asset Description</th>
                    <th>Week Due</th>
                    <th>Frequency</th>
                    <th>Checklists</th>
                    <th>Ticket</th>
                </tr>
                </thead>

                <tbody>
                @forelse($records as $record)
                    <tr>
                        <td class="mono">
                            {{ $record->ppm_id ?? $record->id }}
                        </td>
                        <td class="mono">{{ $record->job_id }}</td>
                        <td class="mono">{{ $record->asset_id }}</td>
                        <td>{{ $record->asset_description }}</td>
                        <td>{{ $record->week_due }}</td>
                        <td>{{ $record->frequency_label ?? $record->frequency }}</td>
                        <td>{{ $record->ppm_checklists_count }}</td>
                        <td>
                            <a class="ticket-btn"
                               href="{{ route('admin.ppm-tickets.print', ['ppmRecord' => $record->id]) }}"
                               target="_blank" rel="noopener"
                               aria-label="Print PPM ticket for record {{ $record->ppm_id ?? $record->id }}">
                                <span class="ticket-icon" aria-hidden="true">📄</span>
                                Print ticket
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty">
                            No PPM records match your filters.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $records->links() }}
        </div>
    </section>

</div>
</body>
</html>
