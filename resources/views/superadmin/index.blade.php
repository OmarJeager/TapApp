<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">PPM Records</h2>
    </x-slot>

    @php
        $inp = 'w-full rounded border-gray-300 px-2 py-1 text-xs text-gray-800';
    @endphp

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 rounded bg-green-100 px-4 py-3 text-green-800">{{ session('success') }}</div>
            @endif

            {{-- Bulk actions: apply to ALL records matching the header filters --}}
            <form id="filterForm" method="GET" action="{{ route('superadmin.index') }}"
                  class="mb-3 flex flex-wrap items-center gap-2">
                @csrf
                <span id="total" class="mr-2 text-sm text-gray-600">{{ $ppm_records->total() }} result(s)</span>

                <button type="submit" name="status" value="verified"
                        formmethod="POST" formaction="{{ route('superadmin.bulk') }}"
                        data-confirm="Mark ALL filtered records as VERIFIED?"
                        style="background-color:#16a34a;color:#fff"
                        class="rounded px-4 py-2 text-sm font-medium hover:opacity-90">
                    Verified (all filtered)
                </button>

                <button type="submit" name="status" value="not_verified"
                        formmethod="POST" formaction="{{ route('superadmin.bulk') }}"
                        data-confirm="Mark ALL filtered records as NOT VERIFIED?"
                        style="background-color:#dc2626;color:#fff"
                        class="rounded px-4 py-2 text-sm font-medium hover:opacity-90">
                    Not verified (all filtered)
                </button>

                <a href="{{ route('superadmin.index') }}"
                   class="rounded bg-gray-200 px-4 py-2 text-sm hover:bg-gray-300">Reset</a>
            </form>

            <div class="overflow-x-auto bg-white shadow sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        {{-- Titles --}}
                        <tr class="bg-gray-800 text-white">
                            <th class="px-4 py-3 text-left">#</th>
                            <th class="px-4 py-3 text-left">Job ID</th>
                            <th class="px-4 py-3 text-left">Asset ID</th>
                            <th class="px-4 py-3 text-left">Week Due</th>
                            <th class="px-4 py-3 text-left">Frequency</th>
                            <th class="px-4 py-3 text-left">Completed By</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>

                        {{-- Filters --}}
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2"></th>

                            <th class="px-2 py-2">
                                <input form="filterForm" type="text" name="job_id" value="{{ request('job_id') }}"
                                       placeholder="Filter…" autocomplete="off" class="{{ $inp }}">
                            </th>

                            <th class="px-2 py-2">
                                <div class="flex gap-1">
                                    <select form="filterForm" name="type" class="{{ $inp }} w-20">
                                        <option value="">All</option>
                                        @foreach(['PNL', 'TRQ', 'TST'] as $t)
                                            <option value="{{ $t }}" @selected(request('type') === $t)>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                    <input form="filterForm" type="text" name="asset_id" value="{{ request('asset_id') }}"
                                           placeholder="Starts with…" autocomplete="off" class="{{ $inp }}">
                                </div>
                            </th>

                            <th class="px-2 py-2">
                                <input form="filterForm" type="text" name="week_due" value="{{ request('week_due') }}"
                                       placeholder="Filter…" autocomplete="off" class="{{ $inp }}">
                            </th>

                            <th class="px-2 py-2">
                                <select form="filterForm" name="frequency" class="{{ $inp }}">
                                    <option value="">All</option>
                                    @foreach($frequencies as $f)
                                        <option value="{{ $f }}" @selected(request('frequency') === $f)>{{ $f }}</option>
                                    @endforeach
                                </select>
                            </th>

                            <th class="px-2 py-2">
                                <input form="filterForm" type="text" name="completed_by" value="{{ request('completed_by') }}"
                                       placeholder="Name or matricule…" autocomplete="off" class="{{ $inp }}">
                            </th>

                            <th class="px-2 py-2">
                                <select form="filterForm" name="state" class="{{ $inp }}">
                                    <option value="">All</option>
                                    <option value="verified" @selected(request('state') === 'verified')>Verified</option>
                                    <option value="not_verified" @selected(request('state') === 'not_verified')>Not verified</option>
                                    <option value="none" @selected(request('state') === 'none')>No checklist</option>
                                </select>
                            </th>

                            <th class="px-2 py-2"></th>
                        </tr>
                    </thead>

                    <tbody id="rows" class="divide-y divide-gray-100">
                        @include('superadmin.partials.rows')
                    </tbody>
                </table>
            </div>

            <div id="links" class="mt-4">{{ $ppm_records->links() }}</div>
        </div>
    </div>

    <script>
        (function () {
            const form   = document.getElementById('filterForm');
            const rows   = document.getElementById('rows');
            const links  = document.getElementById('links');
            const total  = document.getElementById('total');
            const fields = document.querySelectorAll('[form="filterForm"]');
            let timer;

            function buildUrl() {
                const params = new URLSearchParams(new FormData(form));
                params.delete('_token');
                params.delete('status');
                for (const [k, v] of [...params]) if (v === '') params.delete(k);
                const qs = params.toString();
                return form.action + (qs ? '?' + qs : '');
            }

            async function load(url) {
                const res = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await res.json();
                rows.innerHTML    = data.rows;
                links.innerHTML   = data.links;
                total.textContent = data.total + ' result(s)';
                history.replaceState(null, '', url);
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
