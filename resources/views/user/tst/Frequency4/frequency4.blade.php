{{-- resources/views/user/tst/Frequency4/frequency4.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TST Frequency 4 Checklist</title>
<style>
:root{
    --brand:#f4511e; --brand-2:#ff9800; --ok:#2e9e5b; --ok-bg:#e6f6ed;
    --nok:#d32f2f; --nok-bg:#fdeaea; --ink:#1f2933; --muted:#6b7785;
    --line:#d9dee5; --bg:#e9ecf1; --card:#fff; --radius:16px;
    --shadow:0 6px 22px rgba(20,30,50,.10);
}
*{box-sizing:border-box}
body{margin:0;font-family:'Times New Roman',Times,serif;color:#111;background:var(--bg);padding:0 16px 90px}
.ui,.topbar,.progress-box,.panel,.status-pill,.save-btn,.flash,.alert-error{font-family:'Segoe UI',Arial,sans-serif}
svg.i{width:1.1em;height:1.1em;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;vertical-align:-.15em;flex:none}

/* ---------- top bar ---------- */
.topbar{max-width:none;margin:0;padding:22px 0 8px;display:flex;justify-content:flex-start;align-items:center;gap:10px}
.back-btn{display:inline-flex;align-items:center;gap:10px;padding:11px 20px;border-radius:12px;color:#fff;text-decoration:none;font-weight:700;
    background:linear-gradient(135deg,var(--brand-2),var(--brand));box-shadow:0 8px 18px rgba(244,81,30,.3);position:relative;overflow:hidden;transition:.3s}
.back-btn::before{content:"";position:absolute;top:0;left:-120%;width:70%;height:100%;background:rgba(255,255,255,.28);transform:skewX(-25deg);transition:left .6s}
.back-btn:hover::before{left:140%}.back-btn:hover{transform:translateY(-3px)}
.back-btn svg{transition:transform .3s}.back-btn:hover svg{transform:translateX(-4px)}
.logout-trigger{display:inline-flex;align-items:center;gap:8px;padding:11px 18px;border:0;border-radius:12px;color:#fff;font:700 15px 'Segoe UI',Arial;cursor:pointer;
    background:linear-gradient(135deg,#ef5350,#c62828);box-shadow:0 7px 18px rgba(198,40,40,.3);transition:.2s}
.logout-trigger:hover{transform:translateY(-3px)}
.logout-overlay{position:fixed;inset:0;z-index:2000;display:flex;align-items:center;justify-content:center;padding:20px;background:rgba(18,24,32,.58);opacity:0;visibility:hidden;transition:.25s}
.logout-overlay.is-open{opacity:1;visibility:visible}
.logout-dialog{width:min(100%,390px);padding:30px;border-radius:18px;background:#fff;text-align:center;font-family:Arial,sans-serif;transform:translateY(18px) scale(.96);transition:.25s}
.logout-overlay.is-open .logout-dialog{transform:none}
.logout-icon{display:grid;place-items:center;width:58px;height:58px;margin:0 auto 14px;border-radius:50%;color:#c62828;background:#ffebee;font-size:24px}
.logout-dialog h2{margin:0 0 8px}.logout-dialog p{margin:0 0 22px;color:var(--muted)}
.logout-actions{display:flex;justify-content:center;gap:10px}
.logout-actions button{padding:11px 16px;border:0;border-radius:8px;font:700 14px Arial;cursor:pointer}
.logout-cancel{background:#eee}.logout-confirm{background:#c62828;color:#fff}

/* ---------- sheet (like the original PNL page) ---------- */
.sheet{max-width:900px;margin:10px auto 30px;background:#fff;border:1px solid #b9bfc7;border-radius:6px;box-shadow:var(--shadow);padding:30px 40px;animation:rise .6s both;position:relative;overflow:hidden}
.sheet::before{content:"";position:absolute;left:0;top:0;right:0;height:5px;background:linear-gradient(90deg,var(--brand-2),var(--brand))}
h1.title{font-size:22px;margin:0 0 14px;display:flex;align-items:center;gap:10px}
h1.title svg{color:var(--brand)}
.record-info{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px;font-size:14px;margin-bottom:20px}
.record-info span{background:#f6f7f9;border-left:4px solid var(--brand-2);border-radius:6px;padding:8px 12px;transition:.2s}
.record-info span:hover{background:#fff3e6;transform:translateX(3px)}
.record-info span.wide{grid-column:1/-1}
.record-info strong{display:flex;align-items:center;gap:6px;font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.4px;font-family:'Segoe UI',Arial,sans-serif}

/* ---------- progress ---------- */
.progress-box{position:sticky;top:0;z-index:50;margin:0 0 18px;padding:10px 14px;border-radius:14px;background:rgba(255,255,255,.94);
    backdrop-filter:blur(8px);box-shadow:var(--shadow);display:flex;align-items:center;gap:14px;flex-wrap:wrap;border:1px solid #eceff3}
.count-pill{display:inline-flex;align-items:center;gap:8px;padding:7px 16px;border-radius:99px;font-weight:800;font-size:14px;white-space:nowrap;
    color:#a85a00;background:#fff3e0;border:2px solid #ffd9a0;transition:.4s}
.count-pill .num{font-size:17px;font-variant-numeric:tabular-nums}
.count-pill .tick{width:0;overflow:hidden;transition:width .35s}
.count-pill.complete{color:#fff;background:linear-gradient(135deg,#43c37a,#1f9a55);border-color:#1f9a55;box-shadow:0 0 0 0 rgba(46,158,91,.5);animation:glow 2s infinite}
.count-pill.complete .tick{width:1.2em}
.progress-bar{flex:1;min-width:120px;height:10px;border-radius:99px;background:#e8ebf0;overflow:hidden}
.progress-bar span{display:block;height:100%;width:0;border-radius:99px;background:linear-gradient(90deg,var(--brand-2),var(--brand));transition:width .5s cubic-bezier(.22,1,.36,1),background .4s}
.progress-bar.complete span{background:linear-gradient(90deg,#43c37a,#1f9a55)}
.chip{display:inline-flex;align-items:center;gap:6px;padding:7px 13px;border:0;border-radius:99px;font:700 13px 'Segoe UI',Arial;cursor:pointer;transition:.2s}
.chip.ok{background:var(--ok-bg);color:var(--ok)}.chip.ok:hover{background:var(--ok);color:#fff;transform:translateY(-2px)}
.nok-count{background:var(--nok-bg);color:var(--nok);cursor:default;transition:.3s}
.nok-count.has{animation:pop .4s}

/* ---------- checklist table ---------- */
table.checklist,table.dpn-table{width:100%;border-collapse:separate;border-spacing:0;margin-bottom:22px;border:1px solid #222;border-radius:10px;overflow:hidden}
table th,table td{border-bottom:1px solid #cfd4db;border-right:1px solid #cfd4db;padding:9px 10px;font-size:14px;vertical-align:middle}
table th:last-child,table td:last-child{border-right:0}
table tbody tr:last-child td{border-bottom:0}
table.checklist th,table.dpn-table th{background:linear-gradient(#f7f8fa,#e9ecf0);text-align:center;font-family:'Segoe UI',Arial,sans-serif;font-size:13px;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #222}
table td.num{width:48px;text-align:center;font-weight:700;color:var(--brand)}
table td.desc{text-align:left}
table td.ok,table td.nok{width:64px;text-align:center}
table td.comment{width:210px}
tr.q-row{animation:rise .45s both;transition:background .25s}
tr.q-row:hover{background:#fff8ee}
tr.q-row.is-nok{background:#fff1f1}
tr.q-row.is-nok td.desc{box-shadow:inset 4px 0 0 var(--nok)}
tr.q-row.is-ok td.desc{box-shadow:inset 4px 0 0 var(--ok)}

/* custom radios */
.rd{position:relative;display:inline-block;width:28px;height:28px;cursor:pointer}
.rd input{position:absolute;inset:0;opacity:0;cursor:pointer;margin:0;width:100%;height:100%}
.rd i{position:absolute;inset:0;border:2px solid #b4bcc6;border-radius:50%;background:#fff;display:grid;place-items:center;transition:.2s;color:transparent}
.rd i svg{width:15px;height:15px;stroke-width:3}
.rd:hover i{transform:scale(1.12);border-color:#7d8794}
.rd.r-ok input:checked + i{background:var(--ok);border-color:var(--ok);color:#fff;box-shadow:0 4px 10px rgba(46,158,91,.4);animation:pop .35s}
.rd.r-nok input:checked + i{background:var(--nok);border-color:var(--nok);color:#fff;box-shadow:0 4px 10px rgba(211,47,47,.4);animation:pop .35s}
.rd input:focus-visible + i{outline:3px solid #90caf9}

table input[type=text]{width:100%;border:1.5px solid #cfd4db;border-radius:8px;padding:7px 9px;font:13px 'Segoe UI',Arial;background:#fafbfc;transition:.2s}
table input[type=text]:focus{outline:0;border-color:var(--brand-2);background:#fff;box-shadow:0 0 0 3px rgba(255,152,0,.16)}
tr.is-nok td.comment input{border-color:#f0a3a3;background:#fff}

.notes{font-size:13px;line-height:1.6;margin:22px 0;padding:14px 16px;background:#fff8ed;border-left:5px solid var(--brand-2);border-radius:0 10px 10px 0}
.notes strong{display:flex;align-items:center;gap:6px;margin-bottom:4px;font-family:'Segoe UI',Arial,sans-serif}

/* ---------- maintenance (unchanged design) ---------- */
.panel{background:var(--card);border-radius:var(--radius);padding:22px;margin-top:22px;box-shadow:var(--shadow);border:1px solid #eceff3;animation:rise .6s both}
.panel h2{margin:0 0 16px;font-size:18px;display:flex;align-items:center;gap:10px}
.panel h2 svg{color:var(--brand)}
.grid-2{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px}
.lbl{display:block;margin-bottom:6px;font-size:13px;font-weight:700;color:var(--muted)}
.field{position:relative}
.field svg{position:absolute;left:12px;top:13px;color:var(--muted)}
.panel .field input{width:100%;padding:11px 12px 11px 38px;border:1.5px solid var(--line);border-radius:10px;font:inherit;background:#fafbfc;transition:.2s}
.panel .field input:focus{outline:0;border-color:var(--brand-2);background:#fff;box-shadow:0 0 0 4px rgba(255,152,0,.16)}
.panel .field input[readonly]{background:#eef1f5;font-weight:800}
.err-inline{color:var(--nok);font-size:12px;margin-top:4px;min-height:14px}
.status-pill{display:flex;align-items:center;gap:10px;margin-top:12px;padding:12px 16px;border-radius:12px;font-weight:700}
.status-pill.verified{background:var(--ok-bg);color:#17683b}
.status-pill.pending{background:var(--nok-bg);color:#8f1d1d}

/* ---------- alerts / submit ---------- */
.alert-error{margin:14px 0;padding:12px 16px;border-radius:12px;background:var(--nok-bg);color:#8f1d1d;border-left:5px solid var(--nok);animation:shake .4s}
.alert-error ul{margin:0;padding-left:18px}
.flash{position:fixed;top:20px;right:20px;z-index:3000;max-width:min(420px,calc(100vw - 40px));padding:14px 20px;border-radius:12px;color:#155724;background:#d4edda;
    border-left:5px solid #28a745;box-shadow:0 8px 24px rgba(0,0,0,.18);font-weight:700;display:flex;gap:10px;animation:slide .35s both}
.edit-form{display:flex;gap:10px;flex-wrap:wrap;margin-top:12px}
.edit-form input{flex:1;min-width:200px;padding:11px;border:1.5px solid var(--line);border-radius:10px;font:inherit}
.submit-bar{position:fixed;left:0;right:0;bottom:0;z-index:60;padding:12px 16px;background:rgba(255,255,255,.94);backdrop-filter:blur(8px);box-shadow:0 -6px 20px rgba(0,0,0,.08)}
.submit-bar .in{max-width:900px;margin:0 auto;display:flex;justify-content:flex-end;align-items:center;gap:12px}
.save-btn{display:inline-flex;align-items:center;gap:10px;padding:14px 32px;border:0;border-radius:12px;color:#fff;font:800 16px 'Segoe UI',Arial;cursor:pointer;
    background:linear-gradient(135deg,var(--brand-2),var(--brand));box-shadow:0 8px 20px rgba(244,81,30,.35);transition:.25s}
.save-btn:hover{transform:translateY(-3px);box-shadow:0 12px 26px rgba(244,81,30,.45)}
.save-btn svg{transition:transform .25s}.save-btn:hover svg{transform:translateX(5px)}
.save-btn:active{transform:scale(.96)}
.save-btn:disabled{background:#9aa3ad;box-shadow:none;cursor:not-allowed;transform:none}
.save-btn.loading svg{animation:spin 1s linear infinite}
.scroll-toggle{position:fixed;right:20px;bottom:90px;z-index:70;display:none;align-items:center;gap:8px;padding:11px 16px;border:0;border-radius:999px;color:#fff;background:linear-gradient(135deg,var(--brand-2),var(--brand));box-shadow:0 6px 18px rgba(244,81,30,.35);font:700 14px 'Segoe UI',Arial;cursor:pointer;transition:.2s}
.scroll-toggle.is-visible{display:inline-flex}
.scroll-toggle:hover{transform:translateY(-2px)}
.page-footer{text-align:center;font-size:12px;color:#555;margin-top:18px}

@keyframes rise{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
@keyframes pop{50%{transform:scale(1.15)}}
@keyframes glow{70%{box-shadow:0 0 0 12px rgba(46,158,91,0)}100%{box-shadow:0 0 0 0 rgba(46,158,91,0)}}
@keyframes slide{from{opacity:0;transform:translateX(30px)}to{opacity:1;transform:none}}
@keyframes shake{25%{transform:translateX(-6px)}75%{transform:translateX(6px)}}
@keyframes spin{to{transform:rotate(360deg)}}
@media (prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}
@media (max-width:600px){
    body{padding:0 0 90px;background:#f5f6f8}
    .topbar{padding:14px 12px 4px}.logout-trigger span{display:none}
    .sheet{border:0;border-radius:0;padding:18px 12px 24px}
    table.checklist,table.dpn-table{display:block;overflow-x:auto;white-space:nowrap;-webkit-overflow-scrolling:touch}
    table td.desc,table td.comment{white-space:normal;min-width:150px}
    .save-btn{width:100%;justify-content:center}
    .flash{left:10px;right:10px;max-width:none}
}
</style>
</head>
<body>

@php $icon = fn($d) => '<svg class="i" viewBox="0 0 24 24" aria-hidden="true">'.$d.'</svg>'; @endphp

<div class="topbar">
    <a href="{{ route('user.index') }}" class="back-btn">{!! $icon('<path d="M19 12H5M12 19l-7-7 7-7"/>') !!} <span>Back</span></a>
</div>
<button type="button" class="scroll-toggle" id="scrollToggle" aria-label="Scroll to bottom">↓ Go to bottom</button>

@include('layouts.main')

@section('title', 'TST Checklist')

@section('content')

    @include('components.ppm-record-card', [
        'record' => $ppmRecord
    ])

@if (session('status'))
    <div class="flash" role="status">{!! $icon('<path d="M20 6L9 17l-5-5"/>') !!} {{ session('status') }}</div>
@endif

<div class="sheet">

    <h1 class="title">{!! $icon('<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>') !!} PPM Details — TST Frequency 4</h1>

    <div class="record-info">
        <span><strong>{!! $icon('<path d="M4 4h16v16H4z"/><path d="M9 9h6v6H9z"/>') !!} PPM ID</strong>{{ $ppmRecord->ppm_id }}</span>
        <span><strong>{!! $icon('<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>') !!} Asset ID</strong>{{ $ppmRecord->asset_id }}</span>
        <span><strong>{!! $icon('<path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-2.5 2.5-2.5-.5-.5-2.5z"/>') !!} Job ID</strong>{{ $ppmRecord->job_id }}</span>
        <span><strong>{!! $icon('<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>') !!} Week Due</strong>{{ $ppmRecord->week_due }}</span>
        <span class="wide"><strong>{!! $icon('<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>') !!} Asset Description</strong>{{ $ppmRecord->asset_description }}</span>
    </div>

    @if (session('error'))<div class="alert-error">{{ session('error') }}</div>@endif
    @if ($errors->any())
        <div class="alert-error"><ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('ppm-checklists.tst4.store') }}" id="tstForm">
        @csrf
        <input type="hidden" name="ppm_records_id" value="{{ $ppmRecord->id }}">

        <fieldset {{ $isLocked ? 'disabled' : '' }} style="border:0;padding:0;margin:0;min-width:0;">

            {{-- progress / 20/20 counter --}}
            <div class="progress-box">
                <div class="count-pill" id="countPill">
                    <span class="tick">{!! $icon('<path d="M20 6L9 17l-5-5" style="stroke-width:3"/>') !!}</span>
                    <span class="num"><span id="doneCount">0</span>/{{ $questions->count() }}</span>
                    <span id="countLabel">checked</span>
                </div>
                <div class="progress-bar" id="progressBar"><span id="progressFill"></span></div>
                <span class="chip nok-count" id="nokChip">{!! $icon('<path d="M18 6L6 18M6 6l12 12"/>') !!} NOK: <b id="nokCount">0</b></span>
                @unless ($isLocked)
                    <button type="button" class="chip ok" id="allOkBtn">{!! $icon('<path d="M20 6L9 17l-5-5"/>') !!} All OK</button>
                @endunless
            </div>

            <table class="checklist">
                <thead>
                    <tr>
                        <th style="width:48px">#</th>
                        <th style="text-align:left">Description</th>
                        <th>OK</th>
                        <th>NOK</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($questions as $index => $question)
                    @php
                        $existing = $answers->get($question->id);
                        $old = old("answers.$index");
                        $resp = $old['response'] ?? $existing->response ?? 'ok';
                    @endphp
                    <tr class="q-row {{ $resp === 'not_ok' ? 'is-nok' : 'is-ok' }}" style="animation-delay:{{ min($index * 40, 500) }}ms">
                        <td class="num">[{{ $question->order }}]</td>
                        <td class="desc">
                            {{ $question->question_text }}
                            <input type="hidden" name="answers[{{ $index }}][checklist_question_id]" value="{{ $question->id }}">
                        </td>
                        <td class="ok">
                            <label class="rd r-ok" title="OK">
                                <input type="radio" name="answers[{{ $index }}][response]" value="ok" @checked($resp === 'ok')>
                                <i>{!! $icon('<path d="M20 6L9 17l-5-5"/>') !!}</i>
                            </label>
                        </td>
                        <td class="nok">
                            <label class="rd r-nok" title="Not OK">
                                <input type="radio" name="answers[{{ $index }}][response]" value="not_ok" @checked($resp === 'not_ok')>
                                <i>{!! $icon('<path d="M18 6L6 18M6 6l12 12"/>') !!}</i>
                            </label>
                        </td>
                        <td class="comment">
                            <input type="text" name="answers[{{ $index }}][comment]" placeholder="Comment…"
                                   value="{{ $old['comment'] ?? $existing->comment ?? '' }}">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="notes">
                <strong>{!! $icon('<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/>') !!} Notes:</strong>
                The maintenance responsible (MR) needs to validate the maintenance and turn on the equipment.<br>
                If some points are marked as NOK, it is necessary to close them during the maintenance and the Reliability responsible needs to validate them.<br>
                After the maintenance, the equipment needs to be identified with the evidence of the maintenance (equipment ID, maintenance date, expire maintenance date, signature).
                <br><br>
                <strong>{!! $icon('<path d="M10.3 3.9L1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/>') !!} Remarks:</strong>
                Every abnormality must be reported to maintenance department. Issues which can affect safety, productivity or product quality must be reported immediately to maintenance team. Other issues must be reported to Work Instruction for Manufacturing Feedback (Daily Problem Report).
            </div>

            <table class="dpn-table">
                <thead>
                    <tr><th style="width:60px">Line #</th><th>SP Consumed [DPN]</th><th>Observations</th></tr>
                </thead>
                <tbody>
                @foreach ($questions as $index => $question)
                    @php
                        $existing = $answers->get($question->id);
                        $old = old("answers.$index");
                    @endphp
                    <tr>
                        <td class="num">[{{ $question->order }}]</td>
                        <td><input type="text" name="answers[{{ $index }}][dpn]" value="{{ $old['dpn'] ?? $existing->dpn ?? '' }}"></td>
                        <td><input type="text" name="answers[{{ $index }}][observation]" value="{{ $old['observation'] ?? $existing->observation ?? '' }}"></td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{-- ============ MAINTENANCE (unchanged) ============ --}}
            <section class="panel">
                <h2>{!! $icon('<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>') !!} Maintenance</h2>

                <div class="grid-2">
                    <div>
                        <label class="lbl">Start time</label>
                        <div class="field">{!! $icon('<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>') !!}
                            <input type="time" name="start_time" id="start_time_input" value="{{ old('start_time', $checklist?->start_time?->format('H:i')) }}">
                        </div>
                    </div>
                    <div>
                        <label class="lbl">Finish time</label>
                        <div class="field">{!! $icon('<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>') !!}
                            <input type="time" name="end_time" id="end_time_input" value="{{ old('end_time', $checklist?->end_time?->format('H:i')) }}">
                        </div>
                        <div class="err-inline" id="end_time_error"></div>
                    </div>
                    <div>
                        <label class="lbl">Total time (minutes)</label>
                        <div class="field">{!! $icon('<path d="M5 22h14M5 2h14M17 22v-4.2a2 2 0 0 0-.6-1.4L12 12l-4.4 4.4a2 2 0 0 0-.6 1.4V22M7 2v4.2a2 2 0 0 0 .6 1.4L12 12l4.4-4.4A2 2 0 0 0 17 6.2V2"/>') !!}
                            <input type="number" name="total_time_minutes" id="total_time_input" min="0" readonly value="{{ old('total_time_minutes', $checklist->total_time_minutes ?? '') }}">
                        </div>
                    </div>
                    <div>
                        <label class="lbl">Completed by (matricule)</label>
                        <div class="field">{!! $icon('<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>') !!}
                            <input type="text" name="completed_by_matricule" value="{{ old('completed_by_matricule', $checklist->completed_by_matricule ?? auth()->user()?->matricule ?? '') }}">
                        </div>
                    </div>
                    <div>
                        <label class="lbl">Verified by (matricule)</label>
                        <div class="field">{!! $icon('<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/>') !!}
                            <input type="text" name="verified_by_matricule" value="{{ old('verified_by_matricule', $checklist->verified_by_matricule ?? '') }}">
                        </div>
                    </div>
                    <div>
                        <label class="lbl">Verification date</label>
                        <div class="field">{!! $icon('<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>') !!}
                            <input type="date" name="verified_at" value="{{ old('verified_at', $checklist?->verified_at?->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div>
                        <label class="lbl">Quality check – verified by</label>
                        <div class="field">{!! $icon('<path d="M12 15l-3.5 2 1-4L6 10l4-.5L12 6l2 3.5 4 .5-3.5 3 1 4z"/>') !!}
                            <input type="text" name="verified_by_quality_matricule" value="{{ old('verified_by_quality_matricule', $checklist->verified_by_quality_matricule ?? '') }}">
                        </div>
                    </div>
                    <div>
                        <label class="lbl">Quality date</label>
                        <div class="field">{!! $icon('<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>') !!}
                            <input type="date" name="verified_quality_at" value="{{ old('verified_quality_at', $checklist?->verified_quality_at?->format('Y-m-d')) }}">
                        </div>
                    </div>
                </div>

                @php
                    $adminVerified   = strtolower(trim((string) ($checklist->status_admin ?? ''))) === 'verified';
                    $qualityVerified = strtolower(trim((string) ($checklist->status_quality ?? ''))) === 'verified';
                    $adminBy   = trim((string) ($checklist->verified_by_matricule ?? '')) ?: 'N/A';
                    $qualityBy = trim((string) ($checklist->verified_by_quality_matricule ?? '')) ?: 'N/A';
                @endphp
                <div class="status-pill {{ $adminVerified ? 'verified' : 'pending' }}">
                    {!! $icon($adminVerified ? '<path d="M20 6L9 17l-5-5"/>' : '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>') !!}
                    {{ $adminVerified ? "Admin verification: Verified by $adminBy." : 'Admin verification: Not verified yet.' }}
                </div>
                <div class="status-pill {{ $qualityVerified ? 'verified' : 'pending' }}">
                    {!! $icon($qualityVerified ? '<path d="M20 6L9 17l-5-5"/>' : '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>') !!}
                    {{ $qualityVerified ? "Quality status: Verified by $qualityBy." : 'Quality status: Not verified yet.' }}
                </div>
            </section>

            @unless ($isLocked)
                <div class="submit-bar"><div class="in">
                    <button type="submit" class="save-btn" id="saveBtn">Save &amp; Next {!! $icon('<path d="M5 12h14M12 5l7 7-7 7"/>') !!}</button>
                </div></div>
            @endunless
        </fieldset>
    </form>

    @if ($isLocked)
        <div class="status-pill verified">{!! $icon('<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>') !!} Verified by quality — this checklist is read-only.</div>
        @if ($editRequest?->status === 'pending')
            <div class="status-pill pending">{!! $icon('<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>') !!} Edit request sent. Waiting for admin decision.</div>
        @else
            @if ($editRequest?->status === 'rejected')
                <div class="alert-error"><b>Admin did not accept your edit request.</b><br>Reason: {{ $editRequest->admin_note }}</div>
            @endif
            <form method="POST" action="{{ route('ppm-checklists.tst4.edit-request', $ppmRecord) }}" class="edit-form">
                @csrf
                <input type="text" name="request_reason" placeholder="Why do you need to edit? (optional)">
                <button type="submit" class="save-btn" style="padding:11px 22px;font-size:15px">Request edit</button>
            </form>
        @endif
    @elseif ($editRequest?->status === 'approved')
        <div class="status-pill verified">{!! $icon('<path d="M20 6L9 17l-5-5"/>') !!} Admin accepted your request. You can edit now.</div>
    @endif

    <div class="page-footer">DPEO MEN-MEC 00.39-07.005 F1</div>
</div>

<script>
(function () {
    var rows = document.querySelectorAll('tr.q-row'), total = rows.length;
    var pill = document.getElementById('countPill'), bar = document.getElementById('progressBar'),
        fill = document.getElementById('progressFill'), doneEl = document.getElementById('doneCount'),
        nokEl = document.getElementById('nokCount'), nokChip = document.getElementById('nokChip'),
        label = document.getElementById('countLabel');

    function refresh() {
        var done = 0, nok = 0;
        rows.forEach(function (r) {
            var c = r.querySelector('input[type=radio]:checked');
            r.classList.toggle('is-ok', !!c && c.value === 'ok');
            r.classList.toggle('is-nok', !!c && c.value === 'not_ok');
            if (c) { done++; if (c.value === 'not_ok') nok++; }
        });
        doneEl.textContent = done; nokEl.textContent = nok;
        fill.style.width = (total ? done / total * 100 : 0) + '%';
        var full = total > 0 && done === total;
        pill.classList.toggle('complete', full);
        bar.classList.toggle('complete', full);
        label.textContent = full ? 'checked ✓ all done' : 'checked';
        nokChip.classList.toggle('has', nok > 0);
        if (nok > 0) { nokChip.classList.remove('has'); void nokChip.offsetWidth; nokChip.classList.add('has'); }
    }

    rows.forEach(function (r) {
        r.querySelectorAll('input[type=radio]').forEach(function (i) {
            i.addEventListener('change', function () {
                refresh();
                if (i.value === 'not_ok') { var t = r.querySelector('td.comment input'); if (t) t.focus(); }
            });
        });
    });
    var allOk = document.getElementById('allOkBtn');
    if (allOk) allOk.addEventListener('click', function () {
        rows.forEach(function (r) { r.querySelector('input[value=ok]').checked = true; });
        refresh();
    });
    refresh();

    var scrollToggle = document.getElementById('scrollToggle');
    function isBottom() { return window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 40; }
    function updateScrollToggle() {
        var b = isBottom();
        scrollToggle.classList.toggle('is-visible', window.scrollY > 80);
        scrollToggle.textContent = b ? '↑ Back to top' : '↓ Go to bottom';
        scrollToggle.setAttribute('aria-label', b ? 'Scroll to top' : 'Scroll to bottom');
    }
    window.addEventListener('scroll', updateScrollToggle, { passive: true });
    window.addEventListener('resize', updateScrollToggle);
    scrollToggle.addEventListener('click', function () {
        window.scrollTo({ top: isBottom() ? 0 : document.documentElement.scrollHeight, behavior: 'smooth' });
    });
    updateScrollToggle();

    var s = document.getElementById('start_time_input'), e = document.getElementById('end_time_input'),
        t = document.getElementById('total_time_input'), err = document.getElementById('end_time_error'),
        btn = document.getElementById('saveBtn');
    function m(v) { var p = v.split(':'); return +p[0] * 60 + +p[1]; }
    function calc() {
        err.textContent = ''; if (btn) btn.disabled = false;
        if (!s.value || !e.value) { t.value = ''; return; }
        if (m(e.value) < m(s.value)) { err.textContent = 'End time cannot be earlier than start time.'; t.value = ''; if (btn) btn.disabled = true; return; }
        t.value = m(e.value) - m(s.value);
    }
    ['change', 'input'].forEach(function (ev) { s.addEventListener(ev, calc); e.addEventListener(ev, calc); });
    calc();

    document.getElementById('tstForm').addEventListener('submit', function () {
        if (btn) { btn.classList.add('loading'); setTimeout(function () { btn.disabled = true; }, 0); }
    });

    var f = document.querySelector('.flash');
    if (f) setTimeout(function () { f.style.transition = 'opacity .3s'; f.style.opacity = 0; setTimeout(function () { f.remove(); }, 300); }, 4000);
})();
</script>
</body>
</html>
