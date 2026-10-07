@extends('layouts.main')

@section('title', 'Scan Asset')

@section('content')

<style>
    .scan-page {
        min-height: calc(100vh - 140px);
        display: flex; align-items: center; justify-content: center;
        padding: 25px;
    }

    .scan-card {
        width: 100%; max-width: 560px;
        background: #fff; border: 1px solid #eee; border-radius: 18px;
        box-shadow: 0 10px 35px rgba(0,0,0,.09);
        padding: 34px 30px 30px; text-align: center;
        animation: cardIn .5s ease both;
    }
    @keyframes cardIn { from { opacity: 0; transform: translateY(18px) scale(.98); } to { opacity: 1; transform: none; } }

    /* ---------- Hero icon with pulsing rings ---------- */
    .scan-hero { position: relative; width: 110px; height: 110px; margin: 0 auto 18px; }
    .scan-hero .ring {
        position: absolute; inset: 0; border-radius: 50%;
        border: 2px solid #f28c28; opacity: 0;
        animation: pulse 2.4s ease-out infinite;
    }
    .scan-hero .ring:nth-child(2) { animation-delay: .8s; }
    .scan-hero .ring:nth-child(3) { animation-delay: 1.6s; }
    @keyframes pulse { 0% { transform: scale(.6); opacity: .7; } 100% { transform: scale(1.45); opacity: 0; } }

    .scan-hero .core {
        position: absolute; inset: 14px; border-radius: 50%;
        background: linear-gradient(135deg, #ff9800, #f4511e);
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 8px 22px rgba(244,81,30,.35);
        transition: background .3s, transform .3s;
    }
    .scan-hero .core svg { width: 38px; height: 38px; stroke: #fff; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .scan-hero .core .ic-ok, .scan-hero .core .ic-err { display: none; }

    h1.scan-title { margin: 0; font-size: 26px; font-weight: 800; color: #222; }
    .scan-sub { margin: 6px 0 22px; color: #777; font-size: 14px; }

    /* ---------- Input ---------- */
    .scan-field { position: relative; }
    .scan-field .lead-ic {
        position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
        width: 22px; height: 22px; stroke: #f28c28; fill: none; stroke-width: 2;
        stroke-linecap: round; stroke-linejoin: round; pointer-events: none;
    }
    .scan-field input {
        width: 100%; height: 58px; padding: 0 16px 0 52px;
        font-size: 18px; font-weight: 600; letter-spacing: .5px;
        font-family: monospace; color: #222;
        border: 2px solid #e4e4e4; border-radius: 12px; outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .scan-field input::placeholder { color: #bbb; font-family: inherit; font-weight: 400; letter-spacing: 0; }
    .scan-field input:focus { border-color: #f28c28; box-shadow: 0 0 0 5px rgba(242,140,40,.15); }

    /* laser line while waiting */
    .scan-field .laser {
        position: absolute; left: 10px; right: 10px; top: 6px; height: 2px;
        background: linear-gradient(90deg, transparent, #f4511e, transparent);
        opacity: .0; pointer-events: none; border-radius: 2px;
    }
    .state-idle .laser { opacity: .85; animation: laser 2.2s ease-in-out infinite; }
    @keyframes laser { 0%,100% { top: 6px; } 50% { top: calc(100% - 8px); } }

    .scan-hint { margin-top: 12px; font-size: 12.5px; color: #999; display: flex; gap: 6px; justify-content: center; align-items: center; }
    .scan-hint kbd { background: #f3f3f3; border: 1px solid #ddd; border-radius: 4px; padding: 1px 6px; font-size: 11px; }

    /* ---------- States ---------- */
    .state-loading .scan-field input { border-color: #f28c28; background: #fffaf5; }
    .state-loading .core { animation: spin 1s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }

    .state-success .core { background: linear-gradient(135deg, #43a047, #2e7d32); animation: pop .45s ease; }
    .state-success .core .ic-scan, .state-error .core .ic-scan { display: none; }
    .state-success .core .ic-ok { display: block; }
    .state-success .ring { border-color: #43a047; }
    @keyframes pop { 0% { transform: scale(.6); } 60% { transform: scale(1.15); } 100% { transform: scale(1); } }

    .state-error .core { background: linear-gradient(135deg, #e53935, #b71c1c); }
    .state-error .core .ic-err { display: block; }
    .state-error .ring { border-color: #e53935; }
    .state-error .scan-field input { border-color: #e53935; animation: shake .45s; }
    @keyframes shake { 0%,100% { transform: translateX(0); } 20% { transform: translateX(-8px); } 40% { transform: translateX(8px); } 60% { transform: translateX(-5px); } 80% { transform: translateX(5px); } }

    .scan-error { display: none; margin-top: 14px; padding: 11px 14px; border-radius: 10px; background: #fdecea; color: #b71c1c; font-size: 14px; font-weight: 600; }
    .state-error .scan-error { display: block; animation: cardIn .3s ease; }

    /* ---------- Result card ---------- */
    .result { display: none; margin-top: 20px; text-align: left; }
    .state-success .result { display: block; animation: cardIn .4s ease; }

    .result-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .r-item { background: #f8f8f8; border-radius: 10px; padding: 10px 12px; }
    .r-item.full { grid-column: 1 / -1; }
    .r-item small { display: block; font-size: 11px; text-transform: uppercase; letter-spacing: .6px; color: #999; margin-bottom: 3px; }
    .r-item b { font-size: 15px; color: #222; word-break: break-word; }
    .r-item.hl { background: #fff4e6; }
    .r-item.hl b { color: #d97617; font-size: 18px; }

    .last-box { background: #eef6ff; border: 1px solid #d6e8fb; }
    .last-box.none { background: #f8f8f8; border-color: #eee; }
    .done-badge { display: none; margin-top: 10px; padding: 8px 12px; border-radius: 8px; background: #fff8e1; color: #8a6d00; font-size: 13px; font-weight: 600; }

    .redirect-bar { margin-top: 16px; height: 5px; background: #eee; border-radius: 5px; overflow: hidden; }
    .redirect-bar i { display: block; height: 100%; width: 0; background: linear-gradient(90deg, #ff9800, #f4511e); }
    .state-success .redirect-bar i { animation: fill var(--go, 1.6s) linear forwards; }
    @keyframes fill { to { width: 100%; } }
    .redirect-txt { margin-top: 8px; font-size: 12.5px; color: #888; text-align: center; }

    /* ---------- Tap targets ---------- */
    .scan-hero { cursor: pointer; -webkit-tap-highlight-color: transparent; }
    .scan-hero:active .core { transform: scale(.92); }
    .state-success .result { cursor: pointer; }
    .open-now {
        margin-top: 14px; width: 100%; padding: 14px; border: 0; border-radius: 12px;
        background: linear-gradient(135deg, #ff9800, #f4511e); color: #fff;
        font-size: 16px; font-weight: 800; cursor: pointer;
        box-shadow: 0 8px 20px rgba(255,111,0,.3); transition: .2s;
    }
    .open-now:hover { transform: translateY(-2px); }
    .open-now:active { transform: scale(.97); }

    /* ---------- Camera / QR ---------- */
    .cam-btn {
        margin-top: 14px; display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 18px; border: 2px solid #f28c28; border-radius: 10px;
        background: #fff; color: #d97617; font-size: 14px; font-weight: 700; cursor: pointer;
        transition: .2s;
    }
    .cam-btn:hover { background: #f28c28; color: #fff; transform: translateY(-1px); }
    .cam-btn svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .cam-btn.on { background: #f28c28; color: #fff; }

    .qr-box { display: none; margin-top: 16px; border-radius: 14px; overflow: hidden; border: 2px solid #f28c28; position: relative; background: #000; }
    .qr-box.on { display: block; animation: cardIn .35s ease; }
    #qrReader { width: 100%; }
    #qrReader video { width: 100% !important; display: block; }
    .qr-box::after { /* sweeping line over the camera */
        content: ""; position: absolute; left: 8%; right: 8%; top: 10%; height: 3px;
        background: linear-gradient(90deg, transparent, #ff5722, transparent);
        animation: laser2 2s ease-in-out infinite; pointer-events: none;
    }
    @keyframes laser2 { 0%,100% { top: 10%; } 50% { top: 88%; } }
    .state-success .qr-box, .state-error .qr-box { display: none; }

    @media (max-width: 480px) {
        .scan-card { padding: 26px 18px; }
        .result-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="scan-page">
    <div class="scan-card state-idle" id="scanCard">

        <div class="scan-hero">
            <span class="ring"></span><span class="ring"></span><span class="ring"></span>
            <div class="core">
                <svg class="ic-scan" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M7 8v8M11 8v8M15 8v8M18 8v8"/></svg>
                <svg class="ic-ok" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                <svg class="ic-err" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>
            </div>
        </div>

        <h1 class="scan-title">Scan Ticket</h1>
        <p class="scan-sub">Scan the barcode on the PPM ticket or the Asset ID</p>

        <div class="scan-field">
            <svg class="lead-ic" viewBox="0 0 24 24"><path d="M4 6v12M8 6v12M12 6v12M16 6v12M20 6v12"/></svg>
            <input type="text" id="scanInput" placeholder="Waiting for scan…"
                   autocomplete="off" autocapitalize="characters" spellcheck="false" autofocus>
            <span class="laser"></span>
        </div>

        <div class="scan-error" id="scanError"></div>

        <div class="scan-hint">
            <span>Scanner ready</span> · <span>No need to press</span> <kbd>Enter</kbd>
        </div>

        <button type="button" class="cam-btn" id="camBtn">
            <svg viewBox="0 0 24 24"><path d="M3 8a2 2 0 0 1 2-2h2l1.5-2h7L17 6h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><circle cx="12" cy="13" r="3.5"/></svg>
            <span id="camTxt">Scan QR with camera</span>
        </button>
        <div class="qr-box" id="qrBox"><div id="qrReader"></div></div>

        <div class="result" id="result">
            <div class="result-grid">
                <div class="r-item"><small>Asset</small><b id="rAsset">-</b></div>
                <div class="r-item"><small>Job ID</small><b id="rJob">-</b></div>
                <div class="r-item full"><small>Description</small><b id="rDesc">-</b></div>
                <div class="r-item"><small>Frequency</small><b id="rFreq">-</b></div>
                <div class="r-item hl"><small>Intervention WK</small><b id="rInt">-</b></div>
                <div class="r-item hl full"><small>Next Preventative Maintenance Week</small><b id="rNext">-</b></div>
                <div class="r-item full last-box" id="lastBox">
                    <small>Last preventive done</small>
                    <b id="rLast">-</b>
                </div>
            </div>
            <div class="done-badge" id="doneBadge">This job was already completed. Opening it for viewing.</div>
            <div class="redirect-bar"><i></i></div>
            <div class="redirect-txt">Opening checklist… or tap to open now</div>
            <button type="button" class="open-now" id="openNow">Open now →</button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<script>
(function () {
    const GO_DELAY = 1600; // ms the result card stays visible before redirect
    const card  = document.getElementById('scanCard');
    const input = document.getElementById('scanInput');
    const errEl = document.getElementById('scanError');
    const $ = id => document.getElementById(id);

    let busy = false, timer = null, lastKey = 0, gaps = [];

    card.style.setProperty('--go', (GO_DELAY / 1000) + 's');

    function setState(s) { card.className = 'scan-card state-' + s; }
    function setText(id, v) { $(id).textContent = (v === null || v === undefined || v === '') ? '-' : v; }

    // small beep feedback (no audio files needed)
    function beep(ok) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const o = ctx.createOscillator(), g = ctx.createGain();
            o.type = 'sine'; o.frequency.value = ok ? 880 : 220;
            g.gain.value = .08; o.connect(g); g.connect(ctx.destination);
            o.start(); o.stop(ctx.currentTime + (ok ? .12 : .3));
        } catch (e) {}
    }

    /* ---------- Camera QR scanner ---------- */
    const camBtn = $('camBtn'), qrBox = $('qrBox');
    let qr = null, camOn = false, camWanted = false;

    async function startCam() {
        if (typeof Html5Qrcode === 'undefined') {
            errEl.textContent = 'Camera scanner failed to load.'; setState('error');
            setTimeout(reset, 2200); return;
        }
        try {
            qr = qr || new Html5Qrcode('qrReader');
            await qr.start(
                { facingMode: 'environment' },
                { fps: 12, qrbox: { width: 230, height: 230 } },
                text => {
                    if (busy) return;
                    input.value = text.trim();
                    stopCam().then(submit);        // scanned -> go, no click
                },
                () => {}
            );
            camOn = true; camWanted = true;
            qrBox.classList.add('on'); camBtn.classList.add('on');
            $('camTxt').textContent = 'Stop camera';
        } catch (e) {
            camOn = false; camWanted = false;
            errEl.textContent = 'Cannot open camera. Allow camera access (HTTPS required).';
            setState('error'); setTimeout(reset, 2600);
        }
    }

    async function stopCam() {
        if (qr && camOn) { try { await qr.stop(); } catch (e) {} }
        camOn = false;
        qrBox.classList.remove('on'); camBtn.classList.remove('on');
        $('camTxt').textContent = 'Scan QR with camera';
    }

    camBtn.addEventListener('click', () => {
        if (camOn) { camWanted = false; stopCam(); } else { startCam(); }
    });

    function reset() {
        busy = false; gaps = []; lastKey = 0;
        input.value = ''; input.disabled = false;
        setState('idle');
        if (camWanted) startCam(); else input.focus();
    }

    /* ---------- Tap to open now ---------- */
    let goUrl = null, goTimer = null;
    function goNow() {
        if (!goUrl) return;
        clearTimeout(goTimer);
        const u = goUrl; goUrl = null;
        window.location.href = u;
    }
    $('result').addEventListener('click', goNow);   // tap anywhere on the result card

    // Tap the big icon to open / close the camera
    document.querySelector('.scan-hero').addEventListener('click', () => {
        if (busy) return;
        if (camOn) { camWanted = false; stopCam(); } else { startCam(); }
    });

    // Phones / tablets: open the camera automatically
    if (window.matchMedia('(pointer: coarse)').matches) startCam();

    async function submit() {
        const code = input.value.trim();
        if (busy || code.length < 3) return;
        busy = true; clearTimeout(timer);
        input.disabled = true;
        setState('loading');

        try {
            const res = await fetch(@json(route('asset-scan.lookup')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': @json(csrf_token()),
                },
                body: JSON.stringify({ code }),
            });
            const data = await res.json();

            if (!res.ok || !data.ok) throw new Error(data.message || 'Not found.');

            const r = data.record, l = data.last_done;
            setText('rAsset', r.asset_id);
            setText('rJob',   r.job_id);
            setText('rDesc',  r.asset_description);
            setText('rFreq',  r.frequency_label);
            setText('rInt',   r.intervention_week);
            setText('rNext',  r.next_pm_week);

            if (l) {
                const ago = l.days_ago === 0 ? 'today' : l.days_ago + ' day(s) ago';
                setText('rLast', `${l.date} (WK ${l.week ?? '-'}) · ${ago} · by ${l.by ?? '-'}`);
                $('lastBox').classList.remove('none');
            } else {
                setText('rLast', 'No previous preventive recorded');
                $('lastBox').classList.add('none');
            }
            $('doneBadge').style.display = r.already_done ? 'block' : 'none';

            setState('success'); beep(true);
            goUrl = data.url;
            goTimer = setTimeout(goNow, GO_DELAY);

        } catch (e) {
            errEl.textContent = e.message || 'Something went wrong. Try again.';
            setState('error'); beep(false);
            setTimeout(reset, 2200);
        }
    }

    /* Scanners type very fast (< ~60ms between chars). Auto-submit only for
       fast input so slow manual typing never fires a partial code. */
    input.addEventListener('keydown', e => {
        const now = performance.now();
        if (now - lastKey > 500) gaps = [];
        else gaps.push(now - lastKey);
        if (gaps.length > 20) gaps.shift();
        lastKey = now;

        if (e.key === 'Enter') { e.preventDefault(); submit(); }
    });

    input.addEventListener('input', () => {
        clearTimeout(timer);
        if (input.value.trim().length < 4 || !gaps.length) return;
        const avg = gaps.reduce((a, b) => a + b, 0) / gaps.length;
        if (avg < 60) timer = setTimeout(submit, 120);   // scanner finished typing
    });

    // Keep the field focused so a scan always lands in it
    input.addEventListener('blur', () => { if (!busy && !camOn) setTimeout(() => input.focus(), 50); });
    document.addEventListener('keydown', () => { if (!busy && document.activeElement !== input) input.focus(); });
})();
</script>

@endsection