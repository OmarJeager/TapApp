<x-app-layout>
    <div class="audit-shell">
        <header class="audit-hero">
            <div class="hero-icon"><i class="fa-solid fa-shield-halved"></i></div>
            <div class="hero-copy">
                <span class="eyebrow">TAPAPP / SECURITY CENTER</span>
                <h1>User Activity Timeline</h1>
                <p>Review account events, administrative actions and session security.</p>
            </div>
            <a class="audit-export" href="{{ route('ceo.security.activity.export') }}">
                <i class="fa-solid fa-file-csv"></i> Export CSV
            </a>
        </header>

        @if(session('success'))
            <div class="audit-alert"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
        @endif

        <section class="audit-metrics">
            <article><span class="metric-icon orange"><i class="fa-solid fa-list-check"></i></span><div><small>Events in view</small><strong>{{ $logs->total() }}</strong></div></article>
            <article><span class="metric-icon blue"><i class="fa-solid fa-filter"></i></span><div><small>Event types</small><strong>{{ $events->count() }}</strong></div></article>
            <article><span class="metric-icon green"><i class="fa-solid fa-clock-rotate-left"></i></span><div><small>Latest event</small><strong>{{ $logs->first()?->created_at?->diffForHumans() ?? 'No events' }}</strong></div></article>
        </section>

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
            <button type="submit"><i class="fa-solid fa-sliders"></i> Apply filters</button>
            <a href="{{ route('ceo.security.activity') }}">Reset</a>
        </form>

        <section class="timeline-card">
            <div class="timeline-heading"><div><h2>Audit trail</h2><p>Newest activity appears first.</p></div><span class="live-chip"><i></i> AUDIT LOG</span></div>
            <div class="timeline-list">
                @forelse($logs as $log)
                    <article class="timeline-row">
                        <div class="timeline-mark"><i class="fa-solid {{ str_contains($log->event, 'delete') ? 'fa-trash' : (str_contains($log->event, 'login') ? 'fa-right-to-bracket' : (str_contains($log->event, 'suspend') ? 'fa-user-lock' : 'fa-bolt')) }}"></i></div>
                        <div class="timeline-content">
                            <div class="timeline-top"><strong>{{ $log->description ?: str($log->event)->replace('.', ' ')->headline() }}</strong><time>{{ $log->created_at?->format('d M Y · H:i:s') }}</time></div>
                            <p><b>{{ $log->actor?->name ?? 'System / deleted user' }}</b>
                                @if($log->target) <span class="arrow">→</span> {{ $log->target->name }} @endif
                            </p>
                            <div class="timeline-meta"><span class="event-tag">{{ $log->event }}</span>
                                @if($log->ip_address)<span><i class="fa-solid fa-network-wired"></i> {{ $log->ip_address }}</span>@endif
                                @if($log->route_name)<span><i class="fa-solid fa-route"></i> {{ $log->route_name }}</span>@endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="audit-empty"><i class="fa-solid fa-shield-heart"></i><h3>No activity found</h3><p>Try adjusting your filters or return after new events are recorded.</p></div>
                @endforelse
            </div>
            <div class="audit-pagination">{{ $logs->links() }}</div>
        </section>
    </div>

    <style>
        .audit-shell{max-width:1500px;margin:auto;padding:clamp(18px,3vw,38px);color:#172033;min-height:100vh;background:radial-gradient(ellipse at 90% 0%,rgba(249,115,22,.13),transparent 34%);animation:auditEnter .55s ease both}
        @keyframes auditEnter{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
        .audit-hero{display:flex;align-items:center;gap:18px;margin-bottom:28px;flex-wrap:wrap}.hero-icon{width:64px;height:64px;border-radius:20px;display:grid;place-items:center;background:linear-gradient(140deg,#ff9b38,#ea580c);color:#fff;font-size:26px;box-shadow:0 12px 32px #f973163d;animation:heroFloat 4s ease-in-out infinite}@keyframes heroFloat{50%{transform:translateY(-5px) rotate(2deg)}}
        .hero-copy{flex:1;min-width:220px}.eyebrow{font-size:10px;letter-spacing:2px;font-weight:900;color:#ea580c}.audit-hero h1{font-size:clamp(24px,3vw,34px);font-weight:850;margin:4px 0}.audit-hero p,.timeline-heading p{color:#7b8498;margin:0;font-size:13px}
        .audit-export,.audit-filters button{border:0;border-radius:12px;background:linear-gradient(135deg,#f97316,#ea580c);color:white;text-decoration:none;padding:13px 17px;font-weight:800;display:inline-flex;align-items:center;gap:9px;box-shadow:0 8px 22px #f9731628;transition:.22s}.audit-export:hover,.audit-filters button:hover{transform:translateY(-2px);box-shadow:0 12px 25px #f9731640}
        .audit-alert{padding:14px 17px;border-radius:13px;background:#ecfdf3;color:#047857;margin-bottom:18px;border:1px solid #bbf7d0}
        .audit-metrics{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:15px;margin-bottom:20px}.audit-metrics article{background:#fff;border:1px solid #e9edf3;border-radius:17px;padding:19px;display:flex;align-items:center;gap:13px;box-shadow:0 9px 30px #17203308;transition:.25s}.audit-metrics article:hover{transform:translateY(-4px);box-shadow:0 15px 32px #17203312}.metric-icon{width:44px;height:44px;display:grid;place-items:center;border-radius:14px;font-size:18px}.metric-icon.orange{background:#fff1e5;color:#ea580c}.metric-icon.blue{background:#eaf3ff;color:#2563eb}.metric-icon.green{background:#dcfce7;color:#15803d}.audit-metrics small{display:block;color:#8490a4;font-weight:700;font-size:11px;text-transform:uppercase;letter-spacing:.5px}.audit-metrics strong{display:block;font-size:22px;margin-top:4px;overflow-wrap:anywhere}
        .audit-filters{display:flex;align-items:end;gap:12px;flex-wrap:wrap;background:#fff;padding:17px;border:1px solid #e9edf3;border-radius:17px;margin-bottom:20px;box-shadow:0 8px 25px #17203308}.audit-filters label{display:grid;gap:6px;flex:1;min-width:145px}.audit-filters label span{font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:#7b8498;font-weight:800}.audit-filters input,.audit-filters select{width:100%;height:43px;border:1px solid #e1e6ef;background:#fbfcfe;border-radius:10px;padding:0 11px;outline:0}.audit-filters input:focus,.audit-filters select:focus{border-color:#f97316;box-shadow:0 0 0 3px #f973161c}.audit-filters a{padding:12px;color:#697386;text-decoration:none;font-weight:700}
        .timeline-card{background:#fff;border:1px solid #e9edf3;border-radius:20px;overflow:hidden;box-shadow:0 12px 38px #17203309}.timeline-heading{display:flex;justify-content:space-between;align-items:center;padding:22px 24px;border-bottom:1px solid #eef0f4}.timeline-heading h2{margin:0;font-size:19px;font-weight:850}.live-chip{background:#fff4e7;color:#c65b08;padding:7px 10px;border-radius:9px;font-size:10px;font-weight:900;letter-spacing:1px}.live-chip i{display:inline-block;width:6px;height:6px;border-radius:50%;background:#f97316;margin-right:5px;box-shadow:0 0 0 4px #f9731620}
        .timeline-row{display:flex;gap:15px;padding:20px 24px;border-bottom:1px solid #f0f2f6;animation:rowSlide .35s ease both;transition:background .2s}.timeline-row:hover{background:#fffaf5}@keyframes rowSlide{from{opacity:0;transform:translateX(-8px)}to{opacity:1;transform:none}}.timeline-mark{width:38px;height:38px;flex:0 0 38px;border-radius:13px;display:grid;place-items:center;background:#fff1e5;color:#ea580c}.timeline-content{min-width:0;flex:1}.timeline-top{display:flex;justify-content:space-between;gap:15px;flex-wrap:wrap}.timeline-top strong{font-size:14px}.timeline-top time{font-size:11px;color:#8a94a6}.timeline-content p{font-size:12px;color:#687386;margin:6px 0 10px}.arrow{color:#f97316;padding:0 5px}.timeline-meta{display:flex;gap:12px;align-items:center;flex-wrap:wrap;font-size:11px;color:#7d8798}.event-tag{font-family:ui-monospace,monospace;background:#f3f5f8;color:#475569;border-radius:6px;padding:4px 7px}.timeline-meta i{color:#f97316;margin-right:4px}.audit-empty{text-align:center;padding:65px 20px;color:#8791a3}.audit-empty>i{font-size:36px;color:#f5b16e}.audit-empty h3{color:#344054;margin:12px 0 5px}.audit-empty p{margin:0}.audit-pagination{padding:18px;display:flex;justify-content:center}
        @media(max-width:700px){.audit-metrics{grid-template-columns:1fr}.audit-hero{align-items:flex-start}.audit-export{width:100%;justify-content:center}.timeline-row{padding:16px}.timeline-heading{padding:18px}.timeline-top{display:block}.timeline-top time{display:block;margin-top:5px}}
        @media(prefers-reduced-motion:reduce){.audit-shell,.hero-icon,.timeline-row,.audit-metrics article{animation:none;transition:none}}
    </style>
</x-app-layout>
