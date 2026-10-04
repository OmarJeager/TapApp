<style>
/* =====================================================================
   DESIGN TOKENS
===================================================================== */
:root {
    --blue-50:#eff6ff; --blue-100:#dbeafe; --blue-200:#bfdbfe; --blue-400:#60a5fa;
    --blue-500:#3b82f6; --blue-600:#2563eb; --blue-700:#1d4ed8; --blue-900:#1e3a8a;
    --green-50:#f0fdf4; --green-100:#dcfce7; --green-500:#22c55e; --green-700:#15803d;
    --amber-100:#fef3c7; --amber-500:#f59e0b; --amber-700:#b45309;
    --red-100:#fee2e2; --red-500:#ef4444; --red-700:#b91c1c;
    --violet-100:#ede9fe; --violet-600:#7c3aed;
    --cyan-100:#cffafe; --cyan-700:#0e7490;
    --pink-100:#fce7f3; --pink-600:#db2777;
    --slate-50:#f8fafc; --slate-100:#f1f5f9; --slate-200:#e2e8f0; --slate-400:#94a3b8;
    --slate-500:#64748b; --slate-600:#475569; --slate-700:#334155; --slate-800:#1e293b;
    --shadow-sm:0 8px 25px rgba(37,99,235,.10);
    --shadow-md:0 20px 50px rgba(15,23,42,.08);
    --ease-out:cubic-bezier(.16,1,.3,1);
}

* { box-sizing: border-box; }


/* =====================================================================
   1. IMPORT NOTIFICATIONS (success / warning / error)
===================================================================== */
.tap-import-alert {
    position: relative; display: flex; align-items: center;
    width: 100%; min-height: 92px; margin: 0 0 25px 0;
    padding: 18px 22px 22px 22px; border-radius: 18px; overflow: hidden;
    border: 1px solid transparent;
    box-shadow: 0 15px 35px rgba(0,0,0,.08), 0 5px 12px rgba(0,0,0,.04);
    animation: tapAlertEnter .65s var(--ease-out), tapAlertFloat 4s ease-in-out infinite;
    transition: transform .3s ease, box-shadow .3s ease;
}
.tap-import-alert:hover {
    transform: translateY(-4px) scale(1.005);
    box-shadow: 0 22px 45px rgba(0,0,0,.12), 0 8px 20px rgba(0,0,0,.06);
}
.tap-import-alert::before { content:""; position:absolute; left:0; top:0; width:6px; height:100%; }

.tap-alert-glow {
    position:absolute; width:180px; height:180px; right:-70px; top:-80px;
    border-radius:50%; opacity:.18; filter:blur(25px);
    animation: tapGlow 3s ease-in-out infinite; pointer-events:none;
}
.tap-alert-icon-wrapper { position:relative; flex-shrink:0; margin-right:18px; }
.tap-alert-icon {
    position:relative; width:54px; height:54px; display:flex; align-items:center; justify-content:center;
    border-radius:16px; color:white; box-shadow:0 8px 20px rgba(0,0,0,.15);
    animation: tapIconPop .7s var(--ease-out) .15s both, tapIconPulse 2.5s ease-in-out 1s infinite;
}
.tap-alert-icon svg { width:28px; height:28px; }
.tap-alert-content { flex:1; min-width:0; position:relative; z-index:2; }
.tap-alert-title { display:flex; align-items:center; flex-wrap:wrap; gap:9px; font-size:17px; font-weight:800; margin-bottom:5px; }
.tap-alert-message { font-size:14px; line-height:1.6; font-weight:500; word-break:break-word; }
.tap-alert-badge {
    display:inline-flex; align-items:center; padding:4px 9px; border-radius:999px;
    font-size:9px; font-weight:900; letter-spacing:1px; animation: badgeAppear .6s ease .35s both;
}
.tap-alert-info {
    display:inline-flex; align-items:center; gap:7px; margin-top:10px;
    padding:6px 10px; border-radius:8px; font-size:11px; font-weight:700;
}
.tap-info-icon { width:18px; height:18px; display:flex; align-items:center; justify-content:center; border-radius:50%; font-size:11px; font-weight:900; }
.tap-alert-close {
    position:relative; z-index:5; flex-shrink:0; width:38px; height:38px; margin-left:15px;
    display:flex; align-items:center; justify-content:center; border:none; border-radius:10px;
    background:rgba(255,255,255,.55); cursor:pointer;
    transition: transform .3s ease, background .3s ease, color .3s ease;
}
.tap-alert-close svg { width:19px; height:19px; }
.tap-alert-close:hover { transform:rotate(90deg) scale(1.1); background:rgba(255,255,255,.9); }
.tap-alert-progress { position:absolute; left:0; bottom:0; height:4px; width:100%; transform-origin:left; animation: tapProgress 7s linear forwards; }

