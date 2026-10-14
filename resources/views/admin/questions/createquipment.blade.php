<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>New Equipment | TapApp</title>
    <script src="https://unpkg.com/lucide@0.383.0/dist/umd/lucide.min.js"></script>
    <style>
        :root{--bg:#f4f6fb;--surface:#fff;--text:#182238;--muted:#718096;--primary:#f47721;--primary-light:#fff0e5;--border:#e7eaf1;--green:#16865c;--red:#cf4141;--radius:18px}
        *{box-sizing:border-box}
        body{margin:0;background:var(--bg);color:var(--text);font-family:Inter,"Segoe UI",sans-serif}
        button,input,select,textarea{font:inherit}
        button{cursor:pointer}
        button:disabled{opacity:.6;cursor:not-allowed}
        i[data-lucide],svg.lucide{width:18px;height:18px;stroke-width:2.2;flex-shrink:0}
        .app{width:min(1300px,100%);margin:auto;padding:30px}

        .hero{position:relative;overflow:hidden;background:linear-gradient(125deg,#202c43,#344767);color:#fff;padding:30px;border-radius:25px;display:flex;justify-content:space-between;align-items:center;gap:20px;animation:rise .5s ease both}
        .hero::after{content:"";position:absolute;width:240px;height:240px;right:-70px;top:-110px;border:35px solid #ffffff0b;border-radius:50%;pointer-events:none}
        .eyebrow{text-transform:uppercase;letter-spacing:2px;color:#ffbd88;font-size:11px;font-weight:800}
        h1{margin:9px 0;font-size:clamp(25px,4vw,36px)}
        .sub{color:#d4dcec;font-size:14px;line-height:1.6}
        h2{font-size:17px;margin:0}
        .muted{color:var(--muted);font-size:13px}

        .button{border:0;padding:11px 16px;border-radius:11px;font-weight:700;display:inline-flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;transition:transform .2s,box-shadow .2s,background .2s}
        .button:hover:not(:disabled){transform:translateY(-2px);box-shadow:0 7px 18px #19253b18}
        .primary{background:var(--primary);color:#fff}
        .light{background:#fff;color:var(--text)}
        .soft{background:var(--primary-light);color:#b95616}
        .danger{background:#fff0f0;color:var(--red)}
        .icon-btn{padding:8px;border-radius:9px}

        .layout{display:grid;grid-template-columns:minmax(0,1fr) 340px;gap:20px;margin-top:22px;align-items:start}
        .panel{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:0 5px 24px #26334d05;padding:24px;margin-bottom:20px;animation:rise .45s ease both}
        .panel:nth-child(2){animation-delay:.07s}.panel:nth-child(3){animation-delay:.14s}
        .step{display:flex;align-items:center;gap:13px;margin-bottom:20px}
        .step-icon{width:44px;height:44px;border-radius:13px;background:var(--primary-light);color:var(--primary);display:grid;place-items:center;transition:transform .25s}
        .panel:hover .step-icon{transform:rotate(-6deg) scale(1.07)}

        .field{display:flex;flex-direction:column;gap:7px;margin-bottom:16px}
        .field>label{font-size:12px;color:var(--muted);font-weight:700}
        .control{width:100%;border:1px solid var(--border);background:#fff;color:var(--text);padding:12px;border-radius:10px;outline:none;transition:border .2s,box-shadow .2s}
        .control:focus{border-color:var(--primary);box-shadow:0 0 0 3px #f477211a}
        .control.invalid{border-color:var(--red);box-shadow:0 0 0 3px #cf414118;animation:shake .3s}
        .type-input{text-transform:uppercase;letter-spacing:2px;font-weight:800;font-size:18px}
        textarea.control{resize:vertical;line-height:1.6}

        .chips{display:flex;flex-wrap:wrap;gap:8px}
        .chip{border:1px solid var(--border);background:#fff;color:#56647c;padding:8px 14px;border-radius:999px;font-size:12px;font-weight:750;transition:all .2s;display:inline-flex;align-items:center;gap:6px;animation:pop .25s ease both}
        .chip:hover{border-color:var(--primary);color:var(--primary);transform:translateY(-1px)}
        .chip.active{background:var(--primary);border-color:var(--primary);color:#fff}
        .chip .x{display:grid;place-items:center;width:16px;height:16px;border-radius:50%;background:#0002;margin-left:2px}
        .chip .x:hover{background:#0004}
        .chip .x svg{width:10px;height:10px}
        .chip.new-tag{border-style:dashed}
        .row2{display:grid;grid-template-columns:1fr 1fr;gap:20px}

        .variant-add{display:flex;gap:8px;margin-top:6px}
        .variant-add .control{text-transform:uppercase;letter-spacing:1.5px;font-weight:800;padding:10px 12px}
        .variant-add .button{padding:10px 14px;flex-shrink:0}
        .variant-box{background:#fffaf5;border:1px dashed #f4b78a;border-radius:13px;padding:14px;margin-top:4px}
        .variant-box .lbl{font-size:12px;font-weight:800;color:#b95616;display:flex;align-items:center;gap:6px}

        .q-row{display:flex;gap:10px;align-items:flex-start;padding:12px;border:1px solid var(--border);border-radius:14px;margin-bottom:10px;background:#fcfcfe;animation:pop .28s ease both;transition:opacity .2s,box-shadow .2s}
        .q-row:hover{box-shadow:0 8px 20px #26334d0c}
        .q-row.off{opacity:.6}
        .q-row.removing{opacity:0;transform:translateX(20px);transition:all .2s}
        .q-num{min-width:34px;height:34px;border-radius:10px;background:var(--primary-light);color:#b95616;font-weight:800;font-size:12px;display:grid;place-items:center;margin-top:3px}
        .q-body{flex:1;min-width:0}
        .q-body textarea{min-height:46px}
        .q-tools{display:flex;flex-direction:column;gap:5px}
        .switch{position:relative;width:38px;height:22px;flex-shrink:0;display:inline-block}
        .switch input{display:none}
        .switch span{position:absolute;inset:0;background:#d6dce7;border-radius:99px;transition:.25s;cursor:pointer}
        .switch span::after{content:"";position:absolute;width:16px;height:16px;left:3px;top:3px;background:#fff;border-radius:50%;transition:.25s}
        .switch input:checked+span{background:var(--green)}
        .switch input:checked+span::after{transform:translateX(16px)}
        .q-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:6px}
        .empty{padding:36px 20px;text-align:center;border:1px dashed #d6dce7;border-radius:15px;color:var(--muted)}

        .summary{position:sticky;top:20px}
        .summary .panel{margin:0}
        .preview{background:linear-gradient(135deg,#202c43,#344767);color:#fff;border-radius:16px;padding:20px;margin:16px 0}
        .preview .code{font-size:34px;font-weight:900;letter-spacing:3px;min-height:42px}
        .tags{display:flex;gap:6px;flex-wrap:wrap;margin-top:10px}
        .tag{background:#ffffff1f;padding:5px 10px;border-radius:7px;font-size:11px;font-weight:750}
        .tag.hl{background:var(--primary);animation:pop .3s ease}
        .sum-row{display:flex;justify-content:space-between;font-size:13px;padding:9px 0;border-bottom:1px solid var(--border)}
        .sum-row b{font-weight:800}
        .checks{list-style:none;padding:0;margin:14px 0;font-size:13px;display:grid;gap:8px}
        .checks li{display:flex;align-items:center;gap:8px;color:var(--muted);transition:color .2s}
        .checks li.ok{color:var(--green);font-weight:700}

        .toast{position:fixed;top:20px;right:20px;max-width:380px;padding:14px 18px;border-radius:13px;font-size:13px;line-height:1.5;z-index:99;display:flex;gap:10px;align-items:flex-start;transform:translateX(120%);transition:transform .35s cubic-bezier(.2,.9,.3,1.2);box-shadow:0 14px 35px #0002}
        .toast.show{transform:none}
        .toast.ok{background:#e8f8ef;color:#126f4a}
        .toast.error{background:#fff0f0;color:#a72e2e}

        .spin{animation:spin .8s linear infinite}
        @keyframes spin{to{transform:rotate(360deg)}}
        @keyframes rise{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
        @keyframes pop{from{opacity:0;transform:scale(.95) translateY(6px)}to{opacity:1;transform:none}}
        @keyframes shake{25%{transform:translateX(-4px)}75%{transform:translateX(4px)}}

        @media(max-width:1000px){.layout{grid-template-columns:1fr}.summary{position:static}}
        @media(max-width:600px){.app{padding:13px}.hero{flex-direction:column;align-items:flex-start;padding:22px}.row2{grid-template-columns:1fr}.q-row{flex-wrap:wrap}.q-tools{flex-direction:row}.panel{padding:16px}}
        @media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.01ms!important;transition-duration:.01ms!important}}
    </style>
</head>
<body>
<div class="app">
    <header class="hero">
        <div>
            <div class="eyebrow">TapApp · Administration</div>
            <h1>Create New Equipment</h1>
            <div class="sub">Define an equipment type, its variant and its checklist questions, then save them to the database.</div>
        </div>
        <a href="{{ route('admin.checklist-questions.index') }}" class="button light">
            <i data-lucide="arrow-left"></i> Back to Studio
        </a>
    </header>

    <div class="layout">
        <form id="equipmentForm" novalidate onsubmit="return false">
            {{-- STEP 1 --}}
            <section class="panel">
                <div class="step">
                    <div class="step-icon"><i data-lucide="factory"></i></div>
                    <div><h2>1 · Equipment type</h2><div class="muted">A short code, e.g. PNL, TRQ, TST or a brand new one.</div></div>
                </div>
                <div class="field">
                    <label for="type">Type code</label>
                    <input class="control type-input" id="type" maxlength="10" placeholder="e.g. UPS" autocomplete="off" required>
                    <div class="muted">2–10 characters: letters, numbers, dash or underscore.</div>
                </div>
                @if($types->count())
                <div class="field">
                    <label>Existing types (click to reuse)</label>
                    <div class="chips">
                        @foreach($types as $t)
                            <button type="button" class="chip" data-type="{{ $t }}"><i data-lucide="tag" style="width:13px;height:13px"></i>{{ $t }}</button>
                        @endforeach
                    </div>
                </div>
                @endif
            </section>

            {{-- STEP 2 --}}
            <section class="panel">
                <div class="step">
                    <div class="step-icon"><i data-lucide="sliders-horizontal"></i></div>
                    <div><h2>2 · Configuration</h2><div class="muted">Maintenance frequency, variant and visibility.</div></div>
                </div>
                <div class="row2">
                    <div class="field">
                        <label for="frequency">Frequency</label>
                        <input class="control" type="number" min="1" id="frequency" placeholder="None (null)">
                        <div class="chips" id="freqChips">
                            <button type="button" class="chip active" data-f="">None</button>
                            <button type="button" class="chip" data-f="1">1</button>
                            <button type="button" class="chip" data-f="4">4</button>
                        </div>
                    </div>
                    <div class="field">
                        <label>Variant</label>
                        <div class="chips" id="variantChips"></div>

                        <div class="variant-box">
                            <div class="lbl"><i data-lucide="sparkles" style="width:14px;height:14px"></i> Create a new variant</div>
                            <div class="variant-add">
                                <input class="control" id="newVariant" maxlength="10" placeholder="e.g. LV, MV, OUTDOOR" autocomplete="off">
                                <button type="button" class="button soft" id="addVariant"><i data-lucide="plus"></i> Add</button>
                            </div>
                            <div class="muted" style="margin-top:7px">1–10 characters: letters, numbers, dash or underscore. It is selected automatically.</div>
                        </div>
                    </div>
                </div>
                <label style="display:flex;gap:10px;align-items:center;margin-top:6px;font-size:13px">
                    <span class="switch"><input type="checkbox" id="groupActive" checked><span></span></span>
                    Questions are active by default
                </label>
            </section>

            {{-- STEP 3 --}}
            <section class="panel">
                <div class="step">
                    <div class="step-icon"><i data-lucide="list-checks"></i></div>
                    <div style="flex:1"><h2>3 · Checklist questions</h2><div class="muted">Order is assigned automatically from the top. Paste several lines to split them into rows.</div></div>
                </div>
                <div id="qList"></div>
                <div class="q-actions">
                    <button type="button" class="button soft" id="addQ"><i data-lucide="plus"></i> Add question</button>
                    <button type="button" class="button light" id="clearQ"><i data-lucide="eraser"></i> Clear all</button>
                </div>
            </section>
        </form>

        {{-- SUMMARY --}}
        <aside class="summary">
            <div class="panel">
                <h2>Summary</h2>
                <div class="preview">
                    <div class="muted" style="color:#ffbd88;font-size:11px;letter-spacing:2px;font-weight:800">PREVIEW</div>
                    <div class="code" id="pvType">—</div>
                    <div class="tags" id="pvTags"></div>
                </div>
                <div class="sum-row"><span>Questions</span><b id="sumCount">0</b></div>
                <div class="sum-row"><span>Active</span><b id="sumActive">0</b></div>
                <div class="sum-row"><span>Currently in database</span><b>{{ $stats['total'] }}</b></div>
                <ul class="checks">
                    <li id="ckType"><i data-lucide="circle"></i> Valid type code</li>
                    <li id="ckQ"><i data-lucide="circle"></i> At least one question</li>
                </ul>
                <button class="button primary" id="saveBtn" style="width:100%" type="button">
                    <i data-lucide="save"></i> <span>Save equipment</span>
                </button>
            </div>
        </aside>
    </div>
</div>

<div class="toast" id="toast"></div>

<script>
    const saveUrl = @json(route('admin.checklist-questions.equipment.store'));
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const $ = id => document.getElementById(id);
    const TYPE_RE = /^[A-Z0-9_-]{2,10}$/;
    const VARIANT_RE = /^[A-Z0-9_-]{1,10}$/;
    const RESERVED = ['NULL', 'NONE', 'NORMAL'];

    // existing variants from DB (HV always available)
    let variants = [...new Set(['HV', ...@json($variants ?? [])])].sort();
    let newlyAdded = new Set();      // variants created in this session (removable)
    let variant = '';                // '' = normal (null)
    let rows = [{ text: '', active: true }];

    const esc = v => String(v ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]));
    const icons = () => lucide.createIcons();

    function toast(msg, type = 'ok') {
        const t = $('toast');
        t.className = 'toast ' + type;
        t.innerHTML = `<i data-lucide="${type === 'ok' ? 'check-circle-2' : 'alert-triangle'}"></i><div>${esc(msg)}</div>`;
        icons();
        requestAnimationFrame(() => t.classList.add('show'));
        clearTimeout(toast.timer);
        toast.timer = setTimeout(() => t.classList.remove('show'), 5000);
    }

    /* ---------- variants ---------- */
    function renderVariants() {
        $('variantChips').innerHTML =
            `<button type="button" class="chip" data-v=""><i data-lucide="circle" style="width:13px;height:13px"></i>Normal (null)</button>` +
            variants.map(v => `
                <button type="button" class="chip ${newlyAdded.has(v) ? 'new-tag' : ''}" data-v="${esc(v)}">
                    <i data-lucide="${newlyAdded.has(v) ? 'sparkles' : 'zap'}" style="width:13px;height:13px"></i>${esc(v)}
                    ${newlyAdded.has(v) ? `<span class="x" data-remove="${esc(v)}" title="Remove"><i data-lucide="x"></i></span>` : ''}
                </button>`).join('');
        icons();
        updateSummary();
    }

    function addVariant() {
        const input = $('newVariant');
        const v = input.value.trim().toUpperCase();

        if (!VARIANT_RE.test(v)) {
            input.classList.remove('invalid'); void input.offsetWidth; input.classList.add('invalid');
            return toast('Variant must be 1–10 characters: letters, numbers, dash or underscore.', 'error');
        }
        if (RESERVED.includes(v)) {
            input.classList.add('invalid');
            return toast(`"${v}" is reserved. Use the "Normal (null)" option instead.`, 'error');
        }

        if (variants.includes(v)) {
            toast(`Variant ${v} already exists, selected it for you.`);
        } else {
            variants.push(v);
            variants.sort();
            newlyAdded.add(v);
            toast(`New variant ${v} created and selected.`);
        }

        variant = v;
        input.value = '';
        input.classList.remove('invalid');
        renderVariants();
    }

    $('addVariant').onclick = addVariant;
    $('newVariant').addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); addVariant(); } });
    $('newVariant').addEventListener('input', e => {
        e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9_-]/g, '');
        e.target.classList.remove('invalid');
    });

    $('variantChips').addEventListener('click', e => {
        const rm = e.target.closest('[data-remove]');
        if (rm) {
            e.stopPropagation();
            const v = rm.dataset.remove;
            variants = variants.filter(x => x !== v);
            newlyAdded.delete(v);
            if (variant === v) variant = '';
            return renderVariants();
        }
        const b = e.target.closest('[data-v]');
        if (b) { variant = b.dataset.v; updateSummary(); }
    });

    /* ---------- question rows ---------- */
    function renderRows(focusIndex = null) {
        $('qList').innerHTML = rows.length ? rows.map((r, i) => `
            <div class="q-row ${r.active ? '' : 'off'}" data-i="${i}">
                <div class="q-num">#${i + 1}</div>
                <div class="q-body">
                    <textarea class="control q-text" rows="1" maxlength="1000" placeholder="Enter the checklist question...">${esc(r.text)}</textarea>
                </div>
                <div class="q-tools">
                    <label class="switch" title="Active"><input type="checkbox" class="q-act" ${r.active ? 'checked' : ''}><span></span></label>
                </div>
                <div class="q-tools">
                    <button type="button" class="button light icon-btn" data-act="up" title="Move up" ${i === 0 ? 'disabled' : ''}><i data-lucide="chevron-up"></i></button>
                    <button type="button" class="button light icon-btn" data-act="down" title="Move down" ${i === rows.length - 1 ? 'disabled' : ''}><i data-lucide="chevron-down"></i></button>
                </div>
                <button type="button" class="button danger icon-btn" data-act="del" title="Delete"><i data-lucide="trash-2"></i></button>
            </div>`).join('')
            : `<div class="empty"><strong>No questions yet</strong><br>Click “Add question” to start.</div>`;
        icons();
        document.querySelectorAll('.q-text').forEach(autoGrow);
        if (focusIndex !== null) document.querySelectorAll('.q-text')[focusIndex]?.focus();
        updateSummary();
    }

    function autoGrow(el) { el.style.height = 'auto'; el.style.height = el.scrollHeight + 'px'; }

    $('qList').addEventListener('input', e => {
        const row = e.target.closest('.q-row'); if (!row) return;
        const i = +row.dataset.i;
        if (e.target.classList.contains('q-text')) { rows[i].text = e.target.value; autoGrow(e.target); }
        if (e.target.classList.contains('q-act')) { rows[i].active = e.target.checked; row.classList.toggle('off', !e.target.checked); }
        updateSummary();
    });

    $('qList').addEventListener('paste', e => {
        if (!e.target.classList.contains('q-text')) return;
        const text = (e.clipboardData || window.clipboardData).getData('text');
        const lines = text.split(/\r\n|\r|\n/).map(l => l.trim()).filter(Boolean);
        if (lines.length < 2) return;
        e.preventDefault();
        const i = +e.target.closest('.q-row').dataset.i;
        const def = $('groupActive').checked;
        const added = lines.map(t => ({ text: t, active: def }));
        if (!rows[i].text.trim()) rows.splice(i, 1, ...added); else rows.splice(i + 1, 0, ...added);
        renderRows();
        toast(`${lines.length} questions added from pasted text.`);
    });

    $('qList').addEventListener('click', e => {
        const btn = e.target.closest('[data-act]'); if (!btn) return;
        const row = btn.closest('.q-row'); const i = +row.dataset.i;
        if (btn.dataset.act === 'del') {
            row.classList.add('removing');
            setTimeout(() => { rows.splice(i, 1); renderRows(); }, 200);
        } else {
            const j = btn.dataset.act === 'up' ? i - 1 : i + 1;
            [rows[i], rows[j]] = [rows[j], rows[i]];
            renderRows();
        }
    });

    $('addQ').onclick = () => { rows.push({ text: '', active: $('groupActive').checked }); renderRows(rows.length - 1); };
    $('clearQ').onclick = () => { if (!rows.some(r => r.text.trim()) || confirm('Remove all questions from this form?')) { rows = [{ text: '', active: true }]; renderRows(); } };

    /* ---------- config controls ---------- */
    $('type').addEventListener('input', e => {
        e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9_-]/g, '');
        e.target.classList.remove('invalid');
        updateSummary();
    });

    document.querySelectorAll('[data-type]').forEach(b => b.onclick = () => { $('type').value = b.dataset.type; updateSummary(); });
    $('freqChips').addEventListener('click', e => { const b = e.target.closest('[data-f]'); if (b) { $('frequency').value = b.dataset.f; updateSummary(); } });
    $('frequency').addEventListener('input', updateSummary);
    $('groupActive').addEventListener('change', () => { rows.forEach(r => r.active = $('groupActive').checked); renderRows(); });

    /* ---------- summary ---------- */
    function updateSummary() {
        const type = $('type').value, freq = $('frequency').value;
        const filled = rows.filter(r => r.text.trim());
        $('pvType').textContent = type || '—';
        $('pvTags').innerHTML =
            `<span class="tag">Frequency ${freq || '—'}</span>` +
            `<span class="tag ${variant ? 'hl' : ''}">Variant ${esc(variant || 'null')}</span>` +
            `<span class="tag">${filled.length} question(s)</span>`;
        $('sumCount').textContent = filled.length;
        $('sumActive').textContent = filled.filter(r => r.active).length;

        document.querySelectorAll('#freqChips .chip').forEach(c => c.classList.toggle('active', c.dataset.f === String(freq)));
        document.querySelectorAll('#variantChips .chip').forEach(c => c.classList.toggle('active', c.dataset.v === variant));
        document.querySelectorAll('[data-type]').forEach(c => c.classList.toggle('active', c.dataset.type === type));

        setCheck('ckType', TYPE_RE.test(type));
        setCheck('ckQ', filled.length > 0);
    }

    function setCheck(id, ok) {
        const li = $(id);
        li.classList.toggle('ok', ok);
        const el = li.querySelector('svg, i');
        if (el) el.outerHTML = `<i data-lucide="${ok ? 'check-circle-2' : 'circle'}"></i>`;
        icons();
    }

    /* ---------- save ---------- */
    $('saveBtn').onclick = async () => {
        const type = $('type').value;
        const filled = rows.filter(r => r.text.trim());

        if (!TYPE_RE.test(type)) { $('type').classList.add('invalid'); $('type').focus(); return toast('Enter a valid type code (2–10 letters, numbers, - or _).', 'error'); }
        if (!filled.length) return toast('Add at least one question.', 'error');
        if ($('newVariant').value.trim()) return toast('You typed a new variant but did not add it. Click "Add" first.', 'error');

        const btn = $('saveBtn');
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader-2" class="spin"></i> <span>Saving...</span>'; icons();

        try {
            const res = await fetch(saveUrl, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({
                    type,
                    frequency: $('frequency').value || null,
                    variant: variant || null,
                    is_active: $('groupActive').checked,
                    questions: filled.map(r => ({ question_text: r.text.trim(), is_active: r.active })),
                })
            });
            const data = await res.json().catch(() => ({}));
            if (!res.ok) throw new Error(data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'Something went wrong.'));

            toast(data.message);
            btn.innerHTML = '<i data-lucide="check"></i> <span>Saved!</span>'; icons();
            setTimeout(() => location.href = data.redirect, 1200);
        } catch (err) {
            toast(err.message, 'error');
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="save"></i> <span>Save equipment</span>'; icons();
        }
    };

    renderVariants();
    renderRows();
</script>
</body>
</html>