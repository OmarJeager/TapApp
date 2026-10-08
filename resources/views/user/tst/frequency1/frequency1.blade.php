{{-- resources/views/user/tst/Frequency1/frequency1.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TST Frequency 1 Checklist</title>
<style>
:root{
    --brand:#f4511e; --brand-2:#ff9800; --ok:#2e9e5b; --ok-bg:#e6f6ed;
    --nok:#d32f2f; --nok-bg:#fdeaea; --ink:#1f2933; --muted:#6b7785;
    --line:#e4e7ec; --bg:#f3f5f9; --card:#fff; --radius:16px;
    --shadow:0 6px 22px rgba(20,30,50,.08);
}
*{box-sizing:border-box}
body{margin:0;font-family:'Segoe UI',Arial,sans-serif;color:var(--ink);background:var(--bg);padding:0 16px 90px}
svg.i{width:1.1em;height:1.1em;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;vertical-align:-.15em;flex:none}

/* ---------- top bar ---------- */
.topbar{max-width:900px;margin:0 auto;padding:22px 0 8px;display:flex;justify-content:space-between;align-items:center;gap:10px}
.back-btn{display:inline-flex;align-items:center;gap:10px;padding:11px 20px;border-radius:12px;color:#fff;text-decoration:none;font-weight:700;
    background:linear-gradient(135deg,var(--brand-2),var(--brand));box-shadow:0 8px 18px rgba(244,81,30,.3);position:relative;overflow:hidden;transition:.3s}
.back-btn::before{content:"";position:absolute;top:0;left:-120%;width:70%;height:100%;background:rgba(255,255,255,.28);transform:skewX(-25deg);transition:left .6s}
.back-btn:hover::before{left:140%}
.back-btn:hover{transform:translateY(-3px)}
.back-btn:hover svg{transform:translateX(-4px)}
.back-btn svg{transition:transform .3s}
.logout-trigger{display:inline-flex;align-items:center;gap:8px;padding:11px 18px;border:0;border-radius:12px;color:#fff;font:700 15px inherit;cursor:pointer;
    background:linear-gradient(135deg,#ef5350,#c62828);box-shadow:0 7px 18px rgba(198,40,40,.3);transition:.2s}
.logout-trigger:hover{transform:translateY(-3px)}
.logout-overlay{position:fixed;inset:0;z-index:2000;display:flex;align-items:center;justify-content:center;padding:20px;background:rgba(18,24,32,.58);opacity:0;visibility:hidden;transition:.25s}
.logout-overlay.is-open{opacity:1;visibility:visible}
.logout-dialog{width:min(100%,390px);padding:30px;border-radius:18px;background:#fff;text-align:center;transform:translateY(18px) scale(.96);transition:.25s}
.logout-overlay.is-open .logout-dialog{transform:none}
.logout-icon{display:grid;place-items:center;width:58px;height:58px;margin:0 auto 14px;border-radius:50%;color:#c62828;background:#ffebee;font-size:24px}
.logout-dialog h2{margin:0 0 8px}.logout-dialog p{margin:0 0 22px;color:var(--muted)}
.logout-actions{display:flex;justify-content:center;gap:10px}
.logout-actions button{padding:11px 16px;border:0;border-radius:8px;font:700 14px inherit;cursor:pointer}
.logout-cancel{background:#eee}.logout-confirm{background:#c62828;color:#fff}

/* ---------- layout ---------- */
.wrap{max-width:900px;margin:0 auto}
.hero{margin-top:12px;padding:26px;border-radius:22px;color:#fff;position:relative;overflow:hidden;
    background:linear-gradient(135deg,#263238,#37474f 55%,#455a64);box-shadow:var(--shadow);animation:rise .6s both}
.hero::after{content:"";position:absolute;right:-60px;top:-60px;width:220px;height:220px;border-radius:50%;
    background:radial-gradient(circle,rgba(255,152,0,.55),transparent 70%);animation:float 6s ease-in-out infinite}
.hero h1{margin:0 0 4px;font-size:26px;display:flex;align-items:center;gap:12px}
.hero .badge{display:inline-block;margin-bottom:10px;padding:4px 12px;border-radius:99px;font-size:12px;font-weight:700;letter-spacing:.5px;background:rgba(255,255,255,.15)}
.info-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px;margin-top:18px;position:relative;z-index:1}
.info{background:rgba(255,255,255,.1);border-radius:12px;padding:10px 14px;backdrop-filter:blur(4px)}
.info small{display:flex;align-items:center;gap:6px;opacity:.75;font-size:12px;text-transform:uppercase;letter-spacing:.4px}
.info b{display:block;margin-top:3px;font-size:15px;overflow-wrap:anywhere}
.info.wide{grid-column:1/-1}

/* ---------- progress ---------- */
.progress-box{position:sticky;top:0;z-index:50;margin:18px 0;padding:12px 16px;border-radius:14px;background:rgba(255,255,255,.92);
    backdrop-filter:blur(8px);box-shadow:var(--shadow);display:flex;align-items:center;gap:14px;flex-wrap:wrap}
.progress-bar{flex:1;min-width:140px;height:10px;border-radius:99px;background:#e8ebf0;overflow:hidden}
.progress-bar span{display:block;height:100%;width:0;border-radius:99px;background:linear-gradient(90deg,var(--brand-2),var(--brand));transition:width .5s cubic-bezier(.22,1,.36,1)}
.progress-text{font-weight:700;font-size:14px;white-space:nowrap}
.chip{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border:0;border-radius:99px;font:700 13px inherit;cursor:pointer;transition:.2s}
.chip.ok{background:var(--ok-bg);color:var(--ok)}.chip.ok:hover{background:var(--ok);color:#fff;transform:translateY(-2px)}
.nok-count{background:var(--nok-bg);color:var(--nok);cursor:default}

/* ---------- question cards ---------- */
.section-title{margin:26px 0 10px;font-size:13px;font-weight:800;color:var(--muted);text-transform:uppercase;letter-spacing:1px;display:flex;align-items:center;gap:8px}
.q-card{background:var(--card);border-radius:var(--radius);padding:18px;margin-bottom:14px;border:2px solid transparent;box-shadow:var(--shadow);
    animation:rise .5s both;transition:border-color .25s,transform .25s,box-shadow .25s}
.q-card:hover{transform:translateY(-2px)}
.q-card.is-ok{border-left:6px solid var(--ok)}
.q-card.is-nok{border-left:6px solid var(--nok);background:linear-gradient(90deg,#fff7f7,#fff 40%)}
.q-head{display:flex;gap:14px;align-items:flex-start}
.q-num{flex:none;width:38px;height:38px;border-radius:12px;display:grid;place-items:center;font-weight:800;color:#fff;background:linear-gradient(135deg,var(--brand-2),var(--brand))}
.q-text{flex:1;font-size:16px;line-height:1.45;font-weight:600;padding-top:7px}
.toggle{display:flex;gap:10px;margin-top:14px}
.toggle label{flex:1;cursor:pointer}
.toggle input{position:absolute;opacity:0;pointer-events:none}
.toggle span{display:flex;justify-content:center;align-items:center;gap:8px;padding:12px;border-radius:12px;font-weight:700;border:2px solid var(--line);color:var(--muted);transition:.2s;user-select:none}
.toggle label:hover span{transform:translateY(-2px)}
.toggle .t-ok input:checked + span{background:var(--ok);border-color:var(--ok);color:#fff;box-shadow:0 6px 14px rgba(46,158,91,.35);animation:pop .35s}
.toggle .t-nok input:checked + span{background:var(--nok);border-color:var(--nok);color:#fff;box-shadow:0 6px 14px rgba(211,47,47,.35);animation:pop .35s}
.toggle input:focus-visible + span{outline:3px solid #90caf9}
.extra{display:grid;gap:10px;margin-top:14px}
.field{position:relative}
.field svg{position:absolute;left:12px;top:13px;color:var(--muted)}
.field input{width:100%;padding:11px 12px 11px 38px;border:1.5px solid var(--line);border-radius:10px;font:inherit;background:#fafbfc;transition:.2s}
.field input:focus{outline:0;border-color:var(--brand-2);background:#fff;box-shadow:0 0 0 4px rgba(255,152,0,.16)}
.comment-wrap{max-height:0;opacity:0;overflow:hidden;transition:max-height .35s ease,opacity .3s}
.is-nok .comment-wrap,.comment-wrap.has-value{max-height:70px;opacity:1}
.more-btn{margin-top:12px;background:none;border:0;color:var(--brand);font:700 13px inherit;cursor:pointer;display:inline-flex;gap:6px;align-items:center;padding:0}
.more-btn svg{transition:transform .25s}.more-btn.open svg{transform:rotate(180deg)}
.more{display:none;grid-template-columns:1fr 1fr;gap:10px;margin-top:10px;animation:rise .3s both}
.more.open{display:grid}

/* ---------- notes / maintenance ---------- */
.panel{background:var(--card);border-radius:var(--radius);padding:22px;margin-top:22px;box-shadow:var(--shadow);animation:rise .6s both}
.panel h2{margin:0 0 16px;font-size:18px;display:flex;align-items:center;gap:10px}
.panel h2 svg{color:var(--brand)}
.notes{font-size:14px;line-height:1.6;color:#4a5560;background:#fff8ed;border-left:5px solid var(--brand-2)}
.grid-2{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px}
.lbl{display:block;margin-bottom:6px;font-size:13px;font-weight:700;color:var(--muted)}
.panel .field input{padding-left:38px}
.panel .field input[readonly]{background:#eef1f5;font-weight:800}
.err-inline{color:var(--nok);font-size:12px;margin-top:4px;min-height:14px}
.status-pill{display:flex;align-items:center;gap:10px;margin-top:12px;padding:12px 16px;border-radius:12px;font-weight:700}
.status-pill.verified{background:var(--ok-bg);color:#17683b}
.status-pill.pending{background:var(--nok-bg);color:#8f1d1d}

/* ---------- alerts ---------- */
.alert-error{margin:14px 0;padding:12px 16px;border-radius:12px;background:var(--nok-bg);color:#8f1d1d;border-left:5px solid var(--nok);animation:shake .4s}
.alert-error ul{margin:0;padding-left:18px}
.flash{position:fixed;top:20px;right:20px;z-index:3000;max-width:min(420px,calc(100vw - 40px));padding:14px 20px;border-radius:12px;color:#155724;background:#d4edda;
    border-left:5px solid #28a745;box-shadow:0 8px 24px rgba(0,0,0,.18);font-weight:700;display:flex;gap:10px;animation:slide .35s both}
.edit-form{display:flex;gap:10px;flex-wrap:wrap;margin-top:12px}
.edit-form input{flex:1;min-width:200px;padding:11px;border:1.5px solid var(--line);border-radius:10px;font:inherit}

/* ---------- submit ---------- */
.submit-bar{position:fixed;left:0;right:0;bottom:0;z-index:60;padding:12px 16px;background:rgba(255,255,255,.94);backdrop-filter:blur(8px);box-shadow:0 -6px 20px rgba(0,0,0,.08)}
.submit-bar .in{max-width:900px;margin:0 auto;display:flex;justify-content:space-between;align-items:center;gap:12px}
.save-btn{display:inline-flex;align-items:center;gap:10px;padding:14px 32px;border:0;border-radius:12px;color:#fff;font:800 16px inherit;cursor:pointer;
    background:linear-gradient(135deg,var(--brand-2),var(--brand));box-shadow:0 8px 20px rgba(244,81,30,.35);transition:.25s}
.save-btn:hover{transform:translateY(-3px);box-shadow:0 12px 26px rgba(244,81,30,.45)}
.save-btn:hover svg{transform:translateX(5px)}.save-btn svg{transition:transform .25s}
.save-btn:active{transform:scale(.96)}
.save-btn:disabled{background:#9aa3ad;box-shadow:none;cursor:not-allowed;transform:none}
.save-btn.loading svg{animation:spin 1s linear infinite}
.footer{text-align:center;color:var(--muted);font-size:12px;margin-top:26px}

@keyframes rise{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:none}}
@keyframes pop{50%{transform:scale(1.06)}}
@keyframes float{50%{transform:translate(-20px,20px)}}
@keyframes slide{from{opacity:0;transform:translateX(30px)}to{opacity:1;transform:none}}
@keyframes shake{25%{transform:translateX(-6px)}75%{transform:translateX(6px)}}
@keyframes spin{to{transform:rotate(360deg)}}
@media (prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}
@media (max-width:600px){
    body{padding:0 10px 90px}.hero{padding:20px}.hero h1{font-size:21px}
    .logout-trigger span{display:none}.more{grid-template-columns:1fr}
    .save-btn{width:100%;justify-content:center}.submit-bar .hint{display:none}
    .flash{left:10px;right:10px;max-width:none}
}
</style>
</head>
<body>

@php
    $icon = fn($d) => '<svg class="i" viewBox="0 0 24 24" aria-hidden="true">'.$d.'</svg>';
@endphp

{{-- ============ TOP BAR ============ --}}
<div class="topbar">
    <a href="{{ route('user.index') }}" class="back-btn">
        {!! $icon('<path d="M19 12H5M12 19l-7-7 7-7"/>') !!} <span>Back</span>
    </a>
    <button type="button" class="logout-trigger" onclick="logoutDialog.classList.add('is-open')">
        {!! $icon('<path d="M10 17l5-5-5-5M15 12H3m9-8h6a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-6"/>') !!} <span>Log out</span>
    </button>
</div>

<div class="logout-overlay" id="logoutDialog" role="dialog" aria-modal="true" onclick="if(event.target===this)this.classList.remove('is-open')">
    <div class="logout-dialog">
        <div class="logout-icon">{!! $icon('<path d="M10 17l5-5-5-5M15 12H3m9-8h6a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-6"/>') !!}</div>
        <h2>Log out?</h2>
        <p>Are you sure you want to log out?</p>
        <div class="logout-actions">
            <button type="button" class="logout-cancel" onclick="logoutDialog.classList.remove('is-open')">Stay here</button>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="logout-confirm">Yes, log out</button></form>
        </div>
    </div>
</div>

@if (session('status'))
    <div class="flash" role="status">{!! $icon('<path d="M20 6L9 17l-5-5"/>') !!} {{ session('status') }}</div>
@endif

<div class="wrap">

    {{-- ============ HERO / RECORD ============ --}}
    <section class="hero">
        <span class="badge">TST · FREQUENCY 1</span>
        <h1>{!! $icon('<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>') !!} TST Frequency 1 Checklist</h1>
        <div class="info-grid">
            <div class="info"><small>{!! $icon('<path d="M4 4h16v16H4z"/><path d="M9 9h6v6H9z"/>') !!} PPM ID</small><b>{{ $ppmRecord->ppm_id }}</b></div>
            <div class="info"><small>{!! $icon('<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>') !!} Asset ID</small><b>{{ $ppmRecord->asset_id }}</b></div>
            <div class="info"><small>{!! $icon('<path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-2.5 2.5-2.5-.5-.5-2.5z"/>') !!} Job ID</small><b>{{ $ppmRecord->job_id }}</b></div>
            <div class="info"><small>{!! $icon('<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>') !!} Week due</small><b>{{ $ppmRecord->week_due }}</b></div>
            <div class="info wide"><small>{!! $icon('<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>') !!} Asset description</small><b>{{ $ppmRecord->asset_description }}</b></div>
        </div>
    </section>

    @if (session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert-error"><ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('ppm-checklists.tst1.store') }}" id="tstForm">
        @csrf
        <input type="hidden" name="ppm_records_id" value="{{ $ppmRecord->id }}">

        <fieldset {{ $isLocked ? 'disabled' : '' }} style="border:0;padding:0;margin:0;min-width:0;">

            {{-- ============ PROGRESS ============ --}}
            <div class="progress-box">
                <div class="progress-text"><span id="doneCount">0</span>/{{ $questions->count() }} checked</div>
                <div class="progress-bar"><span id="progressFill"></span></div>
                <span class="chip nok-count">{!! $icon('<path d="M18 6L6 18M6 6l12 12"/>') !!} NOK: <b id="nokCount">0</b></span>
                @unless ($isLocked)
                    <button type="button" class="chip ok" id="allOkBtn">{!! $icon('<path d="M20 6L9 17l-5-5"/>') !!} All OK</button>
                @endunless
            </div>

            {{-- ============ QUESTIONS ============ --}}
            @forelse ($questions as $index => $question)
                @php
                    $existing = $answers->get($question->id);
                    $old      = old("answers.$index");
                    $resp     = $old['response'] ?? $existing->response ?? 'ok';
                    $comment  = $old['comment'] ?? $existing->comment ?? '';
                    $dpn      = $old['dpn'] ?? $existing->dpn ?? '';
                    $obs      = $old['observation'] ?? $existing->observation ?? '';
                    $hasMore  = $dpn !== '' || $obs !== '';
                @endphp

                <article class="q-card {{ $resp === 'not_ok' ? 'is-nok' : 'is-ok' }}" style="animation-delay: {{ min($index * 50, 600) }}ms">
                    <div class="q-head">
                        <div class="q-num">{{ $question->order }}</div>
                        <div class="q-text">{{ $question->question_text }}</div>
                    </div>
                    <input type="hidden" name="answers[{{ $index }}][checklist_question_id]" value="{{ $question->id }}">

                    <div class="toggle">
                        <label class="t-ok">
                            <input type="radio" name="answers[{{ $index }}][response]" value="ok" @checked($resp === 'ok')>
                            <span>{!! $icon('<path d="M20 6L9 17l-5-5"/>') !!} OK</span>
                        </label>
                        <label class="t-nok">
                            <input type="radio" name="answers[{{ $index }}][response]" value="not_ok" @checked($resp === 'not_ok')>
                            <span>{!! $icon('<path d="M18 6L6 18M6 6l12 12"/>') !!} NOK</span>
                        </label>
                    </div>

                    <div class="comment-wrap {{ $comment !== '' ? 'has-value' : '' }}">
                        <div class="extra"><div class="field" style="margin-top:12px">
                            {!! $icon('<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>') !!}
                            <input type="text" name="answers[{{ $index }}][comment]" value="{{ $comment }}" placeholder="Comment (explain the issue)…">
                        </div></div>
                    </div>

                    <button type="button" class="more-btn {{ $hasMore ? 'open' : '' }}">
                        {!! $icon('<path d="M6 9l6 6 6-6"/>') !!} Spare parts &amp; observations
                    </button>
                    <div class="more {{ $hasMore ? 'open' : '' }}">
                        <div class="field">{!! $icon('<path d="M21 16V8l-9-5-9 5v8l9 5z"/>') !!}
                            <input type="text" name="answers[{{ $index }}][dpn]" value="{{ $dpn }}" placeholder="SP consumed [DPN]">
                        </div>
                        <div class="field">{!! $icon('<circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/>') !!}
                            <input type="text" name="answers[{{ $index }}][observation]" value="{{ $obs }}" placeholder="Observations">
                        </div>
                    </div>
                </article>
            @empty
                <div class="panel">No active questions found for TST Frequency 1.</div>
            @endforelse

            {{-- ============ NOTES ============ --}}
            <section class="panel notes">
                <h2>{!! $icon('<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8"/>') !!} Notes</h2>
                If some points are marked as NOK, they must be closed during the maintenance and validated by the Reliability responsible.<br>
                After the maintenance, identify the equipment with the maintenance evidence (equipment ID, maintenance date, expiry date, signature).<br><br>
                <strong>Remarks:</strong> Every abnormality must be reported to the maintenance department. Issues that can affect safety, productivity or product quality must be reported immediately; others go to the Daily Problem Report.
            </section>

            {{-- ============ MAINTENANCE ============ --}}
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
                <div class="submit-bar">
                    <div class="in">
                        <span class="hint" style="color:var(--muted);font-size:13px">Review all answers before saving.</span>
                        <button type="submit" class="save-btn" id="saveBtn">
                            Save &amp; Next {!! $icon('<path d="M5 12h14M12 5l7 7-7 7"/>') !!}
                        </button>
                    </div>
                </div>
            @endunless
        </fieldset>
    </form>

    {{-- ============ LOCK / EDIT REQUEST (outside main form) ============ --}}
    @if ($isLocked)
        <div class="status-pill verified">{!! $icon('<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>') !!} Verified by quality — this checklist is read-only.</div>

        @if ($editRequest?->status === 'pending')
            <div class="status-pill pending">{!! $icon('<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>') !!} Edit request sent. Waiting for admin decision.</div>
        @else
            @if ($editRequest?->status === 'rejected')
                <div class="alert-error"><b>Admin did not accept your edit request.</b><br>Reason: {{ $editRequest->admin_note }}</div>
            @endif
            <form method="POST" action="{{ route('ppm-checklists.tst1.edit-request', $ppmRecord) }}" class="edit-form">
                @csrf
                <input type="text" name="request_reason" placeholder="Why do you need to edit? (optional)">
                <button type="submit" class="save-btn" style="padding:11px 22px;font-size:15px">Request edit</button>
            </form>
        @endif
    @elseif ($editRequest?->status === 'approved')
        <div class="status-pill verified">{!! $icon('<path d="M20 6L9 17l-5-5"/>') !!} Admin accepted your request. You can edit now.</div>
    @endif

    <div class="footer">DPEO MEN-MEC 00.39-07.005 F1</div>
</div>

<script>
(function () {
    var cards = document.querySelectorAll('.q-card');
    var total = cards.length;
    var fill = document.getElementById('progressFill');
    var doneEl = document.getElementById('doneCount');
    var nokEl = document.getElementById('nokCount');

    function refresh() {
        var done = 0, nok = 0;
        cards.forEach(function (c) {
            var checked = c.querySelector('input[type=radio]:checked');
            c.classList.toggle('is-ok', !!checked && checked.value === 'ok');
            c.classList.toggle('is-nok', !!checked && checked.value === 'not_ok');
            if (checked) { done++; if (checked.value === 'not_ok') nok++; }
        });
        doneEl.textContent = done;
        nokEl.textContent = nok;
        fill.style.width = (total ? done / total * 100 : 0) + '%';
    }

    cards.forEach(function (c) {
        c.querySelectorAll('input[type=radio]').forEach(function (r) {
            r.addEventListener('change', function () {
                refresh();
                if (r.value === 'not_ok') {
                    var t = c.querySelector('.comment-wrap input'); if (t) setTimeout(function () { t.focus(); }, 350);
                }
            });
        });
        var btn = c.querySelector('.more-btn'), more = c.querySelector('.more');
        btn.addEventListener('click', function () { btn.classList.toggle('open'); more.classList.toggle('open'); });
    });

    var allOk = document.getElementById('allOkBtn');
    if (allOk) allOk.addEventListener('click', function () {
        cards.forEach(function (c) { c.querySelector('input[value=ok]').checked = true; });
        refresh();
    });
    refresh();

    /* ---- time calculation ---- */
    var s = document.getElementById('start_time_input'), e = document.getElementById('end_time_input'),
        t = document.getElementById('total_time_input'), err = document.getElementById('end_time_error'),
        btnSave = document.getElementById('saveBtn');
    function m(v) { var p = v.split(':'); return +p[0] * 60 + +p[1]; }
    function calc() {
        err.textContent = ''; if (btnSave) btnSave.disabled = false;
        if (!s.value || !e.value) { t.value = ''; return; }
        if (m(e.value) < m(s.value)) {
            err.textContent = 'End time cannot be earlier than start time.'; t.value = '';
            if (btnSave) btnSave.disabled = true; return;
        }
        t.value = m(e.value) - m(s.value);
    }
    ['change', 'input'].forEach(function (ev) { s.addEventListener(ev, calc); e.addEventListener(ev, calc); });
    calc();

    /* ---- loading state + double submit guard ---- */
    var form = document.getElementById('tstForm');
    form.addEventListener('submit', function () {
        if (btnSave) { btnSave.classList.add('loading'); setTimeout(function () { btnSave.disabled = true; }, 0); }
    });

    /* ---- flash auto-hide ---- */
    var f = document.querySelector('.flash');
    if (f) setTimeout(function () { f.style.transition = 'opacity .3s'; f.style.opacity = 0; setTimeout(function () { f.remove(); }, 300); }, 4000);
})();
</script>
</body>
</html>