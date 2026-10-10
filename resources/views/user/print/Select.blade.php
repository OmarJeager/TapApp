<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Print PPM Tickets | TapApp</title>

    {{-- Font Awesome icons --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        :root {
            --primary: #f97316;
            --primary-dark: #ea580c;
            --primary-light: #fff7ed;
            --dark: #172033;
            --muted: #64748b;
            --border: #e2e8f0;
            --surface: #ffffff;
            --background: #f4f6fb;
            --success: #16a34a;
            --danger: #dc2626;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, "Segoe UI", Arial, sans-serif;
            color: var(--dark);
            background:
                radial-gradient(circle at 8% 8%, #ffedd5 0, transparent 28%),
                radial-gradient(circle at 95% 90%, #e0e7ff 0, transparent 30%),
                var(--background);
            display: flex;
            flex-direction: column;
        }

        button, select, input {
            font: inherit;
        }

        .topbar {
            min-height: 76px;
            padding: 0 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            background: rgba(255, 255, 255, .88);
            border-bottom: 1px solid rgba(226, 232, 240, .9);
            backdrop-filter: blur(14px);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 19px;
            letter-spacing: -.5px;
        }

        .brand-icon {
            width: 43px;
            height: 43px;
            display: grid;
            place-items: center;
            color: #fff;
            background: linear-gradient(135deg, #fb923c, #ea580c);
            border-radius: 14px;
            box-shadow: 0 6px 16px rgba(249, 115, 22, .25);
        }

        .brand small {
            display: block;
            margin-top: 3px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.5px;
        }

        .topbar-label {
            color: var(--muted);
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .topbar-label i {
            color: var(--primary);
        }

        main {
            width: 100%;
            max-width: 1080px;
            margin: auto;
            padding: 55px 22px 65px;
            flex: 1;
        }

        .page-heading {
            text-align: center;
            margin-bottom: 32px;
            animation: fadeDown .6s ease both;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 13px;
            border-radius: 30px;
            background: #ffedd5;
            color: #c2410c;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.1px;
            text-transform: uppercase;
        }

        .page-heading h1 {
            margin: 18px 0 10px;
            font-size: clamp(28px, 4vw, 42px);
            letter-spacing: -1.5px;
            line-height: 1.15;
        }

        .page-heading h1 span {
            color: var(--primary);
        }

        .page-heading p {
            margin: 0 auto;
            max-width: 530px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.8;
        }

        .main-grid {
            display: grid;
            grid-template-columns: .85fr 1.4fr;
            gap: 24px;
            align-items: stretch;
        }

        .info-panel {
            position: relative;
            overflow: hidden;
            padding: 30px 27px;
            color: white;
            background: linear-gradient(145deg, #1e293b, #111827);
            border-radius: 24px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, .12);
            animation: fadeUp .65s ease .1s both;
        }

        .info-panel::before {
            content: "";
            position: absolute;
            width: 210px;
            height: 210px;
            right: -85px;
            top: -75px;
            border: 35px solid rgba(249, 115, 22, .12);
            border-radius: 50%;
        }

        .info-icon {
            width: 56px;
            height: 56px;
            display: grid;
            place-items: center;
            color: #fff;
            background: linear-gradient(135deg, #fb923c, #ea580c);
            border-radius: 18px;
            font-size: 23px;
            margin-bottom: 26px;
            position: relative;
        }

        .info-panel h2 {
            margin: 0 0 12px;
            font-size: 24px;
            letter-spacing: -.5px;
            position: relative;
        }

        .info-panel > p {
            color: #cbd5e1;
            font-size: 13px;
            line-height: 1.8;
            margin-bottom: 28px;
            position: relative;
        }

        .steps {
            display: grid;
            gap: 19px;
            position: relative;
        }

        .step {
            display: flex;
            align-items: flex-start;
            gap: 13px;
        }

        .step-number {
            flex-shrink: 0;
            width: 31px;
            height: 31px;
            display: grid;
            place-items: center;
            background: rgba(255, 255, 255, .09);
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 10px;
            color: #fdba74;
            font-size: 12px;
            font-weight: 800;
        }

        .step strong {
            display: block;
            font-size: 12px;
            margin: 1px 0 5px;
        }

        .step span {
            color: #94a3b8;
            font-size: 11px;
            line-height: 1.6;
        }

        .info-footer {
            margin-top: 28px;
            padding-top: 18px;
            border-top: 1px solid rgba(255, 255, 255, .12);
            display: flex;
            align-items: center;
            gap: 9px;
            color: #cbd5e1;
            font-size: 11px;
        }

        .info-footer i {
            color: #4ade80;
        }

        .form-card {
            padding: 32px;
            background: var(--surface);
            border: 1px solid rgba(226, 232, 240, .85);
            border-radius: 24px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, .07);
            animation: fadeUp .65s ease .2s both;
        }

        .form-heading {
            display: flex;
            align-items: center;
            gap: 13px;
            padding-bottom: 22px;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 25px;
        }

        .form-heading-icon {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 14px;
            font-size: 18px;
        }

        .form-heading h2 {
            font-size: 17px;
            margin: 0 0 5px;
        }

        .form-heading p {
            font-size: 11px;
            color: var(--muted);
            margin: 0;
        }

        .field {
            margin-bottom: 20px;
            animation: fieldIn .3s ease both;
        }

        .field.hidden {
            display: none;
        }

        .field label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 9px;
            font-size: 12px;
            font-weight: 750;
            color: #334155;
        }

        .field label i {
            color: var(--primary);
            width: 15px;
            text-align: center;
        }

        .select-wrap {
            position: relative;
        }

        .select-wrap > i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #94a3b8;
            font-size: 14px;
            transition: color .2s;
        }

        .select-wrap select {
            width: 100%;
            min-height: 49px;
            appearance: none;
            -webkit-appearance: none;
            padding: 12px 42px 12px 42px;
            color: #334155;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            outline: none;
            font-size: 13px;
            cursor: pointer;
            transition: border-color .2s, box-shadow .2s,
                        background .2s, transform .2s;
        }

        .select-wrap select:focus {
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(249, 115, 22, .12);
        }

        .select-wrap:focus-within > i {
            color: var(--primary);
        }

        .select-wrap::after {
            content: "\f107";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        select:disabled {
            opacity: .65;
            cursor: not-allowed;
        }

        /* ===== Year / week search ===== */
        .week-filters {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }

        .week-filters input {
            width: 100%;
            min-height: 44px;
            padding: 10px 14px;
            color: #334155;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 13px;
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        .week-filters input:focus {
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(249, 115, 22, .12);
        }

        .hint {
            color: #94a3b8;
            font-size: 10px;
            line-height: 1.6;
            margin-top: 7px;
        }

        .variant-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .variant-option {
            position: relative;
        }

        .variant-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .variant-option label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 100px;
            padding: 14px 8px;
            border: 1px solid var(--border);
            border-radius: 14px;
            cursor: pointer;
            transition: all .2s ease;
            background: #fff;
            margin: 0;
        }

        .variant-option label i {
            font-size: 22px;
            color: #94a3b8;
            transition: color .2s;
        }

        .variant-option label strong {
            font-size: 12px;
            color: #334155;
        }

        .variant-option label small {
            color: #94a3b8;
            font-size: 10px;
            font-weight: 500;
        }

        .variant-option input:checked + label {
            background: var(--primary-light);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, .09);
        }

        .variant-option input:checked + label i,
        .variant-option input:checked + label strong {
            color: var(--primary-dark);
        }

        .variant-option label:hover {
            border-color: #fdba74;
            transform: translateY(-2px);
        }

        .error-message {
            padding: 12px 14px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 11px;
            font-size: 12px;
            margin-bottom: 20px;
        }

        .status-message {
            display: none;
            align-items: center;
            gap: 9px;
            padding: 11px 13px;
            margin-bottom: 17px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            color: var(--muted);
            font-size: 11px;
        }

        .status-message.show {
            display: flex;
            animation: fieldIn .2s ease;
        }

        .status-message.error {
            color: var(--danger);
            background: #fef2f2;
            border-color: #fecaca;
        }

        .status-message.success {
            color: #166534;
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .spinner {
            width: 14px;
            height: 14px;
            border: 2px solid #fed7aa;
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin .7s linear infinite;
            flex-shrink: 0;
        }

        .submit-btn {
            width: 100%;
            min-height: 51px;
            padding: 13px 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 11px;
            color: #fff;
            background: linear-gradient(135deg, #fb923c, #ea580c);
            border: 0;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 8px 18px rgba(249, 115, 22, .22);
            transition: transform .2s, box-shadow .2s, opacity .2s;
        }

        .submit-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(249, 115, 22, .3);
        }

        .submit-btn:active:not(:disabled) {
            transform: translateY(0);
        }

        .submit-btn:disabled {
            background: #cbd5e1;
            box-shadow: none;
            cursor: not-allowed;
        }

        .submit-btn .btn-arrow {
            transition: transform .2s;
        }

        .submit-btn:hover:not(:disabled) .btn-arrow {
            transform: translateX(4px);
        }

        .form-note {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
            margin-top: 17px;
            color: #94a3b8;
            font-size: 10px;
            text-align: center;
        }

        footer {
            padding: 18px;
            text-align: center;
            color: #94a3b8;
            font-size: 11px;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fieldIn {
            from { opacity: 0; transform: translateY(7px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 800px) {
            main {
                padding-top: 35px;
            }

            .main-grid {
                grid-template-columns: 1fr;
            }

            .info-panel {
                padding: 25px;
            }

            .steps {
                grid-template-columns: 1fr 1fr;
                gap: 16px;
            }

            .info-footer {
                margin-top: 22px;
            }
        }

        @media (max-width: 520px) {
            .topbar {
                min-height: 65px;
                padding: 0 18px;
            }

            .topbar-label {
                font-size: 11px;
            }

            .brand {
                font-size: 16px;
            }

            .brand-icon {
                width: 38px;
                height: 38px;
            }

            main {
                padding: 30px 14px 40px;
            }

            .page-heading {
                margin-bottom: 24px;
            }

            .page-heading h1 {
                letter-spacing: -.8px;
            }

            .form-card {
                padding: 22px 17px;
                border-radius: 19px;
            }

            .info-panel {
                border-radius: 19px;
            }

            .steps {
                grid-template-columns: 1fr;
            }

            .variant-option label {
                min-height: 95px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>

<body>

<header class="topbar">
    <div class="brand">
        <div class="brand-icon">
            <i class="fa-solid fa-ticket"></i>
        </div>
        <div>
            TapApp
            <small>MAINTENANCE MANAGEMENT</small>
        </div>
    </div>

    <div class="topbar-label">
        <i class="fa-solid fa-shield-halved"></i>
        PPM Ticket Center
    </div>
</header>

<main>

    <section class="page-heading">
        <div class="eyebrow">
            <i class="fa-solid fa-bolt"></i>
            QUICK TICKET GENERATOR
        </div>

        <h1>Print PPM <span>Tickets</span></h1>

        <p>
            Select your maintenance category, due week, and checklist type
            to prepare your printable PPM tickets.
        </p>
    </section>

    <div class="main-grid">

        <aside class="info-panel">
            <div class="info-icon">
                <i class="fa-solid fa-print"></i>
            </div>

            <h2>Ready to print?</h2>

            <p>
                Generate the right maintenance ticket sheet in a few
                simple steps. Select the required options to continue.
            </p>

            <div class="steps">
                <div class="step">
                    <div class="step-number">01</div>
                    <div>
                        <strong>Choose category</strong>
                        <span>PNL, TST, or KHM equipment.</span>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">02</div>
                    <div>
                        <strong>Select due week</strong>
                        <span>Search by year or week among published weeks.</span>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">03</div>
                    <div>
                        <strong>Choose frequency</strong>
                        <span>Required for TST tickets.</span>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">04</div>
                    <div>
                        <strong>Generate tickets</strong>
                        <span>Prepare your print-ready sheet.</span>
                    </div>
                </div>
            </div>

            <div class="info-footer">
                <i class="fa-solid fa-circle-check"></i>
                Simple, fast, and organized maintenance printing
            </div>
        </aside>

        <section class="form-card">

            <div class="form-heading">
                <div class="form-heading-icon">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <div>
                    <h2>Ticket Configuration</h2>
                    <p>Complete the required fields below.</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="error-message">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="status-message" id="status-message"
                 role="status" aria-live="polite">
                <span id="status-icon">
                    <i class="fa-solid fa-circle-info"></i>
                </span>
                <span id="status-text"></span>
            </div>

            <form id="ticket-form"
                  method="GET"
                  action="{{ route('tickets.print') }}"
                  target="_blank">

                {{-- Category --}}
                <div class="field">
                    <label for="category">
                        <i class="fa-solid fa-layer-group"></i>
                        Maintenance Category
                    </label>

                    <div class="select-wrap">
                        <i class="fa-solid fa-sitemap"></i>

                        <select id="category" name="category" required>
                            <option value="" selected disabled>
                                Select a category
                            </option>

                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}">
                                    {{ strtoupper($cat) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="hint">
                        Choose the equipment category you want to print.
                    </div>
                </div>

                {{-- Week (published weeks only) --}}
                <div class="field hidden" id="week-field">
                    <label for="week">
                        <i class="fa-regular fa-calendar-days"></i>
                        Week Due
                    </label>

                    {{-- Search inputs: no "name", so they are not submitted --}}
                    <div class="week-filters">
                        <input type="number"
                               id="week-year"
                               placeholder="Year (e.g. 2026)"
                               min="2000"
                               max="2100"
                               autocomplete="off">

                        <input type="number"
                               id="week-search"
                               placeholder="Week (e.g. 40)"
                               min="1"
                               max="53"
                               autocomplete="off">
                    </div>

                    <div class="select-wrap">
                        <i class="fa-solid fa-calendar-week"></i>

                        <select id="week" name="week" required disabled>
                            <option value="" selected disabled>
                                Select a due week
                            </option>
                        </select>
                    </div>

                    <div class="hint">
                        Only published weeks are listed. Type a year and/or a week number to narrow the list.
                    </div>
                </div>

                {{-- Frequency --}}
                <div class="field hidden" id="frequency-field">
                    <label for="frequency">
                        <i class="fa-solid fa-repeat"></i>
                        Maintenance Frequency
                    </label>

                    <div class="select-wrap">
                        <i class="fa-solid fa-clock-rotate-left"></i>

                        <select id="frequency" name="frequency" disabled>
                            <option value="" selected disabled>
                                Select a frequency
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Normal / HV variant --}}
                <div class="field hidden" id="variant-field">
                    <label>
                        <i class="fa-solid fa-list-check"></i>
                        Checklist Type
                    </label>

                    <div class="variant-options">
                        <div class="variant-option">
                            <input type="radio"
                                   id="variant-normal"
                                   name="variant"
                                   value="normal">

                            <label for="variant-normal">
                                <i class="fa-solid fa-clipboard-list"></i>
                                <strong>Normal</strong>
                                <small>Standard checklist</small>
                            </label>
                        </div>

                        <div class="variant-option">
                            <input type="radio"
                                   id="variant-hv"
                                   name="variant"
                                   value="hv">

                            <label for="variant-hv">
                                <i class="fa-solid fa-bolt"></i>
                                <strong>HV Checklist</strong>
                                <small>High voltage</small>
                            </label>
                        </div>
                    </div>

                    <div class="hint">
                        Normal prints TST assets without "HV" in their description.
                        HV prints only assets whose description contains "HV".
                    </div>
                </div>

                <button type="submit" class="submit-btn" id="submit-btn" disabled>
                    <i class="fa-solid fa-print"></i>
                    <span id="submit-text">Generate & Print Tickets</span>
                    <i class="fa-solid fa-arrow-right btn-arrow"></i>
                </button>

                <div class="form-note">
                    <i class="fa-solid fa-circle-info"></i>
                    Your printable tickets will open in a new tab.
                </div>
            </form>
        </section>

    </div>
</main>

<footer>
    &copy; {{ date('Y') }} TapApp &mdash; PPM Maintenance Management
</footer>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const categorySelect = document.getElementById('category');

    const weekField = document.getElementById('week-field');
    const weekSelect = document.getElementById('week');
    const yearInput = document.getElementById('week-year');
    const weekSearchInput = document.getElementById('week-search');

    const frequencyField = document.getElementById('frequency-field');
    const frequencySelect = document.getElementById('frequency');

    const variantField = document.getElementById('variant-field');
    const variantInputs = document.querySelectorAll('input[name="variant"]');

    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');

    const statusMessage = document.getElementById('status-message');
    const statusIcon = document.getElementById('status-icon');
    const statusText = document.getElementById('status-text');

    let categoryRequest = 0;
    let weekRequest = 0;
    let frequencyRequest = 0;
    let searchTimer;

    function resetSelect(select, placeholder) {
        select.replaceChildren();

        const option = document.createElement('option');
        option.value = '';
        option.textContent = placeholder;
        option.disabled = true;
        option.selected = true;

        select.appendChild(option);
    }

    function resetVariant() {
        variantInputs.forEach(input => {
            input.checked = false;
            input.disabled = false;
        });

        document.querySelectorAll('.variant-option label').forEach(label => {
            label.style.opacity = '1';
        });

        variantField.classList.add('hidden');
    }

    function showStatus(message, type = 'info', loading = false) {
        statusMessage.className = 'status-message show';

        if (type === 'error') {
            statusMessage.classList.add('error');
        }

        if (type === 'success') {
            statusMessage.classList.add('success');
        }

        statusIcon.innerHTML = loading
            ? '<span class="spinner"></span>'
            : type === 'error'
                ? '<i class="fa-solid fa-circle-exclamation"></i>'
                : type === 'success'
                    ? '<i class="fa-solid fa-circle-check"></i>'
                    : '<i class="fa-solid fa-circle-info"></i>';

        statusText.textContent = message;
    }

    function hideStatus() {
        statusMessage.className = 'status-message';
        statusText.textContent = '';
    }

    function updateSubmitState() {
        const hasCategory = categorySelect.value !== '';
        const hasWeek = weekSelect.value !== '';
        const isTst = categorySelect.value === 'tst';

        const hasFrequency = !isTst || frequencySelect.value !== '';
        const needsVariant = isTst && frequencySelect.value === '4';

        const selectedVariant = document.querySelector(
            'input[name="variant"]:checked'
        );

        const hasVariant = !needsVariant || !!selectedVariant;

        submitBtn.disabled = !(
            hasCategory &&
            hasWeek &&
            hasFrequency &&
            hasVariant
        );
    }

    async function fetchJson(url) {
        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('The server could not load the requested options.');
        }

        return response.json();
    }

    /** 202640 -> "40/26 (202640)" */
    function formatWeek(value) {
        const w = String(value);

        return w.length === 6
            ? `${w.slice(4)}/${w.slice(2, 4)} (${w})`
            : w;
    }

    async function loadWeeks() {
        const requestId = ++categoryRequest;

        resetSelect(weekSelect, 'Loading weeks...');
        weekSelect.disabled = true;

        resetSelect(frequencySelect, 'Select a frequency');
        frequencySelect.disabled = true;
        frequencyField.classList.add('hidden');

        resetVariant();
        hideStatus();
        updateSubmitState();

        if (!categorySelect.value) {
            weekField.classList.add('hidden');
            return;
        }

        weekField.classList.remove('hidden');

        showStatus('Loading published maintenance weeks...', 'info', true);

        try {
            const params = new URLSearchParams({
                category: categorySelect.value
            });

            if (yearInput.value) {
                params.set('year', yearInput.value);
            }

            if (weekSearchInput.value) {
                params.set('week', weekSearchInput.value);
            }

            const weeks = await fetchJson(
                `{{ route('tickets.weeks') }}?${params.toString()}`
            );

            if (requestId !== categoryRequest) return;

            resetSelect(weekSelect, 'Select a due week');

            weeks.forEach(week => {
                const option = document.createElement('option');
                option.value = String(week);
                option.textContent = formatWeek(week);
                weekSelect.appendChild(option);
            });

            weekSelect.disabled = false;

            if (weeks.length === 0) {
                showStatus('No published weeks were found for this search.', 'error');
            } else {
                showStatus(`${weeks.length} published week(s) loaded.`, 'success');
            }
        } catch (error) {
            if (requestId !== categoryRequest) return;

            resetSelect(weekSelect, 'Unable to load weeks');
            weekSelect.disabled = true;
            showStatus(error.message, 'error');
        }

        updateSubmitState();
    }

    async function loadFrequencies() {
        const requestId = ++weekRequest;

        ++frequencyRequest;

        resetSelect(frequencySelect, 'Loading frequencies...');
        frequencySelect.disabled = true;

        frequencyField.classList.add('hidden');
        resetVariant();

        updateSubmitState();

        if (categorySelect.value !== 'tst' || !weekSelect.value) {
            frequencyField.classList.add('hidden');
            frequencySelect.disabled = true;
            updateSubmitState();
            return;
        }

        frequencyField.classList.remove('hidden');

        showStatus('Loading TST frequencies...', 'info', true);

        try {
            const params = new URLSearchParams({
                category: categorySelect.value,
                week: weekSelect.value
            });

            const frequencies = await fetchJson(
                `{{ route('tickets.frequencies') }}?${params.toString()}`
            );

            if (requestId !== weekRequest) return;

            resetSelect(frequencySelect, 'Select a frequency');

            frequencies.forEach(frequency => {
                const option = document.createElement('option');
                option.value = frequency;
                option.textContent = `Frequency ${frequency}`;
                frequencySelect.appendChild(option);
            });

            frequencySelect.disabled = false;

            if (frequencies.length === 0) {
                showStatus('No frequencies were found for this week.', 'error');
            } else {
                showStatus('Select a maintenance frequency.', 'success');
            }
        } catch (error) {
            if (requestId !== weekRequest) return;

            resetSelect(frequencySelect, 'Unable to load frequencies');
            frequencySelect.disabled = true;
            showStatus(error.message, 'error');
        }

        updateSubmitState();
    }

    async function loadVariants() {
        const requestId = ++frequencyRequest;

        resetVariant();
        updateSubmitState();

        if (
            categorySelect.value !== 'tst' ||
            frequencySelect.value !== '4' ||
            !weekSelect.value
        ) {
            hideStatus();
            return;
        }

        variantField.classList.remove('hidden');

        showStatus('Loading Normal and HV checklist options...', 'info', true);

        try {
            const params = new URLSearchParams({
                category: categorySelect.value,
                week: weekSelect.value,
                frequency: frequencySelect.value
            });

            const variants = await fetchJson(
                `{{ route('tickets.variants') }}?${params.toString()}`
            );

            if (requestId !== frequencyRequest) return;

            const available = new Set(variants);

            document.getElementById('variant-normal').disabled =
                !available.has('normal');

            document.getElementById('variant-hv').disabled =
                !available.has('hv');

            document.querySelector(
                'label[for="variant-normal"]'
            ).style.opacity = available.has('normal') ? '1' : '.4';

            document.querySelector(
                'label[for="variant-hv"]'
            ).style.opacity = available.has('hv') ? '1' : '.4';

            if (variants.length === 0) {
                showStatus(
                    'No Normal or HV assets were found for this selection.',
                    'error'
                );
            } else {
                showStatus(
                    'Choose Normal or HV to continue.',
                    'success'
                );
            }
        } catch (error) {
            if (requestId !== frequencyRequest) return;
            showStatus(error.message, 'error');
        }

        updateSubmitState();
    }

    categorySelect.addEventListener('change', loadWeeks);

    weekSelect.addEventListener('change', loadFrequencies);

    frequencySelect.addEventListener('change', loadVariants);

    // Year / week search: reload published weeks after a short pause
    [yearInput, weekSearchInput].forEach(input => {
        input.addEventListener('input', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(loadWeeks, 350);
        });
    });

    variantInputs.forEach(input => {
        input.addEventListener('change', function () {
            hideStatus();
            updateSubmitState();
        });
    });

    document.getElementById('ticket-form').addEventListener('submit', function (event) {
        updateSubmitState();

        if (submitBtn.disabled) {
            event.preventDefault();
            showStatus('Please complete all required selections.', 'error');
            return;
        }

        submitText.textContent = 'Preparing tickets...';
        submitBtn.querySelector('.fa-print').className = 'spinner';

        // Restore the button after a short delay (the print opens in a new tab).
        window.setTimeout(() => {
            submitText.textContent = 'Generate & Print Tickets';

            const spinner = submitBtn.querySelector('.spinner');
            if (spinner) {
                spinner.className = 'fa-solid fa-print';
            }

            updateSubmitState();
        }, 1800);
    });

    // Initial state
    resetVariant();
    updateSubmitState();
});
</script>

</body>
</html>