.tap-alert-success { background:linear-gradient(135deg,#ecfdf5,#f0fdf9); border-color:#a7f3d0; color:#065f46; }
.tap-alert-success::before, .tap-alert-success .tap-alert-glow { background:#10b981; }
.tap-alert-success .tap-alert-icon { background:linear-gradient(135deg,#10b981,#059669); }
.tap-alert-success .tap-alert-badge { background:#d1fae5; color:#047857; }
.tap-alert-success .tap-alert-progress { background:linear-gradient(90deg,#10b981,#34d399); }

.tap-alert-warning { background:linear-gradient(135deg,#fff7ed,#fffaf5); border-color:#fed7aa; color:#9a3412; }
.tap-alert-warning::before, .tap-alert-warning .tap-alert-glow { background:#f97316; }
.tap-alert-warning .tap-alert-icon { background:linear-gradient(135deg,#fb923c,#ea580c); }
.tap-alert-warning .tap-alert-badge { background:#ffedd5; color:#c2410c; }
.tap-alert-warning .tap-alert-info { background:rgba(249,115,22,.1); }
.tap-alert-warning .tap-info-icon { background:#f97316; color:white; }
.tap-alert-warning .tap-alert-progress { background:linear-gradient(90deg,#f97316,#fb923c); }

.tap-alert-error { background:linear-gradient(135deg,#fef2f2,#fff7f7); border-color:#fecaca; color:#991b1b; }
.tap-alert-error::before, .tap-alert-error .tap-alert-glow { background:#ef4444; }
.tap-alert-error .tap-alert-icon { background:linear-gradient(135deg,#ef4444,#b91c1c); animation: tapIconPop .7s var(--ease-out) .15s both, tapShake .6s ease .8s 2; }
.tap-alert-error .tap-alert-badge { background:#fee2e2; color:#b91c1c; }
.tap-alert-error .tap-alert-progress { background:linear-gradient(90deg,#ef4444,#f87171); }

.tap-alert-closing { animation: tapAlertClose .45s ease forwards !important; }


/* =====================================================================
   2. PAGE HEADER + STATS
===================================================================== */
.page-icon {
    width:56px; height:56px; display:flex; align-items:center; justify-content:center;
    border-radius:18px; color:var(--blue-600);
    background:linear-gradient(135deg,var(--blue-50),var(--blue-100));
    border:1px solid var(--blue-200); box-shadow:var(--shadow-sm);
    animation: floatingIcon 3s ease-in-out infinite;
}
.page-title {
    background:linear-gradient(90deg,#1e293b,#2563eb 60%,#7c3aed);
    -webkit-background-clip:text; background-clip:text; color:transparent;
    background-size:200% auto; animation: gradientShift 6s linear infinite;
}
.stats-card {
    position:relative; display:flex; align-items:center; gap:14px; padding:14px 20px;
    border-radius:20px; background:white; border:1px solid var(--blue-100);
    box-shadow:0 10px 30px rgba(15,23,42,.06); overflow:hidden;
    transition: transform .3s ease, box-shadow .3s ease;
}
.stats-card::after {
    content:""; position:absolute; top:0; left:-60%; width:40%; height:100%;
    background:linear-gradient(90deg,transparent,rgba(96,165,250,.25),transparent);
    transform:skewX(-20deg); animation: shine 4s ease-in-out infinite;
}
.stats-card:hover { transform:translateY(-3px); box-shadow:0 15px 35px rgba(37,99,235,.12); }
.stats-icon {
    width:45px; height:45px; display:flex; align-items:center; justify-content:center;
    border-radius:14px; color:var(--blue-600); background:var(--blue-50);
}


/* =====================================================================
   3. SEARCH
===================================================================== */
.search-hint {
    position:absolute; right:56px; top:50%; transform:translateY(-50%);
    padding:3px 8px; border-radius:7px; font-size:11px; font-weight:800;
    color:var(--slate-400); background:var(--slate-100); border:1px solid var(--slate-200);
    pointer-events:none; transition: opacity .2s ease;
}
#jobSearch:focus ~ .search-hint { opacity:0; }
.search-spinner {
    position:absolute; right:56px; top:50%; margin-top:-9px; width:18px; height:18px;
    border-radius:50%; border:2px solid var(--blue-100); border-top-color:var(--blue-600);
    animation: spin .7s linear infinite; display:none;
}
.search-spinner.active { display:block; }


/* =====================================================================
   4. IMPORT CARD
===================================================================== */
.import-card {
    position:relative; padding:28px; border-radius:28px;
    background:linear-gradient(145deg,#ffffff,#f8fbff);
    border:1px solid var(--blue-100); box-shadow:var(--shadow-md); overflow:hidden;
}
.import-card::before {
    content:""; position:absolute; width:240px; height:240px; top:-130px; right:-80px;
    border-radius:50%; background:var(--blue-100); opacity:.35; pointer-events:none;
    animation: tapGlow 5s ease-in-out infinite;
}
.import-card::after {
    content:""; position:absolute; width:160px; height:160px; bottom:-90px; left:-60px;
    border-radius:50%; background:var(--violet-100); opacity:.45; pointer-events:none;
    animation: tapGlow 6s ease-in-out infinite reverse;
}
.import-header { position:relative; z-index:1; display:flex; align-items:center; justify-content:space-between; gap:20px; margin-bottom:24px; }
.import-main-icon {
    width:55px; height:55px; display:flex; align-items:center; justify-content:center;
    border-radius:17px; color:white;
    background:linear-gradient(135deg,var(--blue-600),var(--violet-600));
    box-shadow:0 10px 25px rgba(37,99,235,.25); animation: floatingIcon 3s ease-in-out infinite;
}
.format-badges { display:flex; gap:8px; flex-wrap:wrap; }
.format-badge {
    display:inline-flex; align-items:center; gap:5px; padding:7px 11px; border-radius:10px;
    font-size:10px; font-weight:900; letter-spacing:.06em; transition: transform .25s ease;
}
.format-badge:hover { transform:translateY(-3px) rotate(-2deg); }
.format-badge svg { width:13px; height:13px; }
.format-badge.excel { color:var(--green-700); background:var(--green-100); border:1px solid #bbf7d0; }
.format-badge.csv   { color:var(--blue-700);  background:var(--blue-100);  border:1px solid var(--blue-200); }

/* Steps */
.import-steps { position:relative; z-index:1; display:flex; gap:10px; margin-bottom:18px; flex-wrap:wrap; }
.import-step {
    flex:1; min-width:150px; display:flex; align-items:center; gap:10px; padding:10px 12px;
    border-radius:14px; background:white; border:1px solid var(--slate-200);
    font-size:12px; font-weight:800; color:var(--slate-500); transition: all .35s ease;
}
.import-step .step-num {
    width:26px; height:26px; flex-shrink:0; display:flex; align-items:center; justify-content:center;
    border-radius:50%; background:var(--slate-100); color:var(--slate-400); font-size:12px; transition: all .35s ease;
}
.import-step.active { border-color:var(--blue-400); color:var(--blue-700); background:var(--blue-50); box-shadow:0 8px 20px rgba(37,99,235,.10); }
.import-step.active .step-num { background:var(--blue-600); color:white; animation: pulseRing 1.8s infinite; }
.import-step.done { border-color:#bbf7d0; color:var(--green-700); background:var(--green-50); }
.import-step.done .step-num { background:var(--green-500); color:white; }

/* Drop zone */
.drop-zone {
    position:relative; z-index:1; display:flex; flex-direction:column; align-items:center; justify-content:center;
    min-height:210px; padding:30px; border-radius:22px; border:2px dashed var(--blue-200);
    background:linear-gradient(145deg,#f8fbff,var(--blue-50)); cursor:pointer; overflow:hidden;
    transition: border-color .3s ease, background .3s ease, transform .3s ease, box-shadow .3s ease;
}
.drop-zone::before {
    content:""; position:absolute; inset:0; pointer-events:none; opacity:0;
    background:repeating-linear-gradient(45deg,rgba(37,99,235,.05) 0 12px,transparent 12px 24px);
    transition: opacity .3s ease;
}
.drop-zone:hover { border-color:var(--blue-400); background:linear-gradient(145deg,var(--blue-50),var(--blue-100)); transform:translateY(-2px); box-shadow:0 15px 35px rgba(37,99,235,.08); }
.drop-zone:hover::before { opacity:1; animation: stripes 1.2s linear infinite; }
.drop-zone.dragover { border-color:var(--blue-600); background:var(--blue-100); transform:scale(1.015); box-shadow:0 20px 40px rgba(37,99,235,.14); border-style:solid; }
.drop-zone.dragover::before { opacity:1; animation: stripes .6s linear infinite; }
.drop-zone.file-selected { border-color:var(--green-500); background:linear-gradient(145deg,#f0fdf4,#ecfdf5); }
.upload-icon-wrapper {
    position:relative; width:72px; height:72px; display:flex; align-items:center; justify-content:center;
    border-radius:22px; background:white; border:1px solid var(--blue-100);
    box-shadow:var(--shadow-sm); transition: transform .3s ease;
}
.upload-icon-wrapper::after {
    content:""; position:absolute; inset:-6px; border-radius:26px; border:2px solid var(--blue-400);
    opacity:0; animation: pulseOut 2.4s ease-out infinite;
}
.drop-zone:hover .upload-icon-wrapper { transform:translateY(-6px) scale(1.05); }
.drop-zone.file-selected .upload-icon-wrapper::after { border-color:var(--green-500); }
.small-format {
    display:inline-flex; align-items:center; gap:5px; padding:5px 10px; border-radius:8px;
    background:white; color:var(--slate-500); border:1px solid var(--slate-200); font-size:11px; font-weight:700;
}
.small-format svg { width:12px; height:12px; color:var(--blue-500); }

/* Selected file */
.selected-file {
    position:relative; z-index:1; margin-top:16px; padding:15px 18px; border-radius:17px;
    background:var(--green-50); border:1px solid #bbf7d0; align-items:center; justify-content:space-between;
    animation: fileIn .5s var(--ease-out);
}
.file-icon { width:45px; height:45px; display:flex; align-items:center; justify-content:center; border-radius:13px; color:var(--green-700); background:var(--green-100); }
.file-check { color:var(--green-500); width:20px; height:20px; flex-shrink:0; animation: tapIconPop .6s var(--ease-out) both; }
.remove-file { width:38px; height:38px; display:flex; align-items:center; justify-content:center; border-radius:11px; color:var(--slate-400); transition:.2s ease; }
.remove-file:hover { color:#dc2626; background:var(--red-100); transform:rotate(90deg); }

/* Footer + button */
.import-footer { position:relative; z-index:1; display:flex; align-items:center; justify-content:space-between; gap:20px; margin-top:22px; padding-top:22px; border-top:1px solid #e5edf7; }
.secure-note svg { color:var(--green-500); }
.upload-button {
    position:relative; overflow:hidden; display:inline-flex; align-items:center; justify-content:center; gap:10px;
    min-width:190px; padding:14px 22px; border:none; border-radius:15px; color:white;
    background:linear-gradient(135deg,var(--blue-600),var(--violet-600));
    font-size:14px; font-weight:800; cursor:pointer; box-shadow:0 10px 25px rgba(37,99,235,.22);
    transition: transform .25s ease, box-shadow .25s ease;
}
.upload-button::after {
    content:""; position:absolute; top:0; left:-70%; width:50%; height:100%;
    background:linear-gradient(90deg,transparent,rgba(255,255,255,.35),transparent);
    transform:skewX(-20deg);
}
.upload-button:not(:disabled)::after { animation: shine 3s ease-in-out infinite; }
.upload-button:not(:disabled):hover { transform:translateY(-3px); box-shadow:0 15px 35px rgba(37,99,235,.30); }
.upload-button:not(:disabled):active { transform:translateY(0); }
.upload-button:not(:disabled) #buttonNormal svg { animation: arrowUp 1.4s ease-in-out infinite; }
#buttonNormal, #buttonLoading { display:inline-flex; align-items:center; justify-content:center; gap:9px; }
.loading-spinner { width:18px; height:18px; border:2px solid rgba(255,255,255,.35); border-top-color:white; border-radius:50%; animation: spin .8s linear infinite; }


/* =====================================================================
   5. TABLE
===================================================================== */
.table-container { background:white; border-radius:28px; border:1px solid var(--blue-100); box-shadow:var(--shadow-md); overflow:hidden; animation: fadeDown .7s ease-out; }
.table-top {
    position:relative; padding:22px 28px; display:flex; align-items:center; justify-content:space-between; gap:15px; overflow:hidden;
    background:linear-gradient(135deg,var(--blue-600),var(--blue-900) 70%,#4c1d95);
    background-size:200% 200%; animation: gradientBg 10s ease infinite;
}
.table-top::after { content:""; position:absolute; right:-40px; top:-60px; width:180px; height:180px; border-radius:50%; background:rgba(255,255,255,.08); pointer-events:none; }
.table-icon { width:48px; height:48px; display:flex; align-items:center; justify-content:center; border-radius:15px; color:white; background:rgba(255,255,255,.14); border:1px solid rgba(255,255,255,.20); backdrop-filter:blur(8px); }
.records-counter { position:relative; z-index:1; display:inline-flex; align-items:center; gap:8px; padding:10px 15px; border-radius:13px; color:white; background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.18); font-size:13px; font-weight:800; }
.counter-dot { width:8px; height:8px; border-radius:50%; background:#4ade80; box-shadow:0 0 0 4px rgba(74,222,128,.15); animation: pulseDot 2s infinite; }

.ppm-table { width:100%; min-width:1200px; border-collapse:separate; border-spacing:0; }
.ppm-table thead th {
    padding:17px 24px; color:var(--slate-600); background:var(--slate-50); border-bottom:2px solid var(--blue-100);
    font-size:11px; font-weight:900; text-transform:uppercase; letter-spacing:.07em; white-space:nowrap; text-align:left;
}
.th-content { display:flex; align-items:center; gap:8px; }
.th-icon {
    width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center;
    border-radius:8px; font-size:12px; color:var(--blue-600); background:var(--blue-50);
}
.ppm-table thead th:nth-child(2) .th-icon { color:var(--violet-600); background:var(--violet-100); }
.ppm-table thead th:nth-child(3) .th-icon { color:var(--amber-700);  background:var(--amber-100); }
.ppm-table thead th:nth-child(4) .th-icon { color:var(--cyan-700);   background:var(--cyan-100); }
.ppm-table thead th:nth-child(5) .th-icon { color:var(--pink-600);   background:var(--pink-100); }
.ppm-table thead th:nth-child(6) .th-icon { color:var(--green-700);  background:var(--green-100); }
.ppm-table thead th:nth-child(7) .th-icon { color:var(--blue-700);   background:var(--blue-100); }

.ppm-table tbody tr { background:white; transition: background .25s ease, transform .25s ease, box-shadow .25s ease; animation: ppmRow .45s ease both; }
.ppm-table tbody tr:nth-child(even) { background:#fbfdff; }
.ppm-table tbody tr:hover { background:var(--blue-50); transform:scale(1.001); box-shadow:inset 4px 0 0 var(--blue-500); }
.ppm-table tbody td { padding:18px 24px; color:var(--slate-700); font-size:14px; white-space:nowrap; vertical-align:middle; border-bottom:1px solid #edf2f7; }
.ppm-table tbody tr:last-child td { border-bottom:none; }

.asset-cell { display:flex; align-items:center; gap:12px; }
.asset-avatar {
    width:42px; height:42px; display:flex; align-items:center; justify-content:center; border-radius:13px;
    color:var(--blue-600); background:linear-gradient(135deg,var(--blue-100),var(--blue-50)); border:1px solid var(--blue-200);
    font-size:14px; font-weight:900; transition: transform .25s ease;
}
.ppm-row:nth-child(4n+2) .asset-avatar { color:var(--violet-600); background:linear-gradient(135deg,var(--violet-100),#f5f3ff); border-color:#ddd6fe; }
.ppm-row:nth-child(4n+3) .asset-avatar { color:var(--cyan-700);  background:linear-gradient(135deg,var(--cyan-100),#ecfeff); border-color:#a5f3fc; }
.ppm-row:nth-child(4n+4) .asset-avatar { color:var(--pink-600);  background:linear-gradient(135deg,var(--pink-100),#fdf2f8); border-color:#fbcfe8; }
.ppm-row:hover .asset-avatar { transform:rotate(-5deg) scale(1.08); }
.asset-id { display:block; color:var(--blue-700); font-weight:900; }
.asset-label { display:block; margin-top:2px; color:var(--slate-400); font-size:10px; text-transform:uppercase; font-weight:700; letter-spacing:.05em; }

.ppm-id-badge { display:inline-flex; align-items:center; gap:6px; padding:7px 11px; border-radius:10px; color:var(--violet-600); background:#f5f3ff; border:1px solid #ddd6fe; font-weight:800; font-size:12px; }
.ppm-id-badge svg { width:13px; height:13px; }
.week-cell { display:flex; align-items:center; gap:8px; font-weight:700; color:var(--amber-700); }
.week-icon { display:inline-flex; width:28px; height:28px; align-items:center; justify-content:center; border-radius:9px; background:var(--amber-100); font-size:14px; }
.plant-cell { display:flex; align-items:center; gap:8px; }
.plant-icon { display:inline-flex; width:28px; height:28px; align-items:center; justify-content:center; border-radius:9px; background:var(--cyan-100); color:var(--cyan-700); }
.plant-icon svg { width:15px; height:15px; }
.data-value { color:var(--slate-600); font-weight:600; }
.model-badge { display:inline-flex; align-items:center; gap:6px; padding:7px 12px; border-radius:10px; color:var(--pink-600); background:#fdf2f8; border:1px solid #fbcfe8; font-size:12px; font-weight:700; }
.model-badge svg { width:13px; height:13px; }

.status-badge { display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; font-size:11px; font-weight:900; }
.status-dot { width:7px; height:7px; border-radius:50%; }
.status-success { color:var(--green-700); background:var(--green-100); }
.status-success .status-dot { background:var(--green-500); animation: pulseRingGreen 2s infinite; }
.status-warning { color:var(--amber-700); background:var(--amber-100); }
.status-warning .status-dot { background:var(--amber-500); animation: pulseDot 1.4s infinite; }
.status-danger  { color:var(--red-700); background:var(--red-100); }
.status-danger .status-dot { background:var(--red-500); }
.status-neutral { color:var(--slate-600); background:var(--slate-100); }
.status-neutral .status-dot { background:var(--slate-400); }

.view-button {
    position:relative; overflow:hidden; display:inline-flex; align-items:center; gap:8px; padding:10px 15px; border-radius:12px; color:white;
    background:linear-gradient(135deg,var(--blue-600),var(--blue-700)); font-size:12px; font-weight:800;
    box-shadow:0 6px 15px rgba(37,99,235,.16); transition: transform .25s ease, box-shadow .25s ease;
}
.view-button:hover { transform:translateY(-2px); box-shadow:0 10px 22px rgba(37,99,235,.25); }
.view-button svg { transition: transform .25s ease; }
.view-button:hover svg:last-child { transform:translateX(3px); }

.empty-state { padding:80px 20px; display:flex; flex-direction:column; align-items:center; text-align:center; }
.empty-icon { width:78px; height:78px; display:flex; align-items:center; justify-content:center; border-radius:24px; color:var(--blue-400); background:var(--blue-50); margin-bottom:18px; animation: floatingIcon 3s ease-in-out infinite; }
.empty-state h3 { color:var(--slate-700); font-size:18px; font-weight:900; }
.empty-state p  { color:var(--slate-400); margin-top:5px; font-size:14px; }

.pagination-container { padding:20px 24px; border-top:1px solid #edf2f7; background:#fbfdff; }

.table-scroll { scrollbar-width:thin; scrollbar-color:var(--blue-400) var(--blue-50); }
.table-scroll::-webkit-scrollbar { height:9px; }
.table-scroll::-webkit-scrollbar-track { background:var(--blue-50); }
.table-scroll::-webkit-scrollbar-thumb { background:var(--blue-400); border-radius:20px; }
.table-scroll::-webkit-scrollbar-thumb:hover { background:var(--blue-600); }


/* =====================================================================
   6. IMPORT LOADING OVERLAY (with percent)
===================================================================== */
.loading-overlay {
    position:fixed; inset:0; z-index:9999; display:flex; align-items:center; justify-content:center;
    padding:20px; background:rgba(15,23,42,.60); backdrop-filter:blur(8px); animation: overlayIn .25s ease;
}
.loading-overlay.hidden { display:none; }
.loading-card {
    width:min(460px,100%); padding:38px 34px 32px; text-align:center; border-radius:28px; background:white;
    border:1px solid var(--blue-100); box-shadow:0 30px 80px rgba(15,23,42,.25); animation: loadingCardIn .4s ease;
}
.loading-animation { position:relative; width:130px; height:130px; margin:0 auto 22px; display:flex; align-items:center; justify-content:center; }
.percent-ring { position:absolute; inset:0; width:100%; height:100%; transform:rotate(-90deg); }
.percent-ring circle { fill:none; stroke-width:8; }
.percent-ring .ring-bg { stroke:var(--blue-100); }
.percent-ring .ring-fg {
    stroke:url(#ringGradient); stroke-linecap:round;
    stroke-dasharray:326.73; stroke-dashoffset:326.73; transition: stroke-dashoffset .35s ease;
}
.percent-text { position:relative; font-size:30px; font-weight:900; color:var(--slate-800); line-height:1; }
.percent-text small { font-size:15px; font-weight:800; color:var(--blue-600); margin-left:1px; }
.loading-card h3 { color:var(--slate-800); font-size:21px; font-weight:900; }
.loading-card p { color:var(--slate-400); font-size:13px; margin-top:7px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.progress-container { margin-top:22px; height:10px; overflow:hidden; border-radius:999px; background:var(--slate-200); }
.progress-fill {
    position:relative; width:0%; height:100%; border-radius:inherit;
    background:linear-gradient(90deg,var(--blue-600),var(--violet-600),var(--blue-400));
    background-size:200% 100%; animation: gradientShift 2s linear infinite; transition: width .35s ease;
}
.progress-fill::after {
    content:""; position:absolute; inset:0;
    background:repeating-linear-gradient(45deg,rgba(255,255,255,.25) 0 10px,transparent 10px 20px);
    animation: stripes 1s linear infinite;
}
.loading-stages { display:flex; justify-content:space-between; gap:6px; margin-top:20px; }
.loading-stage { flex:1; display:flex; flex-direction:column; align-items:center; gap:6px; font-size:11px; font-weight:800; color:var(--slate-400); transition: color .3s ease; }
.loading-stage .stage-icon { width:34px; height:34px; display:flex; align-items:center; justify-content:center; border-radius:11px; background:var(--slate-100); transition: all .35s ease; }
.loading-stage .stage-icon svg { width:17px; height:17px; }
.loading-stage.active { color:var(--blue-700); }
.loading-stage.active .stage-icon { background:var(--blue-100); color:var(--blue-600); animation: floatingIcon 1.2s ease-in-out infinite; }
.loading-stage.done { color:var(--green-700); }
.loading-stage.done .stage-icon { background:var(--green-100); color:var(--green-700); }
.loading-status { display:flex; align-items:center; justify-content:center; gap:8px; margin-top:18px; color:var(--slate-500); font-size:12px; font-weight:700; }
.loading-dot { width:7px; height:7px; border-radius:50%; background:var(--blue-600); animation: pulseDot 1s infinite; }
.loading-card.finished .percent-text, .loading-card.finished .percent-text small { color:var(--green-700); }


/* =====================================================================
   7. ANIMATIONS
===================================================================== */
@keyframes tapAlertEnter { 0%{opacity:0;transform:translateY(-30px) scale(.94)} 60%{opacity:1;transform:translateY(5px) scale(1.01)} 100%{opacity:1;transform:translateY(0) scale(1)} }
@keyframes tapAlertFloat { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-2px)} }
@keyframes tapIconPop { 0%{opacity:0;transform:scale(.3) rotate(-20deg)} 70%{transform:scale(1.15) rotate(5deg)} 100%{opacity:1;transform:scale(1) rotate(0)} }
@keyframes tapIconPulse { 0%,100%{box-shadow:0 8px 20px rgba(0,0,0,.15)} 50%{box-shadow:0 8px 28px rgba(0,0,0,.25)} }
@keyframes tapGlow { 0%,100%{transform:scale(1);opacity:.15} 50%{transform:scale(1.3);opacity:.25} }
@keyframes badgeAppear { from{opacity:0;transform:scale(.7)} to{opacity:1;transform:scale(1)} }
@keyframes tapProgress { from{transform:scaleX(1)} to{transform:scaleX(0)} }
@keyframes tapShake { 0%,100%{transform:translateX(0)} 25%{transform:translateX(-5px)} 75%{transform:translateX(5px)} }
@keyframes tapAlertClose { to{opacity:0;transform:translateY(-20px) scale(.95);max-height:0;margin-bottom:0;padding-top:0;padding-bottom:0} }
@keyframes spin { to{transform:rotate(360deg)} }
@keyframes pulseDot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.45;transform:scale(.75)} }
@keyframes floatingIcon { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-5px)} }
@keyframes fadeDown { from{opacity:0;transform:translateY(-15px)} to{opacity:1;transform:translateY(0)} }
@keyframes ppmRow { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
@keyframes searchAnimation { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:translateY(0)} }
@keyframes overlayIn { from{opacity:0} to{opacity:1} }
@keyframes loadingCardIn { from{opacity:0;transform:translateY(20px) scale(.96)} to{opacity:1;transform:translateY(0) scale(1)} }
@keyframes fileIn { from{opacity:0;transform:translateX(-20px) scale(.97)} to{opacity:1;transform:translateX(0) scale(1)} }
@keyframes shine { 0%{left:-70%} 60%,100%{left:130%} }
@keyframes stripes { to{background-position:40px 0} }
@keyframes gradientShift { to{background-position:200% center} }
@keyframes gradientBg { 0%,100%{background-position:0% 50%} 50%{background-position:100% 50%} }
@keyframes pulseOut { 0%{opacity:.6;transform:scale(.9)} 100%{opacity:0;transform:scale(1.25)} }
@keyframes pulseRing { 0%{box-shadow:0 0 0 0 rgba(37,99,235,.45)} 70%{box-shadow:0 0 0 9px rgba(37,99,235,0)} 100%{box-shadow:0 0 0 0 rgba(37,99,235,0)} }
@keyframes pulseRingGreen { 0%{box-shadow:0 0 0 0 rgba(34,197,94,.5)} 70%{box-shadow:0 0 0 7px rgba(34,197,94,0)} 100%{box-shadow:0 0 0 0 rgba(34,197,94,0)} }
@keyframes arrowUp { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-3px)} }

.animate-fade-down { animation: fadeDown .6s ease-out; }
.animate-search { animation: searchAnimation .25s ease-out; }

.ppm-row:nth-child(1){animation-delay:.05s} .ppm-row:nth-child(2){animation-delay:.10s}
.ppm-row:nth-child(3){animation-delay:.15s} .ppm-row:nth-child(4){animation-delay:.20s}
.ppm-row:nth-child(5){animation-delay:.25s} .ppm-row:nth-child(6){animation-delay:.30s}
.ppm-row:nth-child(7){animation-delay:.35s}


/* =====================================================================
   8. RESPONSIVE + ACCESSIBILITY
===================================================================== */
@media (max-width: 768px) {
    .import-card { padding:20px; }
    .import-header { flex-direction:column; align-items:flex-start; }
    .import-footer { flex-direction:column; align-items:stretch; }
    .upload-button { width:100%; }
    .table-top { flex-direction:column; align-items:flex-start; }
    .records-counter { align-self:stretch; justify-content:center; }
}
@media (max-width: 640px) {
    .tap-import-alert { align-items:flex-start; padding:15px 14px 19px; border-radius:15px; }
    .tap-alert-icon-wrapper { margin-right:12px; }
    .tap-alert-icon { width:44px; height:44px; border-radius:13px; }
    .tap-alert-icon svg { width:23px; height:23px; }
    .tap-alert-title { font-size:14px; }
    .tap-alert-message { font-size:12px; }
    .tap-alert-close { width:32px; height:32px; margin-left:8px; }
    .tap-alert-info { font-size:10px; }
}
@media (max-width: 480px) {
    .drop-zone { min-height:180px; padding:20px; }
    .format-badges { display:none; }
    .loading-card { padding:30px 22px; }
    .search-hint { display:none; }
}
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after { animation-duration:.01ms !important; animation-iteration-count:1 !important; transition-duration:.01ms !important; }
}
</style>


<script>
function closeTapAlert(button) {

    const alert = button.closest('.tap-import-alert');

    if (!alert) return;

    alert.classList.add('tap-alert-closing');

    setTimeout(() => {
        alert.remove();
    }, 450);
}


/*
 * Automatically remove the notification
 * after 7 seconds.
 */
document.addEventListener('DOMContentLoaded', function () {

    const alert = document.getElementById('tapImportAlert');

    if (!alert) return;

    setTimeout(() => {

        if (!alert.classList.contains('tap-alert-closing')) {

            alert.classList.add('tap-alert-closing');

            setTimeout(() => {
                alert.remove();
            }, 450);
        }

    }, 7000);

});
</script>
<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200">
                {{ __('PPM Records') }}
            </h2>
        </div>
    </x-slot>

    @include('layouts.main')

    {{-- ==========================================
         IMPORT NOTIFICATIONS
    ========================================== --}}

    @if (session('success'))
        <div class="tap-import-alert tap-alert-success" id="tapImportAlert">

            <div class="tap-alert-glow"></div>

            <div class="tap-alert-icon-wrapper">
                <div class="tap-alert-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M5 13L9 17L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <div class="tap-alert-content">
                <div class="tap-alert-title">
                    Import Successful
                    <span class="tap-alert-badge">SUCCESS</span>
                </div>

                <div class="tap-alert-message">
                    {{ session('success') }}
                </div>
            </div>

            <button type="button" class="tap-alert-close" onclick="closeTapAlert(this)" aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M6 6L18 18M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>

            <div class="tap-alert-progress"></div>
        </div>
    @endif


    @if (session('warning'))
        <div class="tap-import-alert tap-alert-warning" id="tapImportAlert">

            <div class="tap-alert-glow"></div>

            <div class="tap-alert-icon-wrapper">
                <div class="tap-alert-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M12 9V13" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                        <path d="M12 17.2V17.3" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                        <path d="M10.3 4.8L2.7 18C2 19.3 2.9 20.8 4.4 20.8H19.6C21.1 20.8 22 19.3 21.3 18L13.7 4.8C13 3.5 11 3.5 10.3 4.8Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <div class="tap-alert-content">

                <div class="tap-alert-title">
                    Import Completed
                    <span class="tap-alert-badge">WARNING</span>
                </div>

                <div class="tap-alert-message">
                    {{ session('warning') }}
                </div>

                <div class="tap-alert-info">
                    <span class="tap-info-icon">!</span>
                    Existing Job IDs were protected and skipped.
                </div>

            </div>

            <button type="button" class="tap-alert-close" onclick="closeTapAlert(this)" aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M6 6L18 18M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>

            <div class="tap-alert-progress"></div>
        </div>
    @endif


    @if (session('error'))
        <div class="tap-import-alert tap-alert-error" id="tapImportAlert">

            <div class="tap-alert-glow"></div>

            <div class="tap-alert-icon-wrapper">
                <div class="tap-alert-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M12 8V13" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                        <path d="M12 16.8V16.9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </div>
            </div>

            <div class="tap-alert-content">

                <div class="tap-alert-title">
                    Import Failed
                    <span class="tap-alert-badge">ERROR</span>
                </div>

                <div class="tap-alert-message">
                    {{ session('error') }}
                </div>

            </div>

            <button type="button" class="tap-alert-close" onclick="closeTapAlert(this)" aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M6 6L18 18M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>

            <div class="tap-alert-progress"></div>
        </div>
    @endif

    <div class="py-10">

        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8">

            {{-- =========================================================
                PAGE HEADER
            ========================================================== --}}
            <div class="mb-8 animate-fade-down">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                    <div>
                        <div class="flex items-center gap-3">

                            <div class="page-icon">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v10a2 2 0 01-2 2z"/>
                                </svg>
                            </div>

                            <div>
                                <h1 class="page-title text-3xl font-extrabold">
                                    PPM Records
                                </h1>

                                <p class="mt-1 text-gray-500">
                                    Search, import and manage your PPM assets
                                </p>
                            </div>

                        </div>
                    </div>


                    {{-- TOTAL --}}
                    <div class="stats-card">

                        <div class="stats-icon">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v10a2 2 0 01-2 2z"/>
                            </svg>
                        </div>

                        <div>
                            <span class="block text-xs uppercase tracking-wider text-gray-400 font-bold">
                                Total Assets
                            </span>

                            <span class="block text-2xl font-extrabold text-blue-600">
                                {{ number_format($ppmRecords->total()) }}
                            </span>
                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================================
                SEARCH
            ========================================================== --}}
            <div class="relative max-w-3xl mb-8">

                <div class="relative group">

                    <div class="absolute inset-y-0 left-0 flex items-center pl-5 pointer-events-none">

                        <svg class="w-6 h-6 text-blue-500 transition-transform duration-300 group-focus-within:scale-110"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"/>
                        </svg>

                    </div>

                    <input
                        type="text"
                        id="jobSearch"
                        autocomplete="off"
                        placeholder="Search by Job ID..."
                        class="
                            w-full pl-14 pr-14 py-5 rounded-2xl border border-blue-100 bg-white
                            text-gray-800 placeholder-gray-400 shadow-sm outline-none
                            transition-all duration-300
                            hover:border-blue-300 hover:shadow-md
                            focus:border-blue-500 focus:ring-4 focus:ring-blue-100 focus:shadow-xl
                        "
                    />

                    <span class="search-hint">Job ID</span>
                    <span class="search-spinner" id="searchSpinner"></span>

                    <button
                        type="button"
                        id="clearSearch"
                        class="
                            hidden absolute right-5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full
                            flex items-center justify-center text-gray-400
                            hover:text-blue-600 hover:bg-blue-50 transition-all duration-200
                        ">

                        ✕

                    </button>

                </div>


                <div
                    id="searchResults"
                    class="
                        hidden absolute z-50 left-0 right-0 mt-3 bg-white border border-blue-100
                        rounded-2xl shadow-2xl overflow-hidden animate-search
                    ">
                </div>

            </div>


            {{-- =========================================================
                IMPORT CARD
            ========================================================== --}}
            <form
                id="importForm"
                action="{{ route('ppm-records.import') }}"
                method="POST"
                enctype="multipart/form-data"
                class="import-card mb-10">

                @csrf

                {{-- Header --}}
                <div class="import-header">

                    <div class="flex items-center gap-4">

                        <div class="import-main-icon">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 16V4m0 0L8 8m4-4l4 4M5 20h14a2 2 0 002-2v-3a2 2 0 00-2-2h-1m-12 0H5a2 2 0 00-2 2v3a2 2 0 002 2h14"/>
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-xl font-extrabold text-gray-800">
                                Import PPM Records
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Upload your maintenance records file
                            </p>
                        </div>

                    </div>


                    <div class="format-badges">

                        <span class="format-badge excel">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M10 3v18M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>
                            XLSX
                        </span>

                        <span class="format-badge excel">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M10 3v18M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>
                            XLS
                        </span>

                        <span class="format-badge csv">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h10"/></svg>
                            CSV
                        </span>

                    </div>

                </div>


                {{-- Steps --}}
                <div class="import-steps">

                    <div class="import-step active" id="step1">
                        <span class="step-num">1</span>
                        Choose file
                    </div>

                    <div class="import-step" id="step2">
                        <span class="step-num">2</span>
                        Upload &amp; Import
                    </div>

                    <div class="import-step" id="step3">
                        <span class="step-num">3</span>
                        Done
                    </div>

                </div>


                {{-- Drop zone --}}
                <label for="ppm-file" id="dropZone" class="drop-zone">

                    <input
                        id="ppm-file"
                        type="file"
                        name="file"
                        accept=".xlsx,.xls,.csv"
                        required
                        class="hidden"
                    />

                    <div class="upload-icon-wrapper">

                        <svg id="uploadIcon" class="w-9 h-9 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 0115.9 6H16a5 5 0 011 9.9M12 12v8m0-8l-3 3m3-3l3 3"/>
                        </svg>

                    </div>


                    <div class="mt-4 text-center">

                        <p class="text-lg font-bold text-gray-700">
                            Drop your file here
                        </p>

                        <p class="text-sm text-gray-400 mt-1">
                            or
                            <span class="text-blue-600 font-bold">
                                browse from your computer
                            </span>
                        </p>

                    </div>


                    <div class="mt-4 flex justify-center gap-2 flex-wrap">

                        <span class="small-format">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M10 3v18M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>
                            Excel
                        </span>

                        <span class="small-format">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h10"/></svg>
                            CSV
                        </span>

                        <span class="small-format">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                            Max 10 MB
                        </span>

                    </div>

                </label>


                {{-- Selected file --}}
                <div id="selectedFile" class="selected-file hidden">

                    <div class="flex items-center gap-4 min-w-0">

                        <div class="file-icon">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"/>
                            </svg>
                        </div>

                        <div class="min-w-0">

                            <p id="fileName" class="font-bold text-gray-700 truncate"></p>

                            <p id="fileSize" class="text-xs text-gray-400 mt-1"></p>

                        </div>

                        <svg class="file-check" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>

                    </div>


                    <button type="button" id="removeFile" class="remove-file">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                </div>


                {{-- Upload button --}}
                <div class="import-footer">

                    <div class="secure-note flex items-center gap-2 text-sm text-gray-400">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 11c0-1.1.9-2 2-2s2 .9 2 2v1H12v-1zm-4 1V9a4 4 0 118 0v3m-9 0h10a1 1 0 011 1v6a1 1 0 01-1 1H7a1 1 0 01-1-1v-6a1 1 0 011-1z"/>
                        </svg>

                        <span>
                            Your data will be imported securely
                        </span>

                    </div>


                    <button
                        type="submit"
                        id="uploadButton"
                        class="upload-button disabled:opacity-60 disabled:cursor-not-allowed"
                        disabled>

                        <span id="buttonNormal">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 16V4m0 0l-4 4m4-4l4 4M5 20h14"/>
                            </svg>

                            Upload & Import

                        </span>


                        <span id="buttonLoading" class="hidden">

                            <span class="loading-spinner"></span>

                            Importing...

                        </span>

                    </button>

                </div>

            </form>


            {{-- =========================================================
                TABLE
            ========================================================== --}}
            <div class="table-container">

                {{-- Table top --}}
                <div class="table-top">

                    <div class="flex items-center gap-4 relative z-10">

                        <div class="table-icon">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 10h18M3 14h18M7 3v18M17 3v18"/>
                            </svg>
                        </div>

                        <div>

                            <h3 class="text-xl font-extrabold text-white">
                                PPM Assets
                            </h3>

                            <p class="text-blue-100 text-sm mt-1">
                                All registered maintenance assets
                            </p>

                        </div>

                    </div>


                    <div class="records-counter">

                        <span class="counter-dot"></span>

                        {{ number_format($ppmRecords->total()) }}

                        <span class="font-normal opacity-80">
                            records
                        </span>

                    </div>

                </div>


                {{-- Table --}}
                <div class="overflow-x-auto table-scroll">

                    <table class="ppm-table">

                        <thead>

                            <tr>

                                <th>
                                    <div class="th-content">
                                        <span class="th-icon">◈</span>
                                        Asset ID
                                    </div>
                                </th>

                                <th>
                                    <div class="th-content">
                                        <span class="th-icon">#</span>
                                        PPM ID
                                    </div>
                                </th>

                                <th>
                                    <div class="th-content">
                                        <span class="th-icon">◷</span>
                                        Week Due
                                    </div>
                                </th>

                                <th>
                                    <div class="th-content">
                                        <span class="th-icon">⌂</span>
                                        Plant
                                    </div>
                                </th>

                                <th>
                                    <div class="th-content">
                                        <span class="th-icon">▣</span>
                                        Asset ID
                                    </div>
                                </th>

                                <th>
                                    <div class="th-content">
                                        <span class="th-icon">●</span>
                                        Status
                                    </div>
                                </th>

                                <th>
                                    <div class="th-content">
                                        <span class="th-icon">→</span>
                                        Actions
                                    </div>
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($ppmRecords as $record)

                                <tr class="ppm-row">

                                    {{-- ASSET --}}
                                    <td>

                                        <div class="asset-cell">

                                            <div class="asset-avatar">
                                                {{ strtoupper(substr($record->asset_id ?? 'A', 0, 1)) }}
                                            </div>

                                            <div>

                                                <span class="asset-id">
                                                    {{ $record->asset_id ?? '—' }}
                                                </span>

                                                <span class="asset-label">
                                                    Asset
                                                </span>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- PPM --}}
                                    <td>

                                        <span class="ppm-id-badge">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                            {{ $record->ppm_id ?? '—' }}
                                        </span>

                                    </td>


                                    {{-- WEEK --}}
                                    <td>

                                        <div class="week-cell">

                                            <span class="week-icon">
                                                📅
                                            </span>

                                            <span>
                                                {{ $record->week_due ?? '—' }}
                                            </span>

                                        </div>

                                    </td>


                                    {{-- PLANT --}}
                                    <td>

                                        <div class="plant-cell">

                                            <span class="plant-icon">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21V10l6 4V10l6 4V6h3v15H3z"/></svg>
                                            </span>

                                            <span class="data-value">
                                                {{ $record->plant_group ?? '—' }}
                                            </span>

                                        </div>

                                    </td>


                                    {{-- MODEL --}}
                                    <td>

                                        <span class="model-badge">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M3 9h2m-2 6h2m14-6h2m-2 6h2M7 7h10v10H7V7z"/></svg>
                                            {{ $record->asset_id ?? '—' }}
                                        </span>

                                    </td>


                                    {{-- STATUS --}}
                                    <td>

                                        @php
                                            $status = $record->status ?? 'Active';

                                            $statusClass = match(strtolower($status)) {
                                                'active', 'completed', 'ok' => 'status-success',
                                                'pending', 'waiting' => 'status-warning',
                                                'inactive', 'cancelled', 'failed' => 'status-danger',
                                                default => 'status-neutral',
                                            };
                                        @endphp

                                        <span class="status-badge {{ $statusClass }}">

                                            <span class="status-dot"></span>

                                            {{ $status }}

                                        </span>

                                    </td>


                                    {{-- ACTION --}}
                                    <td>

                                        <a href="{{ route('ppm-records.show', $record->id) }}" class="view-button">

                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.5 12S6 5 12 5s9.5 7 9.5 7-3.5 7-9.5 7S2.5 12 2.5 12z"/>
                                            </svg>

                                            <span>
                                                View Details
                                            </span>

                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>

                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7">

                                        <div class="empty-state">

                                            <div class="empty-icon">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4"/>
                                                </svg>
                                            </div>

                                            <h3>
                                                No PPM records found
                                            </h3>

                                            <p>
                                                Upload an Excel or CSV file to add maintenance records.
                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if($ppmRecords->hasPages())

                    <div class="pagination-container">

                        {{ $ppmRecords->links() }}

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
        IMPORT LOADING OVERLAY (with percent)
    ========================================================== --}}
    <div id="importLoading" class="loading-overlay hidden">

        <div class="loading-card" id="loadingCard">

            <div class="loading-animation">

                <svg class="percent-ring" viewBox="0 0 120 120">
                    <defs>
                        <linearGradient id="ringGradient" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#2563eb"/>
                            <stop offset="100%" stop-color="#7c3aed"/>
                        </linearGradient>
                    </defs>
                    <circle class="ring-bg" cx="60" cy="60" r="52"/>
                    <circle class="ring-fg" id="ringFg" cx="60" cy="60" r="52"/>
                </svg>

                <div class="percent-text">
                    <span id="percentValue">0</span><small>%</small>
                </div>

            </div>


            <h3 id="loadingTitle">
                Importing PPM Records
            </h3>

            <p id="loadingFileName">
                Please wait while your file is being processed...
            </p>


            <div class="progress-container">
                <div class="progress-fill" id="progressFill"></div>
            </div>


            <div class="loading-stages">

                <div class="loading-stage active" id="stageUpload">
                    <span class="stage-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16V4m0 0l-4 4m4-4l4 4M5 20h14"/></svg>
                    </span>
                    Uploading
                </div>

                <div class="loading-stage" id="stageProcess">
                    <span class="stage-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 3.6 3 8 3s8-1 8-3V7M4 7c0 2 3.6 3 8 3s8-1 8-3M4 7c0-2 3.6-3 8-3s8 1 8 3M4 12c0 2 3.6 3 8 3s8-1 8-3"/></svg>
                    </span>
                    Processing
                </div>

                <div class="loading-stage" id="stageDone">
                    <span class="stage-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    Done
                </div>

            </div>


            <div class="loading-status">

                <span class="loading-dot"></span>

                <span id="loadingStatusText">Uploading your file...</span>

            </div>

        </div>

    </div>


    {{-- =========================================================
        JAVASCRIPT
    ========================================================== --}}
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            /* =====================================================
               SEARCH
            ====================================================== */

            const searchInput =
                document.getElementById('jobSearch');

            const searchResults =
                document.getElementById('searchResults');

            const clearSearch =
                document.getElementById('clearSearch');

            const searchSpinner =
                document.getElementById('searchSpinner');

            let searchTimeout = null;


            searchInput.addEventListener('input', function () {

                const jobId = this.value.trim();

                clearTimeout(searchTimeout);


                if (jobId.length === 0) {

                    searchResults.innerHTML = '';

                    searchResults.classList.add('hidden');

                    clearSearch.classList.add('hidden');

                    searchSpinner.classList.remove('active');

                    return;
                }


                clearSearch.classList.remove('hidden');


                searchTimeout = setTimeout(function () {

                    searchSpinner.classList.add('active');

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

                        searchSpinner.classList.remove('active');

                        searchResults.innerHTML = '';


                        if (data.length === 0) {

                            searchResults.innerHTML = `

                                <div class="px-6 py-8 text-center">

                                    <div class="text-blue-300 text-3xl mb-3">
                                        🔍
                                    </div>

                                    <p class="text-gray-700 font-bold">
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


                        data.forEach(function (record) {

                            const item =
                                document.createElement('a');

                            item.href = record.url;

                            item.className = `
                                flex items-center justify-between px-6 py-5
                                border-b border-gray-100 hover:bg-blue-50
                                transition-all duration-200 group
                            `;


                            item.innerHTML = `

                                <div class="flex items-center gap-4">

                                    <div
                                        class="
                                            w-11 h-11 rounded-xl bg-blue-100 text-blue-600
                                            flex items-center justify-center
                                            group-hover:scale-110 transition-transform duration-200
                                        "
                                    >

                                        🔧

                                    </div>


                                    <div>

                                        <div class="font-bold text-blue-600">
                                            Job ID:
                                            ${record.job_id}
                                        </div>


                                        <div class="text-sm text-gray-500 mt-1">
                                            Asset ID:
                                            ${record.asset_id ?? 'N/A'}
                                        </div>

                                    </div>

                                </div>


                                <div
                                    class="
                                        flex items-center gap-2 text-blue-600 font-semibold text-sm
                                        opacity-60 group-hover:opacity-100 group-hover:translate-x-1
                                        transition-all duration-200
                                    "
                                >

                                    Details

                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>

                                </div>

                            `;


                            searchResults.appendChild(item);

                        });


                        searchResults.classList.remove('hidden');

                    })

                    .catch(error => {

                        console.error(error);

                        searchSpinner.classList.remove('active');

                        searchResults.innerHTML = `

                            <div class="p-6 text-center text-red-500">

                                Error while searching.

                            </div>

                        `;

                        searchResults.classList.remove('hidden');

                    });

                }, 250);

            });


            clearSearch.addEventListener('click', function () {

                searchInput.value = '';

                searchResults.innerHTML = '';

                searchResults.classList.add('hidden');

                clearSearch.classList.add('hidden');

                searchSpinner.classList.remove('active');

                searchInput.focus();

            });


            document.addEventListener('click', function (event) {

                if (
                    !searchInput.contains(event.target) &&
                    !searchResults.contains(event.target)
                ) {

                    searchResults.classList.add('hidden');

                }

            });


            /* =====================================================
               FILE IMPORT
            ====================================================== */

            const fileInput       = document.getElementById('ppm-file');
            const dropZone        = document.getElementById('dropZone');
            const selectedFile    = document.getElementById('selectedFile');
            const fileName        = document.getElementById('fileName');
            const fileSize        = document.getElementById('fileSize');
            const removeFile      = document.getElementById('removeFile');
            const uploadButton    = document.getElementById('uploadButton');
            const importForm      = document.getElementById('importForm');
            const importLoading   = document.getElementById('importLoading');
            const loadingFileName = document.getElementById('loadingFileName');
            const buttonNormal    = document.getElementById('buttonNormal');
            const buttonLoading   = document.getElementById('buttonLoading');

            /* Progress UI */
            const loadingCard  = document.getElementById('loadingCard');
            const loadingTitle = document.getElementById('loadingTitle');
            const percentValue = document.getElementById('percentValue');
            const ringFg       = document.getElementById('ringFg');
            const progressFill = document.getElementById('progressFill');
            const statusText   = document.getElementById('loadingStatusText');
            const stageUpload  = document.getElementById('stageUpload');
            const stageProcess = document.getElementById('stageProcess');
            const stageDone    = document.getElementById('stageDone');
            const step1        = document.getElementById('step1');
            const step2        = document.getElementById('step2');
            const step3        = document.getElementById('step3');

            const RING_LENGTH = 326.73;


            /* Step indicator */
            function setSteps(active) {

                [step1, step2, step3].forEach(function (el, i) {

                    el.classList.remove('active', 'done');

                    if (i + 1 < active) el.classList.add('done');
                    if (i + 1 === active) el.classList.add('active');

                });

            }


            /* Percent */
            function setPercent(value) {

                const percent =
                    Math.max(0, Math.min(100, Math.round(value)));

                percentValue.textContent = percent;

                progressFill.style.width = percent + '%';

                ringFg.style.strokeDashoffset =
                    RING_LENGTH - (RING_LENGTH * percent / 100);

            }


            /* File size */
            function formatFileSize(bytes) {

                if (bytes === 0) {
                    return '0 Bytes';
                }

                const units = [
                    'Bytes',
                    'KB',
                    'MB',
                    'GB'
                ];

                const index =
                    Math.floor(
                        Math.log(bytes) /
                        Math.log(1024)
                    );

                return (
                    parseFloat(
                        (bytes / Math.pow(1024, index))
                        .toFixed(2)
                    ) +
                    ' ' +
                    units[index]
                );

            }


            /* Show file */
            function showFile(file) {

                if (!file) {
                    return;
                }


                const allowedExtensions = [
                    'xlsx',
                    'xls',
                    'csv'
                ];

                const extension =
                    file.name
                        .split('.')
                        .pop()
                        .toLowerCase();


                if (!allowedExtensions.includes(extension)) {

                    alert(
                        'Please select an XLSX, XLS, or CSV file.'
                    );

                    fileInput.value = '';

                    return;

                }


                if (file.size > 10 * 1024 * 1024) {

                    alert(
                        'The selected file is larger than 10 MB.'
                    );

                    fileInput.value = '';

                    return;

                }


                fileName.textContent =
                    file.name;

                fileSize.textContent =
                    formatFileSize(file.size);


                selectedFile.classList.remove('hidden');

                selectedFile.classList.add('flex');

                uploadButton.disabled = false;


                dropZone.classList.add('file-selected');

                setSteps(2);

            }


            fileInput.addEventListener('change', function () {

                if (this.files.length > 0) {

                    showFile(this.files[0]);

                }

            });


            /* Drag over */
            [
                'dragenter',
                'dragover'
            ].forEach(eventName => {

                dropZone.addEventListener(
                    eventName,
                    function (event) {

                        event.preventDefault();

                        dropZone.classList.add('dragover');

                    }
                );

            });


            /* Drag leave */
            [
                'dragleave',
                'drop'
            ].forEach(eventName => {

                dropZone.addEventListener(
                    eventName,
                    function (event) {

                        event.preventDefault();

                        dropZone.classList.remove('dragover');

                    }
                );

            });


            /* Drop */
            dropZone.addEventListener('drop', function (event) {

                const files =
                    event.dataTransfer.files;

                if (files.length > 0) {

                    fileInput.files = files;

                    showFile(files[0]);

                }

            });


            /* Remove */
            removeFile.addEventListener('click', function (event) {

                event.preventDefault();

                event.stopPropagation();

                fileInput.value = '';

                selectedFile.classList.add('hidden');

                selectedFile.classList.remove('flex');

                uploadButton.disabled = true;

                dropZone.classList.remove('file-selected');

                setSteps(1);

            });


            /* Submit (AJAX so we can show a real upload percent) */
            importForm.addEventListener('submit', function (event) {

                if (!fileInput.files.length) {

                    event.preventDefault();

                    return;

                }

                event.preventDefault();


                uploadButton.disabled = true;

                buttonNormal.classList.add('hidden');

                buttonLoading.classList.remove('hidden');


                loadingFileName.textContent =
                    'Processing: ' +
                    fileInput.files[0].name;

                setPercent(0);

                importLoading.classList.remove('hidden');

                /*
                 * Prevent accidental navigation/back interaction
                 * while the import is being submitted.
                 */
                document.body.style.overflow = 'hidden';

                setSteps(2);


                /*
                 * Percent plan:
                 *  0  - 60 %  real upload progress
                 *  60 - 95 %  eased while the server imports the rows
                 *  100 %      server answered
                 */
                let processingTimer = null;
                let current = 0;

                function startProcessing() {

                    stageUpload.classList.remove('active');
                    stageUpload.classList.add('done');
                    stageProcess.classList.add('active');

                    statusText.textContent = 'Importing records into the database...';

                    current = Math.max(current, 60);

                    processingTimer = setInterval(function () {

                        current += (95 - current) * 0.04;

                        setPercent(current);

                    }, 300);

                }

                function stopProcessing() {

                    clearInterval(processingTimer);

                }


                const xhr = new XMLHttpRequest();

                xhr.open('POST', importForm.action, true);

                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');


                xhr.upload.addEventListener('progress', function (e) {

                    if (!e.lengthComputable) return;

                    current = (e.loaded / e.total) * 60;

                    setPercent(current);

                    statusText.textContent =
                        'Uploading your file... ' + Math.round((e.loaded / e.total) * 100) + '%';

                });


                xhr.upload.addEventListener('load', startProcessing);


                xhr.addEventListener('load', function () {

                    stopProcessing();

                    setPercent(100);

                    stageProcess.classList.remove('active');
                    stageProcess.classList.add('done');
                    stageDone.classList.add('done');

                    loadingCard.classList.add('finished');

                    loadingTitle.textContent = 'Import finished';

                    statusText.textContent = 'Loading your results...';

                    setSteps(4);

                    /*
                     * The server redirects back to this page with a flash
                     * message. XHR already followed the redirect, so show
                     * that returned page (keeps the success/warning/error alert).
                     */
                    setTimeout(function () {

                        document.body.style.overflow = '';

                        if (xhr.responseText && xhr.responseText.indexOf('<html') !== -1) {

                            try {
                                history.replaceState(null, '', xhr.responseURL || window.location.href);
                            } catch (e) {}

                            document.open();
                            document.write(xhr.responseText);
                            document.close();

                        } else {

                            window.location.reload();

                        }

                    }, 700);

                });


                xhr.addEventListener('error', function () {

                    stopProcessing();

                    document.body.style.overflow = '';

                    /* Fallback: normal form submit */
                    importForm.submit();

                });


                xhr.send(new FormData(importForm));

            });

        });

    </script>

</x-app-layout>
