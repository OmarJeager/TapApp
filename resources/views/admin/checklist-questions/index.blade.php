
@extends('layouts.main')

@section('content')
<style>
    .cq-page {
        --cq-primary: #6558e8;
        --cq-ink: #182230;
        --cq-muted: #738096;
        --cq-border: #e7eaf2;
        --cq-surface: #ffffff;
        padding: 24px;
        color: var(--cq-ink);
        animation: cqEnter .55s ease both;
    }

    .cq-page * { box-sizing: border-box; }

    @keyframes cqEnter {
        from { opacity: 0; transform: translateY(14px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes cqFade {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .cq-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 25px;
    }

    .cq-heading h1 {
        font-size: clamp(24px, 3vw, 32px);
        font-weight: 800;
        margin: 0 0 7px;
        letter-spacing: -1px;
    }

    .cq-subtitle { color: var(--cq-muted); margin: 0; }

    .cq-btn {
        border: 1px solid transparent;
        border-radius: 11px;
        padding: 11px 16px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: transform .2s, box-shadow .2s, background .2s;
    }

    .cq-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 18px #1b25401c;
    }

    .cq-primary {
        background: var(--cq-primary);
        color: white;
    }

    .cq-secondary {
        background: white;
        color: var(--cq-ink);
        border-color: var(--cq-border);
    }

    .cq-danger {
        background: #fff0f0;
        color: #c03945;
        border-color: #ffdada;
    }

    .cq-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 23px;
    }

    .cq-stat {
        padding: 20px;
        border: 1px solid var(--cq-border);
        border-radius: 17px;
        background: var(--cq-surface);
        box-shadow: 0 5px 20px #17203a06;
        transition: transform .25s, box-shadow .25s;
    }

    .cq-stat:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px #17203a0d;
    }

    .cq-stat span {
        color: var(--cq-muted);
        font-size: 13px;
    }

    .cq-stat strong {
        display: block;
        margin-top: 9px;
        font-size: 29px;
        font-weight: 800;
    }

    .cq-panel {
        background: var(--cq-surface);
        border: 1px solid var(--cq-border);
        border-radius: 18px;
        padding: 22px;
        margin-bottom: 20px;
        box-shadow: 0 5px 20px #17203a05;
    }

    .cq-panel h2 {
        font-size: 18px;
        font-weight: 800;
        margin: 0 0 7px;
    }

    .cq-description {
        color: var(--cq-muted);
        font-size: 13px;
        margin: 0 0 20px;
    }

    .cq-form-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 15px;
    }

    .cq-field { min-width: 0; }

    .cq-field label {
        display: block;
        font-size: 12px;
        font-weight: 750;
        margin-bottom: 8px;
    }

    .cq-input, .cq-select, .cq-textarea {
        width: 100%;
        border: 1px solid #dfe4ed;
        background: #fff;
        color: var(--cq-ink);
        border-radius: 10px;
        padding: 11px 12px;
        font: inherit;
        font-size: 13px;
        transition: border-color .2s, box-shadow .2s;
    }

    .cq-input:focus, .cq-select:focus, .cq-textarea:focus {
        outline: none;
        border-color: var(--cq-primary);
        box-shadow: 0 0 0 3px #6558e81b;
    }

    .cq-textarea {
        min-height: 150px;
        resize: vertical;
        line-height: 1.7;
    }

    .cq-filter-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 12px;
        align-items: end;
    }

    .cq-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }

    .cq-toolbar h2 { margin: 0; }

    .cq-count {
        display: inline-flex;
        padding: 6px 10px;
        border-radius: 30px;
        color: #5747d8;
        background: #f0edff;
        font-size: 12px;
        font-weight: 800;
    }

    .cq-table-wrap { overflow-x: auto; }

    .cq-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 790px;
    }

    .cq-table th {
        text-align: left;
        padding: 13px 12px;
        background: #f7f8fc;
        color: #758096;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .6px;
    }

    .cq-table td {
        padding: 15px 12px;
        border-bottom: 1px solid #edf0f5;
        vertical-align: middle;
        font-size: 13px;
    }

    .cq-table tbody tr {
        animation: cqFade .35s ease both;
        transition: background .2s;
    }

    .cq-table tbody tr:hover { background: #faf9ff; }

    .cq-question {
        font-weight: 650;
        min-width: 240px;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    .cq-badge {
        display: inline-flex;
        padding: 5px 9px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 800;
        background: #f0edff;
        color: #5a49d6;
    }

    .cq-badge.hv { background: #fff1e7; color: #bd5c15; }
    .cq-badge.normal { background: #eaf5ff; color: #2466a3; }

    .cq-status {
        font-size: 11px;
        font-weight: 800;
        padding: 6px 9px;
        border-radius: 20px;
        display: inline-block;
    }

    .cq-active { color: #167448; background: #e8f8ef; }
    .cq-inactive { color: #9a4e24; background: #fff1e7; }

    .cq-actions { display: flex; flex-wrap: wrap; gap: 7px; }

    .cq-small {
        border: 1px solid var(--cq-border);
        background: white;
        padding: 7px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: .2s;
    }

    .cq-small:hover { border-color: var(--cq-primary); color: var(--cq-primary); }

    .cq-hidden { display: none !important; }

    .cq-notice {
        padding: 14px 16px;
        border-radius: 11px;
        margin-bottom: 18px;
        animation: cqEnter .3s ease;
        font-size: 13px;
    }

    .cq-success { background: #eaf8ef; color: #17663d; }
    .cq-error { background: #fff0f0; color: #a42c35; }
    .cq-warning { background: #fff7e6; color: #805517; }

    .cq-check {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 650;
        margin: 15px 0;
    }

    .cq-modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: #10172b85;
        backdrop-filter: blur(5px);
        display: grid;
        place-items: center;
        padding: 18px;
        animation: cqEnter .2s ease;
    }

    .cq-modal {
        width: min(620px, 100%);
        max-height: 90vh;
        overflow-y: auto;
        background: white;
        border-radius: 19px;
        padding: 25px;
        box-shadow: 0 25px 80px #0003;
        animation: cqEnter .25s ease;
    }

    .cq-modal-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .cq-modal-head h2 { margin: 0; font-size: 20px; }

    .cq-modal-close {
        border: 0;
        background: #f2f3f8;
        border-radius: 9px;
        width: 35px;
        height: 35px;
        font-size: 21px;
        cursor: pointer;
    }

    .cq-preview {
        padding: 12px;
        background: #f6f5ff;
        color: #5345bc;
        border-radius: 10px;
        margin: 13px 0;
        font-size: 13px;
        font-weight: 700;
    }

    .cq-footer {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 9px;
        margin-top: 20px;
    }

    .cq-error-text { color: #b42332; font-size: 12px; margin-top: 5px; }

    @media(max-width: 900px) {
        .cq-filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .cq-form-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media(max-width: 600px) {
        .cq-page { padding: 13px; }
        .cq-stats { grid-template-columns: 1fr; gap: 10px; }
        .cq-stat { padding: 15px; }
        .cq-stat strong { font-size: 24px; }
        .cq-filter-grid, .cq-form-grid { grid-template-columns: 1fr; }
        .cq-panel { padding: 15px; }
    }

    @media(prefers-reduced-motion: reduce) {
        .cq-page *, .cq-page *::before, .cq-page *::after {
            animation-duration: .01ms !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="cq-page">
    <div class="cq-heading">
        <div>
            <h1>Checklist Questions</h1>
            <p class="cq-subtitle">
                Manage, filter, create and replace your PPM checklist questions.
            </p>
        </div>

        <button type="button" class="cq-btn cq-primary"
                onclick="cqOpenCreate()">
            + Create question
        </button>
    </div>

    @if(session('success'))
        <div class="cq-notice cq-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="cq-notice cq-error">{{ session('error') }}</div>
    @endif

    @if(session('warning'))
        <div class="cq-notice cq-warning">{{ session('warning') }}</div>
    @endif

    @if($errors->any())
        <div class="cq-notice cq-error">
            <strong>Please correct the following:</strong>
            <ul style="margin-bottom:0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="cq-stats">
        <div class="cq-stat">
            <span>Total questions</span>
            <strong>{{ $totalQuestions }}</strong>
        </div>
        <div class="cq-stat">
            <span>Active questions</span>
            <strong>{{ $activeQuestions }}</strong>
        </div>
        <div class="cq-stat">
            <span>Inactive questions</span>
            <strong>{{ $inactiveQuestions }}</strong>
        </div>
    </div>

    <section class="cq-panel">
        <h2>Filter questions</h2>
        <p class="cq-description">
            Choose a type to display all its questions. Add filters to narrow the list.
        </p>

        <form method="GET" action="{{ route('admin.checklist-questions.index') }}"
              class="cq-filter-grid" id="cqFilterForm">

            <div class="cq-field">
                <label for="filterType">Question type</label>
                <select name="type" id="filterType" class="cq-select">
                    <option value="">All types</option>
                    @foreach(['PNL', 'TRQ', 'TST'] as $type)
                        <option value="{{ $type }}"
                            @selected(request('type') === $type)>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="cq-field" id="filterFrequencyWrap">
                <label for="filterFrequency">Frequency</label>
                <select name="frequency" id="filterFrequency" class="cq-select">
                    <option value="">All frequencies</option>
                    @foreach([1, 2, 3, 4, 5, 6, 12] as $frequency)
                        <option value="{{ $frequency }}"
                            @selected((string)request('frequency') === (string)$frequency)>
                            Frequency {{ $frequency }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="cq-field" id="filterVariantWrap">
                <label for="filterVariant">Variant</label>
                <select name="variant" id="filterVariant" class="cq-select">
                    <option value="">All variants</option>
                    <option value="normal" @selected(request('variant') === 'normal')>
                        Normal
                    </option>
                    <option value="HV" @selected(request('variant') === 'HV')>
                        HV
                    </option>
                </select>
            </div>

            <div class="cq-field">
                <label for="filterActive">Status</label>
                <select name="active" id="filterActive" class="cq-select">
                    <option value="">All statuses</option>
                    <option value="1" @selected(request('active') === '1')>
                        Active
                    </option>
                    <option value="0" @selected(request('active') === '0')>
                        Inactive
                    </option>
                </select>
            </div>

            <div class="cq-field">
                <label for="filterSearch">Search text</label>
                <input class="cq-input" id="filterSearch" name="search"
                       value="{{ request('search') }}"
                       placeholder="Search questions...">
            </div>

            <div style="display:flex;gap:8px;grid-column:1/-1;flex-wrap:wrap">
                <button class="cq-btn cq-primary" type="submit">Apply filters</button>
                <a class="cq-btn cq-secondary"
                   href="{{ route('admin.checklist-questions.index') }}">
                    Reset
                </a>
                <button class="cq-btn cq-secondary" type="button"
                        onclick="cqOpenReplace()">
                    Paste / replace questions
                </button>
            </div>
        </form>
    </section>

    <section class="cq-panel">
        <div class="cq-toolbar">
            <h2>Question library</h2>
            <span class="cq-count">{{ $questions->count() }} questions shown</span>
        </div>

        <div class="cq-table-wrap">
            <table class="cq-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Question</th>
                        <th>Type</th>
                        <th>Frequency</th>
                        <th>Variant</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($questions as $question)
                        <tr>
                            <td>{{ $question->order }}</td>
                            <td class="cq-question">{{ $question->question_text }}</td>
                            <td><span class="cq-badge">{{ $question->type }}</span></td>
                            <td>
                                {{ $question->type === 'TST'
                                    ? 'F' . $question->frequency
                                    : '—' }}
                            </td>
                            <td>
                                @if($question->type === 'TST')
                                    <span class="cq-badge {{ $question->variant === 'HV' ? 'hv' : 'normal' }}">
                                        {{ $question->variant ?: 'normal' }}
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <span class="cq-status {{ $question->is_active ? 'cq-active' : 'cq-inactive' }}">
                                    {{ $question->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="cq-actions">
                                    <button type="button" class="cq-small"
                                        onclick="cqEdit(this)"
                                        data-id="{{ $question->id }}"
                                        data-type="{{ $question->type }}"
                                        data-frequency="{{ $question->frequency }}"
                                        data-variant="{{ $question->variant ?: 'normal' }}"
                                        data-text="{{ $question->question_text }}"
                                        data-order="{{ $question->order }}"
                                        data-active="{{ $question->is_active ? 1 : 0 }}">
                                        Edit
                                    </button>

                                    <form method="POST"
                                        action="{{ route('admin.checklist-questions.destroy', $question) }}"
                                        onsubmit="return confirm('Delete this question? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="cq-small">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:40px;color:#738096">
                                No questions match your filters.
                                <br><br>
                                <button type="button" class="cq-btn cq-primary"
                                        onclick="cqOpenCreate()">
                                    Create your first question
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

{{-- Create / edit question modal --}}
<div class="cq-modal-backdrop cq-hidden" id="cqQuestionModal"
     role="dialog" aria-modal="true" aria-labelledby="cqQuestionTitle"
     onclick="if(event.target===this) cqCloseModal('cqQuestionModal')">
    <div class="cq-modal">
        <div class="cq-modal-head">
            <h2 id="cqQuestionTitle">Create question</h2>
            <button type="button" class="cq-modal-close"
                    onclick="cqCloseModal('cqQuestionModal')"
                    aria-label="Close">×</button>
        </div>

        <form method="POST" id="cqQuestionForm"
              action="{{ route('admin.checklist-questions.store') }}">
            @csrf
            <div id="cqQuestionMethod"></div>

            <div class="cq-form-grid">
                <div class="cq-field">
                    <label for="cqType">Type</label>
                    <select name="type" id="cqType" class="cq-select" required>
                        <option value="PNL">PNL</option>
                        <option value="TRQ">TRQ</option>
                        <option value="TST">TST</option>
                    </select>
                </div>

                <div class="cq-field" id="cqFrequencyWrap">
                    <label for="cqFrequency">Frequency</label>
                    <select name="frequency" id="cqFrequency" class="cq-select">
                        @foreach([1, 2, 3, 4, 5, 6, 12] as $frequency)
                            <option value="{{ $frequency }}">Frequency {{ $frequency }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="cq-field" id="cqVariantWrap">
                    <label for="cqVariant">Variant</label>
                    <select name="variant" id="cqVariant" class="cq-select">
                        <option value="normal">Normal</option>
                        <option value="HV">HV</option>
                    </select>
                </div>

                <div class="cq-field" style="grid-column:1/-1">
                    <label for="cqText">Question text</label>
                    <textarea name="question_text" id="cqText"
                        class="cq-textarea" required maxlength="2000"
                        placeholder="Enter the checklist question..."></textarea>
                </div>

                <div class="cq-field">
                    <label for="cqOrder">Display order</label>
                    <input name="order" id="cqOrder" type="number" min="0"
                           value="1" class="cq-input" required>
                </div>
            </div>

            <label class="cq-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="cqActive" value="1" checked>
                Question is active
            </label>

            <div class="cq-footer">
                <button type="button" class="cq-btn cq-secondary"
                        onclick="cqCloseModal('cqQuestionModal')">
                    Cancel
                </button>
                <button type="submit" class="cq-btn cq-primary" id="cqSaveBtn">
                    Save question
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Bulk replacement modal --}}
<div class="cq-modal-backdrop cq-hidden" id="cqReplaceModal"
     role="dialog" aria-modal="true" aria-labelledby="cqReplaceTitle"
     onclick="if(event.target===this) cqCloseModal('cqReplaceModal')">
    <div class="cq-modal">
        <div class="cq-modal-head">
            <h2 id="cqReplaceTitle">Bulk question replacement</h2>
            <button type="button" class="cq-modal-close"
                    onclick="cqCloseModal('cqReplaceModal')"
                    aria-label="Close">×</button>
        </div>

        <p class="cq-description">
            Paste one question per line. Empty lines are ignored. The selected
            group will be replaced with the pasted questions.
        </p>

        <form method="POST" action="{{ route('admin.checklist-questions.replace') }}"
              id="cqReplaceForm">
            @csrf
            @method('PUT')

            <div class="cq-form-grid">
                <div class="cq-field">
                    <label for="cqReplaceType">Type</label>
                    <select name="type" id="cqReplaceType" class="cq-select" required>
                        <option value="PNL">PNL</option>
                        <option value="TRQ">TRQ</option>
                        <option value="TST">TST</option>
                    </select>
                </div>

                <div class="cq-field" id="cqReplaceFrequencyWrap">
                    <label for="cqReplaceFrequency">Frequency</label>
                    <select name="frequency" id="cqReplaceFrequency" class="cq-select">
                        @foreach([1, 2, 3, 4, 5, 6, 12] as $frequency)
                            <option value="{{ $frequency }}">Frequency {{ $frequency }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="cq-field" id="cqReplaceVariantWrap">
                    <label for="cqReplaceVariant">Variant</label>
                    <select name="variant" id="cqReplaceVariant" class="cq-select">
                        <option value="normal">Normal</option>
                        <option value="HV">HV</option>
                    </select>
                </div>
            </div>

            <div class="cq-field" style="margin-top:16px">
                <label for="cqQuestionsText">Questions (one per line)</label>
                <textarea name="questions_text" id="cqQuestionsText"
                    class="cq-textarea" required maxlength="100000"
                    placeholder="Check the equipment condition&#10;Verify the safety guard&#10;Inspect the electrical connections"></textarea>
            </div>

            <div class="cq-preview" id="cqReplacePreview">
                0 questions detected
            </div>

            <label class="cq-check">
                <input type="checkbox" name="is_active" value="1" checked>
                Import pasted questions as active
            </label>
            <input type="hidden" name="is_active" value="0">

            <div class="cq-notice cq-warning">
                <strong>Warning:</strong> This operation replaces the entire selected
                group, not just the visible rows. It is blocked if that group contains
                questions with saved answers. Review the type, frequency and variant
                before confirming.
            </div>

            <label class="cq-check">
                <input type="checkbox" id="cqReplaceConfirm" required>
                I have reviewed the selected group and replacement list.
            </label>

            <div class="cq-footer">
                <button type="button" class="cq-btn cq-secondary"
                        onclick="cqCloseModal('cqReplaceModal')">
                    Cancel
                </button>
                <button type="submit" class="cq-btn cq-primary" id="cqReplaceSubmit">
                    Replace questions
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const byId = id => document.getElementById(id);

    function configureType(typeId, frequencyWrapId, variantWrapId) {
        const type = byId(typeId);
        const frequencyWrap = byId(frequencyWrapId);
        const variantWrap = byId(variantWrapId);

        function update() {
            const isTst = type.value === 'TST';

            frequencyWrap.classList.toggle('cq-hidden', !isTst);
            variantWrap.classList.toggle('cq-hidden', !isTst);

            frequencyWrap.querySelector('select').disabled = !isTst;
            variantWrap.querySelector('select').disabled = !isTst;
        }

        type.addEventListener('change', update);
        update();
    }

    configureType('filterType', 'filterFrequencyWrap', 'filterVariantWrap');
    configureType('cqType', 'cqFrequencyWrap', 'cqVariantWrap');
    configureType(
        'cqReplaceType',
        'cqReplaceFrequencyWrap',
        'cqReplaceVariantWrap'
    );

    // Match TST group selection to the active filters when opening replacement.
    window.cqOpenReplace = function () {
        byId('cqReplaceType').value =
            byId('filterType').value || 'PNL';

        byId('cqReplaceFrequency').value =
            byId('filterFrequency').value || '1';

        byId('cqReplaceVariant').value =
            byId('filterVariant').value || 'normal';

        byId('cqQuestionsText').value = '';
        byId('cqReplaceConfirm').checked = false;
        byId('cqReplaceModal').classList.remove('cq-hidden');

        byId('cqReplaceType').dispatchEvent(new Event('change'));
        cqUpdatePreview();
    };

    window.cqUpdatePreview = function () {
        const lines = byId('cqQuestionsText').value
            .split(/\r\n|\r|\n/)
            .map(line => line.trim())
            .filter(Boolean);

        byId('cqReplacePreview').textContent =
            lines.length + (lines.length === 1
                ? ' question detected'
                : ' questions detected');
    };

    byId('cqQuestionsText').addEventListener('input', cqUpdatePreview);

    window.cqOpenCreate = function () {
        byId('cqQuestionForm').reset();
        byId('cqQuestionForm').action =
            @json(route('admin.checklist-questions.store'));
        byId('cqQuestionMethod').innerHTML = '';
        byId('cqQuestionTitle').textContent = 'Create question';
        byId('cqSaveBtn').textContent = 'Save question';
        byId('cqType').dispatchEvent(new Event('change'));
        byId('cqQuestionModal').classList.remove('cq-hidden');
    };

    window.cqEdit = function (button) {
        const form = byId('cqQuestionForm');

        form.action = @json(url('/admin/checklist-questions')) +
            '/' + encodeURIComponent(button.dataset.id);

        byId('cqQuestionMethod').innerHTML =
            '<input type="hidden" name="_method" value="PUT">';

        byId('cqQuestionTitle').textContent = 'Edit question';
        byId('cqSaveBtn').textContent = 'Update question';

        byId('cqType').value = button.dataset.type;
        byId('cqFrequency').value = button.dataset.frequency || '1';
        byId('cqVariant').value = button.dataset.variant || 'normal';
        byId('cqText').value = button.dataset.text;
        byId('cqOrder').value = button.dataset.order;
        byId('cqActive').checked = button.dataset.active === '1';

        byId('cqType').dispatchEvent(new Event('change'));
        byId('cqQuestionModal').classList.remove('cq-hidden');
    };

    window.cqCloseModal = function (id) {
        byId(id).classList.add('cq-hidden');
    };

    // Close the current modal using Escape.
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            ['cqQuestionModal', 'cqReplaceModal'].forEach(cqCloseModal);
        }
    });

    byId('cqReplaceForm').addEventListener('submit', function (event) {
        const count = byId('cqQuestionsText').value
            .split(/\r\n|\r|\n/)
            .map(line => line.trim())
            .filter(Boolean).length;

        if (count === 0 || count > 500) {
            event.preventDefault();
            alert('Enter between 1 and 500 questions before replacing.');
            return;
        }

        const group = byId('cqReplaceType').value +
            (byId('cqReplaceType').value === 'TST'
                ? ' / F' + byId('cqReplaceFrequency').value +
                  ' / ' + byId('cqReplaceVariant').value
                : '');

        if (!confirm('Replace all questions in ' + group +
            ' with ' + count + ' pasted questions?')) {
            event.preventDefault();
        }
    });
})();
</script>
@endsection
