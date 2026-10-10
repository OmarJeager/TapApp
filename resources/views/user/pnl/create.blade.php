<div class="back-btn-container"> <a href="{{ route('user.index') }}" class="back-btn"> <span class="back-arrow">←</span> <span>Back</span> </a> </div>
<button type="button" class="logout-trigger" onclick="document.getElementById('logoutDialog').classList.add('is-open')" aria-label="Log out"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17l5-5-5-5M15 12H3m9-8h6a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-6"/></svg> Log out</button>
<div class="logout-overlay" id="logoutDialog" role="dialog" aria-modal="true" aria-labelledby="logoutTitle" onclick="if(event.target === this) this.classList.remove('is-open')">
    <div class="logout-dialog">
        <div class="logout-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M10 17l5-5-5-5M15 12H3m9-8h6a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-6"/></svg></div>
        <h2 id="logoutTitle">Log out?</h2>
        <p>Are you sure you want to log out?</p>
        <div class="logout-actions">
            <button type="button" class="logout-cancel" onclick="document.getElementById('logoutDialog').classList.remove('is-open')">Stay here</button>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="logout-confirm">Yes, log out</button></form>
        </div>
    </div>
</div>
@include('layouts.main')

@section('title', 'PNL Checklist')

@section('content')

    {{-- =========================
         PNL TITLE
    ========================= --}}

    {{-- =========================
         PPM RECORD
    ========================= --}}

    @include('components.ppm-record-card', [
        'record' => $ppmRecord
    ])
