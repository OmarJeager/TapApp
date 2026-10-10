
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checklist Studio | TapApp</title>

    <style>
        :root {
            --bg: #f4f6fb;
            --surface: #fff;
            --text: #182238;
            --muted: #718096;
            --primary: #f47721;
            --primary-light: #fff0e5;
            --border: #e7eaf1;
            --green: #16865c;
            --red: #cf4141;
            --radius: 18px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Inter, "Segoe UI", sans-serif;
        }

        button, input, select, textarea { font: inherit; }
        button { cursor: pointer; }
        button:disabled { opacity: .55; cursor: wait; }

        .app {
            width: min(1450px, 100%);
            margin: auto;
            padding: 30px;
        }

        .hero {
            position: relative;
            overflow: hidden;
            background: linear-gradient(125deg, #202c43, #344767);
            color: white;
            padding: 30px;
            border-radius: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            animation: rise .5s ease both;
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 240px;
            height: 240px;
            right: -70px;
            top: -110px;
            border: 35px solid #ffffff0b;
            border-radius: 50%;
            pointer-events: none;
        }

        .eyebrow {
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #ffbd88;
            font-size: 11px;
            font-weight: 800;
        }

        h1 { margin: 9px 0; font-size: clamp(25px, 4vw, 36px); }
        .sub { color: #d4dcec; font-size: 14px; line-height: 1.6; }

        .button {
            border: 0;
            padding: 11px 16px;
            border-radius: 11px;
            font-weight: 700;
            transition: transform .2s, box-shadow .2s, background .2s;
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 18px #19253b18;
        }

        .primary { background: var(--primary); color: white; }
        .light { background: white; color: var(--text); }
        .soft { background: var(--primary-light); color: #b95616; }
        .danger { background: #fff0f0; color: var(--red); }
        .success { background: #e8f8ef; color: var(--green); }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin: 22px 0;
        }

        .stat, .panel, .question-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 5px 24px #26334d05;
        }

        .stat { padding: 20px; }
        .stat-label { font-size: 13px; color: var(--muted); }
        .stat-value { font-size: 30px; font-weight: 800; margin-top: 8px; }

        .panel { padding: 22px; margin-bottom: 20px; }
        .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 17px;
        }

        h2 { font-size: 18px; margin: 0; }
        .muted { color: var(--muted); font-size: 13px; }

        .filters {
            display: grid;
            grid-template-columns: 2fr repeat(4, minmax(120px, 1fr));
            gap: 12px;
        }

        .field { display: flex; flex-direction: column; gap: 7px; }
        .field label {
            font-size: 12px;
            color: var(--muted);
            font-weight: 700;
        }

        .control {
            width: 100%;
            min-width: 0;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--text);
            padding: 11px 12px;
            border-radius: 10px;
            outline: none;
            transition: border .2s, box-shadow .2s;
        }

        .control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px #f477211a;
        }

        textarea.control { resize: vertical; line-height: 1.65; }

        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .question-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 13px;
        }

        .question-card {
            padding: 17px;
            transition: transform .2s, box-shadow .2s, opacity .2s;
            animation: rise .32s ease both;
        }

        .question-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px #26334d0d;
        }

        .question-card.inactive { opacity: .68; }

        .question-top {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
        }

        .number {
            color: var(--primary);
            font-weight: 800;
            font-size: 12px;
        }

        .question-text {
            font-weight: 650;
            line-height: 1.65;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
            margin: 9px 0 16px;
        }

        .tags { display: flex; flex-wrap: wrap; gap: 6px; }

        .tag {
            background: #f1f3f8;
            color: #56647c;
            padding: 5px 9px;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 750;
        }

        .tag.orange { background: var(--primary-light); color: #b95616; }
        .tag.green { background: #e8f8ef; color: var(--green); }
        .tag.red { background: #fff0f0; color: var(--red); }

        .actions {
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
            margin-top: 14px;
        }

        .small { font-size: 12px; padding: 8px 10px; }

        .modal {
            position: fixed;
            inset: 0;
            background: #15203699;
            backdrop-filter: blur(5px);
            z-index: 50;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
        }

        .modal.open { display: flex; animation: fade .2s ease; }

        .modal-box {
            width: min(650px, 100%);
            max-height: 92vh;
            overflow: auto;
            background: white;
            border-radius: 22px;
            padding: 24px;
            box-shadow: 0 25px 80px #0002;
            animation: rise .25s ease;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 18px 0;
        }

        .full { grid-column: 1 / -1; }

        .notice {
            display: none;
            padding: 13px 16px;
            border-radius: 12px;
            margin-bottom: 15px;
            font-size: 13px;
            line-height: 1.5;
        }

        .notice.show { display: block; animation: rise .25s ease; }
        .notice.ok { background: #e8f8ef; color: #126f4a; }
        .notice.error { background: #fff0f0; color: #a72e2e; }

        .empty {
            grid-column: 1 / -1;
            padding: 45px 20px;
            text-align: center;
            border: 1px dashed #d6dce7;
            border-radius: 15px;
            color: var(--muted);
        }

        .empty strong { display: block; margin-bottom: 7px; color: var(--text); }

        .check-row { display: flex; align-items: center; gap: 9px; font-size: 13px; }

        @keyframes rise {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fade { from { opacity: 0; } to { opacity: 1; } }

        @media (max-width: 1000px) {
            .filters { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .question-list { grid-template-columns: 1fr; }
        }

        @media (max-width: 600px) {
            .app { padding: 13px; }
            .hero { padding: 22px; align-items: flex-start; flex-direction: column; }
            .stats { gap: 8px; }
            .stat { padding: 13px; }
            .stat-value { font-size: 24px; }
            .stat-label { font-size: 11px; }
            .panel { padding: 15px; }
            .filters, .form-grid { grid-template-columns: 1fr; }
            .full { grid-column: auto; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>
<body>
<div class="app">
    <header class="hero">
        <div>
            <div class="eyebrow">TapApp · Administration</div>
            <h1>Checklist Studio</h1>
            <div class="sub">Manage, organize and publish your maintenance checklist questions.</div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <button class="button light" onclick="openBulk()">⇧ Bulk paste</button>
            <button class="button primary" onclick="openCreate()">＋ New question</button>
        </div>
    </header>

    <div class="stats">
        <div class="stat">
            <div class="stat-label">Total questions</div>
            <div class="stat-value" id="totalStat">{{ $stats['total'] }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">Active questions</div>
            <div class="stat-value" id="activeStat">{{ $stats['active'] }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">Hidden questions</div>
            <div class="stat-value" id="hiddenStat">{{ $stats['hidden'] }}</div>
        </div>
    </div>

    <div id="notice" class="notice"></div>

    <section class="panel">
        <div class="panel-head">
            <div>
                <h2>Find questions</h2>
                <div class="muted" style="margin-top:5px">Filters update the list automatically.</div>
            </div>
            <button class="button soft" onclick="resetFilters()">Reset filters ↺</button>
        </div>

        <div class="filters">
            <div class="field">
                <label for="search">Search text</label>
                <input class="control" id="search" placeholder="Search questions..." autocomplete="off">
            </div>
            <div class="field">
                <label for="filterType">Type</label>
                <select class="control" id="filterType">
                    <option value="">All types</option>
                    <option value="PNL">PNL</option>
                    <option value="TRQ">TRQ</option>
                    <option value="TST">TST</option>
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
                    <option value="null">null</option>
                    <option value="HV">HV</option>
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
                <div class="muted" id="resultCount" style="margin-top:5px">
                    {{ $questions->count() }} questions
                </div>
            </div>
            <button class="button soft" onclick="copyVisible()">Copy visible questions</button>
        </div>

        <div class="question-list" id="questionList">
            @forelse($questions as $question)
                <article class="question-card {{ $question->is_active ? '' : 'inactive' }}">
                    <div class="question-top">
                        <span class="number">#{{ $question->order }}</span>
                        <span class="tag {{ $question->is_active ? 'green' : 'red' }}">
                            {{ $question->is_active ? 'ACTIVE' : 'HIDDEN' }}
                        </span>
                    </div>
                    <div class="question-text">{{ $question->question_text }}</div>
                    <div class="tags">
                        <span class="tag orange">{{ $question->type }}</span>
                        <span class="tag">Frequency {{ $question->frequency ?? '—' }}</span>
                        <span class="tag">{{ $question->variant ?: 'null' }}</span>
                    </div>
                    <div class="actions">
                        <button class="button soft small" onclick="editQuestion({{ $question->id }})">Edit</button>
                        <button class="button {{ $question->is_active ? 'light' : 'success' }} small"
                            onclick="toggleQuestion({{ $question->id }})">
                            {{ $question->is_active ? 'Hide' : 'Activate' }}
                        </button>
                        <button class="button danger small" onclick="deleteQuestion({{ $question->id }})">Delete</button>
                    </div>
                </article>
            @empty
                <div class="empty"><strong>No questions yet</strong>Create your first question or paste a list.</div>
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
            <button class="button light" onclick="closeModal('editorModal')">✕</button>
        </div>

        <form id="questionForm">
            <div class="form-grid">
                <div class="field">
                    <label for="qType">Type</label>
                    <select class="control" id="qType" required>
                        <option value="PNL">PNL</option>
                        <option value="TRQ">TRQ</option>
                        <option value="TST">TST</option>
                    </select>
                </div>
                <div class="field">
                    <label for="qFrequency">Frequency</label>
                    <select class="control" id="qFrequency">
                        <option value="">Not specified</option>
                        <option value="1">Frequency 1</option>
                        <option value="4">Frequency 4</option>
                    </select>
                </div>
                <div class="field">
                    <label for="qVariant">Variant</label>
                    <select class="control" id="qVariant">
                        <option value="null">null</option>
                        <option value="HV">HV</option>
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
            <button class="button light" onclick="closeModal('bulkModal')">✕</button>
        </div>

        <form id="bulkForm">
            <div class="form-grid">
                <div class="field">
                    <label for="bulkType">Type</label>
                    <select class="control" id="bulkType" required>
                        <option value="PNL">PNL</option>
                        <option value="TRQ">TRQ</option>
                        <option value="TST">TST</option>
                    </select>
                </div>
                <div class="field">
                    <label for="bulkFrequency">Frequency</label>
                    <select class="control" id="bulkFrequency">
                        <option value="">Not specified</option>
                        <option value="1">Frequency 1</option>
                        <option value="4">Frequency 4</option>
                    </select>
                </div>
                <div class="field">
                    <label for="bulkVariant">Variant</label>
                    <select class="control" id="bulkVariant">
                        <option value="null">null</option>
                        <option value="HV">HV</option>
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
    let editingId = null;
    let filterTimer = null;

    const $ = id => document.getElementById(id);

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, char => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        })[char]);
    }

    async function api(url, method = 'GET', data = null) {
        const options = {
            method,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf
            }
        };

        if (data !== null) {
            options.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(data);
        }

        const response = await fetch(url, options);
        const result = await response.json().catch(() => ({}));

        if (!response.ok) {
            let message = result.message || 'Something went wrong.';

            if (result.errors) {
                message = Object.values(result.errors).flat().join(' ');
            }

            throw new Error(message);
        }

        return result;
    }

    function notify(message, type = 'ok') {
        const box = $('notice');
        box.textContent = message;
        box.className = 'notice show ' + (type === 'error' ? 'error' : 'ok');

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function closeModal(id) {
        $(id).classList.remove('open');
    }

    function openCreate() {
        editingId = null;
        $('questionForm').reset();
        $('qActive').checked = true;
        $('qVariant').value = 'null';
        $('editorTitle').textContent = 'Create question';
        $('saveQuestion').textContent = 'Save question';
        $('editorModal').classList.add('open');
    }

    function openBulk() {
        $('bulkForm').reset();
        $('bulkVariant').value = 'null';
        $('bulkMode').value = 'append';
        $('bulkActive').checked = true;
        $('bulkModal').classList.add('open');
    }

    function editQuestion(id) {
        const q = questions.find(item => Number(item.id) === Number(id));

        if (!q) {
            notify('Question not found. Refresh the list.', 'error');
            return;
        }

        editingId = id;
        $('qType').value = q.type;
        $('qFrequency').value = q.frequency ?? '';
        $('qVariant').value = q.variant || 'null';
        $('qOrder').value = q.order ?? '';
        $('qText').value = q.question_text;
        $('qActive').checked = Boolean(q.is_active);

        $('editorTitle').textContent = 'Edit question';
        $('saveQuestion').textContent = 'Save changes';
        $('editorModal').classList.add('open');
    }

    function renderQuestions() {
        const list = $('questionList');

        $('resultCount').textContent = `${questions.length} question(s)`;

        if (!questions.length) {
            list.innerHTML = `
                <div class="empty">
                    <strong>No matching questions</strong>
                    Try changing the filters or create a new question.
                </div>`;
            return;
        }

        list.innerHTML = questions.map((q, index) => `
            <article class="question-card ${q.is_active ? '' : 'inactive'}"
                     style="animation-delay:${Math.min(index * 20, 200)}ms">
                <div class="question-top">
                    <span class="number">#${escapeHtml(q.order)}</span>
                    <span class="tag ${q.is_active ? 'green' : 'red'}">
                        ${q.is_active ? 'ACTIVE' : 'HIDDEN'}
                    </span>
                </div>
                <div class="question-text">${escapeHtml(q.question_text)}</div>
                <div class="tags">
                    <span class="tag orange">${escapeHtml(q.type)}</span>
                    <span class="tag">Frequency ${escapeHtml(q.frequency ?? '—')}</span>
                    <span class="tag">${escapeHtml(q.variant || 'null')}</span>
                </div>
                <div class="actions">
                    <button class="button soft small" onclick="editQuestion(${Number(q.id)})">Edit</button>
                    <button class="button ${q.is_active ? 'light' : 'success'} small"
                        onclick="toggleQuestion(${Number(q.id)})">
                        ${q.is_active ? 'Hide' : 'Activate'}
                    </button>
                    <button class="button danger small" onclick="deleteQuestion(${Number(q.id)})">Delete</button>
                </div>
            </article>
        `).join('');
    }

    async function loadQuestions() {
        const params = new URLSearchParams();

        if ($('search').value.trim()) params.set('search', $('search').value.trim());
        if ($('filterType').value) params.set('type', $('filterType').value);
        if ($('filterFrequency').value) params.set('frequency', $('filterFrequency').value);
        if ($('filterVariant').value) params.set('variant', $('filterVariant').value);
        if ($('filterActive').value !== '') params.set('is_active', $('filterActive').value);

        try {
            const result = await api(`${baseUrl}?${params.toString()}`);

            questions = result.questions;
            $('totalStat').textContent = result.stats.total;
            $('activeStat').textContent = result.stats.active;
            $('hiddenStat').textContent = result.stats.hidden;

            renderQuestions();
        } catch (error) {
            notify(error.message, 'error');
        }
    }

    function scheduleFilter() {
        clearTimeout(filterTimer);
        filterTimer = setTimeout(loadQuestions, 220);
    }

    function resetFilters() {
        $('search').value = '';
        $('filterType').value = '';
        $('filterFrequency').value = '';
        $('filterVariant').value = '';
        $('filterActive').value = '';
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

    $('questionForm').addEventListener('submit', async event => {
        event.preventDefault();

        const button = $('saveQuestion');
        button.disabled = true;

        const data = {
            type: $('qType').value,
            frequency: $('qFrequency').value || null,
            variant: $('qVariant').value,
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

    async function deleteQuestion(id) {
        if (!confirm('Delete this question? This cannot be undone.')) return;

        try {
            const result = await api(`${baseUrl}/${id}`, 'DELETE');
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
                variant: $('bulkVariant').value,
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

    ['filterType', 'filterFrequency', 'filterVariant', 'filterActive'].forEach(id => {
        $(id).addEventListener('change', loadQuestions);
    });

    $('search').addEventListener('input', scheduleFilter);

    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', event => {
            if (event.target === modal) modal.classList.remove('open');
        });
    });
</script>
</body>
</html>
