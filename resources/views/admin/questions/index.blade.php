<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checklist Studio | TapApp</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --bg: #f3f5fa;
            --surface: #ffffff;
            --text: #172036;
            --muted: #6e7a91;
            --primary: #f47721;
            --primary-dark: #d85f0d;
            --primary-light: #fff0e5;
            --navy: #1b2740;
            --border: #e6e9f1;
            --green: #16865c;
            --red: #cf4141;
            --radius: 18px;
            --shadow: 0 6px 26px #26334d0a;
            --ease: cubic-bezier(.22, .8, .3, 1);
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            margin: 0;
            background:
                radial-gradient(900px 400px at 85% -5%, #f4772112, transparent 60%),
                radial-gradient(700px 400px at -5% 20%, #34476712, transparent 60%),
                var(--bg);
            color: var(--text);
            font-family: "Plus Jakarta Sans", Inter, "Segoe UI", sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        button, input, select, textarea { font: inherit; }
        button { cursor: pointer; }
        button:disabled { opacity: .55; cursor: wait; }
        a { text-decoration: none; }
        :focus-visible { outline: 3px solid #f4772155; outline-offset: 2px; }

        .app { width: min(1450px, 100%); margin: auto; padding: 30px; }

        /* ---------- Hero ---------- */
        .hero {
            position: relative;
            overflow: hidden;
            background: linear-gradient(125deg, #1a2439, #31446a 60%, #3c4f78);
            background-size: 200% 200%;
            animation: rise .55s var(--ease) both, drift 14s ease-in-out infinite;
            color: #fff;
            padding: 34px;
            border-radius: 26px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            box-shadow: 0 20px 50px #1a243926;
        }

        .hero::before, .hero::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .hero::before {
            width: 320px; height: 320px; right: -90px; top: -150px;
            border: 42px solid #ffffff0a;
            animation: float 9s ease-in-out infinite;
        }

        .hero::after {
            width: 180px; height: 180px; left: 38%; bottom: -110px;
            background: radial-gradient(circle, #f4772144, transparent 70%);
            animation: float 7s ease-in-out infinite reverse;
        }

        .hero > * { position: relative; z-index: 1; }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 999px;
            background: #ffffff14;
            backdrop-filter: blur(6px);
            color: #ffc59b;
            font-size: 12px;
            font-weight: 700;
        }

        .eyebrow i { width: 14px; height: 14px; }

        h1 { margin: 14px 0 8px; font-size: clamp(26px, 4vw, 38px); letter-spacing: -.5px; }
        .sub { color: #ced8ec; font-size: 14px; line-height: 1.7; max-width: 520px; }

        /* ---------- Buttons ---------- */
        .button {
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 0;
            padding: 11px 17px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            transition: transform .2s var(--ease), box-shadow .2s, background .2s;
        }

        .button i { width: 17px; height: 17px; }
        .button:hover { transform: translateY(-2px); box-shadow: 0 9px 20px #19253b1f; }
        .button:active { transform: translateY(0) scale(.97); }

        .primary { background: linear-gradient(135deg, var(--primary), #ff9147); color: #fff; }
        .primary:hover { background: linear-gradient(135deg, var(--primary-dark), var(--primary)); }
        .light { background: #fff; color: var(--text); }
        .soft { background: var(--primary-light); color: #b95616; }
        .danger { background: #fff0f0; color: var(--red); }
        .success { background: #e8f8ef; color: var(--green); }

        .ripple {
            position: absolute;
            border-radius: 50%;
            background: #ffffff66;
            transform: scale(0);
            animation: ripple .6s ease-out;
            pointer-events: none;
        }

        /* ---------- Navigation cards ---------- */
        .nav-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin: 22px 0;
        }

        .navigation-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: linear-gradient(120deg, #fff, #fff8f2);
            box-shadow: var(--shadow);
            transition: transform .3s var(--ease), box-shadow .3s, border-color .3s;
            animation: rise .5s var(--ease) both;
        }

        .navigation-card:nth-child(2) { animation-delay: .08s; }

        .navigation-card:hover {
            transform: translateY(-4px);
            border-color: #f4772140;
            box-shadow: 0 16px 34px #26334d17;
        }

        .navigation-icon {
            width: 52px; height: 52px; flex-shrink: 0;
            display: grid; place-items: center;
            border-radius: 15px;
            background: var(--primary-light);
            color: var(--primary);
            transition: transform .3s var(--ease);
        }

        .navigation-card:hover .navigation-icon { transform: rotate(-8deg) scale(1.08); }
        .navigation-content { flex: 1; min-width: 0; }
        .navigation-content h2 { margin: 0 0 5px; font-size: 16px; }
        .navigation-content p { margin: 0; color: var(--muted); font-size: 12.5px; line-height: 1.65; }

        /* ---------- Stats ---------- */
        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 22px;
        }

        .stat {
            position: relative;
            overflow: hidden;
            padding: 20px 20px 20px 24px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            animation: rise .5s var(--ease) both;
            transition: transform .25s var(--ease);
        }

        .stat:hover { transform: translateY(-3px); }
        .stat::before {
            content: "";
            position: absolute; left: 0; top: 0; bottom: 0; width: 5px;
            background: var(--accent, var(--primary));
        }
        .stat:nth-child(1) { --accent: #3d5a98; animation-delay: .05s; }
        .stat:nth-child(2) { --accent: var(--green); animation-delay: .12s; }
        .stat:nth-child(3) { --accent: var(--red); animation-delay: .19s; }

        .stat-label { font-size: 13px; color: var(--muted); font-weight: 600; }
        .stat-value { font-size: 32px; font-weight: 800; margin-top: 6px; letter-spacing: -.5px; }

        /* ---------- Panels ---------- */
        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 22px;
            margin-bottom: 20px;
            animation: rise .5s var(--ease) both;
        }

        .panel-head {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 12px; margin-bottom: 17px;
        }

        h2 { font-size: 18px; margin: 0; }
        .muted { color: var(--muted); font-size: 13px; }

        /* ---------- Type chips ---------- */
        .chips { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px; }

        .chip {
            border: 1px solid var(--border);
            background: #f7f8fc;
            color: #55627a;
            padding: 7px 14px;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 700;
            transition: all .2s var(--ease);
            animation: pop .35s var(--ease) both;
        }

        .chip:hover { transform: translateY(-2px); border-color: #f4772155; color: #b95616; }
        .chip.active {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
            box-shadow: 0 6px 16px #f4772140;
        }

        /* ---------- Filters ---------- */
        .filters {
            display: grid;
            grid-template-columns: 2fr repeat(4, minmax(120px, 1fr));
            gap: 12px;
        }

        .field { display: flex; flex-direction: column; gap: 7px; }
        .field label { font-size: 12px; color: var(--muted); font-weight: 700; }

        .control {
            width: 100%; min-width: 0;
            border: 1px solid var(--border);
            background: #fbfcfe;
            color: var(--text);
            padding: 11px 12px;
            border-radius: 11px;
            outline: none;
            transition: border .2s, box-shadow .2s, background .2s;
        }

        .control:hover { border-color: #d3d9e6; }
        .control:focus { background: #fff; border-color: var(--primary); box-shadow: 0 0 0 4px #f477211a; }
        textarea.control { resize: vertical; line-height: 1.65; }

        .search-wrap { position: relative; }
        .search-wrap i {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            width: 17px; height: 17px; color: var(--muted); pointer-events: none;
        }
        .search-wrap .control { padding-left: 38px; }

        /* ---------- Question list ---------- */
        .toolbar {
            display: flex; align-items: center; justify-content: space-between;
            gap: 12px; flex-wrap: wrap; margin-bottom: 15px;
        }

        .question-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            transition: opacity .2s;
        }

        .question-list.loading { opacity: .45; pointer-events: none; }

        .question-card {
            position: relative;
            padding: 18px 18px 18px 22px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: transform .3s var(--ease), box-shadow .3s, opacity .3s;
            animation: rise .4s var(--ease) both;
        }

        .question-card::before {
            content: "";
            position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
            background: var(--green);
            transition: width .25s var(--ease);
        }

        .question-card.inactive::before { background: var(--red); }
        .question-card:hover { transform: translateY(-4px); box-shadow: 0 16px 32px #26334d14; }
        .question-card:hover::before { width: 7px; }
        .question-card.inactive { opacity: .7; }
        .question-card.removing { animation: out .3s var(--ease) forwards; }

        .question-top { display: flex; justify-content: space-between; gap: 12px; align-items: center; }
        .number { color: var(--primary); font-weight: 800; font-size: 12px; }

        .question-text {
            font-weight: 650; line-height: 1.65; white-space: pre-wrap;
            overflow-wrap: anywhere; margin: 10px 0 16px;
        }

        .tags { display: flex; flex-wrap: wrap; gap: 6px; }

        .tag {
            background: #f1f3f8; color: #56647c;
            padding: 5px 10px; border-radius: 8px;
            font-size: 11px; font-weight: 750;
        }

        .tag.orange { background: var(--primary-light); color: #b95616; }
        .tag.green { background: #e8f8ef; color: var(--green); }
        .tag.red { background: #fff0f0; color: var(--red); }

        .actions { display: flex; gap: 7px; flex-wrap: wrap; margin-top: 14px; }
        .small { font-size: 12px; padding: 8px 12px; border-radius: 10px; }
        .small i { width: 14px; height: 14px; }

        .empty {
            grid-column: 1 / -1;
            padding: 50px 20px; text-align: center;
            border: 1.5px dashed #d6dce7; border-radius: 16px;
            color: var(--muted);
        }

        .empty i { width: 36px; height: 36px; color: var(--primary); margin-bottom: 10px; animation: float 3s ease-in-out infinite; }
        .empty strong { display: block; margin-bottom: 6px; color: var(--text); }

        /* ---------- Modals ---------- */
        .modal {
            position: fixed; inset: 0;
            background: #12192d8c;
            backdrop-filter: blur(6px);
            z-index: 50;
            display: none; align-items: center; justify-content: center;
            padding: 18px;
        }

        .modal.open { display: flex; animation: fade .2s ease; }

        .modal-box {
            width: min(660px, 100%);
            max-height: 92vh; overflow: auto;
            background: #fff;
            border-radius: 24px;
            padding: 26px;
            box-shadow: 0 30px 90px #0003;
            animation: modalIn .35s var(--ease);
        }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin: 18px 0; }
        .full { grid-column: 1 / -1; }
        .check-row { display: flex; align-items: center; gap: 9px; font-size: 13px; }
        .check-row input { accent-color: var(--primary); width: 17px; height: 17px; }

        /* ---------- Toasts ---------- */
        .toasts {
            position: fixed; top: 20px; right: 20px; z-index: 100;
            display: flex; flex-direction: column; gap: 10px;
            width: min(380px, calc(100% - 40px));
        }

        .toast {
            display: flex; align-items: flex-start; gap: 11px;
            padding: 14px 16px;
            border-radius: 14px;
            background: #fff;
            border: 1px solid var(--border);
            box-shadow: 0 14px 40px #1a243926;
            font-size: 13.5px; line-height: 1.5; font-weight: 600;
            animation: toastIn .4s var(--ease);
            position: relative; overflow: hidden;
        }

        .toast i { width: 20px; height: 20px; flex-shrink: 0; }
        .toast.ok i { color: var(--green); }
        .toast.error i { color: var(--red); }
        .toast.leaving { animation: toastOut .3s var(--ease) forwards; }
        .toast::after {
            content: ""; position: absolute; left: 0; bottom: 0; height: 3px; width: 100%;
            background: var(--green); transform-origin: left;
            animation: timer 4.5s linear forwards;
        }
        .toast.error::after { background: var(--red); }

        /* ---------- Keyframes ---------- */
        @keyframes rise { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
        @keyframes fade { from { opacity: 0; } to { opacity: 1; } }
        @keyframes pop { from { opacity: 0; transform: scale(.8); } to { opacity: 1; transform: scale(1); } }
        @keyframes out { to { opacity: 0; transform: scale(.92) translateY(8px); } }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-14px); } }
        @keyframes drift { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
        @keyframes modalIn { from { opacity: 0; transform: translateY(24px) scale(.96); } to { opacity: 1; transform: none; } }
        @keyframes toastIn { from { opacity: 0; transform: translateX(40px); } to { opacity: 1; transform: none; } }
        @keyframes toastOut { to { opacity: 0; transform: translateX(40px); } }
        @keyframes timer { to { transform: scaleX(0); } }
        @keyframes ripple { to { transform: scale(3.2); opacity: 0; } }
        @keyframes flash { 0% { color: var(--primary); transform: scale(1.12); } 100% { color: inherit; transform: scale(1); } }
        .stat-value.flash { animation: flash .5s var(--ease); }

        /* ---------- Responsive ---------- */
        @media (max-width: 1000px) {
            .filters { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .question-list, .nav-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 650px) {
            .navigation-card { align-items: flex-start; flex-direction: column; }
            .navigation-card .button { width: 100%; }
        }

        @media (max-width: 600px) {
            .app { padding: 13px; }
            .hero { padding: 22px; align-items: flex-start; flex-direction: column; }
            .stats { gap: 8px; }
            .stat { padding: 13px 13px 13px 17px; }
            .stat-value { font-size: 24px; }
            .stat-label { font-size: 11px; }
            .panel { padding: 15px; }
            .filters, .form-grid { grid-template-columns: 1fr; }
            .full { grid-column: auto; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>
<body>
<div class="toasts" id="toasts" aria-live="polite"></div>

<div class="app">
    <header class="hero">
        <div>
            <div class="eyebrow"><i data-lucide="shield-check"></i> TapApp · Administration</div>
            <h1>Checklist Studio</h1>
            <div class="sub">Manage, organize and publish your maintenance checklist questions.</div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <button class="button light" onclick="openBulk()"><i data-lucide="clipboard-paste"></i> Bulk paste</button>
            <button class="button primary" onclick="openCreate()"><i data-lucide="plus"></i> New question</button>
        </div>
    </header>

    <div class="nav-grid">
        <section class="navigation-card">
            <div class="navigation-icon"><i data-lucide="file-plus-2"></i></div>
            <div class="navigation-content">
                <h2>Create PPM questions</h2>
                <p>Add questions, select their equipment type and maintenance frequency, and configure HV-specific questions.</p>
            </div>
            <a href="{{ route('admin.checklist-questions.create') }}" class="button primary">
                <i data-lucide="plus-circle"></i> Create question <i data-lucide="arrow-up-right"></i>
            </a>
        </section>

        <section class="navigation-card">
            <div class="navigation-icon"><i data-lucide="factory"></i></div>
            <div class="navigation-content">
                <h2>Create new equipment</h2>
                <p>Add a new equipment type with its frequency and variant, then save its questions to the database.</p>
            </div>
            <a href="{{ route('admin.checklist-questions.create.equipment') }}" class="button primary">
                <i data-lucide="plus-circle"></i> New equipment <i data-lucide="arrow-up-right"></i>
            </a>
        </section>
    </div>

    <div class="stats">
        <div class="stat">
            <div class="stat-label">Total questions</div>
            <div class="stat-value" id="totalStat" data-value="{{ $stats['total'] }}">{{ $stats['total'] }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">Active questions</div>
            <div class="stat-value" id="activeStat" data-value="{{ $stats['active'] }}">{{ $stats['active'] }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">Hidden questions</div>
            <div class="stat-value" id="hiddenStat" data-value="{{ $stats['hidden'] }}">{{ $stats['hidden'] }}</div>
        </div>
    </div>

    <section class="panel">
        <div class="panel-head">
            <div>
                <h2>Find questions</h2>
                <div class="muted" style="margin-top:5px">Filters update the list automatically.</div>
            </div>
            <button class="button soft" onclick="resetFilters()"><i data-lucide="rotate-ccw"></i> Reset filters</button>
        </div>

        <div class="chips" id="typeChips"></div>

        <div class="filters">
            <div class="field">
                <label for="search">Search text</label>
                <div class="search-wrap">
                    <i data-lucide="search"></i>
                    <input class="control" id="search" placeholder="Search questions..." autocomplete="off">
                </div>
            </div>
            <div class="field">
                <label for="filterType">Type</label>
                <select class="control" id="filterType">
                    <option value="">All types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="filterFrequency">Frequency</label>
                <select class="control" id="filterFrequency">
                    <option value="">All frequencies</option>
                    <option value="1">Frequency 1</option>
                    <option value="4">Frequency 4</option>
                </select>
            </div>
            <div class="field">
                <label for="filterVariant">Variant</label>
                <select class="control" id="filterVariant">
                    <option value="">All variants</option>
                    <option value="null">No variant (null)</option>
                    @foreach($variants as $variant)
                        <option value="{{ $variant }}">{{ $variant }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="filterActive">Visibility</label>
                <select class="control" id="filterActive">
                    <option value="">All statuses</option>
                    <option value="1">Active</option>
                    <option value="0">Hidden</option>
                </select>
            </div>
        </div>
    </section>

    <section class="panel">
        <div class="toolbar">
            <div>
                <h2>Question library</h2>
                <div class="muted" id="resultCount" style="margin-top:5px">{{ $questions->count() }} question(s)</div>
            </div>
            <button class="button soft" onclick="copyVisible()"><i data-lucide="copy"></i> Copy visible questions</button>
        </div>

        <div class="question-list" id="questionList">
            @forelse($questions as $question)
                <article class="question-card {{ $question->is_active ? '' : 'inactive' }}"
                         style="animation-delay:{{ min($loop->index * 25, 250) }}ms">
                    <div class="question-top">
                        <span class="number">#{{ $question->order }}</span>
                        <span class="tag {{ $question->is_active ? 'green' : 'red' }}">
                            {{ $question->is_active ? 'Active' : 'Hidden' }}
                        </span>
                    </div>
                    <div class="question-text">{{ $question->question_text }}</div>
                    <div class="tags">
                        <span class="tag orange">{{ $question->type }}</span>
                        <span class="tag">Frequency {{ $question->frequency ?? '—' }}</span>
                        <span class="tag">{{ $question->variant ?: 'null' }}</span>
                    </div>
                    <div class="actions">
                        <button class="button soft small" onclick="editQuestion({{ $question->id }})"><i data-lucide="pencil"></i> Edit</button>
                        <button class="button {{ $question->is_active ? 'light' : 'success' }} small"
                                onclick="toggleQuestion({{ $question->id }})">
                            <i data-lucide="{{ $question->is_active ? 'eye-off' : 'eye' }}"></i>
                            {{ $question->is_active ? 'Hide' : 'Activate' }}
                        </button>
                        <button class="button danger small" onclick="deleteQuestion({{ $question->id }}, this)"><i data-lucide="trash-2"></i> Delete</button>
                    </div>
                </article>
            @empty
                <div class="empty">
                    <i data-lucide="clipboard-list"></i>
                    <strong>No questions yet</strong>
                    Create your first question or paste a list.
                </div>
            @endforelse
        </div>
    </section>
</div>

<!-- Create / edit modal -->
<div class="modal" id="editorModal">
    <div class="modal-box">
        <div class="panel-head">
            <div>
                <h2 id="editorTitle">Create question</h2>
                <div class="muted" style="margin-top:5px">Configure the question and its checklist group.</div>
            </div>
            <button class="button light" onclick="closeModal('editorModal')" aria-label="Close"><i data-lucide="x"></i></button>
        </div>

        <form id="questionForm">
            <div class="form-grid">
                <div class="field">
                    <label for="qType">Type</label>
                    <select class="control" id="qType" required>
                        @foreach($types as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="qFrequency">Frequency</label>
                    <select class="control" id="qFrequency">
                        <option value="">Null</option>
                        <option value="1">Frequency 1</option>
                        <option value="4">Frequency 4</option>
                    </select>
                </div>
                <div class="field">
                    <label for="qVariant">Variant</label>
                    <select class="control" id="qVariant">
                        <option value="">null</option>
                        @foreach($variants as $variant)
                            <option value="{{ $variant }}">{{ $variant }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="qOrder">Display order</label>
                    <input class="control" type="number" min="0" id="qOrder" placeholder="Auto">
                </div>
                <div class="field full">
                    <label for="qText">Question text</label>
                    <textarea class="control" id="qText" rows="4" maxlength="1000" required
                              placeholder="Enter the checklist question..."></textarea>
                </div>
                <div class="full check-row">
                    <input type="checkbox" id="qActive" checked>
                    <label for="qActive">Question is active and visible</label>
                </div>
            </div>
            <div class="actions">
                <button class="button primary" id="saveQuestion" type="submit">Save question</button>
                <button class="button light" type="button" onclick="closeModal('editorModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Bulk paste modal -->
<div class="modal" id="bulkModal">
    <div class="modal-box">
        <div class="panel-head">
            <div>
                <h2>Bulk question editor</h2>
                <div class="muted" style="margin-top:5px">Paste one question per line from Excel, Word or a text file.</div>
            </div>
            <button class="button light" onclick="closeModal('bulkModal')" aria-label="Close"><i data-lucide="x"></i></button>
        </div>

        <form id="bulkForm">
            <div class="form-grid">
                <div class="field">
                    <label for="bulkType">Type</label>
                    <select class="control" id="bulkType" required>
                        @foreach($types as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="bulkFrequency">Frequency</label>
                    <select class="control" id="bulkFrequency">
                        <option value="">Null</option>
                        <option value="1">Frequency 1</option>
                        <option value="4">Frequency 4</option>
                    </select>
                </div>
                <div class="field">
                    <label for="bulkVariant">Variant</label>
                    <select class="control" id="bulkVariant">
                        <option value="">null</option>
                        @foreach($variants as $variant)
                            <option value="{{ $variant }}">{{ $variant }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="bulkMode">Import mode</label>
                    <select class="control" id="bulkMode">
                        <option value="append">Append questions</option>
                        <option value="replace">Replace this group</option>
                    </select>
                </div>
                <div class="field full">
                    <label for="bulkText">Questions — one per line</label>
                    <textarea class="control" id="bulkText" rows="9" required
                              placeholder="Inspect the equipment condition&#10;Check all safety labels&#10;Verify the connections"></textarea>
                </div>
                <div class="full">
                    <div class="muted">
                        Replace affects only the selected type, frequency and variant.
                        Existing questions with saved answers cannot be replaced.
                    </div>
                </div>
                <div class="full check-row">
                    <input type="checkbox" id="bulkActive" checked>
                    <label for="bulkActive">Import questions as active</label>
                </div>
            </div>
            <div class="actions">
                <button class="button primary" id="bulkSubmit" type="submit">Import questions</button>
                <button class="button light" type="button" onclick="closeModal('bulkModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    const baseUrl = @json(url('/checklist-questions'));
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    let questions = @json($questions);
    let knownTypes = @json($types);
    let editingId = null;
    let filterTimer = null;

    const $ = id => document.getElementById(id);
    const icons = () => window.lucide && lucide.createIcons();

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, char => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
        })[char]);
    }

    async function api(url, method = 'GET', data = null) {
        const options = {
            method,
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }
        };

        if (data !== null) {
            options.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(data);
        }

        const response = await fetch(url, options);
        const result = await response.json().catch(() => ({}));

        if (!response.ok) {
            let message = result.message || 'Something went wrong.';
            if (result.errors) message = Object.values(result.errors).flat().join(' ');
            throw new Error(message);
        }

        return result;
    }

    /* ---------- Toasts ---------- */
    function notify(message, type = 'ok') {
        const toast = document.createElement('div');
        toast.className = 'toast ' + (type === 'error' ? 'error' : 'ok');
        toast.innerHTML = `<i data-lucide="${type === 'error' ? 'alert-circle' : 'check-circle-2'}"></i>
                           <span>${escapeHtml(message)}</span>`;
        $('toasts').appendChild(toast);
        icons();

        const remove = () => {
            toast.classList.add('leaving');
            setTimeout(() => toast.remove(), 300);
        };

        setTimeout(remove, 4500);
        toast.addEventListener('click', remove);
    }

    /* ---------- Animated numbers ---------- */
    function setStat(id, value) {
        const el = $(id);
        const from = Number(el.dataset.value || 0);
        const to = Number(value);
        el.dataset.value = to;

        if (from === to) { el.textContent = to; return; }

        const start = performance.now();
        const duration = 600;

        const tick = now => {
            const t = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - t, 3);
            el.textContent = Math.round(from + (to - from) * eased);
            if (t < 1) requestAnimationFrame(tick);
        };

        requestAnimationFrame(tick);
        el.classList.remove('flash');
        void el.offsetWidth;
        el.classList.add('flash');
    }

    /* ---------- Modals ---------- */
    function closeModal(id) { $(id).classList.remove('open'); }

    function openCreate() {
        editingId = null;
        $('questionForm').reset();
        $('qActive').checked = true;
        $('qVariant').value = '';
        // pre-select the type currently filtered
        if ($('filterType').value) $('qType').value = $('filterType').value;
        $('editorTitle').textContent = 'Create question';
        $('saveQuestion').textContent = 'Save question';
        $('editorModal').classList.add('open');
        setTimeout(() => $('qText').focus(), 80);
    }

    function openBulk() {
        $('bulkForm').reset();
        $('bulkVariant').value = '';
        $('bulkMode').value = 'append';
        $('bulkActive').checked = true;
        if ($('filterType').value) $('bulkType').value = $('filterType').value;
        $('bulkModal').classList.add('open');
    }

    function editQuestion(id) {
        const q = questions.find(item => Number(item.id) === Number(id));

        if (!q) {
            notify('Question not found. Refresh the list.', 'error');
            return;
        }

        editingId = id;
        ensureOption('qType', q.type);
        ensureOption('qVariant', q.variant);

        $('qType').value = q.type;
        $('qFrequency').value = q.frequency ?? '';
        $('qVariant').value = q.variant || '';
        $('qOrder').value = q.order ?? '';
        $('qText').value = q.question_text;
        $('qActive').checked = Boolean(q.is_active);

        $('editorTitle').textContent = 'Edit question';
        $('saveQuestion').textContent = 'Save changes';
        $('editorModal').classList.add('open');
    }

    function ensureOption(selectId, value) {
        if (!value) return;
        const select = $(selectId);
        if (![...select.options].some(o => o.value === value)) {
            select.add(new Option(value, value));
        }
    }

    /* ---------- Dynamic selects (types + variants) ---------- */
    function fillSelect(id, values, firstOptions = []) {
        const select = $(id);
        const current = select.value;

        select.innerHTML =
            firstOptions.map(o => `<option value="${escapeHtml(o.value)}">${escapeHtml(o.label)}</option>`).join('') +
            values.map(v => `<option value="${escapeHtml(v)}">${escapeHtml(v)}</option>`).join('');

        if ([...select.options].some(o => o.value === current)) select.value = current;
    }

    function refreshSelects(types = [], variants = []) {
        knownTypes = types;

        fillSelect('filterType', types, [{ value: '', label: 'All types' }]);
        fillSelect('qType', types);
        fillSelect('bulkType', types);

        fillSelect('filterVariant', variants, [
            { value: '', label: 'All variants' },
            { value: 'null', label: 'No variant (null)' },
        ]);
        fillSelect('qVariant', variants, [{ value: '', label: 'null' }]);
        fillSelect('bulkVariant', variants, [{ value: '', label: 'null' }]);

        renderChips();
    }

    function renderChips() {
        const active = $('filterType').value;
        const items = [{ value: '', label: 'All' }, ...knownTypes.map(t => ({ value: t, label: t }))];

        $('typeChips').innerHTML = items.map((item, i) => `
            <button type="button" class="chip ${item.value === active ? 'active' : ''}"
                    style="animation-delay:${i * 30}ms"
                    onclick="pickType('${escapeHtml(item.value)}')">${escapeHtml(item.label)}</button>
        `).join('');
    }

    function pickType(value) {
        $('filterType').value = value;
        renderChips();
        loadQuestions();
    }

    /* ---------- Rendering ---------- */
    function renderQuestions() {
        const list = $('questionList');

        $('resultCount').textContent = `${questions.length} question(s)`;

        if (!questions.length) {
            list.innerHTML = `
                <div class="empty">
                    <i data-lucide="search-x"></i>
                    <strong>No matching questions</strong>
                    Try changing the filters or create a new question.
                </div>`;
            icons();
            return;
        }

        list.innerHTML = questions.map((q, index) => `
            <article class="question-card ${q.is_active ? '' : 'inactive'}"
                     style="animation-delay:${Math.min(index * 25, 250)}ms">
                <div class="question-top">
                    <span class="number">#${escapeHtml(q.order)}</span>
                    <span class="tag ${q.is_active ? 'green' : 'red'}">${q.is_active ? 'Active' : 'Hidden'}</span>
                </div>
                <div class="question-text">${escapeHtml(q.question_text)}</div>
                <div class="tags">
                    <span class="tag orange">${escapeHtml(q.type)}</span>
                    <span class="tag">Frequency ${escapeHtml(q.frequency ?? '—')}</span>
                    <span class="tag">${escapeHtml(q.variant || 'null')}</span>
                </div>
                <div class="actions">
                    <button class="button soft small" onclick="editQuestion(${Number(q.id)})"><i data-lucide="pencil"></i> Edit</button>
                    <button class="button ${q.is_active ? 'light' : 'success'} small" onclick="toggleQuestion(${Number(q.id)})">
                        <i data-lucide="${q.is_active ? 'eye-off' : 'eye'}"></i> ${q.is_active ? 'Hide' : 'Activate'}
                    </button>
                    <button class="button danger small" onclick="deleteQuestion(${Number(q.id)}, this)"><i data-lucide="trash-2"></i> Delete</button>
                </div>
            </article>
        `).join('');

        icons();
    }

    async function loadQuestions() {
        const params = new URLSearchParams();

        if ($('search').value.trim()) params.set('search', $('search').value.trim());
        if ($('filterType').value) params.set('type', $('filterType').value);
        if ($('filterFrequency').value) params.set('frequency', $('filterFrequency').value);
        if ($('filterVariant').value) params.set('variant', $('filterVariant').value);
        if ($('filterActive').value !== '') params.set('is_active', $('filterActive').value);

        $('questionList').classList.add('loading');

        try {
            const result = await api(`${baseUrl}?${params.toString()}`);

            questions = result.questions;
            refreshSelects(result.types, result.variants);

            setStat('totalStat', result.stats.total);
            setStat('activeStat', result.stats.active);
            setStat('hiddenStat', result.stats.hidden);

            renderQuestions();
        } catch (error) {
            notify(error.message, 'error');
        } finally {
            $('questionList').classList.remove('loading');
        }
    }

    function scheduleFilter() {
        clearTimeout(filterTimer);
        filterTimer = setTimeout(loadQuestions, 220);
    }

    function resetFilters() {
        ['search', 'filterType', 'filterFrequency', 'filterVariant', 'filterActive'].forEach(id => $(id).value = '');
        renderChips();
        loadQuestions();
    }

    function copyVisible() {
        const text = questions.map(q => q.question_text).join('\n');

        if (!text) {
            notify('There are no questions to copy.', 'error');
            return;
        }

        const area = document.createElement('textarea');
        area.value = text;
        area.style.position = 'fixed';
        area.style.opacity = '0';
        document.body.appendChild(area);
        area.select();

        try {
            const copied = document.execCommand('copy');
            notify(copied ? 'Visible questions copied.' : 'Copy was blocked by your browser.',
                copied ? 'ok' : 'error');
        } finally {
            area.remove();
        }
    }

    /* ---------- Actions ---------- */
    $('questionForm').addEventListener('submit', async event => {
        event.preventDefault();

        const button = $('saveQuestion');
        button.disabled = true;

        const data = {
            type: $('qType').value,
            frequency: $('qFrequency').value || null,
            variant: $('qVariant').value || null,
            order: $('qOrder').value === '' ? null : Number($('qOrder').value),
            question_text: $('qText').value.trim(),
            is_active: $('qActive').checked
        };

        try {
            const url = editingId ? `${baseUrl}/${editingId}` : baseUrl;
            const method = editingId ? 'PUT' : 'POST';
            const result = await api(url, method, data);

            closeModal('editorModal');
            notify(result.message);
            await loadQuestions();
        } catch (error) {
            notify(error.message, 'error');
        } finally {
            button.disabled = false;
        }
    });

    async function toggleQuestion(id) {
        try {
            const result = await api(`${baseUrl}/${id}/toggle`, 'PATCH');
            notify(result.message);
            await loadQuestions();
        } catch (error) {
            notify(error.message, 'error');
        }
    }

    async function deleteQuestion(id, btn) {
        if (!confirm('Delete this question? This cannot be undone.')) return;

        try {
            const result = await api(`${baseUrl}/${id}`, 'DELETE');
            const card = btn && btn.closest('.question-card');
            if (card) {
                card.classList.add('removing');
                await new Promise(r => setTimeout(r, 280));
            }
            notify(result.message);
            await loadQuestions();
        } catch (error) {
            notify(error.message, 'error');
        }
    }

    $('bulkForm').addEventListener('submit', async event => {
        event.preventDefault();

        const mode = $('bulkMode').value;

        if (mode === 'replace' &&
            !confirm('Replace all questions in this exact group? This action cannot be undone.')) {
            return;
        }

        const button = $('bulkSubmit');
        button.disabled = true;

        try {
            const result = await api(`${baseUrl}/bulk`, 'POST', {
                type: $('bulkType').value,
                frequency: $('bulkFrequency').value || null,
                variant: $('bulkVariant').value || null,
                mode,
                questions_text: $('bulkText').value,
                is_active: $('bulkActive').checked
            });

            closeModal('bulkModal');
            notify(result.message);
            await loadQuestions();
        } catch (error) {
            notify(error.message, 'error');
        } finally {
            button.disabled = false;
        }
    });

    /* ---------- Wiring ---------- */
    ['filterFrequency', 'filterVariant', 'filterActive'].forEach(id => $(id).addEventListener('change', loadQuestions));
    $('filterType').addEventListener('change', () => { renderChips(); loadQuestions(); });
    $('search').addEventListener('input', scheduleFilter);

    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', event => {
            if (event.target === modal) modal.classList.remove('open');
        });
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') document.querySelectorAll('.modal.open').forEach(m => m.classList.remove('open'));
    });

    // Button ripple
    document.addEventListener('click', event => {
        const btn = event.target.closest('.button');
        if (!btn || btn.disabled) return;
        const rect = btn.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const dot = document.createElement('span');
        dot.className = 'ripple';
        dot.style.cssText = `width:${size}px;height:${size}px;left:${event.clientX - rect.left - size / 2}px;top:${event.clientY - rect.top - size / 2}px`;
        btn.appendChild(dot);
        setTimeout(() => dot.remove(), 600);
    });

    renderChips();
    icons();
</script>
</body>
</html>
