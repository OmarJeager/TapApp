<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PPM Details</title>
    <style>
        :root {
            --bg: #f4f6fb; --card: #fff; --text: #1e293b; --muted: #64748b;
            --border: #e2e8f0; --primary: #4f46e5; --primary-soft: #eef2ff;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Segoe UI', system-ui, sans-serif; background: var(--bg); color: var(--text); }
        .container { max-width: 1400px; margin: 0 auto; padding: 28px 20px; }

        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; animation: fadeDown .5s ease; }
        .header h1 { margin: 0; font-size: 26px; }
        .header p { margin: 4px 0 0; color: var(--muted); font-size: 14px; }
        .total-chip { background: var(--primary-soft); color: var(--primary); padding: 8px 16px; border-radius: 999px; font-weight: 600; font-size: 14px; }

        /* top progress bar */
        #progress { position: fixed; top: 0; left: 0; height: 3px; width: 0; background: linear-gradient(90deg, #6366f1, #06b6d4); z-index: 100; transition: width .3s ease, opacity .3s; opacity: 0; }
        #progress.active { opacity: 1; width: 75%; transition: width 8s cubic-bezier(.1, .8, .2, 1); }
        #progress.done { width: 100%; transition: width .2s; }

        .card { position: relative; background: var(--card); border-radius: 16px; box-shadow: 0 6px 24px rgba(30, 41, 59, .07); overflow: hidden; animation: fadeUp .6s ease; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 1150px; }

        thead th { text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); padding: 14px 14px 8px; background: #f8fafc; white-space: nowrap; }
        thead tr.filters th { padding: 0 14px 14px; border-bottom: 1px solid var(--border); text-transform: none; }
        .filters input, .filters select {
            width: 100%; padding: 8px 10px; border: 1px solid var(--border); border-radius: 8px;
            font-size: 13px; background: #fff; color: var(--text); outline: none; transition: border-color .2s, box-shadow .2s;
        }
        .filters input:focus, .filters select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, .15); }

        tbody td { padding: 13px 14px; border-bottom: 1px solid #f1f5f9; font-size: 14px; vertical-align: middle; }
        tbody tr { transition: background .2s, transform .2s; }
        tbody tr:hover { background: var(--primary-soft); }
        td small { display: block; color: var(--muted); font-size: 11px; margin-top: 2px; }
        .mono { font-family: ui-monospace, Consolas, monospace; font-size: 13px; }
        .strong { font-weight: 600; }
        .desc { max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .pill { background: #f1f5f9; padding: 3px 10px; border-radius: 6px; font-weight: 600; font-size: 13px; }

        .badge { padding: 5px 12px; border-radius: 999px; font-size: 12px; font-weight: 600; white-space: nowrap; }
        .badge-green { background: #dcfce7; color: #15803d; }
        .badge-orange { background: #ffedd5; color: #c2410c; }
        .badge-gray { background: #e2e8f0; color: #475569; }

        .row-anim { opacity: 0; animation: fadeUp .45s ease forwards; animation-delay: calc(var(--i) * 28ms); }
        .empty { text-align: center; padding: 60px 0 !important; color: var(--muted); }
        .empty-icon { font-size: 38px; margin-bottom: 8px; }

        /* loading overlay */
        #loader { position: absolute; inset: 0; background: rgba(255, 255, 255, .7); backdrop-filter: blur(2px); display: flex; align-items: center; justify-content: center; opacity: 0; pointer-events: none; transition: opacity .25s; z-index: 10; }
        #loader.show { opacity: 1; pointer-events: all; }
        .spinner { width: 42px; height: 42px; border: 4px solid #c7d2fe; border-top-color: var(--primary); border-radius: 50%; animation: spin .8s linear infinite; }
        tbody.loading { opacity: .45; transition: opacity .2s; }

        .footer { display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; flex-wrap: wrap; gap: 10px; background: #f8fafc; }
        .footer span { color: var(--muted); font-size: 13px; }
        .btns { display: flex; gap: 8px; }
        .btn { padding: 8px 16px; border: 1px solid var(--border); background: #fff; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600; transition: all .2s; }
        .btn:hover:not(:disabled) { background: var(--primary); color: #fff; border-color: var(--primary); transform: translateY(-1px); }
        .btn:disabled { opacity: .4; cursor: not-allowed; }
        .btn-reset { background: transparent; color: var(--primary); border-color: var(--primary); }

        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
        @keyframes fadeDown { from { opacity: 0; transform: translateY(-14px); } to { opacity: 1; transform: none; } }
    </style>
</head>
<body>
<div id="progress"></div>

<div class="container">
    <div class="header">
        <div>
            <h1>PPM Records</h1>
            <p>Jobs, assets and checklist verification status</p>
        </div>
        <div class="total-chip"><span id="total">{{ $records->total() }}</span> records</div>
    </div>

    <div class="card">
        <div id="loader"><div class="spinner"></div></div>

        <form id="filterForm" onsubmit="return false;">
            <input type="hidden" name="page" id="page" value="{{ $records->currentPage() }}">
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th style="width:100px">Year</th>
                        <th>Job ID</th>
                        <th>Asset ID</th>
                        <th>Description</th>
                        <th style="width:110px">Week Due</th>
                        <th style="width:120px">Frequency</th>
                        <th>Completed By</th>
                        <th>Verified By</th>
                        <th>Admin Verified By</th>
                        <th style="width:150px">Status</th>
                    </tr>
                    <tr class="filters">
                        <th>
                            <select name="year">
                                <option value="all" @selected($year === 'all')>All</option>
                                @foreach ($years as $y)
                                    <option value="{{ $y }}" @selected((string) $year === (string) $y)>{{ $y }}</option>
                                @endforeach
                            </select>
                        </th>
                        <th><input type="text" name="job_id" placeholder="Search…" value="{{ request('job_id') }}"></th>
                        <th><input type="text" name="asset_id" placeholder="Search…" value="{{ request('asset_id') }}"></th>
                        <th></th>
                        <th><input type="number" name="week" min="1" max="53" placeholder="Week" value="{{ request('week') }}"></th>
                        <th>
                            <select name="frequency">
                                <option value="">All</option>
                                @foreach ($frequencies as $f)
                                    <option value="{{ $f }}" @selected(request('frequency') == $f)>{{ $f }} wk</option>
                                @endforeach
                            </select>
                        </th>
                        <th><input type="text" name="completed_by" placeholder="Name…" value="{{ request('completed_by') }}"></th>
                        <th></th>
                        <th></th>
                        <th>
                            <select name="status">
                                <option value="">All</option>
                                <option value="verified" @selected(request('status') === 'verified')>Verified</option>
                                <option value="not_verified" @selected(request('status') === 'not_verified')>Not verified</option>
                                <option value="no_checklist" @selected(request('status') === 'no_checklist')>No checklist</option>
                            </select>
                        </th>
                    </tr>
                    </thead>
                    <tbody id="rows">
                        @include('admin.partials.ppm-rows', ['records' => $records])
                    </tbody>
                </table>
            </div>
        </form>

        <div class="footer">
            <span id="pageInfo">Page {{ $records->currentPage() }} of {{ $records->lastPage() }}</span>
            <div class="btns">
                <button type="button" class="btn btn-reset" id="resetBtn">Reset filters</button>
                <button type="button" class="btn" id="prevBtn">← Prev</button>
                <button type="button" class="btn" id="nextBtn">Next →</button>
            </div>
        </div>
    </div>
</div>

<script>
    const form      = document.getElementById('filterForm');
    const rows      = document.getElementById('rows');
    const loader    = document.getElementById('loader');
    const progress  = document.getElementById('progress');
    const pageInput = document.getElementById('page');
    const prevBtn   = document.getElementById('prevBtn');
    const nextBtn   = document.getElementById('nextBtn');
    const baseUrl   = "{{ route('admin.showdetails') }}";
    let currentPage = {{ $records->currentPage() }};
    let lastPage    = {{ $records->lastPage() }};
    let timer, controller;

    function updatePager() {
        prevBtn.disabled = currentPage <= 1;
        nextBtn.disabled = currentPage >= lastPage;
        document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${lastPage}`;
    }
    updatePager();

    function startLoading() {
        loader.classList.add('show');
        rows.classList.add('loading');
        progress.classList.remove('done');
        progress.classList.add('active');
    }
    function stopLoading() {
        loader.classList.remove('show');
        rows.classList.remove('loading');
        progress.classList.add('done');
        setTimeout(() => progress.classList.remove('active', 'done'), 300);
    }

    async function load() {
        const params = new URLSearchParams(new FormData(form));
        [...params.keys()].forEach(k => { if (!params.get(k)) params.delete(k); });

        if (controller) controller.abort();     // cancel previous request
        controller = new AbortController();
        startLoading();

        try {
            const res = await fetch(`${baseUrl}?${params}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                signal: controller.signal,
            });
            const data = await res.json();
            rows.innerHTML = data.html;
            document.getElementById('total').textContent = data.total;
            currentPage = data.page;
            lastPage    = data.last;
            pageInput.value = currentPage;
            updatePager();
            history.replaceState(null, '', `${baseUrl}?${params}`);
            stopLoading();
        } catch (e) {
            if (e.name !== 'AbortError') { console.error(e); stopLoading(); }
        }
    }

    // filters changed -> back to page 1
    function onFilter(delay) {
        pageInput.value = 1;
        clearTimeout(timer);
        timer = setTimeout(load, delay);
    }
    form.querySelectorAll('input[type=text], input[type=number]').forEach(el =>
        el.addEventListener('input', () => onFilter(450)));
    form.querySelectorAll('select').forEach(el =>
        el.addEventListener('change', () => onFilter(0)));

    prevBtn.onclick = () => { if (currentPage > 1)        { pageInput.value = currentPage - 1; load(); } };
    nextBtn.onclick = () => { if (currentPage < lastPage) { pageInput.value = currentPage + 1; load(); } };

    document.getElementById('resetBtn').onclick = () => {
        form.reset();
        form.querySelectorAll('input[type=text], input[type=number]').forEach(i => i.value = '');
        form.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
        form.querySelector('[name=year]').value = "{{ now()->year }}";
        form.querySelector('[name=status]').value = '';
        onFilter(0);
    };
</script>
</body>
</html>
