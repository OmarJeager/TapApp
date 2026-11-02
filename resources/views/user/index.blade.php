
@extends('layouts.main')

@section('title', 'PPM Records')

@section('content')

<style>
:root {
    --primary: #f28c28;
    --primary-dark: #dc7417;
    --primary-light: #fff2e4;
    --dark: #202938;
    --muted: #788497;
    --border: #e9edf3;
    --surface: #ffffff;
    --soft: #f7f9fc;
    --radius: 17px;
    --shadow: 0 10px 35px rgba(25,35,50,.07);
}

.ppm-page,
.ppm-page * {
    box-sizing: border-box;
}

.ppm-page {
    width: 100%;
    max-width: 1600px;
    margin: auto;
    padding: clamp(12px, 2.5vw, 30px);
    color: var(--dark);
    animation: ppmFade .5s ease both;
}

@keyframes ppmFade {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes ppmSlide {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* BACK BUTTON */

.ppm-back-wrap {
    margin-bottom: 22px;
}

.ppm-back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    min-height: 44px;
    padding: 11px 20px;
    border-radius: 12px;
    background: linear-gradient(135deg,#ff9d2e,#ef741c);
    color: white;
    text-decoration: none;
    font-size: 14px;
    font-weight: 800;
    box-shadow: 0 7px 18px rgba(242,140,40,.22);
    transition: .25s;
}

.ppm-back-btn:hover {
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(242,140,40,.3);
}

.ppm-back-arrow {
    font-size: 21px;
}

/* PAGE HEADER */

.ppm-heading {
    margin-bottom: 23px;
}

.ppm-heading h1 {
    margin: 0;
    font-size: clamp(25px,3vw,35px);
    font-weight: 850;
    letter-spacing: -.8px;
    color: var(--dark);
}

.ppm-heading p {
    margin: 7px 0 0;
    font-size: 14px;
    color: var(--muted);
}

/* SCANNER */

.scan-box {
    display: flex;
    gap: 10px;
    width: 100%;
    margin-bottom: 23px;
    padding: 12px;
    border: 1px solid #f6dfc7;
    border-radius: 16px;
    background: linear-gradient(120deg,#fff9f2,#fff);
    box-shadow: 0 5px 20px rgba(35,45,60,.04);
}

.scan-box input {
    flex: 1;
    min-width: 0;
    height: 48px;
    padding: 0 15px;
    border: 1px solid #ead9c8;
    border-radius: 11px;
    outline: none;
    background: white;
    color: var(--dark);
    font-size: 15px;
    transition: .2s;
}

.scan-box input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(242,140,40,.12);
}

.scan-box button {
    min-width: 85px;
    padding: 0 20px;
    border: none;
    border-radius: 11px;
    background: var(--primary);
    color: white;
    font-size: 14px;
    font-weight: 800;
    cursor: pointer;
    transition: .2s;
}

.scan-box button:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

/* MAIN CARD */

.table-card {
    overflow: hidden;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    background: var(--surface);
    box-shadow: var(--shadow);
    transition: opacity .2s;
}

.table-card.loading {
    cursor: progress;
}

.table-card.loading tbody {
    opacity: .4;
    pointer-events: none;
}

/* CARD HEADER */

.table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding: 20px 22px;
    border-bottom: 1px solid var(--border);
}

.table-title {
    font-size: 17px;
    font-weight: 850;
    color: var(--dark);
}

.record-count {
    padding: 7px 12px;
    border-radius: 50px;
    background: var(--primary-light);
    color: #a9560d;
    font-size: 12px;
    font-weight: 800;
}

/* FILTERS */

.filter-panel {
    display: grid;
    grid-template-columns: repeat(5,minmax(120px,1fr)) auto;
    gap: 10px;
    padding: 17px 20px;
    border-bottom: 1px solid var(--border);
    background: var(--soft);
}

.filter-field {
    min-width: 0;
}

.filter-field label {
    display: block;
    margin-bottom: 7px;
    color: #697588;
    font-size: 11px;
    font-weight: 850;
    letter-spacing: .5px;
    text-transform: uppercase;
}

.col-filter {
    width: 100%;
    min-width: 0;
    height: 41px;
    padding: 0 11px;
    border: 1px solid #dfe5ed;
    border-radius: 10px;
    outline: none;
    background: white;
    color: var(--dark);
    font-size: 13px;
    transition: .2s;
}

.col-filter::placeholder {
    color: #a0aaba;
}

.col-filter:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(242,140,40,.12);
}

