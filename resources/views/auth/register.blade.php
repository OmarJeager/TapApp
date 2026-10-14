
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#101318">
    <title>TapApp | Maintenance Management</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        :root {
            --orange: #ff7900;
            --orange2: #ff4d00;
            --dark: #101318;
            --muted: #718096;
            --border: #e3e8ef;
            --surface: #ffffff;
            --page: #f0f3f8;
            --text: #142033;
            --input: #ffffff;
            --shadow: 0 25px 80px rgba(22, 34, 54, .10);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--page);
            color: var(--text);
            min-height: 100vh;
        }

        button, input { font: inherit; }
        button { cursor: pointer; }

        .app {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(350px, 43%) minmax(0, 57%);
        }

        /* LEFT BRANDING PANEL */

        .brand-panel {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            padding: 34px clamp(28px, 4vw, 64px);
            background:
                radial-gradient(ellipse at 80% 45%, rgba(255, 87, 0, .23), transparent 42%),
                linear-gradient(145deg, #151a21, #080a0e 72%);
            color: white;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .brand-panel::before {
            content: "";
            position: absolute;
            z-index: -1;
            inset: 0;
            background:
                linear-gradient(125deg, transparent 48%, rgba(255, 112, 0, .10) 49%, transparent 50%),
                radial-gradient(circle at 20% 90%, rgba(255, 110, 0, .14), transparent 30%);
            animation: atmosphere 9s ease-in-out infinite alternate;
        }

        .brand-panel::after {
            content: "";
            position: absolute;
            z-index: -1;
            width: 330px;
            height: 330px;
            right: -200px;
            top: 15%;
            border: 1px solid rgba(255, 122, 0, .45);
            border-radius: 50%;
            box-shadow: 0 0 0 35px rgba(255, 122, 0, .035),
                        0 0 0 70px rgba(255, 122, 0, .025);
            animation: orbit 18s linear infinite;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 13px;
            text-decoration: none;
            color: white;
            width: fit-content;
        }

        .brand-logo i {
            font-size: 43px;
            color: var(--orange);
            filter: drop-shadow(0 0 12px rgba(255, 121, 0, .3));
        }

        .brand-logo strong {
            display: block;
            font-size: 27px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .brand-logo strong span { color: var(--orange); }

        .brand-logo small {
            display: block;
            font-size: 11px;
            margin-top: 3px;
            color: #c6cbd4;
            letter-spacing: .3px;
        }

        .eyebrow {
            margin-top: 72px;
            display: flex;
            align-items: center;
            gap: 13px;
            color: #d6dce5;
            font-size: 10px;
            letter-spacing: 2px;
            font-weight: 700;
        }

        .eyebrow::before {
            content: "";
            width: 40px;
            height: 2px;
            background: var(--orange);
        }

        .brand-title {
            margin: 24px 0 18px;
            max-width: 570px;
            font-size: clamp(36px, 4vw, 58px);
            line-height: 1.12;
            letter-spacing: -2.5px;
            font-weight: 800;
        }

        .brand-title span {
            color: var(--orange);
            display: inline-block;
            text-shadow: 0 0 35px rgba(255, 100, 0, .15);
        }

        .brand-description {
            max-width: 430px;
            color: #b9c1ce;
            font-size: 13px;
            line-height: 1.9;
        }

        .features {
            margin-top: 30px;
            display: grid;
            gap: 20px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 14px;
            animation: slideUp .65s both;
        }

        .feature:nth-child(2) { animation-delay: .1s; }
        .feature:nth-child(3) { animation-delay: .2s; }
        .feature:nth-child(4) { animation-delay: .3s; }

        .feature-icon {
            height: 45px;
            width: 45px;
            flex-shrink: 0;
            display: grid;
            place-items: center;
            border: 1px solid rgba(255, 121, 0, .65);
            border-radius: 13px;
            background: linear-gradient(145deg, rgba(255, 121, 0, .18), rgba(255, 121, 0, .025));
            color: var(--orange);
            font-size: 17px;
            transition: transform .25s, background .25s;
        }

        .feature:hover .feature-icon {
            transform: translateY(-4px) rotate(-4deg);
            background: rgba(255, 121, 0, .24);
        }

        .feature strong { display: block; font-size: 12px; }
        .feature small { display: block; color: #aab3c0; margin-top: 5px; font-size: 11px; }

        .brand-bottom { margin-top: auto; padding-top: 35px; }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border: 1px solid rgba(255, 121, 0, .35);
            border-radius: 18px;
            background: rgba(255,255,255,.035);
            overflow: hidden;
        }

        .stat { padding: 17px 10px; text-align: center; }
        .stat i { color: var(--orange); font-size: 18px; margin-bottom: 8px; }
        .stat strong { display: block; font-size: 18px; }
        .stat small { display: block; margin-top: 5px; font-size: 9px; color: #aab3c0; }

        .brand-footer {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-top: 18px;
            color: #8993a1;
            font-size: 10px;
        }

        /* RIGHT AUTH PANEL */

        .main-panel {
            min-width: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 24px clamp(20px, 4vw, 60px);
            background:
                radial-gradient(ellipse at 85% 5%, rgba(255, 121, 0, .075), transparent 35%),
                radial-gradient(ellipse at 10% 95%, rgba(102, 139, 190, .08), transparent 35%),
                var(--page);
        }

        .topbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 18px;
            margin-bottom: 28px;
        }

        .language-select {
            border: 0;
            background: transparent;
            color: var(--text);
            padding: 8px;
            outline: none;
            cursor: pointer;
            font-size: 12px;
        }

        .theme-button {
            width: 54px;
            height: 29px;
            border-radius: 30px;
            border: 1px solid var(--border);
            background: #dce3ed;
            display: flex;
            align-items: center;
            padding: 3px;
            color: #364152;
        }

        .theme-button span {
            height: 21px;
            width: 21px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: white;
            transition: transform .3s;
            box-shadow: 0 2px 6px #0002;
        }

        .auth-shell {
            width: 100%;
            max-width: 760px;
            margin: auto;
            background: var(--surface);
            border: 1px solid rgba(255,255,255,.8);
            border-radius: 25px;
            box-shadow: var(--shadow);
            padding: clamp(22px, 3vw, 42px);
            animation: appear .65s ease both;
        }

        .tabs {
            max-width: 300px;
            margin: 0 auto 27px;
            padding: 5px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            border: 1px solid var(--border);
            background: var(--page);
            border-radius: 50px;
        }

        .tab {
            border: 0;
            border-radius: 40px;
            padding: 13px 12px;
            font-size: 13px;
            font-weight: 700;
            color: var(--muted);
            background: transparent;
            transition: all .3s ease;
        }

        .tab.active {
            color: white;
            background: linear-gradient(110deg, var(--orange2), var(--orange));
            box-shadow: 0 5px 16px rgba(255, 105, 0, .26);
        }

        .tab:hover:not(.active) { color: var(--orange); }

        .form-view { display: none; animation: slideUp .4s ease both; }
        .form-view.active { display: block; }

        .form-heading { margin-bottom: 23px; }

        .form-heading h1 {
            margin: 0 0 10px;
            font-size: clamp(25px, 3vw, 32px);
            letter-spacing: -1px;
            color: var(--text);
        }

        .form-heading p {
            margin: 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        .progress {
            display: flex;
            align-items: center;
            margin: 25px 0;
        }

        .progress-step {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #8792a4;
            font-size: 11px;
            white-space: nowrap;
        }

        .progress-step .number {
            width: 31px;
            height: 31px;
            border: 1px solid var(--border);
            background: var(--surface);
            display: grid;
            place-items: center;
            border-radius: 50%;
            font-weight: 700;
        }

        .progress-step.active { color: var(--orange); }
        .progress-step.active .number {
            color: white;
            border-color: var(--orange);
            background: var(--orange);
            box-shadow: 0 3px 12px #ff790044;
        }

        .progress-line {
            height: 1px;
            background: var(--border);
            flex: 1;
            margin: 0 12px;
        }

        .fields-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 17px 18px;
        }

        .field { min-width: 0; }
        .field.full { grid-column: 1 / -1; }

        .field label {
            display: block;
            margin-bottom: 8px;
            font-size: 11px;
            font-weight: 700;
            color: var(--text);
        }

        .input-wrap { position: relative; }

        .input-wrap > i:first-child {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #7b8aa0;
            font-size: 13px;
            pointer-events: none;
        }

        .input-wrap input {
            width: 100%;
            height: 46px;
            border: 1px solid var(--border);
            border-radius: 11px;
            outline: none;
            background: var(--input);
            color: var(--text);
            padding: 0 43px;
            font-size: 12px;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        .input-wrap input::placeholder { color: #9aa5b5; }

        .input-wrap input:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 4px rgba(255,121,0,.09);
        }

        .input-wrap input.is-invalid { border-color: #e5484d; }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: 0;
            color: #8290a3;
            padding: 5px;
        }

        .field-help {
            margin: 7px 0 0;
            color: #8b98ab;
            font-size: 10px;
            line-height: 1.5;
        }

        .email-suggestions {
            position: absolute;
            z-index: 10;
            top: calc(100% + 5px);
            left: 0;
            right: 0;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            box-shadow: var(--shadow);
            display: none;
            overflow: hidden;
        }

        .email-suggestions button {
            display: block;
            width: 100%;
            padding: 11px 14px;
            text-align: left;
            color: var(--text);
            background: transparent;
            border: 0;
            font-size: 12px;
        }

        .email-suggestions button:hover { background: var(--page); }

        .password-strength {
            margin-top: 9px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .strength-bars { display: flex; gap: 4px; flex: 1; }
        .strength-bars span {
            height: 4px;
            flex: 1;
            border-radius: 5px;
            background: var(--border);
            transition: background .2s;
        }

        .strength-label { font-size: 10px; color: var(--muted); white-space: nowrap; }

        .terms {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.7;
            margin: 22px 0;
        }

        .terms input {
            accent-color: var(--orange);
            margin-top: 3px;
            flex-shrink: 0;
        }

        .terms a, .text-link {
            color: var(--orange2);
            text-decoration: none;
            font-weight: 600;
        }

        .terms a:hover, .text-link:hover { text-decoration: underline; }

        .submit-button {
            position: relative;
            width: 100%;
            min-height: 51px;
            padding: 14px 18px;
            border: 0;
            border-radius: 13px;
            color: white;
            font-size: 13px;
            font-weight: 700;
            background: linear-gradient(110deg, var(--orange2), var(--orange));
            box-shadow: 0 8px 20px rgba(255, 105, 0, .22);
            transition: transform .2s, box-shadow .2s;
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(255, 105, 0, .30);
        }

        .submit-button:active { transform: scale(.99); }
        .submit-button i { margin-right: 9px; }

        .form-bottom {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-top: 23px;
            color: var(--muted);
            font-size: 11px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .divider-line { flex: 1; height: 1px; background: var(--border); }

        .forgot-row {
            display: flex;
            justify-content: flex-end;
            margin: -4px 0 19px;
            font-size: 11px;
        }

        .alert {
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 18px;
            font-size: 12px;
            line-height: 1.6;
        }

        .alert-error { background: #fff0f0; color: #a5262b; border: 1px solid #ffd0d0; }
        .alert-success { background: #eafaf0; color: #16713a; border: 1px solid #c6efd4; }
        .alert ul { margin: 5px 0 0; padding-left: 20px; }

        .security-note {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
            margin-top: 22px;
            color: var(--muted);
            font-size: 10px;
        }

        .security-note i { color: #16a36a; }

        /* DARK MODE */

        body.dark {
            --surface: #171c25;
            --page: #10141b;
            --text: #eef2f8;
            --muted: #a3afc2;
            --border: #303846;
            --input: #141922;
            --shadow: 0 25px 80px rgba(0,0,0,.28);
        }

        body.dark .theme-button { background: #3a4657; color: white; }
        body.dark .theme-button span { transform: translateX(23px); background: #202938; }
        body.dark .alert-error { background: #351d22; color: #ffb9bd; border-color: #633038; }
        body.dark .alert-success { background: #183025; color: #b4f3cb; border-color: #245a3b; }

        /* ANIMATIONS */

        @keyframes appear {
            from { opacity: 0; transform: translateY(14px) scale(.99); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes atmosphere {
            from { transform: translateX(-10px); opacity: .6; }
            to { transform: translateX(10px); opacity: 1; }
        }

        @keyframes orbit { to { transform: rotate(360deg); } }

        /* RESPONSIVE */

        @media (max-width: 1000px) {
            .app { grid-template-columns: minmax(290px, 36%) minmax(0, 64%); }
            .brand-panel { padding: 28px; }
            .brand-title { font-size: 39px; }
            .main-panel { padding: 20px; }
            .auth-shell { padding: 25px; }
            .eyebrow { margin-top: 55px; }
        }

        @media (max-width: 760px) {
            .app { display: block; }
            .brand-panel { min-height: auto; padding: 24px; }
            .brand-logo i { font-size: 35px; }
            .brand-logo strong { font-size: 23px; }
            .eyebrow { margin-top: 35px; }
            .brand-title { font-size: 39px; }
            .brand-description { max-width: 550px; }
            .features { grid-template-columns: 1fr 1fr; gap: 15px; }
            .feature-icon { width: 38px; height: 38px; }
            .brand-bottom { padding-top: 25px; }
            .stats { margin-top: 0; }
            .main-panel { min-height: auto; padding: 18px 14px 35px; }
            .topbar { margin-bottom: 17px; }
            .auth-shell { margin: 0 auto; }
        }

        @media (max-width: 480px) {
            .brand-panel { padding: 22px 18px; }
            .brand-title { font-size: 34px; letter-spacing: -1.5px; }
            .features { grid-template-columns: 1fr 1fr; }
            .feature { gap: 9px; }
            .feature strong { font-size: 10px; }
            .feature small { font-size: 9px; }
            .stats { grid-template-columns: repeat(3, 1fr); }
            .stat { padding: 13px 4px; }
            .stat strong { font-size: 15px; }
            .stat small { font-size: 8px; }
            .auth-shell { padding: 21px 16px; border-radius: 19px; }
            .fields-grid { grid-template-columns: 1fr; gap: 16px; }
            .field.full { grid-column: auto; }
            .progress-step { gap: 5px; font-size: 9px; }
            .progress-line { margin: 0 6px; }
            .form-heading h1 { font-size: 26px; }
            .brand-footer { font-size: 9px; }
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
<div class="app">

    <!-- LEFT: TAPAPP BRANDING -->
    <aside class="brand-panel">
        <a href="{{ url('/') }}" class="brand-logo">
            <i class="fa-solid fa-gear"></i>
            <span>
                <strong>TAP<span>APP</span></strong>
                <small data-i18n="brand_subtitle">Maintenance Management</small>
            </span>
        </a>

        <div class="eyebrow" data-i18n="eyebrow">SMART MAINTENANCE • BETTER PERFORMANCE</div>

        <h2 class="brand-title">
            <span data-i18n="hero_line1">Manage Your</span><br>
            <span class="hero-white" data-i18n="hero_line2">Maintenance,</span><br>
            <span data-i18n="hero_line3">Smarter</span>
        </h2>

        <p class="brand-description" data-i18n="hero_description">
            TapApp helps you track, plan and manage maintenance operations with ease.
            Keep your assets running, reduce downtime and boost productivity — all in one place.
        </p>

        <div class="features">
            <div class="feature">
                <div class="feature-icon"><i class="fa-solid fa-clipboard-check"></i></div>
                <div><strong data-i18n="feature1_title">PPM Records</strong><small data-i18n="feature1_text">Stay on schedule</small></div>
            </div>
            <div class="feature">
                <div class="feature-icon"><i class="fa-solid fa-list-check"></i></div>
                <div><strong data-i18n="feature2_title">Checklists</strong><small data-i18n="feature2_text">Ensure compliance</small></div>
            </div>
            <div class="feature">
                <div class="feature-icon"><i class="fa-solid fa-chart-column"></i></div>
                <div><strong data-i18n="feature3_title">Reports</strong><small data-i18n="feature3_text">Track performance</small></div>
            </div>
            <div class="feature">
                <div class="feature-icon"><i class="fa-solid fa-users"></i></div>
                <div><strong data-i18n="feature4_title">Team & Assets</strong><small data-i18n="feature4_text">Work together efficiently</small></div>
            </div>
        </div>

        <div class="brand-bottom">
            <div class="stats">
                <div class="stat">
                    <i class="fa-solid fa-users"></i>
                    <strong>+200</strong>
                    <small data-i18n="stat1">Assets Managed</small>
                </div>
                <div class="stat">
                    <i class="fa-solid fa-people-group"></i>
                    <strong>+50</strong>
                    <small data-i18n="stat2">Team Members</small>
                </div>
                <div class="stat">
                    <i class="fa-solid fa-shield-halved"></i>
                    <strong>99.9%</strong>
                    <small data-i18n="stat3">System Reliability</small>
                </div>
            </div>

            <div class="brand-footer">
                <span data-i18n="footer_left">Built for a more reliable tomorrow</span>
                <span>© {{ date('Y') }} TapApp</span>
            </div>
        </div>
    </aside>

    <!-- RIGHT: AUTHENTICATION -->
    <main class="main-panel">
        <header class="topbar">
            <label for="language" style="display:flex;align-items:center;gap:7px">
                <i class="fa-solid fa-globe"></i>
                <select class="language-select" id="language" aria-label="Choose language">
                    <option value="en">English</option>
                    <option value="fr">Français</option>
                    <option value="ar">العربية</option>
                </select>
            </label>

            <button type="button" class="theme-button" id="themeToggle" aria-label="Toggle dark mode">
                <span><i class="fa-solid fa-sun"></i></span>
            </button>
        </header>

        <section class="auth-shell">
            <!-- SWITCH BETWEEN SIGN UP AND LOGIN -->
            <div class="tabs" role="tablist" aria-label="Authentication">
                <button type="button" class="tab active" id="signupTab" role="tab"
                        aria-selected="true" data-view="signup" data-i18n="tab_signup">
                    Sign Up
                </button>
                <button type="button" class="tab" id="loginTab" role="tab"
                        aria-selected="false" data-view="login" data-i18n="tab_login">
                    Login
                </button>
            </div>

            <!-- LARAVEL SESSION / VALIDATION MESSAGES -->
            @if (session('status'))
                <div class="alert alert-success" role="status">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error" role="alert">
                    <strong data-i18n="error_heading">Please check the following:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- SIGN UP FORM -->
            <div class="form-view active" id="signupView">
                <div class="form-heading">
                    <h1 data-i18n="signup_title">Create your account</h1>
                    <p data-i18n="signup_description">
                        Join TapApp and start managing your maintenance operations efficiently.
                        It only takes a few minutes.
                    </p>
                </div>

                <div class="progress" aria-label="Registration steps">
                    <div class="progress-step active">
                        <span class="number">1</span>
                        <span data-i18n="step_personal">Personal Info</span>
                    </div>
                    <div class="progress-line"></div>
                    <div class="progress-step">
                        <span class="number">2</span>
                        <span data-i18n="step_security">Security</span>
                    </div>
                    <div class="progress-line"></div>
                    <div class="progress-step">
                        <span class="number">3</span>
                        <span data-i18n="step_profile">Profile</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    <div class="fields-grid">
                        <div class="field">
                            <label for="register_name" data-i18n="label_name">Full Name</label>
                            <div class="input-wrap">
                                <i class="fa-regular fa-user"></i>
                                <input id="register_name" type="text" name="name"
                                       value="{{ old('name') }}"
                                       placeholder="Enter your full name"
                                       data-i18n-placeholder="placeholder_name"
                                       autocomplete="name" required autofocus
                                       class="@error('name') is-invalid @enderror">
                            </div>
                        </div>

                        <div class="field">
                            <label for="matricule" data-i18n="label_matricule">Matricule</label>
                            <div class="input-wrap">
                                <i class="fa-regular fa-id-card"></i>
                                <input id="matricule" type="text" name="matricule"
                                       value="{{ old('matricule') }}"
                                       placeholder="Enter your matricule"
                                       data-i18n-placeholder="placeholder_matricule"
                                       autocomplete="off" required
                                       class="@error('matricule') is-invalid @enderror">
                            </div>
                        </div>

                        <div class="field full">
                            <label for="register_email" data-i18n="label_email">Email Address</label>
                            <div class="input-wrap">
                                <i class="fa-regular fa-envelope"></i>
                                <input id="register_email" type="email" name="email"
                                       value="{{ old('email') }}"
                                       placeholder="you@company.com"
                                       data-i18n-placeholder="placeholder_email"
                                       autocomplete="email" required
                                       class="@error('email') is-invalid @enderror">
                                <div class="email-suggestions" id="emailSuggestions"></div>
                            </div>
                            <p class="field-help" data-i18n="email_help">
                                We'll use this to send you important updates.
                            </p>
                        </div>

                        <div class="field">
                            <label for="register_password" data-i18n="label_password">Password</label>
                            <div class="input-wrap">
                                <i class="fa-solid fa-lock"></i>
                                <input id="register_password" type="password" name="password"
                                       placeholder="Create a strong password"
                                       data-i18n-placeholder="placeholder_password"
                                       autocomplete="new-password" required
                                       class="@error('password') is-invalid @enderror">
                                <button type="button" class="password-toggle"
                                        data-target="register_password" aria-label="Show password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>

                            <div class="password-strength">
                                <div class="strength-bars">
                                    <span></span><span></span><span></span><span></span>
                                </div>
                                <span class="strength-label" id="strengthLabel" data-i18n="strength_empty">
                                    Password strength
                                </span>
                            </div>
                        </div>

                        <div class="field">
                            <label for="password_confirmation" data-i18n="label_confirm">
                                Confirm Password
                            </label>
                            <div class="input-wrap">
                                <i class="fa-solid fa-lock"></i>
                                <input id="password_confirmation" type="password"
                                       name="password_confirmation"
                                       placeholder="Confirm your password"
                                       data-i18n-placeholder="placeholder_confirm"
                                       autocomplete="new-password" required>
                                <button type="button" class="password-toggle"
                                        data-target="password_confirmation"
                                        aria-label="Show password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            <p class="field-help" id="passwordMatch" aria-live="polite"></p>
                        </div>
                    </div>

                    <label class="terms">
                        <input type="checkbox" id="terms" name="terms" value="1" required>
                        <span>
                            <span data-i18n="terms_text">I agree to the</span>
                            <a href="{{ url('/terms') }}" target="_blank" rel="noopener"
                               data-i18n="terms_link">Terms of Service</a>
                            <span data-i18n="and_text">and</span>
                            <a href="{{ url('/privacy') }}" target="_blank" rel="noopener"
                               data-i18n="privacy_link">Privacy Policy</a>.
                        </span>
                    </label>

                    <button type="submit" class="submit-button" id="registerButton">
                        <i class="fa-solid fa-user-plus"></i>
                        <span data-i18n="signup_button">Create Account</span>
                        <i class="fa-solid fa-arrow-right" style="float:right;margin-top:2px"></i>
                    </button>
                </form>

                <div class="form-bottom">
                    <span class="divider-line"></span>
                    <span data-i18n="already_account">Already have an account?</span>
                    <a href="{{ route('login') }}" class="text-link switch-link" data-view="login"
                       data-i18n="login_link">Login</a>
                    <span class="divider-line"></span>
                </div>
            </div>

            <!-- LOGIN FORM -->
            <div class="form-view" id="loginView">
                <div class="form-heading">
                    <h1 data-i18n="login_title">Welcome back</h1>
                    <p data-i18n="login_description">
                        Sign in to continue managing your maintenance operations.
                    </p>
                </div>

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <div class="fields-grid">
                        <div class="field full">
                            <label for="login_email" data-i18n="label_email">Email Address</label>
                            <div class="input-wrap">
                                <i class="fa-regular fa-envelope"></i>
                                <input id="login_email" type="email" name="email"
                                       value="{{ old('email') }}"
                                       placeholder="you@company.com"
                                       data-i18n-placeholder="placeholder_email"
                                       autocomplete="username" required>
                            </div>
                        </div>

                        <div class="field full">
                            <label for="login_password" data-i18n="label_password">Password</label>
                            <div class="input-wrap">
                                <i class="fa-solid fa-lock"></i>
                                <input id="login_password" type="password" name="password"
                                       placeholder="Enter your password"
                                       data-i18n-placeholder="login_password_placeholder"
                                       autocomplete="current-password" required>
                                <button type="button" class="password-toggle"
                                        data-target="login_password" aria-label="Show password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="forgot-row">
                        @if (Route::has('password.request'))
                            <a class="text-link" href="{{ route('password.request') }}"
                               data-i18n="forgot_password">Forgot your password?</a>
                        @endif
                    </div>

                    <label class="terms" style="margin-top:0">
                        <input type="checkbox" name="remember" value="1">
                        <span data-i18n="remember_me">Remember me on this device</span>
                    </label>

                    <button type="submit" class="submit-button" id="loginButton">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span data-i18n="login_button">Sign In</span>
                        <i class="fa-solid fa-arrow-right" style="float:right;margin-top:2px"></i>
                    </button>
                </form>

                <div class="form-bottom">
                    <span class="divider-line"></span>
                    <span data-i18n="no_account">Don't have an account?</span>
                    <button type="button" class="text-link switch-link"
                            data-view="signup" style="border:0;background:transparent"
                            data-i18n="signup_link">Sign Up</button>
                    <span class="divider-line"></span>
                </div>
            </div>

            <div class="security-note">
                <i class="fa-solid fa-shield-halved"></i>
                <span data-i18n="secure_note">Secure & Trusted • Your data matters</span>
            </div>
        </section>
    </main>
</div>

<script>
    const translations = {
        en: {
            brand_subtitle: "Maintenance Management",
            eyebrow: "SMART MAINTENANCE • BETTER PERFORMANCE",
            hero_line1: "Manage Your",
            hero_line2: "Maintenance,",
            hero_line3: "Smarter",
            hero_description: "TapApp helps you track, plan and manage maintenance operations with ease. Keep your assets running, reduce downtime and boost productivity — all in one place.",
            feature1_title: "PPM Records", feature1_text: "Stay on schedule",
            feature2_title: "Checklists", feature2_text: "Ensure compliance",
            feature3_title: "Reports", feature3_text: "Track performance",
            feature4_title: "Team & Assets", feature4_text: "Work together efficiently",
            stat1: "Assets Managed", stat2: "Team Members", stat3: "System Reliability",
            footer_left: "Built for a more reliable tomorrow",
            tab_signup: "Sign Up", tab_login: "Login",
            signup_title: "Create your account",
            signup_description: "Join TapApp and start managing your maintenance operations efficiently. It only takes a few minutes.",
            login_title: "Welcome back",
            login_description: "Sign in to continue managing your maintenance operations.",
            step_personal: "Personal Info", step_security: "Security", step_profile: "Profile",
            label_name: "Full Name", label_matricule: "Matricule",
            label_email: "Email Address", label_password: "Password",
            label_confirm: "Confirm Password",
            placeholder_name: "Enter your full name",
            placeholder_matricule: "Enter your matricule",
            placeholder_email: "you@company.com",
            placeholder_password: "Create a strong password",
            placeholder_confirm: "Confirm your password",
            login_password_placeholder: "Enter your password",
            email_help: "We'll use this to send you important updates.",
            strength_empty: "Password strength", strength_weak: "Weak",
            strength_fair: "Fair", strength_good: "Good", strength_strong: "Strong",
            match_ok: "Passwords match.", match_no: "Passwords do not match.",
            terms_text: "I agree to the", terms_link: "Terms of Service",
            and_text: "and", privacy_link: "Privacy Policy",
            signup_button: "Create Account", login_button: "Sign In",
            already_account: "Already have an account?", login_link: "Login",
            no_account: "Don't have an account?", signup_link: "Sign Up",
            forgot_password: "Forgot your password?", remember_me: "Remember me on this device",
            secure_note: "Secure & Trusted • Your data matters",
            error_heading: "Please check the following:",
            email_hint: "Suggested email addresses"
        },
        fr: {
            brand_subtitle: "Gestion de la maintenance",
            eyebrow: "MAINTENANCE INTELLIGENTE • MEILLEURES PERFORMANCES",
            hero_line1: "Gérez votre",
            hero_line2: "maintenance,",
            hero_line3: "intelligemment",
            hero_description: "TapApp vous aide à planifier et gérer vos opérations de maintenance. Gardez vos équipements opérationnels, réduisez les arrêts et améliorez votre productivité.",
            feature1_title: "Enregistrements PPM", feature1_text: "Respectez le planning",
            feature2_title: "Checklists", feature2_text: "Assurez la conformité",
            feature3_title: "Rapports", feature3_text: "Suivez les performances",
            feature4_title: "Équipe et équipements", feature4_text: "Collaborez efficacement",
            stat1: "Équipements gérés", stat2: "Membres d'équipe", stat3: "Fiabilité du système",
            footer_left: "Pour un avenir plus fiable",
            tab_signup: "Inscription", tab_login: "Connexion",
            signup_title: "Créez votre compte",
            signup_description: "Rejoignez TapApp et gérez efficacement vos opérations de maintenance en quelques minutes.",
            login_title: "Bon retour",
            login_description: "Connectez-vous pour continuer à gérer vos opérations de maintenance.",
            step_personal: "Informations", step_security: "Sécurité", step_profile: "Profil",
            label_name: "Nom complet", label_matricule: "Matricule",
            label_email: "Adresse e-mail", label_password: "Mot de passe",
            label_confirm: "Confirmer le mot de passe",
            placeholder_name: "Saisissez votre nom complet",
            placeholder_matricule: "Saisissez votre matricule",
            placeholder_email: "vous@entreprise.com",
            placeholder_password: "Créez un mot de passe fort",
            placeholder_confirm: "Confirmez votre mot de passe",
            login_password_placeholder: "Saisissez votre mot de passe",
            email_help: "Nous utiliserons cette adresse pour vous envoyer des informations importantes.",
            strength_empty: "Sécurité du mot de passe", strength_weak: "Faible",
            strength_fair: "Moyen", strength_good: "Bon", strength_strong: "Fort",
            match_ok: "Les mots de passe correspondent.", match_no: "Les mots de passe ne correspondent pas.",
            terms_text: "J'accepte les", terms_link: "Conditions d'utilisation",
            and_text: "et la", privacy_link: "Politique de confidentialité",
            signup_button: "Créer un compte", login_button: "Se connecter",
            already_account: "Vous avez déjà un compte ?", login_link: "Connexion",
            no_account: "Vous n'avez pas de compte ?", signup_link: "Inscription",
            forgot_password: "Mot de passe oublié ?", remember_me: "Se souvenir de moi",
            secure_note: "Sécurisé et fiable • Vos données comptent",
            error_heading: "Veuillez vérifier les éléments suivants :",
            email_hint: "Adresses e-mail suggérées"
        },
        ar: {
            brand_subtitle: "إدارة الصيانة",
            eyebrow: "صيانة ذكية • أداء أفضل",
            hero_line1: "أدر",
            hero_line2: "عمليات الصيانة",
            hero_line3: "بذكاء",
            hero_description: "يساعدك TapApp على تخطيط وإدارة عمليات الصيانة بسهولة، وتقليل التوقفات وتحسين الإنتاجية في مكان واحد.",
            feature1_title: "سجلات الصيانة", feature1_text: "حافظ على الجدول",
            feature2_title: "قوائم الفحص", feature2_text: "ضمان الامتثال",
            feature3_title: "التقارير", feature3_text: "تابع الأداء",
            feature4_title: "الفريق والمعدات", feature4_text: "تعاون بكفاءة",
            stat1: "معدات مُدارة", stat2: "أعضاء الفريق", stat3: "موثوقية النظام",
            footer_left: "من أجل مستقبل أكثر موثوقية",
            tab_signup: "إنشاء حساب", tab_login: "تسجيل الدخول",
            signup_title: "أنشئ حسابك",
            signup_description: "انضم إلى TapApp وابدأ إدارة عمليات الصيانة بكفاءة خلال دقائق.",
            login_title: "مرحباً بعودتك",
            login_description: "سجّل الدخول لمتابعة إدارة عمليات الصيانة.",
            step_personal: "المعلومات الشخصية", step_security: "الأمان", step_profile: "الملف الشخصي",
            label_name: "الاسم الكامل", label_matricule: "الرقم الوظيفي",
            label_email: "البريد الإلكتروني", label_password: "كلمة المرور",
            label_confirm: "تأكيد كلمة المرور",
            placeholder_name: "أدخل اسمك الكامل",
            placeholder_matricule: "أدخل رقمك الوظيفي",
            placeholder_email: "you@company.com",
            placeholder_password: "أنشئ كلمة مرور قوية",
            placeholder_confirm: "أكد كلمة المرور",
            login_password_placeholder: "أدخل كلمة المرور",
            email_help: "سنستخدم هذا البريد لإرسال التحديثات المهمة.",
            strength_empty: "قوة كلمة المرور", strength_weak: "ضعيفة",
            strength_fair: "متوسطة", strength_good: "جيدة", strength_strong: "قوية",
            match_ok: "كلمتا المرور متطابقتان.", match_no: "كلمتا المرور غير متطابقتين.",
            terms_text: "أوافق على", terms_link: "شروط الخدمة",
            and_text: "و", privacy_link: "سياسة الخصوصية",
            signup_button: "إنشاء الحساب", login_button: "تسجيل الدخول",
            already_account: "لديك حساب بالفعل؟", login_link: "تسجيل الدخول",
            no_account: "ليس لديك حساب؟", signup_link: "إنشاء حساب",
            forgot_password: "هل نسيت كلمة المرور؟", remember_me: "تذكرني على هذا الجهاز",
            secure_note: "آمن وموثوق • بياناتك مهمة",
            error_heading: "يرجى التحقق مما يلي:",
            email_hint: "عناوين بريد مقترحة"
        }
    };

    const languageSelect = document.getElementById('language');

    function translatePage(language) {
        const dictionary = translations[language] || translations.en;

        document.querySelectorAll('[data-i18n]').forEach(element => {
            const key = element.dataset.i18n;
            if (dictionary[key]) element.textContent = dictionary[key];
        });

        document.querySelectorAll('[data-i18n-placeholder]').forEach(element => {
            const key = element.dataset.i18nPlaceholder;
            if (dictionary[key]) element.placeholder = dictionary[key];
        });

        document.documentElement.lang = language;
        document.documentElement.dir = language === 'ar' ? 'rtl' : 'ltr';
        document.body.classList.toggle('rtl', language === 'ar');

        // Preserve a consistent input layout for email and password fields.
        document.querySelectorAll('input[type="email"], input[type="password"]').forEach(input => {
            input.dir = 'ltr';
        });

        updatePasswordStrength();
        updatePasswordMatch();
    }

    languageSelect.addEventListener('change', event => {
        translatePage(event.target.value);
    });

    // Switch between the two forms with an animation.
    function showView(view) {
        const isSignup = view === 'signup';

        document.getElementById('signupView').classList.toggle('active', isSignup);
        document.getElementById('loginView').classList.toggle('active', !isSignup);

        document.getElementById('signupTab').classList.toggle('active', isSignup);
        document.getElementById('loginTab').classList.toggle('active', !isSignup);

        document.getElementById('signupTab').setAttribute('aria-selected', String(isSignup));
        document.getElementById('loginTab').setAttribute('aria-selected', String(!isSignup));
    }

    document.querySelectorAll('[data-view]').forEach(button => {
        button.addEventListener('click', event => {
            event.preventDefault();
            showView(button.dataset.view);
        });
    });

    // If Laravel returns registration errors, keep the signup view visible.
    @if ($errors->any())
        showView('signup');
    @endif

    // If a user navigates to /register?view=login, show login.
    if (new URLSearchParams(window.location.search).get('view') === 'login') {
        showView('login');
    }

    // Show / hide password fields.
    document.querySelectorAll('.password-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.target);
            const icon = button.querySelector('i');
            const showing = input.type === 'text';

            input.type = showing ? 'password' : 'text';
            icon.classList.toggle('fa-eye', showing);
            icon.classList.toggle('fa-eye-slash', !showing);
            button.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
        });
    });

    // Password strength.
    const passwordInput = document.getElementById('register_password');
    const strengthBars = document.querySelectorAll('.strength-bars span');
    const strengthLabel = document.getElementById('strengthLabel');

    function updatePasswordStrength() {
        if (!passwordInput) return;

        const value = passwordInput.value;
        let score = 0;

        if (value.length >= 8) score++;
        if (/[a-z]/.test(value) && /[A-Z]/.test(value)) score++;
        if (/\d/.test(value)) score++;
        if (/[^A-Za-z0-9]/.test(value)) score++;

        const lang = languageSelect.value;
        const dictionary = translations[lang] || translations.en;
        const labels = [
            dictionary.strength_empty,
            dictionary.strength_weak,
            dictionary.strength_fair,
            dictionary.strength_good,
            dictionary.strength_strong
        ];

        const colors = ['#dfe5ed', '#e5484d', '#f0a020', '#7bbf43', '#16a36a'];

        strengthBars.forEach((bar, index) => {
            bar.style.background = index < score ? colors[score] : 'var(--border)';
        });

        strengthLabel.textContent = value.length ? labels[score] : labels[0];
    }

    passwordInput.addEventListener('input', () => {
        updatePasswordStrength();
        updatePasswordMatch();
    });

    // Confirm password feedback.
    const confirmInput = document.getElementById('password_confirmation');
    const matchMessage = document.getElementById('passwordMatch');

    function updatePasswordMatch() {
        if (!confirmInput || !passwordInput || !confirmInput.value) {
            matchMessage.textContent = '';
            return;
        }

        const dictionary = translations[languageSelect.value] || translations.en;
        const matches = confirmInput.value === passwordInput.value;

        matchMessage.textContent = matches ? dictionary.match_ok : dictionary.match_no;
        matchMessage.style.color = matches ? '#16a36a' : '#e5484d';
    }

    confirmInput.addEventListener('input', updatePasswordMatch);

    // Email domain suggestions.
    const emailInput = document.getElementById('register_email');
    const suggestionsBox = document.getElementById('emailSuggestions');
    const emailDomains = ['gmail.com', 'outlook.com', 'hotmail.com', 'yahoo.com', 'icloud.com'];

    emailInput.addEventListener('input', () => {
        const value = emailInput.value.trim();
        suggestionsBox.innerHTML = '';

        if (!value || value.includes('@') || value.includes(' ')) {
            suggestionsBox.style.display = 'none';
            return;
        }

        emailDomains.forEach(domain => {
            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = value + '@' + domain;

            button.addEventListener('click', () => {
                emailInput.value = value + '@' + domain;
                suggestionsBox.style.display = 'none';
                emailInput.focus();
            });

            suggestionsBox.appendChild(button);
        });

        suggestionsBox.style.display = 'block';
    });

    document.addEventListener('click', event => {
        if (!event.target.closest('#register_email') &&
            !event.target.closest('#emailSuggestions')) {
            suggestionsBox.style.display = 'none';
        }
    });

    // Theme toggle.
    const themeToggle = document.getElementById('themeToggle');

    themeToggle.addEventListener('click', () => {
        document.body.classList.toggle('dark');
        const icon = themeToggle.querySelector('i');

        icon.classList.toggle('fa-sun');
        icon.classList.toggle('fa-moon');
    });

    // Prevent accidental double submission; the server remains responsible for validation.
    document.querySelectorAll('#registerForm, #loginForm').forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                form.reportValidity();
                return;
            }

            const button = form.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.style.opacity = '.75';

                const label = button.querySelector('span');
                if (label) {
                    label.textContent = languageSelect.value === 'fr'
                        ? 'Veuillez patienter…'
                        : languageSelect.value === 'ar'
                            ? 'يرجى الانتظار…'
                            : 'Please wait…';
                }
            }
        });
    });

    // Initial translation and form state.
    translatePage('en');
</script>
</body>
</html>
