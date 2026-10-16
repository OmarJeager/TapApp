<x-app-layout>
    @php
        // Icon + color per event type (order matters: specific before generic)
        $eventStyle = function (string $event): array {
            return match (true) {
                str_contains($event, 'unsuspend')    => ['fa-lock-open', 'green'],
                str_contains($event, 'suspend')      => ['fa-user-lock', 'red'],
                str_contains($event, 'force_logout') => ['fa-power-off', 'amber'],
                str_contains($event, 'logout')       => ['fa-right-from-bracket', 'gray'],
                str_contains($event, 'login')        => ['fa-right-to-bracket', 'blue'],
                str_contains($event, 'delete')       => ['fa-trash', 'red'],
                str_contains($event, 'export')       => ['fa-file-export', 'purple'],
                str_contains($event, 'create')       => ['fa-user-plus', 'green'],
                str_contains($event, 'update')       => ['fa-pen-to-square', 'blue'],
                default                              => ['fa-bolt', 'orange'],
            };
        };
        $filtersActive = collect(['event', 'user', 'from', 'to'])->filter(fn ($k) => request()->filled($k))->count();
    @endphp

    <div class="audit-shell">
        {{-- HERO --}}
        <header class="audit-hero">
            <div class="hero-icon"><i class="fa-solid fa-shield-halved"></i></div>
            <div class="hero-copy">
                <span class="eyebrow">TAPAPP / SECURITY CENTER</span>
                <h1>User Activity Timeline</h1>
                <p>Review account events, administrative actions and session security.</p>
            </div>
            <a class="audit-export" href="{{ route('ceo.security.activity.export', request()->only(['event', 'user', 'from', 'to'])) }}">
                <i class="fa-solid fa-file-csv"></i> Export CSV
            </a>
        </header>

        {{-- TABS --}}
        <nav class="sec-tabs">
            <a href="{{ route('ceo.security.activity') }}" class="on"><i class="fa-solid fa-timeline"></i> Activity</a>
            <a href="{{ route('ceo.security.users') }}"><i class="fa-solid fa-users-gear"></i> Users</a>
        </nav>

        @if(session('success'))
            <div class="audit-alert" data-autohide><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
        @endif

        {{-- METRICS --}}
        <section class="audit-metrics">
            <article><span class="metric-icon orange"><i class="fa-solid fa-list-check"></i></span><div><small>Events in view</small><strong data-count="{{ $logs->total() }}">0</strong></div></article>
            <article><span class="metric-icon blue"><i class="fa-solid fa-filter"></i></span><div><small>Event types</small><strong data-count="{{ $events->count() }}">0</strong></div></article>
            <article><span class="metric-icon purple"><i class="fa-solid fa-calendar-day"></i></span><div><small>Events today</small><strong data-count="{{ $todayCount ?? 0 }}">0</strong></div></article>
            <article><span class="metric-icon green"><i class="fa-solid fa-clock-rotate-left"></i></span><div><small>Latest event</small><strong class="small">{{ $logs->first()?->created_at?->diffForHumans() ?? 'No events' }}</strong></div></article>
        </section>

        {{-- FILTERS --}}
        <form class="audit-filters" method="GET">
            <label><span>Event type</span>
                <select name="event">
                    <option value="">All events</option>
                    @foreach($events as $event)
                        <option value="{{ $event }}" @selected(request('event') === $event)>{{ $event }}</option>
                    @endforeach
                </select>
            </label>
            <label><span>User ID</span><input type="number" min="1" name="user" value="{{ request('user') }}" placeholder="Actor or target ID"></label>
            <label><span>From</span><input type="date" name="from" value="{{ request('from') }}"></label>
            <label><span>To</span><input type="date" name="to" value="{{ request('to') }}"></label>
            <button type="submit"><i class="fa-solid fa-sliders"></i> Apply filters
                @if($filtersActive)<em>{{ $filtersActive }}</em>@endif
            </button>
            <a href="{{ route('ceo.security.activity') }}">Reset</a>
        </form>

        {{-- TIMELINE --}}
        <section class="timeline-card">
            <div class="timeline-heading">
                <div><h2>Audit trail</h2><p>Newest activity appears first.</p></div>
                <span class="live-chip"><i></i> AUDIT LOG</span>
            </div>

            <div class="timeline-list">
                @forelse($logs as $i => $log)
                    @php [$icon, $tone] = $eventStyle($log->event); @endphp
                    <article class="timeline-row" style="animation-delay: {{ min($i, 12) * 40 }}ms">
                        <div class="timeline-mark tone-{{ $tone }}"><i class="fa-solid {{ $icon }}"></i></div>
                        <div class="timeline-content">
                            <div class="timeline-top">
                                <strong>{{ $log->description ?: str($log->event)->replace('.', ' ')->headline() }}</strong>
                                <time title="{{ $log->created_at?->diffForHumans() }}"><i class="fa-regular fa-clock"></i> {{ $log->created_at?->format('d M Y · H:i:s') }}</time>
                            </div>
                            <p>
                                <span class="who"><i class="fa-solid fa-user"></i> {{ $log->actor?->name ?? 'System / deleted user' }}</span>
                                @if($log->target)
                                    <span class="arrow">→</span>
                                    <a class="who target" href="{{ route('ceo.security.activity', ['user' => $log->target->id]) }}" title="Show all activity for this user">
                                        <i class="fa-solid fa-bullseye"></i> {{ $log->target->name }}
                                    </a>
                                @endif
                            </p>
                            <div class="timeline-meta">
                                <a class="event-tag tone-{{ $tone }}" href="{{ route('ceo.security.activity', ['event' => $log->event]) }}" title="Filter by this event">{{ $log->event }}</a>
                                @if($log->ip_address)<span><i class="fa-solid fa-network-wired"></i> {{ $log->ip_address }}</span>@endif
                                @if($log->method)<span class="method">{{ $log->method }}</span>@endif
                                @if($log->route_name)<span><i class="fa-solid fa-route"></i> {{ $log->route_name }}</span>@endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="audit-empty">
                        <i class="fa-solid fa-shield-heart"></i>
                        <h3>No activity found</h3>
                        <p>Try adjusting your filters or return after new events are recorded.</p>
                    </div>
                @endforelse
            </div>

            <div class="audit-pagination">{{ $logs->links() }}</div>
        </section>
    </div>

    <style>
        .audit-shell{max-width:1500px;margin:auto;padding:clamp(18px,3vw,38px);color:#172033;min-height:100vh;background:radial-gradient(ellipse at 90% 0%,rgba(249,115,22,.13),transparent 34%);animation:auditEnter .55s ease both}
        @keyframes auditEnter{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
        .audit-hero{display:flex;align-items:center;gap:18px;margin-bottom:22px;flex-wrap:wrap}
        .hero-icon{width:64px;height:64px;border-radius:20px;display:grid;place-items:center;background:linear-gradient(140deg,#ff9b38,#ea580c);color:#fff;font-size:26px;box-shadow:0 12px 32px #f973163d;animation:heroFloat 4s ease-in-out infinite}
        @keyframes heroFloat{50%{transform:translateY(-5px) rotate(2deg)}}
        .hero-copy{flex:1;min-width:220px}.eyebrow{font-size:10px;letter-spacing:2px;font-weight:900;color:#ea580c}
        .audit-hero h1{font-size:clamp(24px,3vw,34px);font-weight:850;margin:4px 0}.audit-hero p,.timeline-heading p{color:#7b8498;margin:0;font-size:13px}
        .audit-export,.audit-filters button{border:0;cursor:pointer;border-radius:12px;background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;text-decoration:none;padding:13px 17px;font-weight:800;font-size:14px;display:inline-flex;align-items:center;gap:9px;box-shadow:0 8px 22px #f9731628;transition:.22s}
        .audit-export:hover,.audit-filters button:hover{transform:translateY(-2px);box-shadow:0 12px 25px #f9731640}.audit-export:active,.audit-filters button:active{transform:scale(.97)}
        .audit-filters button em{font-style:normal;background:#fff;color:#ea580c;border-radius:999px;font-size:11px;min-width:20px;height:20px;display:grid;place-items:center;padding:0 5px}

        /* Tabs */
        .sec-tabs{display:inline-flex;gap:4px;background:#fff;border:1px solid #e9edf3;padding:5px;border-radius:14px;margin-bottom:20px;box-shadow:0 6px 20px #17203306}
        .sec-tabs a{padding:10px 16px;border-radius:10px;text-decoration:none;color:#697386;font-weight:750;font-size:13px;display:inline-flex;gap:8px;align-items:center;transition:.2s}
        .sec-tabs a:hover{color:#ea580c;background:#fff6ee}.sec-tabs a.on{background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;box-shadow:0 6px 16px #f9731633}

        .audit-alert{padding:14px 17px;border-radius:13px;background:#ecfdf3;color:#047857;margin-bottom:18px;border:1px solid #bbf7d0;display:flex;gap:10px;align-items:center;animation:alertDrop .4s ease both;transition:opacity .4s,transform .4s}
        .audit-alert.hide{opacity:0;transform:translateY(-8px)}
        @keyframes alertDrop{from{opacity:0;transform:translateY(-10px)}to{opacity:1;transform:none}}

        /* Metrics */
        .audit-metrics{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:15px;margin-bottom:20px}
        .audit-metrics article{background:#fff;border:1px solid #e9edf3;border-radius:17px;padding:19px;display:flex;align-items:center;gap:13px;box-shadow:0 9px 30px #17203308;transition:.25s}
        .audit-metrics article:hover{transform:translateY(-4px);box-shadow:0 15px 32px #17203312}
        .metric-icon{width:44px;height:44px;display:grid;place-items:center;border-radius:14px;font-size:18px;flex:0 0 44px}
        .metric-icon.orange{background:#fff1e5;color:#ea580c}.metric-icon.blue{background:#eaf3ff;color:#2563eb}.metric-icon.green{background:#dcfce7;color:#15803d}.metric-icon.purple{background:#f3e8ff;color:#7e22ce}
        .audit-metrics small{display:block;color:#8490a4;font-weight:700;font-size:11px;text-transform:uppercase;letter-spacing:.5px}
        .audit-metrics strong{display:block;font-size:24px;margin-top:4px;overflow-wrap:anywhere}.audit-metrics strong.small{font-size:16px;margin-top:8px}

        /* Filters */
        .audit-filters{display:flex;align-items:end;gap:12px;flex-wrap:wrap;background:#fff;padding:17px;border:1px solid #e9edf3;border-radius:17px;margin-bottom:20px;box-shadow:0 8px 25px #17203308}
        .audit-filters label{display:grid;gap:6px;flex:1;min-width:145px}
        .audit-filters label span{font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:#7b8498;font-weight:800}
        .audit-filters input,.audit-filters select{width:100%;height:43px;border:1px solid #e1e6ef;background:#fbfcfe;border-radius:10px;padding:0 11px;outline:0;transition:.2s}
        .audit-filters input:focus,.audit-filters select:focus{border-color:#f97316;box-shadow:0 0 0 3px #f973161c;background:#fff}
        .audit-filters a{padding:12px;color:#697386;text-decoration:none;font-weight:700}.audit-filters a:hover{color:#ea580c}

        /* Timeline */
        .timeline-card{background:#fff;border:1px solid #e9edf3;border-radius:20px;overflow:hidden;box-shadow:0 12px 38px #17203309}
        .timeline-heading{display:flex;justify-content:space-between;align-items:center;padding:22px 24px;border-bottom:1px solid #eef0f4}
        .timeline-heading h2{margin:0;font-size:19px;font-weight:850}
        .live-chip{background:#fff4e7;color:#c65b08;padding:7px 10px;border-radius:9px;font-size:10px;font-weight:900;letter-spacing:1px}
        .live-chip i{display:inline-block;width:6px;height:6px;border-radius:50%;background:#f97316;margin-right:5px;animation:livePulse 1.8s infinite}
        @keyframes livePulse{0%{box-shadow:0 0 0 0 #f9731666}100%{box-shadow:0 0 0 8px #f9731600}}

        .timeline-list{position:relative}
        .timeline-row{display:flex;gap:15px;padding:20px 24px;border-bottom:1px solid #f0f2f6;animation:rowSlide .4s ease both;transition:background .2s}
        .timeline-row:hover{background:#fffaf5}.timeline-row:hover .timeline-mark{transform:scale(1.08) rotate(-4deg)}
        @keyframes rowSlide{from{opacity:0;transform:translateX(-10px)}to{opacity:1;transform:none}}
        .timeline-mark{width:38px;height:38px;flex:0 0 38px;border-radius:13px;display:grid;place-items:center;transition:transform .25s}
        .timeline-content{min-width:0;flex:1}.timeline-top{display:flex;justify-content:space-between;gap:15px;flex-wrap:wrap}
        .timeline-top strong{font-size:14px}.timeline-top time{font-size:11px;color:#8a94a6}.timeline-top time i{margin-right:4px}
        .timeline-content p{font-size:12px;color:#687386;margin:7px 0 10px;display:flex;align-items:center;gap:6px;flex-wrap:wrap}
        .who{display:inline-flex;align-items:center;gap:6px;font-weight:700;color:#344054}.who i{color:#f97316;font-size:11px}
        a.who.target{text-decoration:none;background:#fff6ee;padding:3px 8px;border-radius:7px;transition:.2s}a.who.target:hover{background:#ffe8d1;color:#c2410c}
        .arrow{color:#f97316;padding:0 3px}
        .timeline-meta{display:flex;gap:12px;align-items:center;flex-wrap:wrap;font-size:11px;color:#7d8798}
        .timeline-meta i{color:#f97316;margin-right:4px}
        .event-tag{font-family:ui-monospace,monospace;border-radius:6px;padding:4px 8px;text-decoration:none;font-weight:700;transition:.2s}.event-tag:hover{transform:translateY(-1px);filter:brightness(.96)}
        .method{font-weight:800;background:#f3f5f8;color:#475569;padding:3px 7px;border-radius:6px;font-size:10px}

        /* Tone colors (icon bubble + event tag) */
        .tone-orange{background:#fff1e5;color:#ea580c}.tone-blue{background:#eaf3ff;color:#2563eb}.tone-green{background:#dcfce7;color:#15803d}
        .tone-red{background:#fee2e2;color:#dc2626}.tone-amber{background:#fef3c7;color:#b45309}.tone-purple{background:#f3e8ff;color:#7e22ce}.tone-gray{background:#eef1f5;color:#64748b}

        .audit-empty{text-align:center;padding:65px 20px;color:#8791a3}.audit-empty>i{font-size:36px;color:#f5b16e;animation:heroFloat 4s ease-in-out infinite}
        .audit-empty h3{color:#344054;margin:12px 0 5px}.audit-empty p{margin:0}.audit-pagination{padding:18px;display:flex;justify-content:center}

        a:focus-visible,button:focus-visible,input:focus-visible,select:focus-visible{outline:2px solid #f97316;outline-offset:2px}
        @media(max-width:900px){.audit-metrics{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:700px){.audit-metrics{grid-template-columns:1fr}.audit-hero{align-items:flex-start}.audit-export{width:100%;justify-content:center}.timeline-row{padding:16px}.timeline-heading{padding:18px}.timeline-top{display:block}.timeline-top time{display:block;margin-top:5px}.audit-filters label{min-width:100%}}
        @media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}
    </style>

    <script>
        // Count-up numbers
        document.querySelectorAll('[data-count]').forEach(el => {
            const end = +el.dataset.count, t0 = performance.now(), dur = 700;
            const tick = t => {
                const p = Math.min((t - t0) / dur, 1);
                el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3)));
                if (p < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        });
        // Auto-hide flash message
        document.querySelectorAll('[data-autohide]').forEach(el => setTimeout(() => {
            el.classList.add('hide'); setTimeout(() => el.remove(), 400);
        }, 5000));
    </script>
</x-app-layout>