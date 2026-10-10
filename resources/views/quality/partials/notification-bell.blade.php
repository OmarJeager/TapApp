{{-- resources/views/quality/partials/notification-bell.blade.php --}}
{{-- In dashboard.blade.php replace the old <a>View</a> block with:
     @include('quality.partials.notification-bell')
--}}

<div class="nt-bar">
    <a href="{{ route('quality.index') }}" class="nt-btn nt-btn-ghost">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
        View all
    </a>

    {{-- Bell --}}
    <button type="button" id="ntBell" class="nt-bell {{ ($pendingCount ?? 0) > 0 ? 'has-new' : '' }}" aria-label="Checklists waiting for verification">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg>
        <span id="ntBadge" class="nt-badge" {{ ($pendingCount ?? 0) > 0 ? '' : 'hidden' }}>{{ $pendingCount ?? 0 }}</span>
    </button>
</div>

{{-- Overlay + drawer --}}
<div id="ntOverlay" class="nt-overlay"></div>

<aside id="ntDrawer" class="nt-drawer" aria-hidden="true">
    <header class="nt-drawer-head">
        <div>
            <h3>Checklists to verify</h3>
            <p><span id="ntCountText">{{ $pendingCount ?? 0 }}</span> completed by users</p>
        </div>
        <button type="button" id="ntClose" class="nt-close" aria-label="Close">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
    </header>

    <div id="ntBody" class="nt-drawer-body">
        <div class="nt-loading"><span class="nt-spinner nt-spinner-lg"></span> Loading…</div>
    </div>
</aside>

<div id="ntToast" class="nt-toast"></div>