.filter-actions {
    display: flex;
    align-items: flex-end;
}

.reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 41px;
    padding: 0 14px;
    border: 1px solid #dce2ea;
    border-radius: 10px;
    background: white;
    color: #4a5565;
    font-size: 13px;
    font-weight: 750;
    text-decoration: none;
    white-space: nowrap;
    transition: .2s;
}

.reset-btn:hover {
    color: var(--primary-dark);
    border-color: var(--primary);
    background: #fffaf4;
}

/* TABLE */

.table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.ppm-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.ppm-table thead {
    background: #fbfcfe;
}

.ppm-table thead th {
    padding: 15px 16px;
    border-bottom: 1px solid var(--border);
    color: #647184;
    font-size: 11px;
    font-weight: 850;
    letter-spacing: .5px;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.ppm-table tbody td {
    padding: 15px 16px;
    border-bottom: 1px solid #edf0f4;
    color: #465264;
    font-size: 13px;
    vertical-align: middle;
}

.ppm-table tbody tr {
    transition: background .2s;
}

.ppm-table tbody tr:hover {
    background: #fffaf5;
}

.ppm-table tbody tr:last-child td {
    border-bottom: none;
}

.week-due {
    color: #3b4656;
    font-weight: 750;
    white-space: nowrap;
}

.job-id,
.asset-id {
    font-family: monospace;
    font-weight: 800;
}

.job-id {
    color: #394456;
}

.asset-id {
    display: inline-flex;
    padding: 6px 9px;
    border: 1px solid #e9edf2;
    border-radius: 8px;
    background: #f6f8fb;
    color: #394456;
    font-size: 12px;
}

.frequency {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    min-height: 29px;
    padding: 0 9px;
    border-radius: 8px;
    background: var(--primary-light);
    color: #b85e0c;
    font-weight: 850;
}

.description {
    max-width: 360px;
    line-height: 1.5;
    overflow-wrap: anywhere;
}

/* ACTIONS */

.action-group {
    display: flex;
    align-items: center;
    gap: 7px;
}

.open-btn,
.print-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 36px;
    padding: 0 13px;
    border-radius: 9px;
    color: white;
    text-decoration: none;
    font-size: 12px;
    font-weight: 800;
    white-space: nowrap;
    transition: .2s;
}

.open-btn {
    background: var(--primary);
}

.open-btn:hover {
    background: var(--primary-dark);
    color: white;
    transform: translateY(-2px);
}

.print-btn {
    background: #394456;
}

.print-btn:hover {
    background: #202938;
    color: white;
    transform: translateY(-2px);
}

/* EMPTY STATE */

.empty-state {
    padding: 55px 20px;
    color: var(--muted);
    text-align: center;
}

.empty-icon {
    margin-bottom: 10px;
    font-size: 38px;
}

.empty-state h3 {
    margin: 0 0 7px;
    color: var(--dark);
    font-size: 18px;
}

.empty-state p {
    margin: 0;
    font-size: 13px;
}

/* PAGINATION */

.pagination-container {
    display: flex;
    justify-content: center;
    padding: 19px;
    border-top: 1px solid var(--border);
}

.pagination-container:empty {
    display: none;
}


