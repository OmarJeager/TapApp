<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">PPM Records</h2>
    </x-slot>
    @include('layouts.main')
    @php
        $inp = 'w-full rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs text-gray-700 shadow-sm transition focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200';
    @endphp

    <style>
        /* Loading overlay */
        #table-loading {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(2px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 40;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease-in-out;
        }
        #table-loading.active {
            opacity: 1;
            pointer-events: all;
        }

        /* Spinner */
        .spinner {
            width: 44px;
            height: 44px;
            border: 4px solid #e5e7eb;
            border-top-color: #2563eb;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Pulsing dots text */
        .loading-text {
            margin-left: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #1e40af;
            letter-spacing: 0.025em;
        }
        .loading-text::after {
            content: '';
            animation: dots 1.4s steps(4, end) infinite;
        }
        @keyframes dots {
            0%, 20% { content: ''; }
            40% { content: '.'; }
            60% { content: '..'; }
            80%, 100% { content: '...'; }
        }

        /* Fade rows on refresh */
        #rows.fading {
            opacity: 0.4;
            transition: opacity 0.15s ease-in-out;
        }

        /* Top progress bar */
        #progress-bar {
            position: absolute;
            top: 0;
            left: 0;
            height: 3px;
            width: 0;
            background: linear-gradient(90deg, #3b82f6, #2563eb, #1d4ed8);
            z-index: 50;
            transition: width 0.3s ease, opacity 0.3s ease;
            opacity: 0;
        }
        #progress-bar.active {
            opacity: 1;
            width: 90%;
            transition: width 3s cubic-bezier(0.1, 0.7, 0.3, 1);
        }
        #progress-bar.done {
            width: 100%;
            opacity: 0;
            transition: width 0.2s ease, opacity 0.3s ease 0.2s;
        }

        /* Smooth row appear */
        #rows tr {
            animation: rowFadeIn 0.25s ease-out;
        }
        @keyframes rowFadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800 text-sm font-medium flex items-center gap-2">
                    <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Bulk actions: apply to ALL records matching the header filters --}}
            <form id="filterForm" method="GET" action="{{ route('quality.index') }}"
                  class="mb-4 flex flex-wrap items-center gap-3">
                @csrf
                <span id="total" class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-200 transition-all">
                    {{ $ppm_records->total() }} result(s)
                </span>

                <div class="flex flex-wrap items-center gap-2 ml-auto">
                    <button type="submit" name="status" value="verified"
                            formmethod="POST" formaction="{{ route('quality.bulk') }}"
                            data-confirm="Mark ALL filtered records as VERIFIED?"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-1 active:scale-[0.98]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Verified (all filtered)
                    </button>

                    <button type="submit" name="status" value="not_verified"
                            formmethod="POST" formaction="{{ route('quality.bulk') }}"
                            data-confirm="Mark ALL filtered records as NOT VERIFIED?"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1 active:scale-[0.98]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        Not verified (all filtered)
                    </button>

                    <a href="{{ route('quality.index') }}"
                       class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-1 active:scale-[0.98]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset
                    </a>
                </div>
            </form>

            {{-- Scrollable container with sticky header + loading overlay --}}
            <div class="relative bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">

                {{-- Top progress bar --}}
                <div id="progress-bar"></div>

                {{-- Loading overlay --}}
                <div id="table-loading">
                    <div class="spinner"></div>
                    <span class="loading-text">Loading</span>
                </div>

                <div class="overflow-auto" style="max-height: 70vh;">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-800 text-white">
                            {{-- Titles Row --}}
                            <tr>
                                <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider w-12 sticky top-0 bg-gray-800 z-30">#</th>
                                <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider w-20 sticky top-0 bg-gray-800 z-30">Year</th>
                                <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider w-24 sticky top-0 bg-gray-800 z-30">Job ID</th>
                                <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider w-40 sticky top-0 bg-gray-800 z-30">Asset ID</th>
                                <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider w-24 sticky top-0 bg-gray-800 z-30">Week Due</th>
                                <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider w-24 sticky top-0 bg-gray-800 z-30">Frequency</th>
                                <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider w-36 sticky top-0 bg-gray-800 z-30">Completed By</th>
                                <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider w-24 sticky top-0 bg-gray-800 z-30">Status</th>
                                <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider w-28 sticky top-0 bg-gray-800 z-30">Actions</th>
                            </tr>

                            {{-- Filters Row (sticky below titles) --}}
                            <tr class="bg-gray-100">
                                <th class="px-2 py-2 sticky bg-gray-100 z-20" style="top: 48px;"></th>

                                <th class="px-2 py-2 sticky bg-gray-100 z-20" style="top: 48px;">
                                    <select form="filterForm" name="year" class="{{ $inp }}">
                                        @for($year = now()->year; $year >= now()->year - 10; $year--)
                                            <option value="{{ $year }}" @selected((string) request('year', now()->year) === (string) $year)>{{ $year }}</option>
                                        @endfor
                                    </select>
                                </th>

                                <th class="px-2 py-2 sticky bg-gray-100 z-20" style="top: 48px;">
                                    <input form="filterForm" type="text" name="job_id" value="{{ request('job_id') }}"
                                           placeholder="Filter…" autocomplete="off" class="{{ $inp }}">
                                </th>

                                <th class="px-2 py-2 sticky bg-gray-100 z-20" style="top: 48px;">
                                    <div class="flex gap-1.5">
                                        <select form="filterForm" name="type" class="{{ $inp }} w-16">
                                            <option value="">All</option>
                                            @foreach(['PNL', 'TRQ', 'TST'] as $t)
                                                <option value="{{ $t }}" @selected(request('type') === $t)>{{ $t }}</option>
                                            @endforeach
                                        </select>
                                        <input form="filterForm" type="text" name="asset_id" value="{{ request('asset_id') }}"
                                               placeholder="Starts with…" autocomplete="off" class="{{ $inp }}">
                                    </div>
                                </th>

                                <th class="px-2 py-2 sticky bg-gray-100 z-20" style="top: 48px;">
                                    <input form="filterForm" type="text" name="week_due" value="{{ request('week_due') }}"
                                           placeholder="Filter…" autocomplete="off" class="{{ $inp }}">
                                </th>

                                <th class="px-2 py-2 sticky bg-gray-100 z-20" style="top: 48px;">
                                    <select form="filterForm" name="frequency" class="{{ $inp }}">
                                        <option value="">All</option>
                                        @foreach($frequencies as $f)
                                            <option value="{{ $f }}" @selected(request('frequency') === $f)>{{ $f }}</option>
                                        @endforeach
                                    </select>
                                </th>

                                <th class="px-2 py-2 sticky bg-gray-100 z-20" style="top: 48px;">
                                    <input form="filterForm" type="text" name="completed_by" value="{{ request('completed_by') }}"
                                           placeholder="Name or matricule…" autocomplete="off" class="{{ $inp }}">
                                </th>

                                <th class="px-2 py-2 sticky bg-gray-100 z-20" style="top: 48px;">
                                    <select form="filterForm" name="state" class="{{ $inp }}">
                                        <option value="">All</option>
                                        <option value="verified" @selected(request('state') === 'verified')>Verified</option>
                                        <option value="not_verified" @selected(request('state') === 'not_verified')>Not verified</option>
                                        <option value="none" @selected(request('state') === 'none')>No checklist</option>
                                    </select>
                                </th>

                                <th class="px-2 py-2 sticky bg-gray-100 z-20" style="top: 48px;"></th>
                            </tr>
                        </thead>

                        <tbody id="rows" class="divide-y divide-gray-100 bg-white">
                            @include('quality.partials.rows')
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="links" class="mt-5">{{ $ppm_records->links() }}</div>
        </div>
    </div>

    <script>
        (function () {
            const form     = document.getElementById('filterForm');
            const rows     = document.getElementById('rows');
            const links    = document.getElementById('links');
            const total    = document.getElementById('total');
            const fields   = document.querySelectorAll('[form="filterForm"]');
            const loader   = document.getElementById('table-loading');
            const progress = document.getElementById('progress-bar');
            let timer;
            let activeRequests = 0;

            function buildUrl() {
                const params = new URLSearchParams(new FormData(form));
                params.delete('_token');
                params.delete('status');
                for (const [k, v] of [...params]) if (v === '') params.delete(k);
                const qs = params.toString();
                return form.action + (qs ? '?' + qs : '');
            }

            function showLoader() {
                activeRequests++;
                loader.classList.add('active');
                rows.classList.add('fading');
                progress.classList.remove('done');
                progress.classList.add('active');
            }

            function hideLoader() {
                activeRequests--;
                if (activeRequests <= 0) {
                    activeRequests = 0;
                    loader.classList.remove('active');
                    rows.classList.remove('fading');
                    progress.classList.remove('active');
                    progress.classList.add('done');
                    setTimeout(() => {
                        progress.classList.remove('done');
                        progress.style.width = '0';
                    }, 400);
                }
            }

            async function load(url) {
                showLoader();
                try {
                    const res = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    });
                    if (!res.ok) throw new Error('Request failed');
                    const data = await res.json();
                    rows.innerHTML    = data.rows;
                    links.innerHTML   = data.links;
                    total.textContent = data.total + ' result(s)';
                    history.replaceState(null, '', url);
                } catch (err) {
                    console.error(err);
                    rows.innerHTML = '<tr><td colspan="9" class="px-4 py-10 text-center text-sm text-red-600">Failed to load data. Please try again.</td></tr>';
                } finally {
                    hideLoader();
                }
            }

            fields.forEach(el => {
                if (el.tagName === 'SELECT') {
                    el.addEventListener('change', () => load(buildUrl()));
                } else {
                    el.addEventListener('input', () => {
                        clearTimeout(timer);
                        timer = setTimeout(() => load(buildUrl()), 300);
                    });
                    el.addEventListener('keydown', e => {
                        if (e.key === 'Enter') { e.preventDefault(); load(buildUrl()); }
                    });
                }
            });

            // Confirm before bulk actions
            form.querySelectorAll('[data-confirm]').forEach(btn =>
                btn.addEventListener('click', e => {
                    if (!confirm(btn.dataset.confirm)) e.preventDefault();
                })
            );

            // Ajax pagination
            links.addEventListener('click', e => {
                const a = e.target.closest('a');
                if (a) { e.preventDefault(); load(a.href); }
            });
        })();
    </script>
</x-app-layout>
