
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>New PPM Question | TapApp</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --orange: #f47721;
            --orange-soft: #fff1e7;
            --navy: #202c43;
            --bg: #f4f6fb;
            --white: #fff;
            --text: #182238;
            --muted: #778198;
            --border: #e6eaf1;
            --green: #16865c;
            --red: #c63e3e;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Inter, "Segoe UI", sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        button, input, select, textarea { font: inherit; }
        button, a { -webkit-tap-highlight-color: transparent; }

        .app {
            max-width: 1250px;
            padding: 28px;
            margin: auto;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
            animation: rise .45s ease both;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 45px;
            height: 45px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: var(--orange-soft);
            color: var(--orange);
        }

        .brand h1 { font-size: 17px; margin: 0; }
        .brand p { color: var(--muted); font-size: 12px; margin: 5px 0 0; }

        .btn {
            border: 0;
            padding: 11px 15px;
            border-radius: 11px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            text-decoration: none;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s, background .2s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px #18223814;
        }

        .btn:disabled { opacity: .6; cursor: wait; }

        .btn-primary { background: var(--orange); color: white; }
        .btn-light { background: white; color: var(--text); border: 1px solid var(--border); }

        .hero {
            position: relative;
            overflow: hidden;
            padding: 30px;
            border-radius: 23px;
            color: white;
            background: linear-gradient(120deg, #202c43, #384c6d);
            margin-bottom: 22px;
            animation: rise .5s ease both;
        }

        .hero:after {
            content: "";
            position: absolute;
            width: 200px;
            height: 200px;
            right: -55px;
            top: -105px;
            border: 30px solid #ffffff0d;
            border-radius: 50%;
            pointer-events: none;
        }

        .eyebrow {
            color: #ffbd88;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 10px;
            font-weight: 800;
        }

        .hero h2 { margin: 12px 0 9px; font-size: clamp(24px, 4vw, 34px); }
        .hero p { color: #d4dcec; font-size: 13px; line-height: 1.8; max-width: 620px; margin: 0; }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 15px;
            margin-bottom: 22px;
        }

        .stat, .panel {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 17px;
            box-shadow: 0 5px 24px #26334d05;
        }

        .stat {
            padding: 19px;
            display: flex;
            align-items: center;
            gap: 14px;
            animation: rise .5s ease both;
        }

        .stat-icon {
            width: 43px;
            height: 43px;
            border-radius: 13px;
            display: grid;
            place-items: center;
            color: var(--orange);
            background: var(--orange-soft);
            flex-shrink: 0;
        }

        .stat:nth-child(2) .stat-icon { color: var(--green); background: #e7f8ef; }
        .stat:nth-child(3) .stat-icon { color: #9a6a15; background: #fff6df; }

        .stat-label { color: var(--muted); font-size: 11px; }
        .stat-value { font-size: 25px; font-weight: 800; margin-top: 6px; }

        .workspace {
            display: grid;
            grid-template-columns: minmax(0, 1.5fr) minmax(270px, .8fr);
            gap: 20px;
            align-items: start;
        }

        .panel { padding: 25px; animation: rise .55s ease both; }

        .panel-heading {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 23px;
        }

        .panel-heading .brand-icon { width: 40px; height: 40px; }
        .panel-heading h3 { font-size: 16px; margin: 0; }
        .panel-heading p { font-size: 12px; color: var(--muted); margin: 5px 0 0; }

        .field { margin-bottom: 19px; }
        .field label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .control {
            width: 100%;
            min-width: 0;
            border: 1px solid var(--border);
            background: white;
            color: var(--text);
            padding: 12px 13px;
            border-radius: 11px;
            outline: none;
            transition: border .2s, box-shadow .2s;
        }

        .control:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 4px #f4772115;
        }

        textarea.control { resize: vertical; min-height: 130px; line-height: 1.7; }

        .help { color: var(--muted); font-size: 11px; margin-top: 7px; line-height: 1.6; }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0 15px;
        }

        .full { grid-column: 1 / -1; }

        .active-box {
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid var(--border);
            background: #fafbfe;
            padding: 14px;
            border-radius: 12px;
        }

        .active-box input { width: 17px; height: 17px; accent-color: var(--orange); }
        .active-box strong { display: block; font-size: 12px; }
        .active-box small { display: block; margin-top: 4px; color: var(--muted); font-size: 11px; }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 22px;
        }

        .actions .btn-primary { flex: 1; }

        .preview {
            position: sticky;
            top: 20px;
        }

        .preview-card {
            border: 1px solid var(--border);
            border-radius: 15px;
            padding: 18px;
            background: linear-gradient(145deg, #fff, #fafbff);
            transition: box-shadow .25s, transform .25s;
        }

        .preview-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px #26334d0d;
        }

        .preview-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 18px;
        }

        .pill {
            background: var(--orange-soft);
            color: #b95616;
            padding: 6px 9px;
            border-radius: 7px;
            font-size: 10px;
            font-weight: 800;
        }

        .live {
            color: var(--green);
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 10px;
            font-weight: 800;
        }

        .preview-question {
            font-size: 14px;
            font-weight: 650;
            line-height: 1.8;
            overflow-wrap: anywhere;
            white-space: pre-wrap;
            min-height: 70px;
        }

        .preview-tags { display: flex; flex-wrap: wrap; gap: 7px; margin-top: 16px; }
        .preview-tags span {
            padding: 7px 9px;
            background: #f0f2f7;
            color: #596780;
            font-size: 10px;
            font-weight: 700;
            border-radius: 8px;
        }

        .info {
            padding: 15px;
            border-radius: 12px;
            background: #fff6ec;
            color: #8e4b1c;
            margin-top: 18px;
            font-size: 11px;
            line-height: 1.8;
        }

        .notice {
            display: none;
            padding: 13px 15px;
            border-radius: 11px;
            font-size: 12px;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        .notice.show { display: block; animation: rise .25s ease; }
        .notice.error { background: #fff0f0; color: var(--red); }
        .notice.ok { background: #e7f8ef; color: var(--green); }

        @keyframes rise {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }

        .brand-icon svg { animation: float 3s ease-in-out infinite; }

        @media (max-width: 850px) {
            .workspace { grid-template-columns: 1fr; }
            .preview { position: static; }
        }

        @media (max-width: 560px) {
            .app { padding: 14px; }
            .topbar { align-items: flex-start; flex-direction: column; }
            .hero, .panel { padding: 19px; }
            .stats { gap: 8px; }
            .stat { padding: 12px; align-items: flex-start; flex-direction: column; gap: 8px; }
            .stat-icon { width: 35px; height: 35px; }
            .stat-value { font-size: 21px; }
            .stat-label { font-size: 10px; }
            .form-grid { grid-template-columns: 1fr; }
            .full { grid-column: auto; }
            .actions .btn-primary { flex-basis: 100%; }
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

    <header class="topbar">
        <div class="brand">
            <div class="brand-icon"><i data-lucide="clipboard-check"></i></div>
            <div>
                <h1>TAP APP <span style="color:#f47721">/</span> Checklist Studio</h1>
                <p>Maintenance question management</p>
            </div>
        </div>

        <a href="{{ route('admin.checklist-questions.index') }}" class="btn btn-light">
            <i data-lucide="arrow-left" size="16"></i>
            Back to question library
        </a>
    </header>

    <section class="hero">
        <div class="eyebrow">Administration · PPM Management</div>
        <h2>Create a new PPM question</h2>
        <p>
            Configure a maintenance checklist question, assign it to the correct
            equipment category and frequency, and preview it before saving.
        </p>
    </section>

    <section class="stats">
        <div class="stat">
            <div class="stat-icon"><i data-lucide="layers"></i></div>
            <div>
                <div class="stat-label">Total questions</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
        </div>
        <div class="stat">
            <div class="stat-icon"><i data-lucide="check-circle-2"></i></div>
            <div>
                <div class="stat-label">Active questions</div>
                <div class="stat-value">{{ $stats['active'] }}</div>
            </div>
        </div>
        <div class="stat">
            <div class="stat-icon"><i data-lucide="eye-off"></i></div>
            <div>
                <div class="stat-label">Hidden questions</div>
                <div class="stat-value">{{ $stats['hidden'] }}</div>
            </div>
        </div>
    </section>

    <div id="notice" class="notice" role="alert"></div>

    <div class="workspace">
        <section class="panel">
            <div class="panel-heading">
                <div class="brand-icon"><i data-lucide="file-plus-2"></i></div>
                <div>
                    <h3>Question configuration</h3>
                    <p>Complete the fields below.</p>
                </div>
            </div>

            <form id="questionForm">
                <div class="form-grid">
                    <div class="field">
                        <label for="type">Equipment type *</label>
                        <select id="type" name="type" class="control" required>
                            <option value="PNL">PNL — Panel</option>
                            <option value="TRQ">TRQ — Torque</option>
                            <option value="TST">TST — Test</option>
                        </select>
                        <div class="help">Select the checklist category.</div>
                    </div>

                    <div class="field">
                        <label for="frequency">Maintenance frequency</label>
                        <select id="frequency" name="frequency" class="control">
                            <option value="">No frequency</option>
                            <option value="1">Frequency 1</option>
                            <option value="4">Frequency 4</option>
                        </select>
                        <div class="help">Choose the applicable PPM cycle.</div>
                    </div>

                    <div class="field">
                        <label for="variant">Question variant</label>
                        <select id="variant" name="variant" class="control">
                            <option value="">Null</option>
                            <option value="">Low Voltage</option>
                            <option value="HV">HV — High Voltage</option>
                        </select>
                        <div class="help">Use HV for questions specific to HV equipment.</div>
                    </div>

                    <div class="field">
                        <label for="order">Display order</label>
                        <input id="order" name="order" class="control"
                               type="number" min="0" placeholder="Automatic">
                        <div class="help">Leave empty to assign the next order.</div>
                    </div>

                    <div class="field full">
                        <label for="question_text">Question text *</label>
                        <textarea id="question_text" name="question_text"
                                  class="control" maxlength="1000" required
                                  placeholder="Example: Inspect the equipment and verify that all safety labels are visible."></textarea>
                        <div class="help"><span id="charCount">0</span>/1000 characters</div>
                    </div>

                    <div class="field full">
                        <label>Visibility</label>
                        <div class="active-box">
                            <input type="checkbox" id="is_active" checked>
                            <div>
                                <strong>Activate this question</strong>
                                <small>Active questions are eligible to appear in checklists that use this group.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary" id="saveBtn">
                        <i data-lucide="save" size="16"></i>
                        Save PPM question
                    </button>
                    <a href="{{ route('admin.checklist-questions.index') }}" class="btn btn-light">
                        Cancel
                    </a>
                </div>
            </form>
        </section>

        <aside class="preview">
            <section class="panel">
                <div class="panel-heading">
                    <div class="brand-icon"><i data-lucide="eye"></i></div>
                    <div>
                        <h3>Live preview</h3>
                        <p>Updates as you edit.</p>
                    </div>
                </div>

                <div class="preview-card">
                    <div class="preview-top">
                        <span class="pill" id="previewType">PNL</span>
                        <span class="live">
                            <i data-lucide="circle-check" size="13"></i>
                            <span id="previewStatus">ACTIVE</span>
                        </span>
                    </div>

                    <div class="preview-question" id="previewText">
                        Your question will appear here...
                    </div>

                    <div class="preview-tags">
                        <span id="previewFrequency">No frequency</span>
                        <span id="previewVariant">Normal</span>
                        <span id="previewOrder">Order: automatic</span>
                    </div>
                </div>

                <div class="info">
                    <strong> <i data-lucide="shield-check" size="14" style="vertical-align:middle"></i>
                        Before saving</strong><br>
                    Verify the type, frequency and variant. Questions in the wrong
                    group may not appear on the intended printed PPM ticket.
                </div>
            </section>
        </aside>
    </div>
</div>

<script>
    lucide.createIcons();

    const form = document.getElementById('questionForm');
    const notice = document.getElementById('notice');
    const saveBtn = document.getElementById('saveBtn');
    const questionText = document.getElementById('question_text');

    const fields = {
        type: document.getElementById('type'),
        frequency: document.getElementById('frequency'),
        variant: document.getElementById('variant'),
        order: document.getElementById('order'),
        active: document.getElementById('is_active')
    };

    function updatePreview() {
        document.getElementById('previewType').textContent = fields.type.value;
        document.getElementById('previewFrequency').textContent =
            fields.frequency.value ? 'Frequency ' + fields.frequency.value : 'No frequency';
        document.getElementById('previewVariant').textContent =
            fields.variant.value || 'Normal';
        document.getElementById('previewOrder').textContent =
            fields.order.value !== '' ? 'Order: ' + fields.order.value : 'Order: automatic';
        document.getElementById('previewText').textContent =
            questionText.value.trim() || 'Your question will appear here...';
        document.getElementById('previewStatus').textContent =
            fields.active.checked ? 'ACTIVE' : 'HIDDEN';

        document.getElementById('charCount').textContent = questionText.value.length;
    }

    Object.values(fields).forEach(field => {
        field.addEventListener('input', updatePreview);
        field.addEventListener('change', updatePreview);
    });

    questionText.addEventListener('input', updatePreview);

    function showNotice(message, type = 'error') {
        notice.textContent = message;
        notice.className = 'notice show ' + type;
        notice.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    form.addEventListener('submit', async event => {
        event.preventDefault();

        if (!questionText.value.trim()) {
            showNotice('Please enter the question text.');
            questionText.focus();
            return;
        }

        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i data-lucide="loader-circle"></i> Saving...';
        lucide.createIcons();

        const data = {
            type: fields.type.value,
            frequency: fields.frequency.value || null,
            variant: fields.variant.value || null,
            order: fields.order.value === '' ? null : Number(fields.order.value),
            question_text: questionText.value.trim(),
            is_active: fields.active.checked
        };

        try {
            const response = await fetch(
                @json(route('admin.checklistquestions.store')),
                {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content
                    },
                    body: JSON.stringify(data)
                }
            );

            const result = await response.json();

            if (!response.ok) {
                let message = result.message || 'Unable to save the question.';
                if (result.errors) {
                    message = Object.values(result.errors).flat().join(' ');
                }
                throw new Error(message);
            }

            showNotice(result.message || 'Question created successfully.', 'ok');

            setTimeout(() => {
                window.location.href =
                    @json(route('admin.checklist-questions.index'));
            }, 700);

        } catch (error) {
            showNotice(error.message || 'An unexpected error occurred.');
        } finally {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i data-lucide="save"></i> Save PPM question';
            lucide.createIcons();
        }
    });

    updatePreview();
</script>
</body>
</html>
