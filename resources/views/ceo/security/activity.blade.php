<x-app-layout>
    @php
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
        $filtersActive = collect(['event', 'user', 'from', 'to', 'severity'])
            ->filter(fn ($k) => request()->filled($k))->count();
    @endphp

    <div class="audit-scene">
        {{-- 4D depth layers --}}
        <div class="depth-grid"></div>
        <div class="depth-orb orb-a"></div>
        <div class="depth-orb orb-b"></div>
        <div class="depth-orb orb-c"></div>

        <div class="audit-shell" data-tilt-root>
            {{-- HERO --}}
            <header class="audit-hero" data-tilt="6">
                <div class="hero-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <div class="hero-copy">
                    <span class="eyebrow">TAPAPP / SECURITY CENTER</span>
                    <h1>User Activity Timeline</h1>
                    <p>Review account events, administrative actions and session security.</p>
                </div>
                <a class="audit-export" href="{{ route('ceo.security.activity.export', request()->only(['event', 'user', 'from', 'to', 'severity'])) }}">
                    <i class="fa-solid fa-file-csv"></i> Export CSV
                </a>
            </header>

            {{-- TABS --}}
            <nav class="sec-tabs" data-tilt="4">
                <a href="{{ route('ceo.security.activity') }}" class="on"><i class="fa-solid fa-timeline"></i> Activity</a>
                <a href="{{ route('ceo.security.users') }}"><i class="fa-solid fa-users-gear"></i> Users</a>
            </nav>

            @if(session('success'))
                <div class="audit-alert" data-autohide><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
            @endif

            {{-- METRICS --}}
            <section class="audit-metrics">
                <article data-tilt="8" data-depth="1"><span class="metric-icon orange"><i class="fa-solid fa-list-check"></i></span><div><small>Events in view</small><strong data-count="{{ $logs->total() }}">0</strong></div></article>
                <article data-tilt="8" data-depth="2"><span class="metric-icon blue"><i class="fa-solid fa-filter"></i></span><div><small>Event types</small><strong data-count="{{ $events->count() }}">0</strong></div></article>
                <article data-tilt="8" data-depth="3"><span class="metric-icon purple"><i class="fa-solid fa-calendar-day"></i></span><div><small>Events today</small><strong data-count="{{ $todayCount ?? 0 }}">0</strong></div></article>
                <article data-tilt="8" data-depth="4"><span class="metric-icon green"><i class="fa-solid fa-clock-rotate-left"></i></span><div><small>Latest event</small><strong class="small">{{ $logs->first()?->created_at?->diffForHumans() ?? 'No events' }}</strong></div></article>
            </section>

            {{-- PRESENCE STRIP --}}
            <section class="presence-strip" data-tilt="4">
                <span class="presence-label"><i class="fa-solid fa-signal"></i> Online now</span>
                <div class="presence-avatars" id="presenceAvatars">
                    <span class="presence-loading"><i class="fa-solid fa-circle-notch fa-spin"></i></span>
                </div>
            </section>

            {{-- FILTERS --}}
            <form class="audit-filters" method="GET" data-tilt="4">
                <label><span>Event type</span>
                    <select name="event">
                        <option value="">All events</option>
                        @foreach($events as $event)
                            <option value="{{ $event }}" @selected(request('event') === $event)>{{ $event }}</option>
                        @endforeach
                    </select>
                </label>
                <label><span>Severity</span>
                    <select name="severity">
                        <option value="">All severities</option>
                        @foreach(\App\Models\UserActivityLog::SEVERITIES as $sev)
                            <option value="{{ $sev }}" @selected(request('severity') === $sev)>{{ ucfirst($sev) }}</option>
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

            {{-- HEATMAP --}}
            <section class="heatmap-card" data-tilt="3">
                <header>
                    <div><h2>Activity heatmap</h2><p>Events by weekday and hour — last 30 days.</p></div>
                    <span class="live-chip"><i></i> LIVE DATA</span>
                </header>
                <div class="heatmap-grid" id="heatmapGrid">
                    <div class="heatmap-loading"><i class="fa-solid fa-circle-notch fa-spin"></i></div>
                </div>
            </section>

            {{-- TIMELINE --}}
            <section class="timeline-card" data-tilt="2">
                <div class="timeline-heading">
                    <div><h2>Audit trail</h2><p>Newest activity appears first.</p></div>
                    <span class="live-chip"><i></i> AUDIT LOG</span>
                </div>

                <div class="timeline-list" id="timelineList">
                    @forelse($logs as $i => $log)
                        @php [$icon, $tone] = $eventStyle($log->event); @endphp
                        <article class="timeline-row" data-severity="{{ $log->severity }}" style="animation-delay: {{ min($i, 12) * 40 }}ms">
                            <div class="timeline-mark tone-{{ $tone }}"><i class="fa-solid {{ $icon }}"></i></div>
                            <div class="timeline-content">
                                <div class="timeline-top">
                                    <strong>{{ $log->description ?: str($log->event)->replace('.', ' ')->headline() }}</strong>
                                    <time title="{{ $log->created_at?->diffForHumans() }}"><i class="fa-regular fa-clock"></i> {{ $log->created_at?->format('d M Y · H:i:s') }}</time>
                                </div>
                                <p>
                                    <span class="who"><i class="fa-solid fa-user"></i> {{ $log->actor?->name ?? $log->actor_name ?? 'System / deleted user' }}</span>
                                    @if($log->target || $log->target_name)
                                        <span class="arrow">→</span>
                                        <a class="who target" href="{{ route('ceo.security.activity', ['user' => $log->target_user_id]) }}" title="Show all activity for this user">
                                            <i class="fa-solid fa-bullseye"></i> {{ $log->target?->name ?? $log->target_name }}
                                        </a>
                                    @endif
                                </p>
                                <div class="timeline-meta">
                                    <a class="event-tag tone-{{ $tone }}" href="{{ route('ceo.security.activity', ['event' => $log->event]) }}" title="Filter by this event">{{ $log->event }}</a>
                                    <span class="sev sev-{{ $log->severity }}">{{ ucfirst($log->severity) }}</span>
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
    </div>

    <style>
        /* ============================================================
           SCENE / DEPTH / 4D
        ============================================================ */
        .audit-scene{
            position:relative;
            min-height:100vh;
            overflow:hidden;
            background:
                radial-gradient(ellipse at 90% 0%, rgba(249,115,22,.18), transparent 42%),
                radial-gradient(ellipse at 5% 60%, rgba(59,130,246,.14), transparent 45%),
                radial-gradient(ellipse at 50% 110%, rgba(168,85,247,.12), transparent 50%),
                linear-gradient(180deg,#f7f8fb 0%,#eef1f7 100%);
            perspective:1400px;
        }
        .depth-grid{
            position:absolute;inset:-10%;
            background-image:
                linear-gradient(rgba(23,32,51,.05) 1px,transparent 1px),
                linear-gradient(90deg,rgba(23,32,51,.05) 1px,transparent 1px);
            background-size:44px 44px;
            transform:translateZ(-320px) rotateX(58deg);
            transform-origin:center top;
            mask-image:linear-gradient(180deg,#000 0%,transparent 72%);
            -webkit-mask-image:linear-gradient(180deg,#000 0%,transparent 72%);
            animation:gridDrift 24s linear infinite;
            pointer-events:none;
        }
        @keyframes gridDrift{
            0%{background-position:0 0,0 0}
            100%{background-position:44px 44px,44px 44px}
        }
        .depth-orb{
            position:absolute;border-radius:50%;filter:blur(60px);opacity:.5;pointer-events:none;
            transform:translateZ(-120px);
        }
        .orb-a{width:420px;height:420px;top:-120px;right:-80px;background:radial-gradient(circle,#fb923c,transparent 70%);animation:floatA 14s ease-in-out infinite}
        .orb-b{width:340px;height:340px;bottom:10%;left:-100px;background:radial-gradient(circle,#60a5fa,transparent 70%);animation:floatB 18s ease-in-out infinite}
        .orb-c{width:280px;height:280px;top:40%;left:45%;background:radial-gradient(circle,#c084fc,transparent 70%);animation:floatC 22s ease-in-out infinite}
        @keyframes floatA{50%{transform:translate3d(-40px,30px,-120px) scale(1.08)}}
        @keyframes floatB{50%{transform:translate3d(50px,-40px,-120px) scale(1.12)}}
        @keyframes floatC{50%{transform:translate3d(-30px,-50px,-120px) scale(1.06)}}

        .audit-shell{
            position:relative;z-index:2;
            max-width:1500px;margin:auto;padding:clamp(18px,3vw,38px);
            color:#172033;min-height:100vh;
            animation:auditEnter .6s ease both;
            transform-style:preserve-3d;
        }
        @keyframes auditEnter{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}

        /* ============================================================
           TILT CARDS (JS-driven CSS vars)
        ============================================================ */
        [data-tilt]{
            transform:perspective(900px) rotateX(var(--rx,0deg)) rotateY(var(--ry,0deg)) translateZ(var(--tz,0px));
            transition:transform .35s cubic-bezier(.2,.8,.2,1), box-shadow .3s;
            will-change:transform;
        }
        [data-depth="1"]{--tz:6px}
        [data-depth="2"]{--tz:4px}
        [data-depth="3"]{--tz:2px}
        [data-depth="4"]{--tz:1px}

        /* ============================================================
           HERO
        ============================================================ */
        .audit-hero{
            display:flex;align-items:center;gap:18px;margin-bottom:22px;flex-wrap:wrap;
            background:linear-gradient(135deg,rgba(255,255,255,.9),rgba(255,255,255,.65));
            border:1px solid rgba(255,255,255,.7);
            border-radius:22px;padding:20px 22px;
            box-shadow:0 20px 45px -25px rgba(23,32,51,.35), inset 0 1px 0 rgba(255,255,255,.9);
            backdrop-filter:blur(10px);
        }
        .hero-icon{
            width:64px;height:64px;border-radius:20px;display:grid;place-items:center;
            background:linear-gradient(140deg,#ff9b38,#ea580c);color:#fff;font-size:26px;
            box-shadow:0 12px 32px #f973163d, inset 0 -2px 0 rgba(0,0,0,.15);
            animation:heroFloat 4s ease-in-out infinite;
            transform:translateZ(30px);
        }
        @keyframes heroFloat{50%{transform:translateY(-6px) rotate(2deg)}}
        .hero-copy{flex:1;min-width:220px}
        .eyebrow{font-size:10px;letter-spacing:2px;font-weight:900;color:#ea580c}
        .audit-hero h1{font-size:clamp(24px,3vw,34px);font-weight:850;margin:4px 0;
            background:linear-gradient(120deg,#172033,#ea580c 70%);
            -webkit-background-clip:text;background-clip:text;color:transparent}
        .audit-hero p,.timeline-heading p,.heatmap-card p{color:#7b8498;margin:0;font-size:13px}

        .audit-export,.audit-filters button{
            border:0;cursor:pointer;border-radius:12px;
            background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;text-decoration:none;
            padding:13px 17px;font-weight:800;font-size:14px;display:inline-flex;align-items:center;gap:9px;
            box-shadow:0 10px 24px #f9731633, inset 0 -2px 0 rgba(0,0,0,.12);
            transition:.22s;position:relative;overflow:hidden;
        }
        .audit-export:after,.audit-filters button:after{
            content:"";position:absolute;inset:0;
            background:linear-gradient(120deg,transparent 30%,rgba(255,255,255,.35) 50%,transparent 70%);
            transform:translateX(-120%);transition:transform .6s;
        }
        .audit-export:hover:after,.audit-filters button:hover:after{transform:translateX(120%)}
        .audit-export:hover,.audit-filters button:hover{transform:translateY(-2px);box-shadow:0 14px 28px #f9731640}
        .audit-export:active,.audit-filters button:active{transform:scale(.97)}
        .audit-filters button em{font-style:normal;background:#fff;color:#ea580c;border-radius:999px;font-size:11px;min-width:20px;height:20px;display:grid;place-items:center;padding:0 5px}

        /* ============================================================
           TABS
        ============================================================ */
        .sec-tabs{
            display:inline-flex;gap:4px;background:rgba(255,255,255,.85);
            border:1px solid #e9edf3;padding:5px;border-radius:14px;margin-bottom:20px;
            box-shadow:0 10px 24px -18px rgba(23,32,51,.4);backdrop-filter:blur(6px);
        }
        .sec-tabs a{padding:10px 16px;border-radius:10px;text-decoration:none;color:#697386;font-weight:750;font-size:13px;display:inline-flex;gap:8px;align-items:center;transition:.2s}
        .sec-tabs a:hover{color:#ea580c;background:#fff6ee}
        .sec-tabs a.on{background:linear-gradient(135deg,#f97316,#ea580c);color:#fff;box-shadow:0 8px 18px #f9731633}

        /* ============================================================
           ALERTS
        ============================================================ */
        .audit-alert{padding:14px 17px;border-radius:13px;background:#ecfdf3;color:#047857;margin-bottom:18px;border:1px solid #bbf7d0;display:flex;gap:10px;align-items:center;animation:alertDrop .4s ease both;transition:opacity .4s,transform .4s}
        .audit-alert.hide{opacity:0;transform:translateY(-8px)}
        @keyframes alertDrop{from{opacity:0;transform:translateY(-10px)}to{opacity:1;transform:none}}

        /* ============================================================
           METRICS
        ============================================================ */
        .audit-metrics{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:15px;margin-bottom:20px;transform-style:preserve-3d}
        .audit-metrics article{
            background:linear-gradient(150deg,rgba(255,255,255,.98),rgba(255,255,255,.82));
            border:1px solid #e9edf3;border-radius:18px;padding:19px;display:flex;align-items:center;gap:13px;
            box-shadow:0 18px 32px -22px rgba(23,32,51,.35), inset 0 1px 0 #fff;
            transition:transform .35s cubic-bezier(.2,.8,.2,1), box-shadow .35s;
            position:relative;overflow:hidden;
        }
        .audit-metrics article:before{
            content:"";position:absolute;inset:0;border-radius:18px;padding:1px;pointer-events:none;
            background:linear-gradient(140deg,rgba(249,115,22,.5),transparent 40%,rgba(59,130,246,.4));
            -webkit-mask:linear-gradient(#000 0 0) content-box,linear-gradient(#000 0 0);
            -webkit-mask-composite:xor;mask-composite:exclude;opacity:0;transition:opacity .3s;
        }
        .audit-metrics article:hover:before{opacity:1}
        .audit-metrics article:hover{transform:translateY(-6px) translateZ(24px);box-shadow:0 30px 44px -25px rgba(249,115,22,.55)}
        .metric-icon{width:44px;height:44px;display:grid;place-items:center;border-radius:14px;font-size:18px;flex:0 0 44px;box-shadow:inset 0 -2px 0 rgba(0,0,0,.08)}
        .metric-icon.orange{background:#fff1e5;color:#ea580c}
        .metric-icon.blue{background:#eaf3ff;color:#2563eb}
        .metric-icon.green{background:#dcfce7;color:#15803d}
        .metric-icon.purple{background:#f3e8ff;color:#7e22ce}
        .audit-metrics small{display:block;color:#8490a4;font-weight:700;font-size:11px;text-transform:uppercase;letter-spacing:.5px}
        .audit-metrics strong{display:block;font-size:24px;margin-top:4px;overflow-wrap:anywhere}
        .audit-metrics strong.small{font-size:16px;margin-top:8px}

        /* ============================================================
           PRESENCE STRIP
        ============================================================ */
        .presence-strip{
            display:flex;align-items:center;gap:14px;
            background:linear-gradient(135deg,rgba(255,255,255,.95),rgba(255,255,255,.78));
            border:1px solid #e9edf3;border-radius:14px;padding:12px 18px;margin-bottom:20px;
            box-shadow:0 12px 24px -22px rgba(23,32,51,.4);backdrop-filter:blur(6px);
        }
        .presence-label{font-size:11px;font-weight:900;letter-spacing:1px;text-transform:uppercase;color:#7b8498;display:inline-flex;align-items:center;gap:6px;flex:0 0 auto}
        .presence-label i{color:#22c55e;animation:pulseDot 1.6s ease-in-out infinite}
        @keyframes pulseDot{50%{opacity:.35}}
        .presence-avatars{display:flex;gap:6px;flex-wrap:wrap}
        .presence-avatars .av{
            position:relative;width:34px;height:34px;border-radius:11px;
            display:grid;place-items:center;background:linear-gradient(140deg,#ffb066,#ea580c);
            color:#fff;font-weight:800;font-size:13px;transition:transform .25s;
            box-shadow:0 6px 14px -6px rgba(249,115,22,.6);
        }
        .presence-avatars .av:hover{transform:translateY(-3px) scale(1.06)}
        .presence-avatars .av:after{
            content:"";position:absolute;right:-2px;bottom:-2px;width:10px;height:10px;
            border-radius:50%;border:2px solid #fff;background:#22c55e;
            box-shadow:0 0 0 0 rgba(34,197,94,.6);animation:ring 2s ease-out infinite;
        }
        @keyframes ring{0%{box-shadow:0 0 0 0 rgba(34,197,94,.6)}100%{box-shadow:0 0 0 8px rgba(34,197,94,0)}}
        .presence-avatars .av.away:after{background:#f59e0b;animation:none}
        .presence-avatars .av.suspended:after{background:#ef4444;animation:none}
        .presence-avatars .empty,.presence-loading{font-size:12px;color:#8a94a6}

        /* ============================================================
           FILTERS
        ============================================================ */
        .audit-filters{
            display:flex;align-items:end;gap:12px;flex-wrap:wrap;
            background:linear-gradient(135deg,rgba(255,255,255,.98),rgba(255,255,255,.85));
            padding:17px;border:1px solid #e9edf3;border-radius:17px;margin-bottom:20px;
            box-shadow:0 18px 32px -26px rgba(23,32,51,.5);backdrop-filter:blur(6px);
        }
        .audit-filters label{display:grid;gap:6px;flex:1;min-width:145px}
        .audit-filters label span{font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:#7b8498;font-weight:800}
        .audit-filters input,.audit-filters select{
            width:100%;height:43px;border:1px solid #e1e6ef;background:#fbfcfe;
            border-radius:10px;padding:0 11px;outline:0;transition:.2s;font:inherit;
        }
        .audit-filters input:focus,.audit-filters select:focus{border-color:#f97316;box-shadow:0 0 0 4px #f9731620;background:#fff}
        .audit-filters a{padding:12px;color:#697386;text-decoration:none;font-weight:700;border-radius:10px}
        .audit-filters a:hover{color:#ea580c;background:#fff6ee}

        /* ============================================================
           HEATMAP
        ============================================================ */
        .heatmap-card{
            background:linear-gradient(150deg,rgba(255,255,255,.98),rgba(255,255,255,.85));
            border:1px solid #e9edf3;border-radius:20px;padding:22px;margin-bottom:22px;
            box-shadow:0 22px 40px -30px rgba(23,32,51,.55);backdrop-filter:blur(6px);
        }
        .heatmap-card header{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}
        .heatmap-card h2{margin:0;font-size:17px;font-weight:850}
        .heatmap-grid{display:grid;grid-template-columns:auto repeat(24,minmax(0,1fr));gap:3px;font-size:9px}
        .hm-row-label{color:#8a94a6;font-weight:800;text-transform:uppercase;align-self:center;padding-right:6px}
        .hm-cell{aspect-ratio:1;border-radius:5px;background:#fff1e5;transition:.15s;cursor:default;position:relative}
        .hm-cell:hover{transform:scale(1.22);z-index:2;box-shadow:0 6px 16px rgba(249,115,22,.35)}
        .hm-hour{color:#8a94a6;font-weight:700;text-align:center}
        .heatmap-loading{grid-column:1/-1;display:grid;place-items:center;height:180px;color:#f97316;font-size:22px}

        /* ============================================================
           TIMELINE
        ============================================================ */
        .timeline-card{
            background:linear-gradient(150deg,rgba(255,255,255,.98),rgba(255,255,255,.85));
            border:1px solid #e9edf3;border-radius:20px;overflow:hidden;
            box-shadow:0 24px 44px -32px rgba(23,32,51,.6);backdrop-filter:blur(6px);
        }
        .timeline-heading{display:flex;justify-content:space-between;align-items:center;padding:22px 24px;border-bottom:1px solid #eef0f4}
        .timeline-heading h2{margin:0;font-size:19px;font-weight:850}
        .live-chip{background:#fff4e7;color:#c65b08;padding:7px 10px;border-radius:9px;font-size:10px;font-weight:900;letter-spacing:1px}
        .live-chip i{display:inline-block;width:6px;height:6px;border-radius:50%;background:#f97316;margin-right:5px;animation:livePulse 1.8s infinite}
        @keyframes livePulse{0%{box-shadow:0 0 0 0 #f9731666}100%{box-shadow:0 0 0 8px #f9731600}}

        .timeline-list{position:relative}
        .timeline-row{
            display:flex;gap:15px;padding:20px 24px;border-bottom:1px solid #f0f2f6;
            animation:rowSlide .4s ease both;transition:background .25s, transform .35s cubic-bezier(.2,.8,.2,1);
            position:relative;transform-style:preserve-3d;
        }
        .timeline-row:before{
            content:"";position:absolute;left:0;top:8px;bottom:8px;width:3px;border-radius:3px;
            background:linear-gradient(180deg,#f97316,#ea580c);opacity:0;transition:opacity .3s;
        }
        .timeline-row:hover:before{opacity:1}
        .timeline-row[data-severity="critical"]:before{background:linear-gradient(180deg,#ef4444,#b91c1c);opacity:.55}
        .timeline-row[data-severity="warning"]:before{background:linear-gradient(180deg,#f59e0b,#d97706);opacity:.55}
        .timeline-row[data-severity="notice"]:before{background:linear-gradient(180deg,#3b82f6,#2563eb);opacity:.55}
        .timeline-row:hover{background:linear-gradient(90deg,#fffaf5,transparent 70%);transform:translateX(4px)}
        .timeline-row:hover .timeline-mark{transform:scale(1.12) rotate(-6deg) translateZ(20px)}
        @keyframes rowSlide{from{opacity:0;transform:translateX(-10px)}to{opacity:1;transform:none}}
        .timeline-mark{
            width:38px;height:38px;flex:0 0 38px;border-radius:13px;display:grid;place-items:center;
            transition:transform .3s cubic-bezier(.2,.8,.2,1);
            box-shadow:inset 0 -2px 0 rgba(0,0,0,.08), 0 6px 14px -8px rgba(0,0,0,.25);
        }
        .timeline-content{min-width:0;flex:1}
        .timeline-top{display:flex;justify-content:space-between;gap:15px;flex-wrap:wrap}
        .timeline-top strong{font-size:14px}
        .timeline-top time{font-size:11px;color:#8a94a6}
        .timeline-top time i{margin-right:4px}
        .timeline-content p{font-size:12px;color:#687386;margin:7px 0 10px;display:flex;align-items:center;gap:6px;flex-wrap:wrap}
        .who{display:inline-flex;align-items:center;gap:6px;font-weight:700;color:#344054}
        .who i{color:#f97316;font-size:11px}
        a.who.target{text-decoration:none;background:#fff6ee;padding:3px 8px;border-radius:7px;transition:.2s}
        a.who.target:hover{background:#ffe8d1;color:#c2410c}
        .arrow{color:#f97316;padding:0 3px}
        .timeline-meta{display:flex;gap:10px;align-items:center;flex-wrap:wrap;font-size:11px;color:#7d8798}
        .timeline-meta i{color:#f97316;margin-right:4px}
        .event-tag{font-family:ui-monospace,monospace;border-radius:6px;padding:4px 8px;text-decoration:none;font-weight:700;transition:.2s}
        .event-tag:hover{transform:translateY(-1px);filter:brightness(.96)}
        .method{font-weight:800;background:#f3f5f8;color:#475569;padding:3px 7px;border-radius:6px;font-size:10px}
        .sev{font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.5px;padding:3px 7px;border-radius:6px}
        .sev-info{background:#eef1f5;color:#475569}
        .sev-notice{background:#eaf3ff;color:#2563eb}
        .sev-warning{background:#fef3c7;color:#b45309}
        .sev-critical{background:#fee2e2;color:#b91c1c}

        /* Tone palette (icon bubble + event tag) */
        .tone-orange{background:#fff1e5;color:#ea580c}
        .tone-blue{background:#eaf3ff;color:#2563eb}
        .tone-green{background:#dcfce7;color:#15803d}
        .tone-red{background:#fee2e2;color:#dc2626}
        .tone-amber{background:#fef3c7;color:#b45309}
        .tone-purple{background:#f3e8ff;color:#7e22ce}
        .tone-gray{background:#eef1f5;color:#64748b}

        .audit-empty{text-align:center;padding:65px 20px;color:#8791a3}
        .audit-empty>i{font-size:36px;color:#f5b16e;animation:heroFloat 4s ease-in-out infinite}
        .audit-empty h3{color:#344054;margin:12px 0 5px}
        .audit-empty p{margin:0}
        .audit-pagination{padding:18px;display:flex;justify-content:center}

        a:focus-visible,button:focus-visible,input:focus-visible,select:focus-visible{outline:2px solid #f97316;outline-offset:2px}

        @media(max-width:900px){.audit-metrics{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:700px){
            .audit-metrics{grid-template-columns:1fr}
            .audit-hero{align-items:flex-start}
            .audit-export{width:100%;justify-content:center}
            .timeline-row{padding:16px}
            .timeline-heading{padding:18px}
            .timeline-top{display:block}
            .timeline-top time{display:block;margin-top:5px}
            .audit-filters label{min-width:100%}
        }
        @media(prefers-reduced-motion:reduce){
            *{animation:none!important;transition:none!important}
            [data-tilt]{transform:none!important}
        }
    </style>

    <script>
    (function () {
        const esc = (v) => String(v ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

        /* --------- Count-up metrics --------- */
        document.querySelectorAll('[data-count]').forEach(el => {
            const end = +el.dataset.count, t0 = performance.now(), dur = 750;
            const tick = t => {
                const p = Math.min((t - t0) / dur, 1);
                el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3)));
                if (p < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        });

        /* --------- Auto-hide flash --------- */
        document.querySelectorAll('[data-autohide]').forEach(el => setTimeout(() => {
            el.classList.add('hide'); setTimeout(() => el.remove(), 400);
        }, 5000));

        /* --------- 4D tilt --------- */
        document.querySelectorAll('[data-tilt]').forEach(card => {
            const max = +card.dataset.tilt || 6;
            card.addEventListener('mousemove', (e) => {
                const r = card.getBoundingClientRect();
                const px = (e.clientX - r.left) / r.width;
                const py = (e.clientY - r.top) / r.height;
                const ry = (px - 0.5) * 2 * max;
                const rx = -(py - 0.5) * 2 * max;
                card.style.setProperty('--ry', ry.toFixed(2) + 'deg');
                card.style.setProperty('--rx', rx.toFixed(2) + 'deg');
            });
            card.addEventListener('mouseleave', () => {
                card.style.setProperty('--ry', '0deg');
                card.style.setProperty('--rx', '0deg');
            });
        });

        /* --------- Presence --------- */
        const presenceUrl = @json(route('ceo.security.users.presence'));
        async function loadPresence() {
            try {
                const res  = await fetch(presenceUrl, { headers: { Accept: 'application/json' } });
                const data = await res.json();
                const host = document.getElementById('presenceAvatars');
                const ids  = Object.keys(data);
                if (!ids.length) { host.innerHTML = '<span class="empty">Nobody online right now.</span>'; return; }
                host.innerHTML = ids.slice(0, 20).map(id => {
                    const p = data[id];
                    return `<div class="av ${esc(p.status)}" title="${esc(p.name || ('User #' + id))} — ${esc(p.last_seen || '')}">${esc((p.name || id).charAt(0).toUpperCase())}</div>`;
                }).join('');
            } catch (e) { /* silent */ }
        }

        /* --------- Heatmap --------- */
        const heatmapUrl = @json(route('ceo.security.activity.heatmap'));
        async function loadHeatmap() {
            try {
                const res = await fetch(heatmapUrl, { headers: { Accept: 'application/json' } });
                const { matrix, max } = await res.json();
                const labels = { 1:'Sun', 2:'Mon', 3:'Tue', 4:'Wed', 5:'Thu', 6:'Fri', 7:'Sat' };
                let html = '<span class="hm-row-label"></span>';
                for (let h = 0; h < 24; h++) html += `<span class="hm-hour">${h}</span>`;
                for (let d = 1; d <= 7; d++) {
                    html += `<span class="hm-row-label">${labels[d]}</span>`;
                    for (let h = 0; h < 24; h++) {
                        const v = matrix[d][h];
                        const a = v === 0 ? 0 : Math.min(1, 0.15 + v / max);
                        html += `<span class="hm-cell" style="background:rgba(249,115,22,${a.toFixed(2)})" title="${labels[d]} ${h}:00 — ${v} event${v === 1 ? '' : 's'}"></span>`;
                    }
                }
                document.getElementById('heatmapGrid').innerHTML = html;
            } catch (e) { /* silent */ }
        }

        /* --------- Live feed (polls newest events, prepends) --------- */
        const feedUrl = @json(route('ceo.security.activity.feed'));
        const list = document.getElementById('timelineList');
        let lastId = {{ (int) ($logs->first()->id ?? 0) }};
        async function pollFeed() {
            try {
                const res = await fetch(feedUrl + '?after=' + lastId, { headers: { Accept: 'application/json' } });
                const { logs, latest_id } = await res.json();
                if (!logs.length) return;
                lastId = latest_id;

                for (const log of logs.reverse()) {
                    const row = document.createElement('article');
                    row.className = 'timeline-row';
                    row.dataset.severity = log.severity || 'info';
                    row.innerHTML = `
                        <div class="timeline-mark tone-orange"><i class="fa-solid fa-bolt"></i></div>
                        <div class="timeline-content">
                            <div class="timeline-top">
                                <strong>${esc(log.description || log.event)}</strong>
                                <time><i class="fa-regular fa-clock"></i> ${esc(log.created_at)}</time>
                            </div>
                            <p>
                                <span class="who"><i class="fa-solid fa-user"></i> ${esc(log.actor)}</span>
                                ${log.target ? `<span class="arrow">→</span><a class="who target"><i class="fa-solid fa-bullseye"></i> ${esc(log.target)}</a>` : ''}
                            </p>
                            <div class="timeline-meta">
                                <span class="event-tag tone-orange">${esc(log.event)}</span>
                                <span class="sev sev-${esc(log.severity)}">${esc((log.severity || 'info').charAt(0).toUpperCase() + (log.severity || 'info').slice(1))}</span>
                                ${log.ip ? `<span><i class="fa-solid fa-network-wired"></i> ${esc(log.ip)}</span>` : ''}
                                ${log.method ? `<span class="method">${esc(log.method)}</span>` : ''}
                            </div>
                        </div>`;
                    list.prepend(row);
                }
                // keep list bounded
                while (list.children.length > 60) list.removeChild(list.lastElementChild);
            } catch (e) { /* silent */ }
        }

        loadPresence(); loadHeatmap();
        setInterval(loadPresence, 60000);
        setInterval(loadHeatmap, 300000);
        setInterval(pollFeed, 15000);
    })();
    </script>
</x-app-layout>
