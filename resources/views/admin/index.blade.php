<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200">
                {{ __('PPM Records') }}
            </h2>
        </div>
    </x-slot>
@include('layouts.main')

    <div class="py-10">

        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8">

            <!-- PAGE HEADER -->
            <div class="mb-8 animate-fade-down">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">

                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-800">
                            PPM Records
                        </h1>

                        <p class="mt-2 text-gray-500">
                            Search and manage your PPM assets
                        </p>
                    </div>

                    <div class="flex items-center gap-3">

                        <div class="px-5 py-3 rounded-2xl bg-blue-50 border border-blue-100">

                            <span class="text-sm text-gray-500">
                                Total Assets
                            </span>

                            <span class="ml-2 font-bold text-blue-600">
                                {{ $ppmRecords->total() }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- SEARCH -->
            <div class="relative max-w-3xl mb-8">

                <div class="relative group">

                    <!-- SEARCH ICON -->
                    <div class="absolute inset-y-0 left-0 flex items-center pl-5 pointer-events-none">

                        <svg
                            class="w-6 h-6 text-blue-500 transition-transform duration-300 group-focus-within:scale-110"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"/>

                        </svg>

                    </div>


                    <!-- INPUT -->
                   <input
    type="text"
    id="jobSearch"
    autocomplete="off"
    placeholder="Search by Job ID..."
    class="
        w-full
        pl-14
        pr-14
        py-5
        rounded-2xl
        border
        border-blue-100
        bg-white
        text-gray-800
        placeholder-gray-400
        shadow-sm
        outline-none
        transition-all
        duration-300
        hover:border-blue-300
        hover:shadow-md
        focus:border-blue-500
        focus:ring-4
        focus:ring-blue-100
        focus:shadow-xl
    "
/>


                    <!-- CLEAR BUTTON -->
                    <button
                        type="button"
                        id="clearSearch"
                        class="
                            hidden
                            absolute
                            right-5
                            top-1/2
                            -translate-y-1/2

                            w-8
                            h-8

                            rounded-full

                            flex
                            items-center
                            justify-center

                            text-gray-400
                            hover:text-blue-600
                            hover:bg-blue-50

                            transition-all
                            duration-200
                        ">

                        ✕

                    </button>

                </div>


                <!-- SEARCH RESULTS -->
                <div
                    id="searchResults"
                    class="
                        hidden
                        absolute
                        z-50
                        left-0
                        right-0
                        mt-3

                        bg-white

                        border
                        border-blue-100

                        rounded-2xl

                        shadow-2xl

                        overflow-hidden

                        animate-search
                    ">
                </div>

            </div>

            <form action="{{ route('ppm-records.import') }}" method="POST" enctype="multipart/form-data" class="import-card">
        @csrf
        <label for="ppm-file">Import maintenance records</label>
        <div class="file-picker">
            <input id="ppm-file" type="file" name="file" accept=".xlsx,.xls,.csv" required>
            <button type="submit" class="btn btn-primary">Upload & Import</button>
        </div>
        <small class="text-muted d-block mt-2">Accepted formats: XLSX, XLS, or CSV.</small>
    </form>

            <!-- TABLE -->
            <div
                class="
                    bg-white
                    rounded-3xl
                    border
                    border-blue-100
                    shadow-xl
                    overflow-hidden

                    animate-table
                ">

                <!-- TABLE HEADER -->
                <div
                    class="
                        px-7
                        py-5
                        bg-gradient-to-r
                        from-blue-600
                        to-blue-800
                        flex
                        flex-col
                        md:flex-row
                        md:items-center
                        md:justify-between
                        gap-3
                    ">

                    <div>

                        <h3 class="text-xl font-bold text-white">
                            PPM Assets
                        </h3>

                        <p class="text-blue-100 text-sm mt-1">
                            All registered assets
                        </p>

                    </div>


                    <div
                        class="
                            px-4
                            py-2
                            rounded-xl
                            bg-white/10
                            border
                            border-white/20
                            text-white
                            text-sm
                            backdrop-blur
                        ">

                        {{ $ppmRecords->total() }} records

                    </div>

                </div>


                <!-- SCROLL -->
                <div class="overflow-x-auto table-scroll">

                    <table class="ppm-table">

                        <thead>

                            <tr>

                                <th>
                                    Asset ID
                                </th>

                                <th>
                                    PPM ID
                                </th>

                                <th>
                                    Week Due
                                </th>

                                <th>
                                    Plant
                                </th>

                                <th>
                                    Model
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($ppmRecords as $record)

                                <tr class="ppm-row">

                                    <!-- ASSET ID -->
                                    <td>

                                        <span
                                            class="
                                                inline-flex
                                                items-center
                                                px-4
                                                py-2
                                                rounded-xl

                                                bg-blue-50
                                                text-blue-700

                                                font-bold

                                                transition-all
                                                duration-300

                                                group-hover:bg-blue-100
                                            ">

                                            {{ $record->asset_id }}

                                        </span>

                                    </td>


                                    <!-- PPM ID -->
                                    <td>

                                        {{ $record->ppm_id ?? '—' }}

                                    </td>


                                    <!-- WEEK -->
                                    <td>

                                        {{ $record->week_due ?? '—' }}

                                    </td>


                                    <!-- PLANT -->
                                    <td>

                                        {{ $record->plant_group ?? '—' }}

                                    </td>


                                    <!-- MODEL -->
                                    <td>

                                        {{ $record->model ?? '—' }}

                                    </td>


                                    <!-- STATUS -->
                                    <td>

                                        <span
                                            class="
                                                inline-flex
                                                items-center
                                                px-4
                                                py-2
                                                rounded-full

                                                text-xs
                                                font-bold

                                                bg-green-100
                                                text-green-700
                                            ">

                                            {{ $record->status ?? 'Active' }}

                                        </span>

                                    </td>


                                    <!-- ACTION -->
                                    <td>

                                        <a
                                            href="{{ route('ppm-records.show', $record->id) }}"
                                            class="
                                                inline-flex
                                                items-center
                                                gap-2

                                                px-5
                                                py-3

                                                rounded-xl

                                                bg-blue-600
                                                text-white

                                                font-semibold
                                                text-sm

                                                shadow-sm

                                                transition-all
                                                duration-300

                                                hover:bg-blue-700
                                                hover:-translate-y-1
                                                hover:shadow-lg

                                                active:translate-y-0
                                            ">

                                            View Details

                                            <svg
                                                class="
                                                    w-4
                                                    h-4
                                                    transition-transform
                                                    duration-300
                                                "
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 5l7 7-7 7"/>

                                            </svg>

                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="7"
                                        class="py-16 text-center">

                                        <div class="flex flex-col items-center">

                                            <div
                                                class="
                                                    w-16
                                                    h-16
                                                    rounded-2xl
                                                    bg-blue-50
                                                    flex
                                                    items-center
                                                    justify-center
                                                    mb-4
                                                ">

                                                <svg
                                                    class="w-8 h-8 text-blue-500"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 13V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v7m16 0v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-5m16 0H4"/>

                                                </svg>

                                            </div>

                                            <h3 class="text-lg font-bold text-gray-700">
                                                No PPM records found
                                            </h3>

                                            <p class="text-gray-400 mt-1">
                                                There are currently no assets available.
                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                <!-- PAGINATION -->
                @if($ppmRecords->hasPages())

                    <div class="px-6 py-5 border-t border-gray-100">

                        {{ $ppmRecords->links() }}

                    </div>

                @endif

            </div>

        </div>

    </div>


    <!-- STYLE -->
    <style>

        /* =========================
           TABLE
        ========================= */

        .ppm-table {

            width: 100%;

            min-width: 1500px;

            border-collapse: separate;

            border-spacing: 0;

        }


        /* =========================
           HEADER
        ========================= */

        .ppm-table thead th {

            padding: 20px 28px;

            color: white;

            font-size: 13px;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: .06em;

            white-space: nowrap;

            background: linear-gradient(
                135deg,
                #2563eb,
                #1e40af
            );

            border-right: 1px solid rgba(255,255,255,.12);

        }


        /* =========================
           BODY
        ========================= */

        .ppm-table tbody tr {

            background: white;

            transition:
                transform .25s ease,
                background-color .25s ease,
                box-shadow .25s ease;

            animation: ppmRow .5s ease both;

        }


        .ppm-table tbody tr:nth-child(even) {

            background: #f8fbff;

        }


        .ppm-table tbody tr:hover {

            background: #eff6ff;

            transform: scale(1.002);

            box-shadow:
                0 8px 25px rgba(37, 99, 235, .10);

        }


        /* =========================
           CELLS
        ========================= */

        .ppm-table tbody td {

            min-width: 180px;

            padding: 22px 28px;

            color: #334155;

            font-size: 14px;

            white-space: nowrap;

            vertical-align: middle;

            border-bottom: 1px solid #e5edf7;

            border-right: 1px solid #edf2f7;

            transition:
                color .2s ease,
                padding-left .2s ease;

        }


        .ppm-table tbody tr:hover td {

            color: #1e3a8a;

        }


        .ppm-table tbody td:first-child {

            color: #2563eb;

            font-weight: 800;

        }


        .ppm-table tbody tr:last-child td {

            border-bottom: none;

        }


        /* =========================
           SCROLLBAR
        ========================= */

        .table-scroll {

            scrollbar-width: thin;

            scrollbar-color:
                #60a5fa
                #eff6ff;

        }


        .table-scroll::-webkit-scrollbar {

            height: 10px;

        }


        .table-scroll::-webkit-scrollbar-track {

            background: #eff6ff;

        }


        .table-scroll::-webkit-scrollbar-thumb {

            background: #60a5fa;

            border-radius: 20px;

        }


        .table-scroll::-webkit-scrollbar-thumb:hover {

            background: #2563eb;

        }


        /* =========================
           ANIMATIONS
        ========================= */

        @keyframes ppmRow {

            from {

                opacity: 0;

                transform: translateY(15px);

            }

            to {

                opacity: 1;

                transform: translateY(0);

            }

        }


        @keyframes fadeDown {

            from {

                opacity: 0;

                transform: translateY(-15px);

            }

            to {

                opacity: 1;

                transform: translateY(0);

            }

        }


        @keyframes searchAnimation {

            from {

                opacity: 0;

                transform: translateY(-8px);

            }

            to {

                opacity: 1;

                transform: translateY(0);

            }

        }


        .animate-fade-down {

            animation:
                fadeDown
                .6s
                ease-out;

        }


        .animate-search {

            animation:
                searchAnimation
                .25s
                ease-out;

        }


        .animate-table {

            animation:
                fadeDown
                .7s
                ease-out;

        }


        /* =========================
           ROW DELAYS
        ========================= */

        .ppm-row:nth-child(1) {
            animation-delay: .05s;
        }

        .ppm-row:nth-child(2) {
            animation-delay: .10s;
        }

        .ppm-row:nth-child(3) {
            animation-delay: .15s;
        }

        .ppm-row:nth-child(4) {
            animation-delay: .20s;
        }

        .ppm-row:nth-child(5) {
            animation-delay: .25s;
        }

        .ppm-row:nth-child(6) {
            animation-delay: .30s;
        }

        .ppm-row:nth-child(7) {
            animation-delay: .35s;
        }

    </style>


    <!-- SEARCH JAVASCRIPT -->
   <script>

document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('jobSearch');

    const searchResults =
        document.getElementById('searchResults');

    const clearSearch =
        document.getElementById('clearSearch');


    let searchTimeout = null;


    searchInput.addEventListener('input', function () {

        const jobId = this.value.trim();

        clearTimeout(searchTimeout);


        // Empty search
        if (jobId.length === 0) {

            searchResults.innerHTML = '';

            searchResults.classList.add('hidden');

            clearSearch.classList.add('hidden');

            return;
        }


        clearSearch.classList.remove('hidden');


        searchTimeout = setTimeout(function () {

            fetch(
                `{{ route('ppm-records.search') }}?job_id=${encodeURIComponent(jobId)}`
            )

            .then(response => {

                if (!response.ok) {
                    throw new Error('Search request failed');
                }

                return response.json();

            })

            .then(data => {

                searchResults.innerHTML = '';


                // No results
                if (data.length === 0) {

                    searchResults.innerHTML = `

                        <div class="px-6 py-6 text-center">

                            <div class="text-gray-400 text-2xl mb-2">
                                🔍
                            </div>

                            <p class="text-gray-600 font-semibold">
                                No job found
                            </p>

                            <p class="text-sm text-gray-400 mt-1">
                                No Job ID matches "${jobId}"
                            </p>

                        </div>

                    `;

                    searchResults.classList.remove('hidden');

                    return;
                }


                // Results
                data.forEach(function (record) {

                    const item =
                        document.createElement('a');


                    item.href = record.url;


                    item.className = `

                        flex
                        items-center
                        justify-between

                        px-6
                        py-5

                        border-b
                        border-gray-100

                        hover:bg-blue-50

                        transition-all
                        duration-200

                        group
                    `;


                    item.innerHTML = `

                        <div class="flex items-center gap-4">

                            <div
                                class="
                                    w-11
                                    h-11
                                    rounded-xl
                                    bg-blue-100
                                    text-blue-600

                                    flex
                                    items-center
                                    justify-center

                                    group-hover:scale-110

                                    transition-transform
                                    duration-200
                                "
                            >

                                🔧

                            </div>


                            <div>

                                <div
                                    class="
                                        font-bold
                                        text-blue-600
                                    "
                                >
                                    Job ID:
                                    ${record.job_id}
                                </div>


                                <div
                                    class="
                                        text-sm
                                        text-gray-500
                                        mt-1
                                    "
                                >
                                    Asset ID:
                                    ${record.asset_id ?? 'N/A'}
                                </div>

                            </div>

                        </div>


                        <div
                            class="
                                flex
                                items-center
                                gap-2

                                text-blue-600
                                font-semibold
                                text-sm

                                opacity-60

                                group-hover:opacity-100
                                group-hover:translate-x-1

                                transition-all
                                duration-200
                            "
                        >

                            Details

                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                />

                            </svg>

                        </div>

                    `;


                    searchResults.appendChild(item);

                });


                searchResults.classList.remove('hidden');

            })

            .catch(error => {

                console.error(error);

                searchResults.innerHTML = `

                    <div
                        class="
                            p-6
                            text-center
                            text-red-500
                        "
                    >

                        Error while searching.

                    </div>

                `;

                searchResults.classList.remove('hidden');

            });

        }, 250);

    });


    // Clear button
    clearSearch.addEventListener('click', function () {

        searchInput.value = '';

        searchResults.innerHTML = '';

        searchResults.classList.add('hidden');

        clearSearch.classList.add('hidden');

        searchInput.focus();

    });


    // Close search results
    document.addEventListener('click', function (event) {

        if (
            !searchInput.contains(event.target) &&
            !searchResults.contains(event.target)
        ) {

            searchResults.classList.add('hidden');

        }

    });

});

</script>

</x-app-layout>
