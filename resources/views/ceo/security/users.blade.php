<x-app-layout>
    <div class="sec-shell">
        {{-- HERO --}}
        <header class="sec-hero">
            <div class="sec-hero-icon"><i class="fa-solid fa-user-shield"></i></div>
            <div class="sec-hero-copy">
                <span class="sec-eyebrow">TAPAPP / SECURITY CENTER</span>
                <h1>User Access Control</h1>
                <p>Inspect accounts, revoke sessions, suspend or restore access.</p>
            </div>
            <a class="sec-btn" href="{{ route('ceo.security.users.export', request()->only(['search','role','status'])) }}">
                <i class="fa-solid fa-file-csv"></i> Export CSV
            </a>
        </header>

        {{-- TABS --}}
        <nav class="sec-tabs">
            <a href="{{ route('ceo.security.activity') }}" class="{{ request()->routeIs('ceo.security.activity') ? 'on' : '' }}"><i class="fa-solid fa-timeline"></i> Activity</a>
            <a href="{{ route('ceo.security.users') }}" class="{{ request()->routeIs('ceo.security.users') ? 'on' : '' }}"><i class="fa-solid fa-users-gear"></i> Users</a>
        </nav>

        @if(session('success'))
            <div class="sec-flash ok" data-autohide><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="sec-flash err" data-autohide><i class="fa-solid fa-triangle-exclamation"></i> {{ $errors->first() }}</div>
        @endif

        {{-- METRICS --}}
        <section class="sec-metrics">
            <article><span class="mi orange"><i class="fa-solid fa-users"></i></span><div><small>Total users</small><strong data-count="{{ $stats['total'] }}">0</strong></div></article>
            <article><span class="mi green"><i class="fa-solid fa-user-check"></i></span><div><small>Active</small><strong data-count="{{ $stats['active'] }}">0</strong></div></article>
            <article><span class="mi gray"><i class="fa-solid fa-user-slash"></i></span><div><small>Inactive</small><strong data-count="{{ $stats['inactive'] }}">0</strong></div></article>
            <article><span class="mi red"><i class="fa-solid fa-user-lock"></i></span><div><small>Suspended</small><strong data-count="{{ $stats['suspended'] }}">0</strong></div></article>
        </section>

        {{-- FILTERS --}}
        <form class="sec-filters" method="GET">
            <label class="grow"><span>Search</span>
                <div class="input-icon"><i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, matricule or role">
                </div>
            </label>
            <label><span>Role</span>
                <select name="role">
                    <option value="">All roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" @selected(request('role') === $role)>{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </label>
            <label><span>Status</span>
                <select name="status">
                    <option value="">All statuses</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    <option value="suspended" @selected(request('status') === 'suspended')>Suspended</option>
                </select>
            </label>
            <button class="sec-btn" type="submit"><i class="fa-solid fa-sliders"></i> Apply filters</button>
            <a class="sec-reset" href="{{ route('ceo.security.users') }}">Reset</a>
        </form>

        {{-- USERS --}}
        <section class="sec-card">
            <div class="sec-card-head">
                <div><h2>Directory</h2><p>{{ $users->total() }} account{{ $users->total() === 1 ? '' : 's' }} match your filters.</p></div>
                <span class="sec-chip"><i></i> LIVE DATA</span>
            </div>

            <div class="sec-list">
                @forelse($users as $i => $user)
                    @php
                        $isSuspended = $user->suspended_until && $user->suspended_until->isFuture();
                        $isSelf = $user->id === auth()->id();
                    @endphp
                    <article class="sec-row" style="animation-delay: {{ min($i, 12) * 40 }}ms">
                        <div class="sec-avatar {{ $isSuspended ? 'is-suspended' : '' }}">
                            @if($user->profile_picture)
                                <img src="{{ asset('storage/'.$user->profile_picture) }}" alt="">
                            @else
                                {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                            @endif
                            <span class="dot {{ $isSuspended ? 'red' : ($user->is_active ? 'green' : 'gray') }}"></span>
                        </div>

                        <div class="sec-info">
                            <div class="sec-name">
                                <strong>{{ $user->name }}</strong>
                                @if($isSelf)<span class="tag you">You</span>@endif
                                <span class="tag role"><i class="fa-solid fa-id-badge"></i> {{ $user->role }}</span>
                                @if($isSuspended)
                                    <span class="tag susp"><i class="fa-solid fa-lock"></i> Suspended</span>
                                @elseif($user->is_active)
                                    <span class="tag ok"><i class="fa-solid fa-circle-check"></i> Active</span>
                                @else
                                    <span class="tag off"><i class="fa-solid fa-circle-pause"></i> Inactive</span>
                                @endif
                            </div>
                            <div class="sec-meta">
                                <span><i class="fa-solid fa-envelope"></i> {{ $user->email }}</span>
                                @if($user->matricule)<span><i class="fa-solid fa-hashtag"></i> {{ $user->matricule }}</span>@endif
                                <span><i class="fa-solid fa-clock"></i> {{ $user->last_seen_at?->diffForHumans() ?? 'Never seen' }}</span>
                                @if($user->last_ip_address)<span><i class="fa-solid fa-network-wired"></i> {{ $user->last_ip_address }}</span>@endif
                            </div>
                            @if($isSuspended)
                                <div class="sec-until"><i class="fa-solid fa-hourglass-half"></i> Until {{ $user->suspended_until->format('d M Y · H:i') }}</div>
                            @endif
                        </div>

                        <div class="sec-actions">
                            <button type="button" class="ico" title="View details" data-act="details" data-id="{{ $user->id }}"><i class="fa-solid fa-eye"></i></button>
                            @unless($isSelf)
                                <button type="button" class="ico warn" title="Force logout" data-act="logout" data-id="{{ $user->id }}" data-name="{{ $user->name }}"><i class="fa-solid fa-right-from-bracket"></i></button>
                                @if($isSuspended)
                                    <button type="button" class="ico good" title="Remove suspension" data-act="unsuspend" data-id="{{ $user->id }}" data-name="{{ $user->name }}"><i class="fa-solid fa-lock-open"></i></button>
                                @else
                                    <button type="button" class="ico danger" title="Suspend account" data-act="suspend" data-id="{{ $user->id }}" data-name="{{ $user->name }}"><i class="fa-solid fa-user-lock"></i></button>
                                @endif
                            @endunless
                        </div>
                    </article>
                @empty
                    <div class="sec-empty"><i class="fa-solid fa-user-magnifying-glass"></i><h3>No users found</h3><p>Change the search or filters to see more accounts.</p></div>
                @endforelse
            </div>

            <div class="sec-pagination">{{ $users->links() }}</div>
        </section>
    </div>

    {{-- DETAILS DRAWER --}}
    <div class="sec-overlay" id="drawerOverlay"></div>
    <aside class="sec-drawer" id="drawer" aria-hidden="true">
        <header>
            <h3>Account details</h3>
            <button type="button" class="ico" id="drawerClose" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </header>
        <div class="drawer-body" id="drawerBody"></div>
    </aside>

    {{-- CONFIRM MODAL (force logout / unsuspend) --}}
    <div class="sec-modal" id="confirmModal" aria-hidden="true">
        <form method="POST" id="confirmForm" class="modal-box">
            @csrf
            <div class="modal-icon" id="confirmIcon"><i class="fa-solid fa-right-from-bracket"></i></div>
            <h3 id="confirmTitle">Confirm</h3>
            <p id="confirmText"></p>
            <div class="modal-actions">
                <button type="button" class="sec-ghost" data-close>Cancel</button>
                <button type="submit" class="sec-btn" id="confirmBtn">Confirm</button>
            </div>
        </form>
    </div>

    {{-- SUSPEND MODAL --}}
    <div class="sec-modal" id="suspendModal" aria-hidden="true">
        <form method="POST" id="suspendForm" class="modal-box wide">
            @csrf
            <div class="modal-icon red"><i class="fa-solid fa-user-lock"></i></div>
            <h3>Suspend <span id="suspendName"></span></h3>
            <p>The user is signed out everywhere and cannot log in until the date you set.</p>

            <label class="field"><span>Reason</span>
                <textarea name="suspension_reason" rows="3" minlength="5" maxlength="2000" required placeholder="Explain why this account is suspended">{{ old('suspension_reason') }}</textarea>
            </label>
            <label class="field"><span>Suspended until</span>
                <input type="datetime-local" name="suspended_until" required value="{{ old('suspended_until') }}">
            </label>
            <div class="quick">
                <button type="button" data-days="1">1 day</button>
                <button type="button" data-days="7">1 week</button>
                <button type="button" data-days="30">30 days</button>
            </div>

            <div class="modal-actions">
                <button type="button" class="sec-ghost" data-close>Cancel</button>
                <button type="submit" class="sec-btn danger"><i class="fa-solid fa-user-lock"></i> Suspend account</button>
            </div>
        </form>
    </div>

    <style>
        :root{--sec-o:#f97316;--sec-o2:#ea580c;--sec-ink:#172033;--sec-mute:#7b8498;--sec-line:#e9edf3}
        .sec-shell{max-width:1500px;margin:auto;padding:clamp(18px,3vw,38px);color:var(--sec-ink);min-height:100vh;background:radial-gradient(ellipse at 90% 0%,rgba(249,115,22,.13),transparent 34%)}
        .sec-hero{display:flex;align-items:center;gap:18px;margin-bottom:22px;flex-wrap:wrap;animation:secIn .5s ease both}
        .sec-hero-icon{width:64px;height:64px;border-radius:20px;display:grid;place-items:center;background:linear-gradient(140deg,#ff9b38,var(--sec-o2));color:#fff;font-size:26px;box-shadow:0 12px 32px #f973163d;animation:secFloat 4s ease-in-out infinite}
        .sec-hero-copy{flex:1;min-width:220px}.sec-eyebrow{font-size:10px;letter-spacing:2px;font-weight:900;color:var(--sec-o2)}
        .sec-hero h1{font-size:clamp(24px,3vw,34px);font-weight:850;margin:4px 0}.sec-hero p,.sec-card-head p{color:var(--sec-mute);margin:0;font-size:13px}
        @keyframes secIn{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
        @keyframes secFloat{50%{transform:translateY(-5px) rotate(2deg)}}

        .sec-btn{border:0;cursor:pointer;border-radius:12px;background:linear-gradient(135deg,var(--sec-o),var(--sec-o2));color:#fff;text-decoration:none;padding:13px 17px;font-weight:800;font-size:14px;display:inline-flex;align-items:center;gap:9px;box-shadow:0 8px 22px #f9731628;transition:.22s}
        .sec-btn:hover{transform:translateY(-2px);box-shadow:0 12px 25px #f9731640}.sec-btn:active{transform:scale(.97)}
        .sec-btn.danger{background:linear-gradient(135deg,#ef4444,#b91c1c);box-shadow:0 8px 22px #ef444430}
        .sec-ghost{border:1px solid #e1e6ef;background:#fff;border-radius:12px;padding:13px 17px;font-weight:700;cursor:pointer;color:#475569;transition:.2s}.sec-ghost:hover{background:#f6f8fb}

        .sec-tabs{display:inline-flex;gap:4px;background:#fff;border:1px solid var(--sec-line);padding:5px;border-radius:14px;margin-bottom:20px;box-shadow:0 6px 20px #17203306}
        .sec-tabs a{padding:10px 16px;border-radius:10px;text-decoration:none;color:#697386;font-weight:750;font-size:13px;display:inline-flex;gap:8px;align-items:center;transition:.2s}
        .sec-tabs a:hover{color:var(--sec-o2);background:#fff6ee}.sec-tabs a.on{background:linear-gradient(135deg,var(--sec-o),var(--sec-o2));color:#fff;box-shadow:0 6px 16px #f9731633}

        .sec-flash{padding:14px 17px;border-radius:13px;margin-bottom:18px;display:flex;gap:10px;align-items:center;animation:secDrop .4s ease both;transition:opacity .4s,transform .4s}
        .sec-flash.ok{background:#ecfdf3;color:#047857;border:1px solid #bbf7d0}.sec-flash.err{background:#fef2f2;color:#b91c1c;border:1px solid #fecaca}
        .sec-flash.hide{opacity:0;transform:translateY(-8px)}
        @keyframes secDrop{from{opacity:0;transform:translateY(-10px)}to{opacity:1;transform:none}}

        .sec-metrics{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:15px;margin-bottom:20px}
        .sec-metrics article{background:#fff;border:1px solid var(--sec-line);border-radius:17px;padding:19px;display:flex;align-items:center;gap:13px;box-shadow:0 9px 30px #17203308;transition:.25s}
        .sec-metrics article:hover{transform:translateY(-4px);box-shadow:0 15px 32px #17203312}
        .mi{width:44px;height:44px;display:grid;place-items:center;border-radius:14px;font-size:18px}
        .mi.orange{background:#fff1e5;color:var(--sec-o2)}.mi.green{background:#dcfce7;color:#15803d}.mi.gray{background:#eef1f5;color:#64748b}.mi.red{background:#fee2e2;color:#dc2626}
        .sec-metrics small{display:block;color:#8490a4;font-weight:700;font-size:11px;text-transform:uppercase;letter-spacing:.5px}.sec-metrics strong{display:block;font-size:24px;margin-top:4px}

        .sec-filters{display:flex;align-items:end;gap:12px;flex-wrap:wrap;background:#fff;padding:17px;border:1px solid var(--sec-line);border-radius:17px;margin-bottom:20px;box-shadow:0 8px 25px #17203308}
        .sec-filters label{display:grid;gap:6px;min-width:150px}.sec-filters label.grow{flex:1;min-width:220px}
        .sec-filters label>span,.field>span{font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--sec-mute);font-weight:800}
        .sec-filters input,.sec-filters select,.field input,.field textarea{width:100%;border:1px solid #e1e6ef;background:#fbfcfe;border-radius:10px;padding:0 11px;outline:0;transition:.2s;font:inherit}
        .sec-filters input,.sec-filters select,.field input{height:43px}.field textarea{padding:11px;resize:vertical}
        .sec-filters input:focus,.sec-filters select:focus,.field input:focus,.field textarea:focus{border-color:var(--sec-o);box-shadow:0 0 0 3px #f973161c;background:#fff}
        .input-icon{position:relative}.input-icon i{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#a0a9ba;font-size:13px}.input-icon input{padding-left:36px}
        .sec-reset{padding:12px;color:#697386;text-decoration:none;font-weight:700}.sec-reset:hover{color:var(--sec-o2)}

        .sec-card{background:#fff;border:1px solid var(--sec-line);border-radius:20px;overflow:hidden;box-shadow:0 12px 38px #17203309}
        .sec-card-head{display:flex;justify-content:space-between;align-items:center;padding:22px 24px;border-bottom:1px solid #eef0f4}.sec-card-head h2{margin:0;font-size:19px;font-weight:850}
        .sec-chip{background:#fff4e7;color:#c65b08;padding:7px 10px;border-radius:9px;font-size:10px;font-weight:900;letter-spacing:1px}
        .sec-chip i{display:inline-block;width:6px;height:6px;border-radius:50%;background:var(--sec-o);margin-right:5px;animation:secPulse 1.8s infinite}
        @keyframes secPulse{0%{box-shadow:0 0 0 0 #f9731666}100%{box-shadow:0 0 0 8px #f9731600}}

        .sec-row{display:flex;gap:16px;align-items:center;padding:18px 24px;border-bottom:1px solid #f0f2f6;transition:background .2s;animation:secRow .4s ease both}
        .sec-row:hover{background:#fffaf5}
        @keyframes secRow{from{opacity:0;transform:translateX(-10px)}to{opacity:1;transform:none}}
        .sec-avatar{position:relative;width:48px;height:48px;flex:0 0 48px;border-radius:15px;display:grid;place-items:center;background:linear-gradient(140deg,#ffb066,var(--sec-o2));color:#fff;font-weight:850;font-size:18px}
        .sec-avatar img{width:100%;height:100%;object-fit:cover;border-radius:15px}.sec-avatar.is-suspended{background:linear-gradient(140deg,#fca5a5,#dc2626)}
        .sec-avatar .dot{position:absolute;right:-3px;bottom:-3px;width:14px;height:14px;border-radius:50%;border:3px solid #fff}
        .dot.green{background:#22c55e}.dot.gray{background:#9aa4b5}.dot.red{background:#ef4444}
        .sec-info{flex:1;min-width:0}.sec-name{display:flex;flex-wrap:wrap;gap:8px;align-items:center}.sec-name strong{font-size:15px}
        .tag{font-size:10.5px;font-weight:800;padding:4px 8px;border-radius:7px;display:inline-flex;gap:5px;align-items:center}
        .tag.role{background:#f3f5f8;color:#475569;text-transform:capitalize}.tag.ok{background:#dcfce7;color:#15803d}.tag.off{background:#eef1f5;color:#64748b}.tag.susp{background:#fee2e2;color:#b91c1c}.tag.you{background:#e0ecff;color:#2563eb}
        .sec-meta{display:flex;flex-wrap:wrap;gap:6px 16px;margin-top:7px;font-size:12px;color:#7d8798}.sec-meta i{color:var(--sec-o);margin-right:5px}
        .sec-until{margin-top:7px;font-size:12px;color:#b91c1c;font-weight:700}
        .sec-actions{display:flex;gap:8px}
        .ico{width:38px;height:38px;border:1px solid #e6eaf1;background:#fff;border-radius:11px;cursor:pointer;color:#5b6678;display:grid;place-items:center;transition:.2s}
        .ico:hover{transform:translateY(-2px);color:var(--sec-o2);border-color:#fdba74;background:#fff6ee}
        .ico.warn:hover{color:#b45309;background:#fffbeb;border-color:#fcd34d}.ico.danger:hover{color:#dc2626;background:#fef2f2;border-color:#fca5a5}.ico.good:hover{color:#15803d;background:#f0fdf4;border-color:#86efac}
        .sec-empty{text-align:center;padding:65px 20px;color:#8791a3}.sec-empty>i{font-size:36px;color:#f5b16e}.sec-empty h3{color:#344054;margin:12px 0 5px}.sec-empty p{margin:0}
        .sec-pagination{padding:18px;display:flex;justify-content:center}

        /* Drawer */
        .sec-overlay{position:fixed;inset:0;background:#0f172a66;backdrop-filter:blur(3px);opacity:0;pointer-events:none;transition:.3s;z-index:60}
        .sec-overlay.open{opacity:1;pointer-events:auto}
        .sec-drawer{position:fixed;top:0;right:0;height:100%;width:min(440px,100%);background:#fff;z-index:70;transform:translateX(105%);transition:transform .35s cubic-bezier(.2,.8,.2,1);box-shadow:-20px 0 50px #0f172a22;display:flex;flex-direction:column}
        .sec-drawer.open{transform:none}
        .sec-drawer header{display:flex;justify-content:space-between;align-items:center;padding:20px 22px;border-bottom:1px solid #eef0f4}.sec-drawer h3{margin:0;font-size:17px;font-weight:850}
        .drawer-body{padding:22px;overflow:auto;flex:1}
        .d-profile{text-align:center;margin-bottom:20px}.d-profile .sec-avatar{width:84px;height:84px;font-size:32px;border-radius:26px;margin:0 auto 12px}.d-profile .sec-avatar img{border-radius:26px}
        .d-profile h4{margin:0;font-size:18px}.d-profile p{margin:4px 0 10px;color:var(--sec-mute);font-size:13px}
        .d-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:22px}
        .d-grid div{background:#f8fafc;border:1px solid #eef1f5;border-radius:12px;padding:11px 12px}.d-grid small{display:block;font-size:10px;text-transform:uppercase;letter-spacing:.5px;color:#8a94a6;font-weight:800}.d-grid b{font-size:13px;word-break:break-word}
        .d-reason{background:#fef2f2;border:1px solid #fecaca;color:#991b1b;border-radius:12px;padding:12px;font-size:13px;margin-bottom:22px}
        .d-title{font-size:13px;font-weight:850;margin:0 0 12px}
        .d-tl{position:relative;padding-left:22px}.d-tl:before{content:"";position:absolute;left:5px;top:6px;bottom:6px;width:2px;background:#f1d6bd}
        .d-item{position:relative;margin-bottom:14px;animation:secRow .35s ease both}.d-item:before{content:"";position:absolute;left:-22px;top:5px;width:12px;height:12px;border-radius:50%;background:#fff;border:3px solid var(--sec-o)}
        .d-item b{font-size:13px;display:block}.d-item span{font-size:11px;color:#8a94a6}.d-item code{font-size:10.5px;background:#f3f5f8;padding:2px 6px;border-radius:5px;color:#475569}
        .d-loading{display:grid;place-items:center;height:200px;color:var(--sec-o);font-size:28px}.d-loading i{animation:secSpin 1s linear infinite}
        @keyframes secSpin{to{transform:rotate(360deg)}}

        /* Modals */
        .sec-modal{position:fixed;inset:0;display:grid;place-items:center;padding:18px;background:#0f172a66;backdrop-filter:blur(3px);z-index:80;opacity:0;pointer-events:none;transition:.25s}
        .sec-modal.open{opacity:1;pointer-events:auto}
        .modal-box{background:#fff;width:min(420px,100%);border-radius:22px;padding:26px;text-align:center;transform:translateY(18px) scale(.96);transition:.3s cubic-bezier(.2,.8,.2,1);box-shadow:0 30px 70px #0f172a33}
        .modal-box.wide{width:min(500px,100%);text-align:left}.sec-modal.open .modal-box{transform:none}
        .modal-icon{width:58px;height:58px;border-radius:18px;display:grid;place-items:center;font-size:23px;background:#fff1e5;color:var(--sec-o2);margin:0 auto 14px}.modal-icon.red{background:#fee2e2;color:#dc2626}.modal-icon.green{background:#dcfce7;color:#15803d}
        .wide .modal-icon{margin-left:0}.modal-box h3{margin:0 0 6px;font-size:19px;font-weight:850}.modal-box p{margin:0 0 18px;color:var(--sec-mute);font-size:13px}
        .field{display:grid;gap:6px;margin-bottom:14px}
        .quick{display:flex;gap:8px;margin:-4px 0 4px}.quick button{border:1px dashed #fdba74;background:#fff8f1;color:var(--sec-o2);padding:6px 11px;border-radius:8px;font-size:12px;font-weight:750;cursor:pointer;transition:.2s}.quick button:hover{background:#ffedd8}
        .modal-actions{display:flex;gap:10px;justify-content:flex-end;margin-top:20px}.modal-box:not(.wide) .modal-actions{justify-content:center}

        a:focus-visible,button:focus-visible,input:focus-visible{outline:2px solid var(--sec-o);outline-offset:2px}
        @media(max-width:900px){.sec-metrics{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:700px){.sec-row{flex-wrap:wrap;padding:16px}.sec-actions{width:100%;justify-content:flex-end}.sec-btn{width:100%;justify-content:center}.sec-filters label{min-width:100%}}
        @media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}
    </style>

    <script>
    (function () {
        const urls = {
            show:      @json(route('ceo.security.users.show', ['user' => '__ID__'])),
            logout:    @json(route('ceo.security.users.force-logout', ['user' => '__ID__'])),
            suspend:   @json(route('ceo.security.users.suspend', ['user' => '__ID__'])),
            unsuspend: @json(route('ceo.security.users.unsuspend', ['user' => '__ID__'])),
        };
        const url = (k, id) => urls[k].replace('__ID__', id);
        const $ = (s) => document.querySelector(s);
        const esc = (v) => String(v ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

        // Count-up for metrics
        document.querySelectorAll('[data-count]').forEach(el => {
            const end = +el.dataset.count, t0 = performance.now(), dur = 700;
            const tick = (t) => {
                const p = Math.min((t - t0) / dur, 1);
                el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3)));
                if (p < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        });

        // Auto-hide flashes
        document.querySelectorAll('[data-autohide]').forEach(el => setTimeout(() => {
            el.classList.add('hide'); setTimeout(() => el.remove(), 400);
        }, 5000));

        // Drawer
        const drawer = $('#drawer'), overlay = $('#drawerOverlay'), body = $('#drawerBody');
        const openDrawer = () => { drawer.classList.add('open'); overlay.classList.add('open'); drawer.setAttribute('aria-hidden', 'false'); };
        const closeDrawer = () => { drawer.classList.remove('open'); overlay.classList.remove('open'); drawer.setAttribute('aria-hidden', 'true'); };
        $('#drawerClose').onclick = closeDrawer; overlay.onclick = closeDrawer;

        async function showDetails(id) {
            body.innerHTML = '<div class="d-loading"><i class="fa-solid fa-circle-notch"></i></div>';
            openDrawer();
            try {
                const res = await fetch(url('show', id), { headers: { 'Accept': 'application/json' } });
                if (!res.ok) throw new Error();
                const { user: u, activities } = await res.json();
                const suspended = u.suspended_until && new Date(u.suspended_until.replace(' ', 'T')) > new Date();
                const avatar = u.profile_picture_url ? `<img src="${esc(u.profile_picture_url)}" alt="">` : esc((u.name || '?').charAt(0).toUpperCase());
                body.innerHTML = `
                    <div class="d-profile">
                        <div class="sec-avatar ${suspended ? 'is-suspended' : ''}">${avatar}</div>
                        <h4>${esc(u.name)}</h4><p>${esc(u.email)}</p>
                        <span class="tag role"><i class="fa-solid fa-id-badge"></i> ${esc(u.role)}</span>
                        ${suspended ? '<span class="tag susp"><i class="fa-solid fa-lock"></i> Suspended</span>'
                            : u.is_active ? '<span class="tag ok"><i class="fa-solid fa-circle-check"></i> Active</span>'
                            : '<span class="tag off"><i class="fa-solid fa-circle-pause"></i> Inactive</span>'}
                    </div>
                    <div class="d-grid">
                        <div><small>Matricule</small><b>${esc(u.matricule || '—')}</b></div>
                        <div><small>Last IP</small><b>${esc(u.last_ip_address || '—')}</b></div>
                        <div><small>Last login</small><b>${esc(u.last_login_at || '—')}</b></div>
                        <div><small>Last seen</small><b>${esc(u.last_seen_at || '—')}</b></div>
                    </div>
                    ${suspended ? `<div class="d-reason"><b><i class="fa-solid fa-lock"></i> Suspended until ${esc(u.suspended_until)}</b><br>${esc(u.suspension_reason || '')}</div>` : ''}
                    <p class="d-title"><i class="fa-solid fa-clock-rotate-left"></i> Recent activity</p>
                    <div class="d-tl">
                        ${activities.length ? activities.map((a, i) => `
                            <div class="d-item" style="animation-delay:${i * 50}ms">
                                <b>${esc(a.description || a.event)}</b>
                                <span><code>${esc(a.event)}</code> · ${esc(a.created_at)}${a.ip_address ? ' · ' + esc(a.ip_address) : ''}</span>
                            </div>`).join('') : '<span style="color:#8a94a6;font-size:13px">No activity recorded yet.</span>'}
                    </div>`;
            } catch (e) {
                body.innerHTML = '<div class="d-loading" style="color:#dc2626;font-size:15px">Could not load this account. Try again.</div>';
            }
        }

        // Modals
        const confirmModal = $('#confirmModal'), suspendModal = $('#suspendModal');
        const open = (m) => { m.classList.add('open'); m.setAttribute('aria-hidden', 'false'); };
        const close = (m) => { m.classList.remove('open'); m.setAttribute('aria-hidden', 'true'); };
        document.querySelectorAll('.sec-modal').forEach(m => {
            m.addEventListener('click', e => { if (e.target === m || e.target.closest('[data-close]')) close(m); });
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') { closeDrawer(); close(confirmModal); close(suspendModal); }
        });

        function askConfirm(kind, id, name) {
            const f = $('#confirmForm'), icon = $('#confirmIcon');
            f.action = url(kind, id);
            if (kind === 'logout') {
                icon.className = 'modal-icon'; icon.innerHTML = '<i class="fa-solid fa-right-from-bracket"></i>';
                $('#confirmTitle').textContent = 'Force logout?';
                $('#confirmText').textContent = `${name} will be signed out of every device immediately.`;
                $('#confirmBtn').innerHTML = '<i class="fa-solid fa-right-from-bracket"></i> Revoke sessions';
            } else {
                icon.className = 'modal-icon green'; icon.innerHTML = '<i class="fa-solid fa-lock-open"></i>';
                $('#confirmTitle').textContent = 'Remove suspension?';
                $('#confirmText').textContent = `${name} will be able to sign in again right away.`;
                $('#confirmBtn').innerHTML = '<i class="fa-solid fa-lock-open"></i> Restore access';
            }
            open(confirmModal);
        }

        function askSuspend(id, name) {
            $('#suspendForm').action = url('suspend', id);
            $('#suspendName').textContent = name;
            open(suspendModal);
            setTimeout(() => $('#suspendForm textarea').focus(), 250);
        }

        // Quick date buttons (local time, formatted for datetime-local)
        document.querySelectorAll('.quick [data-days]').forEach(b => b.onclick = () => {
            const d = new Date(Date.now() + (+b.dataset.days) * 86400000);
            d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
            $('#suspendForm [name=suspended_until]').value = d.toISOString().slice(0, 16);
        });

        // Row actions
        document.querySelectorAll('[data-act]').forEach(btn => btn.addEventListener('click', () => {
            const { act, id, name } = btn.dataset;
            if (act === 'details') showDetails(id);
            else if (act === 'logout' || act === 'unsuspend') askConfirm(act, id, name);
            else if (act === 'suspend') askSuspend(id, name);
        }));

        // Prevent double submits
        document.querySelectorAll('.sec-modal form').forEach(f => f.addEventListener('submit', () => {
            f.querySelectorAll('button[type=submit]').forEach(b => { b.disabled = true; b.style.opacity = .7; });
        }));
    })();
    </script>
</x-app-layout>