@extends('layouts.main')

@section('title', 'PPM Records')

@section('content')

<style>
    .action-group {
    display: flex;
    gap: 6px;
    align-items: center;
}

.print-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 8px 13px;
    border-radius: 6px;
    background: #444;
    color: #fff;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    transition: 0.2s;
    white-space: nowrap;
}

.print-btn:hover {
    background: #222;
    color: #fff;
    transform: translateY(-1px);
}
    * { box-sizing: border-box; }

    .ppm-page { width: 100%; padding: 25px; }

    .page-header { margin-bottom: 25px; }
    .page-title h1 { margin: 0; font-size: 28px; font-weight: 700; color: #222; }
    .page-title p { margin: 5px 0 0; color: #777; font-size: 14px; }

    .table-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #eee;
        overflow: hidden;
    }

    .table-header {
        padding: 18px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        border-bottom: 1px solid #eee;
        flex-wrap: wrap;
    }

    .table-title { font-size: 17px; font-weight: 700; color: #333; }
    .record-count { font-size: 13px; color: #777; }

    .table-wrapper { width: 100%; overflow-x: auto; }

    .ppm-table { width: 100%; border-collapse: collapse; min-width: 950px; }
    .ppm-table thead { background: #f7f7f7; }

    .ppm-table thead tr.title-row th {
        padding: 14px 15px 8px;
        text-align: left;
        font-size: 13px;
        font-weight: 700;
        color: #444;
        white-space: nowrap;
    }

    .ppm-table thead tr.filter-row th {
        padding: 0 15px 12px;
        border-bottom: 2px solid #e8e8e8;
    }

    .col-filter {
        width: 100%;
        height: 36px;
        padding: 0 10px;
        border: 1px solid #d8d8d8;
        border-radius: 6px;
        background: #fff;
        color: #333;
        font-size: 13px;
        font-weight: 400;
        outline: none;
        transition: 0.2s;
    }

    .col-filter::placeholder { color: #aaa; }

    .col-filter:focus {
        border-color: #f28c28;
        box-shadow: 0 0 0 3px rgba(242, 140, 40, 0.12);
    }

    .reset-btn {
        height: 36px;
        padding: 0 14px;
        border-radius: 6px;
        background: #eee;
        color: #333;
        border: 1px solid #ddd;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
        cursor: pointer;
    }

    .reset-btn:hover { background: #ddd; }

    .ppm-table td {
        padding: 13px 15px;
        font-size: 13px;
        color: #444;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }

    .ppm-table tbody tr { transition: background 0.15s; }
    .ppm-table tbody tr:hover { background: #fffaf5; }

    .asset-id {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 5px;
        background: #f1f1f1;
        color: #333;
        font-weight: 700;
        font-family: monospace;
        font-size: 12px;
    }

    .job-id { font-family: monospace; font-weight: 700; color: #333; }

    .frequency {
        display: inline-flex;
        min-width: 30px;
        height: 28px;
        padding: 0 8px;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        background: #f28c28;
        color: #fff;
        font-weight: 700;
    }

    .week-due { font-weight: 600; color: #444; }
    .description { max-width: 300px; line-height: 1.4; }

    .open-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 13px;
        border-radius: 6px;
        background: #f28c28;
        color: #fff;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        transition: 0.2s;
        white-space: nowrap;
    }

    .open-btn:hover { background: #d97617; color: #fff; transform: translateY(-1px); }

    .empty-state { text-align: center; padding: 60px 20px; color: #777; }
    .empty-icon { font-size: 42px; margin-bottom: 12px; }
    .empty-state h3 { margin: 0 0 7px; color: #444; font-size: 18px; }
    .empty-state p { margin: 0; font-size: 14px; }

    .pagination-container {
        padding: 18px 20px;
        display: flex;
        justify-content: center;
        border-top: 1px solid #eee;
    }

    .pagination-container:empty { display: none; }
    .table-card.loading .ppm-table tbody { opacity: 0.5; }

    @media (max-width: 800px) {
        .ppm-page { padding: 15px; }
    }

    @media (max-width: 550px) {
        .page-title h1 { font-size: 23px; }
        .table-header { align-items: flex-start; flex-direction: column; }
    }
 .back-btn-c ontainer { display: flex; justify-content: flex-start; margin: 25px 0; } .back-btn { position: relative; display: inline-flex; align-items: center; justify-content: center; gap: 12px; min-width: 180px; padding: 15px 28px; background: linear-gradient(135deg, #ff9800, #f4511e); color: white; font-size: 18px; font-weight: 700; text-decoration: none; border-radius: 13px; box-shadow: 0 8px 20px rgba(255, 111, 0, 0.35); overflow: hidden; transition: all 0.3s ease; } /* Shine effect */ .back-btn::before { content: ""; position: absolute; top: 0; left: -120%; width: 70%; height: 100%; background: rgba(255, 255, 255, 0.25); transform: skewX(-25deg); transition: left 0.6s ease; } .back-btn:hover::before { left: 140%; } /* Hover animation */ .back-btn:hover { transform: translateY(-4px) scale(1.03); background: linear-gradient(135deg, #ffab00, #ff5722); box-shadow: 0 14px 30px rgba(255, 111, 0, 0.5); } /* Arrow animation */ .back-arrow { position: relative; z-index: 1; font-size: 27px; line-height: 1; transition: transform 0.3s ease; } .back-btn:hover .back-arrow { transform: translateX(-6px); } .back-btn span:last-child { position: relative; z-index: 1; } /* Click animation */ .back-btn:active { transform: scale(0.96); }
</style>

<div class="back-btn-container"> <a href="{{ route('user.dashboard') }}" class="back-btn"> <span class="back-arrow">←</span> <span>Back</span> </a> </div>
<div class="ppm-page">

    <div class="page-header">
        <div class="page-title">
            <h1>PPM Records</h1>
            <p>Preventive maintenance records</p>
        </div>
    </div>

    <div class="table-card" id="tableCard">

        <div class="table-header">
            <div class="table-title">PPM Records</div>

            <div class="record-count" id="recordCount">
                @if($records->total() > 0)
                    Showing {{ $records->firstItem() }} - {{ $records->lastItem() }}
                    of {{ $records->total() }} records
                @else
                    0 records
                @endif
            </div>
        </div>

        <div class="table-wrapper">

            <form method="GET" action="{{ route('user.index') }}" id="filterForm"></form>

            <table class="ppm-table">

                <thead>
                    {{-- Column titles --}}
                    <tr class="title-row">
                        <th>Week Due</th>
                        <th>Job ID</th>
                        <th>Frequency</th>
                        <th>Asset Description</th>
                        <th>Asset ID</th>
                        <th>Action</th>
                    </tr>

                    {{-- Column filters --}}
                    <tr class="filter-row">
                        <th>
                            <input type="text" name="week_due" form="filterForm"
                                   class="col-filter" placeholder="Search..."
                                   value="{{ request('week_due') }}" autocomplete="off">
                        </th>
                        <th>
                            <input type="text" name="job_id" form="filterForm"
                                   class="col-filter" placeholder="Search..."
                                   value="{{ request('job_id') }}" autocomplete="off">
                        </th>
                        <th>
                            <input type="text" name="frequency" form="filterForm"
                                   class="col-filter" placeholder="Search..."
                                   value="{{ request('frequency') }}" autocomplete="off">
                        </th>
                        <th>
                            <input type="text" name="asset_description" form="filterForm"
                                   class="col-filter" placeholder="Search..."
                                   value="{{ request('asset_description') }}" autocomplete="off">
                        </th>
                        <th>
                            <input type="text" name="asset_id" form="filterForm"
                                   class="col-filter" placeholder="Search..."
                                   value="{{ request('asset_id') }}" autocomplete="off">
                        </th>
                        <th>
                            <a href="{{ route('user.index') }}" class="reset-btn">↻ Reset</a>
                        </th>
                    </tr>
                </thead>

                <tbody id="ppmTbody">

                    @forelse($records as $record)
                        <tr>
                            <td><span class="week-due">{{ $record->week_due ?? '-' }}</span></td>

                            <td><span class="job-id">{{ $record->job_id ?? '-' }}</span></td>

                            <td>
                                @if($record->frequency !== null)
                                    <span class="frequency">{{ $record->frequency }}</span>
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                <div class="description">{{ $record->asset_description ?? '-' }}</div>
                            </td>

                            <td>
                                @if($record->asset_id)
                                    <span class="asset-id">{{ $record->asset_id }}</span>
                                @else
                                    -
                                @endif
                            </td>

                            <td>
    <div class="action-group">
        <a href="{{ route('ppm-records.form', $record) }}" class="open-btn">
            Open
        </a>

        <a href="{{ route('tickets.index') }}" class="print-btn">
            🖨 Print
        </a>
    </div>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon">🔍</div>
                                    <h3>No records found</h3>
                                    <p>No PPM records match your current filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="pagination-container" id="paginationBox">
            @if($records->hasPages())
                {{ $records->links() }}
            @endif
        </div>

    </div>

</div>

<script>
    (function () {
        const form   = document.getElementById('filterForm');
        const card   = document.getElementById('tableCard');
        const inputs = document.querySelectorAll('.col-filter');
        let timer = null;
        let controller = null;

        function buildUrl() {
            const params = new URLSearchParams();
            inputs.forEach(i => {
                if (i.value.trim() !== '') params.set(i.name, i.value.trim());
            });
            const qs = params.toString();
            return form.action + (qs ? '?' + qs : '');
        }

        function load() {
            const url = buildUrl();

            if (controller) controller.abort();
            controller = new AbortController();
            card.classList.add('loading');

            fetch(url, {
                signal: controller.signal,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.text())
            .then(html => {
                const doc = new DOMParser().parseFromString(html, 'text/html');

                ['ppmTbody', 'recordCount', 'paginationBox'].forEach(id => {
                    const fresh = doc.getElementById(id);
                    if (fresh) document.getElementById(id).innerHTML = fresh.innerHTML;
                });

                history.replaceState(null, '', url);
                card.classList.remove('loading');
            })
            .catch(err => {
                if (err.name !== 'AbortError') card.classList.remove('loading');
            });
        }

        inputs.forEach(input => {
            input.addEventListener('input', () => {
                clearTimeout(timer);
                timer = setTimeout(load, 300);
            });

            // Enter should not reload the page
            input.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(timer);
                    load();
                }
            });
        });
    })();
</script>

@endsection