<style>
    :root {
        --nt-ink: #14213d;
        --nt-muted: #64708a;
        --nt-line: #e3e8f2;
        --nt-bg: #f6f8fc;
        --nt-card: #ffffff;
        --nt-accent: #2457d6;
        --nt-ok: #138a5b;
        --nt-wait: #b36b00;
        --nt-alert: #e5322d;
    }
    .dark {
        --nt-ink: #eef2fb;
        --nt-muted: #97a3bd;
        --nt-line: #2c3750;
        --nt-bg: #151c2e;
        --nt-card: #1d2640;
    }

    /* ---------- Bar + bell ---------- */
    .nt-bar { display: flex; align-items: center; justify-content: flex-end; gap: 12px; max-width: 80rem; margin: 16px auto 0; padding: 0 1.5rem; }
    .nt-bell { position: relative; width: 46px; height: 46px; border-radius: 50%; border: 1px solid var(--nt-line); background: var(--nt-card); color: var(--nt-ink); display: grid; place-items: center; cursor: pointer; transition: transform .2s, box-shadow .2s, background .2s; }
    .nt-bell:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(20, 33, 61, .15); }
    .nt-bell:focus-visible, .nt-btn:focus-visible, .nt-close:focus-visible { outline: 3px solid rgba(36, 87, 214, .45); outline-offset: 2px; }
    .nt-bell svg { width: 22px; height: 22px; transform-origin: 50% 0; }
    .nt-bell.has-new svg { animation: nt-ring 2.4s ease-in-out infinite; }
    .nt-badge { position: absolute; top: -6px; right: -6px; min-width: 22px; height: 22px; padding: 0 6px; border-radius: 11px; background: var(--nt-alert); color: #fff; font-size: 12px; font-weight: 700; display: grid; place-items: center; box-shadow: 0 0 0 3px var(--nt-bg); }
    .nt-bell.has-new .nt-badge::after { content: ""; position: absolute; inset: 0; border-radius: inherit; background: var(--nt-alert); z-index: -1; animation: nt-pulse 1.8s ease-out infinite; }
    .nt-badge.bump { animation: nt-bump .35s ease; }

    @keyframes nt-ring {
        0%, 70%, 100% { transform: rotate(0); }
        75% { transform: rotate(14deg); } 80% { transform: rotate(-12deg); }
        85% { transform: rotate(8deg); }  90% { transform: rotate(-6deg); } 95% { transform: rotate(3deg); }
    }
    @keyframes nt-pulse { 0% { transform: scale(1); opacity: .7; } 100% { transform: scale(2.1); opacity: 0; } }
    @keyframes nt-bump { 50% { transform: scale(1.35); } }

    /* ---------- Overlay + drawer ---------- */
    .nt-overlay { position: fixed; inset: 0; background: rgba(10, 18, 36, .5); backdrop-filter: blur(2px); opacity: 0; pointer-events: none; transition: opacity .3s; z-index: 60; }
    .nt-overlay.open { opacity: 1; pointer-events: auto; }
    .nt-drawer { position: fixed; top: 0; right: 0; height: 100%; width: min(520px, 100%); background: var(--nt-bg); color: var(--nt-ink); transform: translateX(100%); transition: transform .38s cubic-bezier(.22, 1, .36, 1); z-index: 70; display: flex; flex-direction: column; box-shadow: -20px 0 50px rgba(0, 0, 0, .25); }
    .nt-drawer.open { transform: translateX(0); }
    .nt-drawer-head { display: flex; align-items: center; justify-content: space-between; padding: 20px 22px; background: var(--nt-card); border-bottom: 1px solid var(--nt-line); }
    .nt-drawer-head h3 { font-size: 18px; font-weight: 700; margin: 0; }
    .nt-drawer-head p { margin: 2px 0 0; font-size: 13px; color: var(--nt-muted); }
    .nt-close { width: 38px; height: 38px; border-radius: 50%; border: 0; background: transparent; color: var(--nt-muted); display: grid; place-items: center; cursor: pointer; transition: background .2s, transform .25s; }
    .nt-close:hover { background: var(--nt-line); transform: rotate(90deg); }
    .nt-close svg { width: 20px; height: 20px; }
    .nt-drawer-body { flex: 1; overflow-y: auto; padding: 18px; display: flex; flex-direction: column; gap: 16px; }

    /* ---------- Card ---------- */
    .nt-card { background: var(--nt-card); border: 1px solid var(--nt-line); border-radius: 14px; padding: 16px; animation: nt-in .45s cubic-bezier(.22, 1, .36, 1) both; animation-delay: calc(var(--i, 0) * 70ms); transition: transform .4s, opacity .4s, max-height .5s .1s, margin .5s .1s, padding .5s .1s; max-height: 2000px; }
    .nt-card.leaving { transform: translateX(60px); opacity: 0; max-height: 0; padding-top: 0; padding-bottom: 0; margin-top: -8px; margin-bottom: -8px; overflow: hidden; border-width: 0; }
    @keyframes nt-in { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }

    .nt-card-head { display: flex; align-items: center; gap: 12px; }
    .nt-asset-icon { width: 42px; height: 42px; border-radius: 11px; background: rgba(36, 87, 214, .12); color: var(--nt-accent); display: grid; place-items: center; flex: none; }
    .nt-asset-icon svg { width: 22px; height: 22px; }
    .nt-asset-text { min-width: 0; flex: 1; }
    .nt-asset-text h4 { margin: 0; font-size: 17px; font-weight: 700; letter-spacing: .01em; }
    .nt-asset-text p { margin: 2px 0 0; font-size: 13px; color: var(--nt-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .nt-pill { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; padding: 5px 10px; border-radius: 999px; }
    .nt-pill-wait { background: rgba(179, 107, 0, .13); color: var(--nt-wait); }
    .nt-dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; animation: nt-blink 1.4s infinite; }
    @keyframes nt-blink { 50% { opacity: .25; } }

    .nt-meta { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px 10px; margin: 16px 0 0; }
    .nt-meta dt { font-size: 12px; color: var(--nt-muted); }
    .nt-meta dd { margin: 2px 0 0; font-size: 14px; font-weight: 600; word-break: break-word; }

    .nt-time { display: flex; align-items: center; gap: 10px; margin-top: 16px; padding: 12px; border-radius: 11px; background: var(--nt-bg); }
    .nt-time-box { flex: 1; display: flex; flex-direction: column; gap: 2px; }
    .nt-time-label { font-size: 12px; color: var(--nt-muted); }
    .nt-time-box strong { font-size: 14px; }
    .nt-time-line { flex: none; width: 34px; height: 2px; background: repeating-linear-gradient(90deg, var(--nt-accent) 0 4px, transparent 4px 8px); background-size: 16px 2px; animation: nt-march 1s linear infinite; }
    @keyframes nt-march { to { background-position: 16px 0; } }

    /* ---------- Questions ---------- */
    .nt-questions { margin-top: 14px; border: 1px solid var(--nt-line); border-radius: 11px; overflow: hidden; }
    .nt-questions summary { list-style: none; cursor: pointer; display: flex; align-items: center; gap: 8px; padding: 11px 14px; font-size: 14px; font-weight: 600; transition: background .2s; }
    .nt-questions summary::-webkit-details-marker { display: none; }
    .nt-questions summary:hover { background: var(--nt-bg); }
    .nt-questions summary svg { width: 18px; height: 18px; color: var(--nt-accent); }
    .nt-questions .nt-chevron { margin-left: auto; color: var(--nt-muted); transition: transform .25s; }
    .nt-questions[open] .nt-chevron { transform: rotate(180deg); }
    .nt-qlist { margin: 0; padding: 4px 14px 14px 34px; max-height: 320px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; }
    .nt-questions[open] .nt-qlist { animation: nt-in .3s ease both; }
    .nt-q { margin: 0; font-size: 14px; font-weight: 600; }
    .nt-a { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 5px; }
    .nt-chip { font-size: 12px; font-weight: 600; padding: 3px 9px; border-radius: 6px; background: var(--nt-bg); color: var(--nt-muted); }
    .nt-chip-resp { background: rgba(19, 138, 91, .13); color: var(--nt-ok); }
    .nt-note { margin: 5px 0 0; font-size: 13px; color: var(--nt-muted); }
    .nt-note b { color: var(--nt-ink); }

    /* ---------- Buttons ---------- */
    .nt-card-foot { display: flex; justify-content: flex-end; gap: 10px; margin-top: 16px; }
    .nt-btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border-radius: 10px; font-size: 14px; font-weight: 600; border: 1px solid transparent; cursor: pointer; text-decoration: none; transition: transform .15s, box-shadow .2s, background .2s; }
    .nt-btn svg { width: 17px; height: 17px; }
    .nt-btn:active { transform: scale(.97); }
    .nt-btn-ghost { background: var(--nt-card); color: var(--nt-ink); border-color: var(--nt-line); }
    .nt-btn-ghost:hover { background: var(--nt-bg); }
    .nt-btn-verify { background: var(--nt-ok); color: #fff; }
    .nt-btn-verify:hover { box-shadow: 0 6px 16px rgba(19, 138, 91, .4); transform: translateY(-1px); }
    .nt-btn-verify .nt-spinner { display: none; }
    .nt-btn-verify.loading { pointer-events: none; opacity: .85; }
    .nt-btn-verify.loading .nt-spinner { display: inline-block; }
    .nt-btn-verify.loading .nt-check { display: none; }
    .nt-btn-verify.done { background: var(--nt-ok); }
    .nt-btn-verify.done .nt-check { animation: nt-pop .4s ease; }
    @keyframes nt-pop { 0% { transform: scale(0) rotate(-40deg); } 70% { transform: scale(1.4); } 100% { transform: scale(1); } }

    .nt-spinner { width: 16px; height: 16px; border-radius: 50%; border: 2.5px solid rgba(255, 255, 255, .4); border-top-color: #fff; animation: nt-spin .7s linear infinite; }
    .nt-spinner-lg { width: 22px; height: 22px; border-color: var(--nt-line); border-top-color: var(--nt-accent); }
    @keyframes nt-spin { to { transform: rotate(360deg); } }

    /* ---------- Empty / loading / toast ---------- */
    .nt-loading { display: flex; align-items: center; justify-content: center; gap: 10px; padding: 60px 0; color: var(--nt-muted); }
    .nt-empty { text-align: center; padding: 70px 20px; animation: nt-in .5s ease both; }
    .nt-empty-icon { width: 72px; height: 72px; margin: 0 auto 14px; border-radius: 50%; background: rgba(19, 138, 91, .13); color: var(--nt-ok); display: grid; place-items: center; }
    .nt-empty-icon svg { width: 36px; height: 36px; }
    .nt-empty h4 { margin: 0; font-size: 18px; }
    .nt-empty p { margin: 6px 0 0; color: var(--nt-muted); font-size: 14px; }

    .nt-toast { position: fixed; left: 50%; bottom: 28px; transform: translate(-50%, 30px); background: var(--nt-ink); color: var(--nt-card); padding: 11px 18px; border-radius: 10px; font-size: 14px; font-weight: 600; opacity: 0; pointer-events: none; transition: .3s; z-index: 80; }
    .nt-toast.show { opacity: 1; transform: translate(-50%, 0); }
    .nt-toast.error { background: var(--nt-alert); color: #fff; }

    @media (max-width: 480px) {
        .nt-meta { grid-template-columns: repeat(2, 1fr); }
        .nt-time { flex-direction: column; align-items: stretch; }
        .nt-time-line { display: none; }
    }
    @media (prefers-reduced-motion: reduce) {
        .nt-bell svg, .nt-badge::after, .nt-dot, .nt-time-line, .nt-card { animation: none !important; }
        .nt-drawer, .nt-overlay, .nt-card { transition-duration: .01s !important; }
    }
</style>

<script>
(function () {
    const bell     = document.getElementById('ntBell');
    const badge    = document.getElementById('ntBadge');
    const drawer   = document.getElementById('ntDrawer');
    const overlay  = document.getElementById('ntOverlay');
    const body     = document.getElementById('ntBody');
    const countTxt = document.getElementById('ntCountText');
    const toast    = document.getElementById('ntToast');

    const URL_LIST = @json(route('quality.notifications'));
    const CSRF     = @json(csrf_token());

    function setCount(n) {
        countTxt.textContent = n;
        badge.textContent = n;
        badge.hidden = n < 1;
        bell.classList.toggle('has-new', n > 0);
        badge.classList.remove('bump');
        void badge.offsetWidth;            // restart animation
        badge.classList.add('bump');
    }

    function showToast(msg, isError) {
        toast.textContent = msg;
        toast.classList.toggle('error', !!isError);
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 2400);
    }

    async function load() {
        try {
            const res  = await fetch(URL_LIST, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            body.innerHTML = data.html;
            setCount(data.count);
        } catch (e) {
            body.innerHTML = '<div class="nt-empty"><h4>Could not load</h4><p>Check your connection and try again.</p></div>';
        }
    }

    function openDrawer() {
        drawer.classList.add('open');
        overlay.classList.add('open');
        drawer.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        load();
    }

    function closeDrawer() {
        drawer.classList.remove('open');
        overlay.classList.remove('open');
        drawer.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    bell.addEventListener('click', openDrawer);
    overlay.addEventListener('click', closeDrawer);
    document.getElementById('ntClose').addEventListener('click', closeDrawer);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });

    // Verify button (event delegation, works for cards loaded later)
    body.addEventListener('click', async function (e) {
        const btn = e.target.closest('[data-verify]');
        if (!btn) return;

        const card = btn.closest('.nt-card');
        btn.classList.add('loading');

        try {
            const res = await fetch(btn.dataset.verify, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
            });
            const data = await res.json();
            if (!res.ok || !data.success) throw new Error();

            btn.classList.remove('loading');
            btn.classList.add('done');
            btn.querySelector('.nt-btn-text').textContent = 'Verified';

            setTimeout(() => {
                card.classList.add('leaving');
                setTimeout(() => {
                    card.remove();
                    setCount(data.count);
                    showToast('Checklist verified');
                    if (!body.querySelector('.nt-card')) load();   // shows the empty state
                }, 600);
            }, 500);
        } catch (err) {
            btn.classList.remove('loading');
            showToast('Could not verify. Try again.', true);
        }
    });

    // Read the real count as soon as the page loads, then every 30 seconds.
    // (Does not depend on the controller passing $pendingCount to the view.)
    async function checkCount() {
        if (drawer.classList.contains('open')) return;
        try {
            const res = await fetch(URL_LIST, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const data = await res.json();
            if (String(data.count) !== badge.textContent) setCount(data.count);
        } catch (e) {
            console.error('Notification count failed:', e);   // open the browser console (F12) to see why
        }
    }
    checkCount();
    setInterval(checkCount, 30000);
})();
</script>