<div class="page-header">
</div>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PPM Details</title>
<style>
.logout-trigger { position: fixed; top: 24px; right: 28px; z-index: 1100; display: inline-flex; align-items: center; gap: 9px; padding: 11px 18px; border: 0; border-radius: 12px; color: #fff; background: linear-gradient(135deg, #ef5350, #c62828); box-shadow: 0 7px 18px rgba(198,40,40,.3); font: 700 15px Arial,sans-serif; cursor: pointer; transition: transform .2s, box-shadow .2s; }
.logout-trigger:hover { transform: translateY(-3px); box-shadow: 0 11px 24px rgba(198,40,40,.4); }
.logout-trigger svg, .logout-icon svg { width: 20px; height: 20px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
.logout-overlay { position: fixed; inset: 0; z-index: 2000; display: flex; align-items: center; justify-content: center; padding: 20px; background: rgba(18,24,32,.58); opacity: 0; visibility: hidden; transition: opacity .25s, visibility .25s; }
.logout-overlay.is-open { opacity: 1; visibility: visible; }
.logout-dialog { width: min(100%, 390px); padding: 30px; border-radius: 18px; background: #fff; box-shadow: 0 20px 60px rgba(0,0,0,.25); text-align: center; font-family: Arial,sans-serif; transform: translateY(18px) scale(.96); transition: transform .25s; }
.logout-overlay.is-open .logout-dialog { transform: translateY(0) scale(1); }
.logout-icon { display: grid; width: 58px; height: 58px; margin: 0 auto 16px; place-items: center; border-radius: 50%; color: #c62828; background: #ffebee; }
.logout-icon svg { width: 26px; height: 26px; }
.logout-dialog h2 { margin: 0 0 9px; color: #222; font-size: 23px; }
.logout-dialog p { margin: 0 0 24px; color: #666; font-size: 15px; }
.logout-actions { display: flex; justify-content: center; gap: 10px; }
.logout-actions button { padding: 11px 16px; border: 0; border-radius: 8px; font: 700 14px Arial,sans-serif; cursor: pointer; transition: transform .2s, background .2s; }
.logout-actions button:hover { transform: translateY(-2px); }
.logout-cancel { color: #333; background: #eee; }
.logout-confirm { color: #fff; background: #c62828; }
@media (max-width: 600px) { .logout-trigger { top: 14px; right: 14px; padding: 10px 13px; } }
.back-btn-container { display: flex; justify-content: flex-start; margin: 25px 0; } .back-btn { position: relative; display: inline-flex; align-items: center; justify-content: center; gap: 12px; min-width: 180px; padding: 15px 28px; background: linear-gradient(135deg, #ff9800, #f4511e); color: white; font-size: 18px; font-weight: 700; text-decoration: none; border-radius: 13px; box-shadow: 0 8px 20px rgba(255, 111, 0, 0.35); overflow: hidden; transition: all 0.3s ease; } /* Shine effect */ .back-btn::before { content: ""; position: absolute; top: 0; left: -120%; width: 70%; height: 100%; background: rgba(255, 255, 255, 0.25); transform: skewX(-25deg); transition: left 0.6s ease; } .back-btn:hover::before { left: 140%; } /* Hover animation */ .back-btn:hover { transform: translateY(-4px) scale(1.03); background: linear-gradient(135deg, #ffab00, #ff5722); box-shadow: 0 14px 30px rgba(255, 111, 0, 0.5); } /* Arrow animation */ .back-arrow { position: relative; z-index: 1; font-size: 27px; line-height: 1; transition: transform 0.3s ease; } .back-btn:hover .back-arrow { transform: translateX(-6px); } .back-btn span:last-child { position: relative; z-index: 1; } /* Click animation */ .back-btn:active { transform: scale(0.96); }
    .submit-row {
    margin-top: 30px;
    text-align: right;
}

.save-next-btn {
    background: #f28c28;
    color: white;
    border: none;
    padding: 12px 28px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.2s;
}

.save-next-btn:hover {
    background: #d96f0b;
}

.save-next-btn:disabled {
    background: #999;
    cursor: not-allowed;
}
    body {
        font-family: 'Times New Roman', Times, serif;
        color: #111;
        background: #e9e9e9;
        margin: 0;
        padding: 20px;
    }

    .sheet {
        max-width: 850px;
        margin: 0 auto 30px auto;
        background: #fff;
        border: 1px solid #999;
        box-shadow: 0 0 6px rgba(0,0,0,0.2);
        padding: 30px 40px;
    }

    h1.title {
        font-size: 20px;
        margin: 0 0 10px 0;
    }

    .flash-status {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        max-width: min(420px, calc(100vw - 40px));
        padding: 14px 20px;
        color: #155724;
        background: #d4edda;
        border: 1px solid #c3e6cb;
        border-left: 5px solid #28a745;
        border-radius: 6px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, .18);
        font: bold 15px Arial, sans-serif;
        animation: notification-in .25s ease-out;
    }

    @keyframes notification-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .errors {
        color: #b00000;
        border: 1px solid #b00000;
        padding: 10px;
        margin-bottom: 15px;
    }

    .record-info {
        font-size: 14px;
        margin-bottom: 20px;
    }
    .record-info span {
        display: inline-block;
        margin-right: 25px;
    }

    table.checklist {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table.checklist th, table.checklist td {
        border: 1px solid #000;
        padding: 6px 8px;
        font-size: 14px;
        vertical-align: middle;
    }

    table.checklist th {
        background: #f0f0f0;
        text-align: center;
        width: 70px;
    }

    table.checklist td.num {
        width: 35px;
        text-align: center;
    }

    table.checklist td.desc {
        text-align: left;
    }

    table.checklist td.ok, table.checklist td.nok {
        width: 60px;
        text-align: center;
    }

    table.checklist td.comment {
        width: 180px;
        text-align: left;
    }

    table.checklist td.comment input[type=text] {
        width: 100%;
        border: 1px solid #999;
        padding: 4px 6px;
        font-family: inherit;
        font-size: 13px;
        box-sizing: border-box;
    }

    tr.section-header td {
        background: #dcdcdc;
        font-weight: bold;
        text-align: left;
    }

    .notes {
        font-size: 13px;
        margin: 20px 0;
        line-height: 1.5;
    }
    .notes strong {
        display: block;
        margin-bottom: 4px;
    }

    table.dpn-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 25px;
    }

    table.dpn-table th, table.dpn-table td {
        border: 1px solid #000;
        padding: 6px 8px;
        font-size: 14px;
    }

    table.dpn-table th {
        background: #f0f0f0;
    }

    table.dpn-table td.num {
        width: 35px;
        text-align: center;
    }

    table.dpn-table input[type=text] {
        width: 100%;
        border: none;
        font-family: inherit;
        font-size: 14px;
    }

    .maintenance {
        border-top: 2px solid #000;
        padding-top: 15px;
        font-size: 14px;
    }

    .maintenance h2 {
        font-size: 16px;
        margin: 0 0 15px 0;
    }

    .maintenance-row {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        margin-bottom: 12px;
        align-items: center;
    }

    .maintenance-row label {
        font-weight: bold;
        margin-right: 6px;
    }

    .maintenance input[type=text],
    .maintenance input[type=date],
    .maintenance input[type=time],
    .maintenance input[type=number] {
        border: none;
        border-bottom: 1px solid #000;
        font-family: inherit;
        font-size: 14px;
        padding: 2px 4px;
        background: transparent;
    }

    .error-inline {
        color: #b00000;
        font-size: 12px;
        margin-left: 8px;
    }

    .admin-verification-status {
        margin: 16px 0;
        padding: 12px 16px;
        border: 1px solid;
        border-radius: 6px;
        font-weight: bold;
    }

    .admin-verification-status.verified {
        color: #155724;
        background: #d4edda;
        border-color: #c3e6cb;
    }

    .admin-verification-status.pending {
        color: #721c24;
        background: #f8d7da;
        border-color: #f5c6cb;
    }

    .submit-row {
        margin-top: 25px;
        text-align: right;
    }

    .submit-row button {
        font-size: 15px;
        padding: 8px 20px;
    }

    .page-footer {
        text-align: center;
        font-size: 12px;
        color: #555;
        margin-top: 10px;
    }

    /* =====================================================
       AUTO TIME (shift selector) - compact
    ===================================================== */
    .auto-wrap {
        position: relative;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 16px;
        font-family: Arial, sans-serif;
    }

    /* small pill button */
    .auto-pill {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border: 0;
        border-radius: 999px;
        color: #fff;
        background: linear-gradient(135deg, #ff9800, #f4511e);
        box-shadow: 0 4px 12px rgba(244, 81, 30, .3);
        font: 700 13px Arial, sans-serif;
        cursor: pointer;
        overflow: hidden;
        transition: transform .2s, box-shadow .2s;
    }
    .auto-pill::before {
        content: "";
        position: absolute;
        top: 0; left: -120%;
        width: 60%; height: 100%;
        background: rgba(255, 255, 255, .3);
        transform: skewX(-25deg);
        transition: left .6s ease;
    }
    .auto-pill:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(244, 81, 30, .4); }
    .auto-pill:hover:not(:disabled)::before { left: 140%; }
    .auto-pill:active:not(:disabled) { transform: scale(.96); }
    .auto-pill:disabled { opacity: .55; cursor: not-allowed; }
    .auto-pill > * { position: relative; z-index: 1; }
    .auto-bolt { font-size: 15px; display: inline-block; animation: auto-bolt 2s ease-in-out infinite; }
    @keyframes auto-bolt {
        0%, 100% { transform: scale(1) rotate(0); }
        50%      { transform: scale(1.25) rotate(-12deg); }
    }
    .auto-arrow { width: 14px; height: 14px; fill: none; stroke: #fff; stroke-width: 3; stroke-linecap: round; stroke-linejoin: round; transition: transform .3s; }
    .auto-wrap.open .auto-arrow { transform: rotate(180deg); }

    /* popover */
    .auto-pop {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        z-index: 50;
        width: 330px;
        max-width: 100%;
        padding: 14px;
        background: #fff;
        border: 1px solid #ffd9a8;
        border-radius: 14px;
        box-shadow: 0 14px 36px rgba(0, 0, 0, .18);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-8px) scale(.97);
        transform-origin: top left;
        transition: opacity .22s, transform .22s, visibility .22s;
    }
    .auto-wrap.open .auto-pop { opacity: 1; visibility: visible; transform: none; }
    .auto-pop-title { margin: 0 0 10px; font-size: 13px; font-weight: 700; color: #555; }

    .shift-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
    .shift-card {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
        padding: 10px 4px;
        background: #fff;
        border: 2px solid #eee;
        border-radius: 12px;
        cursor: pointer;
        font-family: inherit;
        opacity: 0;
        transform: translateY(8px);
        transition: transform .25s, box-shadow .25s, border-color .25s, background .25s, opacity .3s;
    }
    .auto-wrap.open .shift-card { opacity: 1; transform: none; }
    .auto-wrap.open .shift-card:nth-child(2) { transition-delay: .05s, 0s, 0s, 0s, .05s; }
    .auto-wrap.open .shift-card:nth-child(3) { transition-delay: .1s, 0s, 0s, 0s, .1s; }
    .shift-card:hover { transform: translateY(-3px); border-color: #ffb066; box-shadow: 0 6px 14px rgba(244, 81, 30, .18); transition-delay: 0s; }
    .shift-icon { font-size: 22px; transition: transform .3s; }
    .shift-card:hover .shift-icon { transform: scale(1.2) rotate(-8deg); }
    .shift-name { font-weight: 700; font-size: 12px; color: #333; }
    .shift-hours { font-size: 11px; color: #888; }
    .shift-check {
        position: absolute; top: 4px; right: 5px;
        width: 16px; height: 16px; border-radius: 50%;
        background: #f4511e; color: #fff; font-size: 10px;
        display: grid; place-items: center;
        transform: scale(0);
        transition: transform .25s cubic-bezier(.2, 1.6, .4, 1);
    }
    .shift-card.selected { border-color: #f4511e; background: linear-gradient(135deg, #fff3e6, #ffe3d1); box-shadow: 0 6px 14px rgba(244, 81, 30, .25); }
    .shift-card.selected .shift-check { transform: scale(1); }

    .auto-pop-row { display: flex; align-items: flex-end; justify-content: space-between; gap: 10px; margin-top: 12px; }
    .shift-date { font-size: 11px; font-weight: 700; color: #555; display: flex; flex-direction: column; gap: 3px; }
    .shift-date input { padding: 6px 8px; border: 1px solid #ccc; border-radius: 8px; font-size: 13px; }
    .auto-pop-actions { display: flex; gap: 6px; }
    .auto-btn { padding: 8px 14px; border: 0; border-radius: 8px; font: 700 13px Arial, sans-serif; cursor: pointer; transition: transform .2s, background .2s, opacity .2s; }
    .auto-btn:hover:not(:disabled) { transform: translateY(-2px); }
    .auto-btn:disabled { opacity: .45; cursor: not-allowed; }
    .auto-ok { color: #fff; background: #2e9e4f; }
    .auto-ok:hover:not(:disabled) { background: #238540; }
    .auto-cancel { color: #333; background: #eee; }

    /* show more (all PNL with time) */
    .auto-more {
        display: flex; align-items: center; justify-content: center; gap: 6px;
        width: 100%; margin-top: 12px; padding: 7px 10px;
        border: 1px dashed #ffb066; border-radius: 8px;
        color: #c75a00; background: #fff8ed;
        font: 700 12px Arial, sans-serif; cursor: pointer;
        transition: background .2s, transform .2s, opacity .2s;
    }
    .auto-more:hover:not(:disabled) { background: #ffeed9; transform: translateY(-1px); }
    .auto-more:disabled { opacity: .45; cursor: not-allowed; }
    .auto-more svg { width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 3; stroke-linecap: round; stroke-linejoin: round; transition: transform .3s; }
    .auto-more.open svg { transform: rotate(180deg); }
    .auto-more-box { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .35s ease; }
    .auto-more-box.open { grid-template-rows: 1fr; }
    .auto-more-inner { overflow: hidden; min-height: 0; }
    .auto-more-inner .shift-timeline { max-height: 240px; }

    /* applied chip */
    .auto-chip {
        display: none;
        align-items: center;
        gap: 8px;
        padding: 5px 6px 5px 12px;
        background: #fff3e6;
        border: 1px solid #ffb066;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        color: #8a3b00;
    }
    .auto-chip.show { display: inline-flex; animation: auto-pop-in .35s cubic-bezier(.2, 1.4, .4, 1); }
    @keyframes auto-pop-in { from { opacity: 0; transform: scale(.7); } to { opacity: 1; transform: none; } }
    .auto-chip .chip-ico { font-size: 15px; }
    .auto-chip button { border: 0; cursor: pointer; font: 700 12px Arial, sans-serif; border-radius: 999px; padding: 4px 9px; transition: transform .2s, background .2s; }
    .auto-chip button:hover { transform: scale(1.06); }
    .chip-list { color: #8a3b00; background: #ffe3d1; }
    .chip-list:hover { background: #ffd2b5; }
    .chip-remove { color: #fff; background: #d32f2f; }
    .chip-remove:hover { background: #b71c1c; }

    .shift-loading { display: none; font-size: 12px; color: #c75a00; font-weight: 700; }
    .shift-loading.show { display: inline-block; animation: auto-blink 1s infinite; }
    @keyframes auto-blink { 50% { opacity: .4; } }

    /* timeline (opens under the bar) */
    .auto-timeline-box {
        flex-basis: 100%;
        display: grid;
        grid-template-rows: 0fr;
        transition: grid-template-rows .35s ease;
    }
    .auto-timeline-box.open { grid-template-rows: 1fr; }
    .auto-timeline-inner { overflow: hidden; min-height: 0; }
    .shift-summary { margin: 8px 0 6px; font-size: 12px; font-weight: 700; color: #444; }
    .shift-timeline { display: flex; flex-direction: column; gap: 5px; max-height: 220px; overflow-y: auto; padding-bottom: 4px; }
    .shift-day { margin-top: 6px; font-size: 11px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #c75a00; border-bottom: 1px dashed #ffb066; padding-bottom: 2px; opacity: 0; animation: auto-slide .35s forwards; }
    .shift-row { display: grid; grid-template-columns: 1fr auto auto; gap: 12px; align-items: center; padding: 6px 10px; background: #fff; border: 1px solid #eee; border-left: 4px solid #ddd; border-radius: 8px; font-size: 12px; opacity: 0; animation: auto-slide .35s forwards; }
    .shift-row.current { border-left-color: #f4511e; background: #fff0e6; font-weight: 700; box-shadow: 0 3px 10px rgba(244, 81, 30, .2); }
    .shift-row .t { font-variant-numeric: tabular-nums; color: #333; }
    .shift-row .m { color: #999; font-size: 11px; }
    @keyframes auto-slide { from { opacity: 0; transform: translateX(-14px); } to { opacity: 1; transform: none; } }

    @media (max-width: 600px) {
        body {
            padding: 0;
            background: #f5f6f8;
            font-family: Arial, sans-serif;
        }

        .sheet {
            width: 100%;
            box-sizing: border-box;
            margin: 0;
            padding: 18px 12px 24px;
            border: 0;
            box-shadow: none;
            border-radius: 0;
        }

        h1.title {
            font-size: 22px;
            margin-bottom: 16px;
        }

        .record-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            padding: 12px;
            margin-bottom: 16px;
            background: #f0f2f5;
            border-radius: 8px;
            font-size: 13px;
        }

        .record-info span {
            display: block;
            margin: 0;
            overflow-wrap: anywhere;
        }

        .record-info span:last-child {
            grid-column: 1 / -1;
        }

        table.checklist,
        table.dpn-table {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            white-space: nowrap;
        }

        table.checklist th,
        table.checklist td,
        table.dpn-table th,
        table.dpn-table td {
            padding: 10px 8px;
            font-size: 13px;
        }

        table.checklist td.desc,
        table.checklist td.comment {
            white-space: normal;
            min-width: 145px;
        }

        table.checklist td.comment input[type=text],
        table.dpn-table input[type=text] {
            min-height: 34px;
            font-size: 14px;
        }

        input[type="radio"] {
            width: 20px;
            height: 20px;
        }

        .notes {
            font-size: 13px;
            line-height: 1.55;
            padding: 12px;
            background: #fff8ed;
            border-radius: 8px;
        }

        .maintenance {
            padding-top: 18px;
        }

        .maintenance-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 6px;
            margin-bottom: 18px;
        }

        .maintenance-row label {
            margin: 0;
        }

        .maintenance input[type=text],
        .maintenance input[type=date],
        .maintenance input[type=time],
        .maintenance input[type=number] {
            width: 100%;
            min-height: 40px;
            box-sizing: border-box;
            padding: 7px 4px;
            font-size: 16px;
        }

        .error-inline {
            margin-left: 0;
        }

        .submit-row {
            margin-top: 24px;
        }

        .save-next-btn {
            width: 100%;
            min-height: 48px;
            font-size: 16px;
        }

        .flash-status {
            top: 10px;
            right: 10px;
            left: 10px;
            max-width: none;
            box-sizing: border-box;
        }

        .auto-pop { width: 100%; }
    }
</style>
</head>
<body>
    @if (session('error'))
    <div class="errors">{{ session('error') }}</div>
@endif
<div class="sheet">

    <h1 class="title">PPM Details</h1>

    <div class="record-info">
        <span><strong>PPM ID:</strong> {{ $ppmRecord->ppm_id }}</span>
        <span><strong>Asset ID:</strong> {{ $ppmRecord->asset_id }}</span>
        <span><strong>Job ID:</strong> {{ $ppmRecord->job_id }}</span>
        <span><strong>Week Due:</strong> {{ $ppmRecord->week_due }}</span><br>
        <span><strong>Asset Description:</strong> {{ $ppmRecord->asset_description }}</span>
    </div>

    @if (session('status'))
        <div class="flash-status" role="status" aria-live="polite">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <form method="POST" action="{{ route('ppm-checklists.pnl.store') }}">
        @csrf
        <input type="hidden" name="ppm_records_id" value="{{ $ppmRecord->id }}">

        <fieldset {{ $isLocked ? 'disabled' : '' }}
                  style="border:0;padding:0;margin:0;min-width:0;">

        {{-- =====================================================
             AUTO TIME (shift) - values carried to the next PNL
        ===================================================== --}}
        <input type="hidden" name="shift" id="shiftInput" value="{{ $autoShift ?? '' }}">
        <input type="hidden" name="schedule_date" id="shiftDateInput" value="{{ $autoDate ?? '' }}">

        <div class="auto-wrap" id="autoWrap"
             data-url="{{ route('ppm-checklists.pnl.schedule', $ppmRecord) }}"
             data-auto-shift="{{ $autoShift ?? '' }}"
             data-auto-date="{{ $autoDate ?? '' }}"
             data-has-times="{{ $checklist?->start_time ? 1 : 0 }}">

            {{-- small button --}}
            <button type="button" class="auto-pill" id="autoPill" aria-expanded="false" aria-controls="autoPop">
                <span class="auto-bolt">⚡</span>
                <span>Auto time</span>
                <svg class="auto-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
            </button>

            {{-- applied chip --}}
            <div class="auto-chip" id="autoChip">
                <span class="chip-ico" id="chipIcon">⚡</span>
                <span id="chipText"></span>
                <button type="button" class="chip-list" id="chipList" title="Show schedule">📋</button>
                <button type="button" class="chip-remove" id="chipRemove" title="Remove auto time">✕ Remove</button>
            </div>

            <span class="shift-loading" id="shiftLoading">Calculating…</span>

            {{-- popover with the 3 shifts --}}
            <div class="auto-pop" id="autoPop" role="dialog" aria-label="Choose a shift">
                <p class="auto-pop-title">Choose a shift</p>

                <div class="shift-cards">
                    @foreach (config('ppm.shifts') as $key => $shift)
                        <button type="button" class="shift-card" data-shift="{{ $key }}" data-icon="{{ $shift['icon'] }}">
                            <span class="shift-icon">{{ $shift['icon'] }}</span>
                            <span class="shift-name">{{ $shift['label'] }}</span>
                            <span class="shift-hours">{{ $shift['start'] }} → {{ $shift['end'] }}</span>
                            <span class="shift-check">✓</span>
                        </button>
                    @endforeach
                </div>

                <div class="auto-pop-row">
                    <label class="shift-date">
                        Start date
                        <input type="date" id="shiftDate" value="{{ !empty($autoDate) ? $autoDate : now()->toDateString() }}">
                    </label>
                    <div class="auto-pop-actions">
                        <button type="button" class="auto-btn auto-cancel" id="autoCancel">Cancel</button>
                        <button type="button" class="auto-btn auto-ok" id="autoOk" disabled>OK</button>
                    </div>
                </div>

                {{-- show more: all PNL of the week with their time --}}
                <button type="button" class="auto-more" id="autoMore" disabled aria-expanded="false">
                    <span id="autoMoreLabel">Show more</span>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="auto-more-box" id="moreBox">
                    <div class="auto-more-inner">
                        <div class="shift-summary" id="moreSummary"></div>
                        <div class="shift-timeline" id="moreTimeline"></div>
                    </div>
                </div>
            </div>

            {{-- timeline of all PNL --}}
            <div class="auto-timeline-box" id="timelineBox">
                <div class="auto-timeline-inner">
                    <div class="shift-summary" id="shiftSummary"></div>
                    <div class="shift-timeline" id="shiftTimeline"></div>
                </div>
            </div>
        </div>

        @php
            $sectionHeaders = [
                1  => 'Equipment',
                4  => 'Print area',
                7  => 'Mechanization area',
                11 => 'Cutter Device',
                12 => 'Test',
            ];
        @endphp

        <table class="checklist">
            <thead>
                <tr>
                    <th style="width:35px;">#</th>
                    <th style="text-align:left;">Description</th>
                    <th>OK</th>
                    <th>NOK</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($questions as $index => $question)
                    @if (isset($sectionHeaders[$question->order]))
                        <tr class="section-header">
                            <td colspan="5">{{ $sectionHeaders[$question->order] }}</td>
                        </tr>
                    @endif
                    @php
                        $existing = $answers->get($question->id);
                        $oldAnswer = old("answers.$index");
                        $currentResponse = $oldAnswer['response'] ?? $existing->response ?? 'ok';
                    @endphp
                    <tr>
                        <td class="num">[{{ $question->order }}]</td>
                        <td class="desc">
                            {{ $question->question_text }}
                            <input type="hidden" name="answers[{{ $index }}][checklist_question_id]" value="{{ $question->id }}">
                        </td>
                        <td class="ok">
                            <input type="radio" name="answers[{{ $index }}][response]" value="ok"
                                @checked($currentResponse === 'ok')>
                        </td>
                        <td class="nok">
                            <input type="radio" name="answers[{{ $index }}][response]" value="not_ok"
                                @checked($currentResponse === 'not_ok')>
                        </td>
                        <td class="comment">
                           <input type="text" name="answers[{{ $index }}][comment]"
                                 value="{{ $oldAnswer['comment'] ?? $existing->comment ?? '' }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="notes">
            <strong>Notes:</strong>
            Always refer the Label Printer manual.<br>
            Always place the battery to recycle.<br>
            The maintenance responsible (MR) needs to validate the maintenance and turn on the equipment.<br>
            If some points are marked as NOK, it is necessary to close them during the maintenance and the Reliability responsible needs to validate them.<br>
            After the maintenance, the equipment needs to be identified with the evidence of the maintenance. The maintenance evidence needs to have filled out the equipment ID, maintenance date, expire maintenance date, signature.
            <br><br>
            <strong>Remarks:</strong>
            Every abnormality must be reported to maintenance department:
            Issue which can affect safety, productivity or product quality must be reported immediately to maintenance team.
            Issues which cannot affect safety, productivity or product quality must be reported to Work Instruction for Manufacturing Feedback (Daily Problem Report).
        </div>

        <table class="dpn-table">
            <thead>
                <tr>
                    <th style="width:60px;">Line #</th>
                    <th>SP Consumed [DPN]</th>
                    <th>Observations</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($questions as $index => $question)
                    @php
                        $existing = $answers->get($question->id);
                        $oldAnswer = old("answers.$index");
                    @endphp
                    <tr>
                        <td class="num">[{{ $question->order }}]</td>
                        <td>
                            <input type="text" name="answers[{{ $index }}][dpn]"
                                   value="{{ $oldAnswer['dpn'] ?? $existing->dpn ?? '' }}">
                        </td>
                        <td>
                            <input type="text" name="answers[{{ $index }}][observation]"
                                   value="{{ $oldAnswer['observation'] ?? $existing->observation ?? '' }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="maintenance">
            <h2>Maintenance</h2>

            <div class="maintenance-row">
                <label>Start Time:</label>
                <input type="time" name="start_time" id="start_time_input"
                       value="{{ old('start_time', $checklist?->start_time?->format('H:i')) }}">

                <label>Finish Time:</label>
                <input type="time" name="end_time" id="end_time_input"
                       value="{{ old('end_time', $checklist?->end_time?->format('H:i')) }}">
                <span class="error-inline" id="end_time_error"></span>
            </div>

            <div class="maintenance-row">
                <label>Total Time (Hours/Mins):</label>
                <input type="number" name="total_time_minutes" id="total_time_input" min="0" readonly
                       value="{{ old('total_time_minutes', $checklist->total_time_minutes ?? '') }}">
            </div>

            <div class="maintenance-row">
                <label>Completed By (Name/No.):</label>
                <input type="text" name="completed_by_matricule"
                      value="{{ old('completed_by_matricule', $checklist->completed_by_matricule ?? auth()->user()?->matricule ?? '') }}">
            </div>

            <div class="maintenance-row">
                <label>Verified By (Name/No.):</label>
                <input type="text" name="verified_by_matricule"
                       value="{{ old('verified_by_matricule', $checklist->verified_by_matricule ?? '') }}">
                <label>Date:</label>
                <input type="date" name="verified_at"
                       value="{{ old('verified_at', $checklist?->verified_at?->format('Y-m-d')) }}">
            </div>
            <div class="maintenance-row">
                <label>If quality check required - Verified By (Name/No.):</label>
                <input type="text" name="verified_by_quality_matricule"
                       value="{{ old('verified_by_quality_matricule', $checklist->verified_by_quality_matricule ?? '') }}">

                <label>Date:</label>
                <input type="date" name="verified_quality_at"
                       value="{{ old('verified_quality_at', $checklist?->verified_quality_at?->format('Y-m-d')) }}">
            </div>

            @php
                $adminVerified = strtolower(trim((string) ($checklist->status_admin ?? ''))) === 'verified';
                $qualityVerified = strtolower(trim((string) ($checklist->status_quality ?? ''))) === 'verified';
                $adminVerifier = trim((string) ($checklist->verified_by_matricule ?? ''));
                $qualityVerifier = trim((string) ($checklist->verified_by_quality_matricule ?? ''));
            @endphp
            <div class="admin-verification-status {{ $adminVerified ? 'verified' : 'pending' }}" role="status" aria-live="polite">
                {{ $adminVerified ? 'Admin verification: Verified by ' . ($adminVerifier !== '' ? $adminVerifier : 'N/A') . '.' : 'Admin verification: Not verified yet.' }}
            </div>
            <div class="admin-verification-status {{ $qualityVerified ? 'verified' : 'pending' }}" role="status" aria-live="polite">
                {{ $qualityVerified ? 'Quality status: Verified by ' . ($qualityVerifier !== '' ? $qualityVerifier : 'N/A') . '.' : 'Quality status: Not verified yet.' }}
            </div>
        </div>

              @unless ($isLocked)
            <div class="submit-row">
                <button type="submit" class="save-next-btn">Save & Next</button>
            </div>
        @endunless

        </fieldset>
    </form>
    @if ($isLocked)
    <div class="admin-verification-status verified">
        🔒 Verified by quality — this checklist is read-only.
    </div>

    @if ($editRequest?->status === 'pending')
        <div class="admin-verification-status pending">
            ⏳ Edit request sent. Waiting for admin decision.
        </div>
    @else
        @if ($editRequest?->status === 'rejected')
            <div class="errors">
                ❌ Admin did not accept your edit request.<br>
                <strong>Reason:</strong> {{ $editRequest->admin_note }}
            </div>
        @endif

        {{-- Separate form, NOT inside the main form --}}
        <form method="POST" action="{{ route('ppm-checklists.pnl.edit-request', $ppmRecord) }}" style="margin-bottom:20px;">
            @csrf
            <input type="text" name="request_reason" placeholder="Why do you need to edit? (optional)"
                   style="width:70%;padding:8px;">
            <button type="submit" class="save-next-btn">Request edit</button>
        </form>
    @endif
@elseif ($editRequest?->status === 'approved')
    <div class="admin-verification-status verified">
        ✅ Admin accepted your request. You can edit now.
    </div>
@endif
    <div class="page-footer">DPEO MEN-MEC 00.39-07.005 F1</div>
</div>

{{-- =====================================================
     Total time (start / end) - supports night shift (midnight)
===================================================== --}}
<script>
(function () {
    var startInput = document.getElementById('start_time_input');
    var endInput = document.getElementById('end_time_input');
    var totalInput = document.getElementById('total_time_input');
    var endError = document.getElementById('end_time_error');
    var form = startInput.closest('form');
    var submitButton = form.querySelector('button[type="submit"]');

    function toMinutes(timeStr) {
        var parts = timeStr.split(':');
        return parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
    }

    function updateTotal() {
        if (!startInput.value || !endInput.value) {
            totalInput.value = '';
            endError.textContent = '';
            if (submitButton) submitButton.disabled = false;
            return;
        }

        var startMin = toMinutes(startInput.value);
        var endMin = toMinutes(endInput.value);

        if (endMin < startMin) {
            if (window.__shiftActive) {
                endMin += 1440;               // crossed midnight (night shift)
            } else {
                endError.textContent = 'End time cannot be earlier than start time.';
                totalInput.value = '';
                if (submitButton) submitButton.disabled = true;
                return;
            }
        }

        endError.textContent = '';
        if (submitButton) submitButton.disabled = false;
        totalInput.value = endMin - startMin;
    }

    startInput.addEventListener('change', updateTotal);
    endInput.addEventListener('change', updateTotal);
    endInput.addEventListener('input', updateTotal);

    updateTotal();
})();
</script>

{{-- =====================================================
     Auto time: open -> choose shift -> OK (start) -> Remove
===================================================== --}}
<script>
(function () {
    var wrap = document.getElementById('autoWrap');
    if (!wrap) return;

    var url        = wrap.dataset.url;
    var pill       = document.getElementById('autoPill');
    var pop        = document.getElementById('autoPop');
    var cards      = wrap.querySelectorAll('.shift-card');
    var dateEl     = document.getElementById('shiftDate');
    var okBtn      = document.getElementById('autoOk');
    var cancelBtn  = document.getElementById('autoCancel');
    var chip       = document.getElementById('autoChip');
    var chipIcon   = document.getElementById('chipIcon');
    var chipText   = document.getElementById('chipText');
    var chipList   = document.getElementById('chipList');
    var chipRemove = document.getElementById('chipRemove');
    var loading    = document.getElementById('shiftLoading');
    var summary    = document.getElementById('shiftSummary');
    var timeline   = document.getElementById('shiftTimeline');
    var tlBox      = document.getElementById('timelineBox');
    var shiftIn    = document.getElementById('shiftInput');
    var dateIn     = document.getElementById('shiftDateInput');
    var startEl    = document.getElementById('start_time_input');
    var endEl      = document.getElementById('end_time_input');
    var moreBtn    = document.getElementById('autoMore');
    var moreLabel  = document.getElementById('autoMoreLabel');
    var moreBox    = document.getElementById('moreBox');
    var moreSum    = document.getElementById('moreSummary');
    var moreTl     = document.getElementById('moreTimeline');
    var moreOpen   = false;

    var selected = null;   // chosen in the popover (not applied yet)
    var applied  = null;   // applied shift

    function fire(el) {
        el.dispatchEvent(new Event('input',  { bubbles: true }));
        el.dispatchEvent(new Event('change', { bubbles: true }));
    }

    /* ---------- open / close ---------- */
    function setOpen(open) {
        wrap.classList.toggle('open', open);
        pill.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    pill.addEventListener('click', function (e) {
        e.stopPropagation();
        setOpen(!wrap.classList.contains('open'));
    });

    pop.addEventListener('click', function (e) { e.stopPropagation(); });
    document.addEventListener('click', function () { setOpen(false); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setOpen(false); });

    cancelBtn.addEventListener('click', function () { setOpen(false); });

    /* ---------- choose a shift (highlight only) ---------- */
    function select(key) {
        selected = key;
        cards.forEach(function (c) { c.classList.toggle('selected', c.dataset.shift === key); });
        okBtn.disabled = !key;
        moreBtn.disabled = !key;

        if (!key) {
            moreOpen = false;
            moreBox.classList.remove('open');
            moreBtn.classList.remove('open');
            moreBtn.setAttribute('aria-expanded', 'false');
            moreLabel.textContent = 'Show more';
            moreTl.innerHTML = '';
            moreSum.textContent = '';
        } else if (moreOpen) {
            loadPreview();
        }
    }

    cards.forEach(function (card) {
        card.addEventListener('click', function () { select(card.dataset.shift); });
    });

    /* ---------- build the list of all PNL (used by preview and by chip) ---------- */
    function fillList(tlEl, sumEl, data) {
        tlEl.innerHTML = '';
        sumEl.textContent = data.shift.label + ' (' + data.shift.start + ' → ' + data.shift.end + ') · '
            + data.items.length + ' PNL over ' + data.days + ' day' + (data.days > 1 ? 's' : '');

        var lastDay = 0, delay = 0, currentRow = null, currentItem = null;

        data.items.forEach(function (it) {
            if (it.day !== lastDay) {
                lastDay = it.day;
                var h = document.createElement('div');
                h.className = 'shift-day';
                h.style.animationDelay = delay + 'ms';
                h.textContent = 'Day ' + it.day + ' · ' + it.date;
                tlEl.appendChild(h);
                delay += 40;
            }

            var row = document.createElement('div');
            row.className = 'shift-row' + (it.current ? ' current' : '');
            row.style.animationDelay = delay + 'ms';
            row.innerHTML = '<span></span><span class="t"></span><span class="m"></span>';
            row.children[0].textContent = it.asset_id + (it.current ? '  ← this PNL' : '');
            row.children[1].textContent = it.start + ' → ' + it.end;
            row.children[2].textContent = it.minutes + ' min';
            tlEl.appendChild(row);
            delay += 40;

            if (it.current) { currentRow = row; currentItem = it; }
        });

        if (currentRow) {
            setTimeout(function () {
                tlEl.scrollTo({ top: Math.max(0, currentRow.offsetTop - 40), behavior: 'smooth' });
            }, delay + 100);
        }

        return currentItem;
    }

    /* ---------- show more: preview of the selected shift (does not change the times) ---------- */
    function loadPreview() {
        if (!selected) return;

        moreSum.textContent = 'Calculating…';
        moreTl.innerHTML = '';

        var q = new URLSearchParams({ shift: selected, date: dateEl.value });

        fetch(url + '?' + q.toString(), { headers: { 'Accept': 'application/json' } })
            .then(function (r) { if (!r.ok) throw new Error(); return r.json(); })
            .then(function (data) { fillList(moreTl, moreSum, data); })
            .catch(function () { moreSum.textContent = 'Could not calculate the schedule.'; });
    }

    moreBtn.addEventListener('click', function () {
        moreOpen = !moreOpen;
        moreBox.classList.toggle('open', moreOpen);
        moreBtn.classList.toggle('open', moreOpen);
        moreBtn.setAttribute('aria-expanded', moreOpen ? 'true' : 'false');
        moreLabel.textContent = moreOpen ? 'Show less' : 'Show more';

        if (moreOpen) loadPreview();
    });

    dateEl.addEventListener('change', function () {
        if (moreOpen) loadPreview();
    });

    /* ---------- render after OK (fills the times) ---------- */
    function render(data, key, keepTimes) {
        var card = wrap.querySelector('.shift-card[data-shift="' + key + '"]');
        var icon = card ? card.dataset.icon : '⚡';

        var currentItem = fillList(timeline, summary, data);

        if (currentItem) {
            window.__shiftActive = true;

            if (!keepTimes) {
                startEl.value = currentItem.start;   // shift start (or previous PNL end)
                endEl.value   = currentItem.end;     // start + est_resource_time
                fire(startEl);
                fire(endEl);
            }

            chipText.textContent = data.shift.label + ' · Day ' + currentItem.day + ' · '
                + currentItem.start + ' → ' + currentItem.end;
        } else {
            chipText.textContent = data.shift.label;
        }

        chipIcon.textContent = icon;
        chip.classList.add('show');
        pill.querySelector('.auto-bolt').textContent = icon;
    }

    /* ---------- OK = start the option ---------- */
    function apply(key, keepTimes) {
        if (!key) return;

        applied = key;
        shiftIn.value = key;                 // saved with the form -> carried to the next PNL
        dateIn.value  = dateEl.value;
        loading.classList.add('show');
        okBtn.disabled = true;

        var q = new URLSearchParams({ shift: key, date: dateEl.value });

        fetch(url + '?' + q.toString(), { headers: { 'Accept': 'application/json' } })
            .then(function (r) { if (!r.ok) throw new Error(); return r.json(); })
            .then(function (data) { render(data, key, keepTimes); })
            .catch(function () {
                chipText.textContent = 'Could not calculate the schedule.';
                chip.classList.add('show');
            })
            .finally(function () {
                loading.classList.remove('show');
                okBtn.disabled = !selected;
            });
    }

    okBtn.addEventListener('click', function () {
        setOpen(false);
        apply(selected, false);
    });

    /* ---------- show / hide the list of all PNL ---------- */
    chipList.addEventListener('click', function () {
        tlBox.classList.toggle('open');
    });

    /* ---------- Remove = remove the option ---------- */
    chipRemove.addEventListener('click', function () {
        applied = null;
        window.__shiftActive = false;

        shiftIn.value = '';
        dateIn.value  = '';

        startEl.value = '';
        endEl.value   = '';
        fire(startEl);
        fire(endEl);

        select(null);
        timeline.innerHTML = '';
        summary.textContent = '';
        tlBox.classList.remove('open');
        chip.classList.remove('show');
        pill.querySelector('.auto-bolt').textContent = '⚡';
    });

    /* ---------- arrived from "Save & Next": same shift automatically ---------- */
    var autoShift = wrap.dataset.autoShift;

    if (autoShift) {
        if (wrap.dataset.autoDate) dateEl.value = wrap.dataset.autoDate;
        select(autoShift);
        // if times were already saved for this PNL, do not overwrite them
        apply(autoShift, wrap.dataset.hasTimes === '1');
    }
})();
</script>

<script>
(function () {
    var notification = document.querySelector('.flash-status');
    if (!notification) return;

    window.setTimeout(function () {
        notification.style.transition = 'opacity .3s ease';
        notification.style.opacity = '0';
        window.setTimeout(function () { notification.remove(); }, 300);
    }, 4000);
})();
</script>

</body>
</html>