/* TABLET */
@media (max-width: 1050px) {
    .filter-panel {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

/* SMARTPHONE — KEEP TABLE */
@media (max-width: 700px) {

    .ppm-page {
        padding: 12px;
    }

    .ppm-heading h1 {
        font-size: 25px;
    }

    .table-header {
        padding: 14px;
    }

    .filter-panel {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        padding: 12px;
        gap: 9px;
    }

    .filter-actions {
        grid-column: 1 / -1;
    }

    .reset-btn {
        width: 100%;
    }

    /* Keep the original table structure */
    .table-wrapper {
        display: block;
        width: 100%;
        overflow-x: auto;
        overflow-y: visible;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #f28c28 #f1f1f1;
    }

    .ppm-table {
        display: table;
        width: 100%;
        min-width: 760px;
        border-collapse: separate;
        border-spacing: 0;
    }

    .ppm-table thead {
        display: table-header-group;
    }

    .ppm-table tbody {
        display: table-row-group;
    }

    .ppm-table thead tr {
        display: table-row;
    }

    .ppm-table tbody tr {
        display: table-row;
        margin: 0;
        padding: 0;
        border: none;
        border-radius: 0;
        background: white;
        box-shadow: none;
    }

    .ppm-table thead th {
        display: table-cell;
        padding: 12px 10px;
        font-size: 10px;
        white-space: nowrap;
        background: #fbfcfe;
    }

    .ppm-table tbody td {
        display: table-cell;
        padding: 12px 10px;
        font-size: 12px;
        vertical-align: middle;
        border-bottom: 1px solid #edf0f4;
    }

    /* Hide mobile card labels */
    .ppm-table tbody td::before {
        display: none;
        content: none;
    }

    .ppm-table tbody td:nth-child(4) {
        max-width: 200px;
    }

    .description {
        max-width: 200px;
        min-width: 140px;
    }

    .action-group {
        display: flex;
        flex-direction: row;
        gap: 5px;
        width: auto;
    }

    .action-group a {
        flex: none;
        min-height: 34px;
        padding: 0 9px;
        font-size: 11px;
    }

    .frequency {
        min-width: 28px;
        min-height: 26px;
    }

    .asset-id {
        font-size: 11px;
        white-space: nowrap;
    }

    .ppm-table tbody tr:has(.empty-state) {
        display: table-row;
    }

    .ppm-table tbody tr:has(.empty-state) td {
        display: table-cell;
    }
}

/* SMALL SMARTPHONES */
@media (max-width: 420px) {

    .ppm-page {
        padding: 9px;
    }

    .scan-box {
        padding: 8px;
        gap: 7px;
    }

    .scan-box input {
        min-width: 0;
        height: 43px;
        font-size: 12px;
    }

    .scan-box button {
        min-width: 55px;
        padding: 0 10px;
    }

    .filter-panel {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .col-filter {
        height: 38px;
        font-size: 11px;
    }

    .table-wrapper {
        border-radius: 0 0 12px 12px;
    }

    .ppm-table {
        min-width: 760px;
    }
}

/* SMALL PHONES */

@media(max-width:420px) {

    .ppm-page {
        padding: 10px;
    }

    .ppm-back-btn {
        width: 100%;
    }

    .scan-box {
        gap: 7px;
        padding: 8px;
    }

    .scan-box input {
        height: 44px;
        padding: 0 10px;
        font-size: 13px;
    }

    .scan-box button {
        min-width: 60px;
        padding: 0 12px;
    }

    .filter-panel {
        gap: 8px;
        padding: 11px;
    }

    .col-filter {
        height: 39px;
        padding: 0 8px;
        font-size: 12px;
    }

    .ppm-table tbody tr {
        gap: 0 9px;
        padding-right: 10px;
        padding-left: 10px;
    }

    .ppm-table tbody td {
        font-size: 12px;
    }
}

/* ACCESSIBILITY */

@media(prefers-reduced-motion:reduce) {
    .ppm-page *,
    .ppm-page *::before,
    .ppm-page *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
    }
}
</style>


<div class="ppm-page">

    <!-- BACK BUTTON -->

    <div class="ppm-back-wrap">
        <a href="{{ route('user.dashboard') }}" class="ppm-back-btn">
            <span class="ppm-back-arrow">←</span>
            <span>Back to Dashboard</span>
        </a>
    </div>


    <!-- PAGE HEADER -->

    <header class="ppm-heading">
        <h1>PPM Records</h1>
        <p>Preventive maintenance records</p>
    </header>


    <!-- BARCODE SCANNER -->

    <form method="POST"
          action="{{ route('ppm-records.scan') }}"
          id="scanForm"
          class="scan-box">

        @csrf

        <input
            type="text"
            name="asset_id"
            id="scanInput"
            placeholder="📷 Scan Asset ID (e.g. PNL00001)..."
            autocomplete="off"
            autocapitalize="characters"
            required
        >

        <button type="submit">Go</button>

    </form>


    <!-- RECORDS CARD -->

    <section class="table-card" id="tableCard">

        <!-- HEADER -->

        <div class="table-header">

            <div class="table-title">
                PPM Records
            </div>

            <div class="record-count" id="recordCount">

                @if($records->total() > 0)

                    Showing {{ $records->firstItem() }}
                    - {{ $records->lastItem() }}
                    of {{ $records->total() }} records

                @else

                    0 records

                @endif

            </div>

        </div>


        <!-- FILTERS -->

        <form method="GET"
              action="{{ route('user.index') }}"
              id="filterForm"
              class="filter-panel">

            <div class="filter-field">

                <label for="filter-week">Week Due</label>

                <input
                    id="filter-week"
                    type="text"
                    name="week_due"
                    class="col-filter"
                    placeholder="Search week..."
                    value="{{ request('week_due') }}"
                    autocomplete="off"
                >

            </div>


            <div class="filter-field">

                <label for="filter-job">Job ID</label>

                <input
                    id="filter-job"
                    type="text"
                    name="job_id"
                    class="col-filter"
                    placeholder="Search job..."
                    value="{{ request('job_id') }}"
                    autocomplete="off"
                >

            </div>


            <div class="filter-field">

                <label for="filter-frequency">Frequency</label>

                <input
                    id="filter-frequency"
                    type="text"
                    name="frequency"
                    class="col-filter"
                    placeholder="Search frequency..."
                    value="{{ request('frequency') }}"
                    autocomplete="off"
                >

            </div>


            <div class="filter-field">

                <label for="filter-description">Asset Description</label>

                <input
                    id="filter-description"
                    type="text"
                    name="asset_description"
                    class="col-filter"
                    placeholder="Search asset..."
                    value="{{ request('asset_description') }}"
                    autocomplete="off"
                >

            </div>


            <div class="filter-field">

                <label for="filter-asset">Asset ID</label>

                <input
                    id="filter-asset"
                    type="text"
                    name="asset_id"
                    class="col-filter"
                    placeholder="Search ID..."
                    value="{{ request('asset_id') }}"
                    autocomplete="off"
                >

            </div>


            <div class="filter-actions">

                <a href="{{ route('user.index') }}" class="reset-btn">
                    ↻ Reset filters
                </a>

            </div>

        </form>


        <!-- TABLE -->

        <div class="table-wrapper">

            <table class="ppm-table">

                <thead>

                    <tr>
                        <th>Week Due</th>
                        <th>Job ID</th>
                        <th>Frequency</th>
                        <th>Asset Description</th>
                        <th>Asset ID</th>
                        <th>Action</th>
                    </tr>

                </thead>


                <tbody id="ppmTbody">

                    @forelse($records as $record)

                        <tr>

                            <td data-label="Week Due">

                                <span class="week-due">
                                    {{ $record->week_due ?? '-' }}
                                </span>

                            </td>


                            <td data-label="Job ID">

                                <span class="job-id">
                                    {{ $record->job_id ?? '-' }}
                                </span>

                            </td>


                            <td data-label="Frequency">

                                @if($record->frequency !== null)

                                    <span class="frequency">
                                        {{ $record->frequency }}
                                    </span>

                                @else

                                    -

                                @endif

                            </td>


                            <td data-label="Asset Description">

                                <div class="description">
                                    {{ $record->asset_description ?? '-' }}
                                </div>

                            </td>


                            <td data-label="Asset ID">

                                @if($record->asset_id)

                                    <span class="asset-id">
                                        {{ $record->asset_id }}
                                    </span>

                                @else

                                    -

                                @endif

                            </td>


                            <td data-label="Actions">

                                <div class="action-group">

                                    <a href="{{ route('ppm-records.form', $record) }}"
                                       class="open-btn">
                                        Open
                                    </a>

                                    <a href="{{ route('tickets.index') }}"
                                       class="print-btn">
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

                                    <p>
                                        No PPM records match your current filters.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <!-- PAGINATION -->

        <div class="pagination-container" id="paginationBox">

            @if($records->hasPages())

                {{ $records->links() }}

            @endif

        </div>

    </section>

</div>


<!-- JAVASCRIPT -->

<script>
(function () {

    'use strict';

    /* =====================================
       BARCODE SCANNER
    ===================================== */

    const scanForm = document.getElementById('scanForm');
    const scanInput = document.getElementById('scanInput');

    if (scanForm && scanInput) {

        let scanTimer = null;
        let submitted = false;

        function submitScan(value) {

            const code = (value || '').trim();

            if (submitted || code.length < 4) return;

            submitted = true;
            scanInput.value = code;

            scanForm.submit();

        }

        scanInput.addEventListener('keydown', function (event) {

            if (event.key === 'Enter') {

                event.preventDefault();

                clearTimeout(scanTimer);

                submitScan(scanInput.value);

            }

        });

        scanInput.addEventListener('input', function () {

            submitted = false;

            clearTimeout(scanTimer);

            // Allows barcode scanners without an Enter suffix.

            scanTimer = setTimeout(function () {

                if (scanInput.value.trim().length >= 4) {
                    submitScan(scanInput.value);
                }

            }, 450);

        });

    }


    /* =====================================
       LIVE FILTER SYSTEM
    ===================================== */

    const form = document.getElementById('filterForm');
    const card = document.getElementById('tableCard');

    if (!form || !card) return;

    const inputs = form.querySelectorAll('.col-filter');

    let timer = null;
    let controller = null;


    function buildUrl() {

        const params = new URLSearchParams();

        inputs.forEach(function (input) {

            if (input.value.trim() !== '') {

                params.set(input.name, input.value.trim());

            }

        });

        const query = params.toString();

        return form.action + (query ? '?' + query : '');

    }


    function loadResults() {

        const url = buildUrl();

        if (controller) controller.abort();

        controller = new AbortController();

        card.classList.add('loading');


        fetch(url, {

            signal: controller.signal,

            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }

        })

        .then(function (response) {

            if (!response.ok) {
                throw new Error('Unable to load records.');
            }

            return response.text();

        })

        .then(function (html) {

            const doc = new DOMParser().parseFromString(
                html,
                'text/html'
            );

            [
                'ppmTbody',
                'recordCount',
                'paginationBox'
            ].forEach(function (id) {

                const fresh = doc.getElementById(id);
                const current = document.getElementById(id);

                if (fresh && current) {
                    current.innerHTML = fresh.innerHTML;
                }

            });

            history.replaceState(null, '', url);

        })

        .catch(function (error) {

            if (error.name !== 'AbortError') {
                console.error(error);
            }

        })

        .finally(function () {

            card.classList.remove('loading');

        });

    }


    /* AUTOMATIC FILTER */

    inputs.forEach(function (input) {

        input.addEventListener('input', function () {

            clearTimeout(timer);

            timer = setTimeout(loadResults, 350);

        });


        input.addEventListener('keydown', function (event) {

            if (event.key === 'Enter') {

                event.preventDefault();

                clearTimeout(timer);

                loadResults();

            }

        });

    });


    /* =====================================
       AJAX PAGINATION
    ===================================== */

    card.addEventListener('click', function (event) {

        const link = event.target.closest('#paginationBox a');

        if (!link) return;

        event.preventDefault();

        if (controller) controller.abort();

        controller = new AbortController();

        card.classList.add('loading');


        fetch(link.href, {

            signal: controller.signal,

            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }

        })

        .then(function (response) {

            if (!response.ok) {
                throw new Error('Unable to load page.');
            }

            return response.text();

        })

        .then(function (html) {

            const doc = new DOMParser().parseFromString(
                html,
                'text/html'
            );

            [
                'ppmTbody',
                'recordCount',
                'paginationBox'
            ].forEach(function (id) {

                const fresh = doc.getElementById(id);
                const current = document.getElementById(id);

                if (fresh && current) {
                    current.innerHTML = fresh.innerHTML;
                }

            });

            history.replaceState(null, '', link.href);

            window.scrollTo({
                top: card.offsetTop - 20,
                behavior: 'smooth'
            });

        })

        .catch(function (error) {

            if (error.name !== 'AbortError') {
                console.error(error);
            }

        })

        .finally(function () {

            card.classList.remove('loading');

        });

    });

})();
</script>

@endsection
