<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#101318">
    <title>Login | TapApp Maintenance Management</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        :root {
            --orange: #ff7900;
            --orange-dark: #f45100;
            --page: #f2f5fa;
            --surface: #fff;
            --text: #152033;
            --muted: #7a879a;
            --border: #e3e8f0;
            --input: #fff;
            --shadow: 0 25px 80px rgba(22, 34, 54, .10);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, sans-serif;
            color: var(--text);
            background: var(--page);
        }

        button, input, select { font: inherit; }
        button { cursor: pointer; }

        .app {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(340px, 43%) minmax(0, 57%);
        }

        /* BRANDING PANEL */

        .brand-panel {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            min-height: 100vh;
            padding: 35px clamp(25px, 4vw, 62px);
            color: #fff;
            background:
                radial-gradient(ellipse at 80% 50%, rgba(255, 93, 0, .22), transparent 45%),
                linear-gradient(145deg, #171c24, #080a0e 75%);
            display: flex;
            flex-direction: column;
        }

        .brand-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            background: repeating-linear-gradient(
                135deg,
                transparent 0,
                transparent 90px,
                rgba(255, 255, 255, .018) 91px,
                transparent 92px
            );
            animation: backgroundMove 18s linear infinite;
        }

        .brand-panel::after {
            content: "";
            position: absolute;
            z-index: -1;
            width: 300px;
            height: 300px;
            border: 1px solid rgba(255, 121, 0, .5);
            border-radius: 50%;
            right: -190px;
            top: 27%;
            box-shadow: 0 0 0 35px rgba(255, 121, 0, .04),
                        0 0 0 70px rgba(255, 121, 0, .025);
            animation: rotate 25s linear infinite;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 13px;
            color: white;
            text-decoration: none;
            width: fit-content;
        }

        .logo-icon {
            color: var(--orange);
            font-size: 42px;
            filter: drop-shadow(0 0 12px rgba(255, 121, 0, .3));
        }

        .logo strong {
            display: block;
            font-size: 27px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .logo strong span { color: var(--orange); }

        .logo small {
            display: block;
            color: #bdc5d1;
            font-size: 11px;
            margin-top: 3px;
        }

        .eyebrow {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 75px;
            color: #d6dce5;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.6px;
        }

        .eyebrow::before {
            content: "";
            width: 38px;
            height: 2px;
            background: var(--orange);
        }

        .hero-title {
            margin: 25px 0 17px;
            font-size: clamp(36px, 4vw, 57px);
            font-weight: 800;
            letter-spacing: -2px;
            line-height: 1.12;
        }

        .hero-title .orange { color: var(--orange); }

        .hero-description {
            max-width: 440px;
            color: #bac3cf;
            font-size: 13px;
            line-height: 1.9;
        }

        .feature-list {
            display: grid;
            gap: 21px;
            margin-top: 34px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 14px;
            animation: slideUp .7s both;
        }

        .feature:nth-child(2) { animation-delay: .12s; }
        .feature:nth-child(3) { animation-delay: .24s; }
        .feature:nth-child(4) { animation-delay: .36s; }

        .feature-icon {
            width: 45px;
            height: 45px;
            flex-shrink: 0;
            display: grid;
            place-items: center;
            color: var(--orange);
            font-size: 17px;
            border: 1px solid rgba(255, 121, 0, .6);
            border-radius: 13px;
            background: linear-gradient(140deg, rgba(255,121,0,.2), rgba(255,121,0,.025));
            transition: transform .25s, background .25s;
        }

        .feature:hover .feature-icon {
            transform: translateY(-4px) rotate(-4deg);
            background: rgba(255,121,0,.3);
        }

        .feature strong { display: block; font-size: 12px; }
        .feature small { display: block; margin-top: 5px; color: #aeb8c5; font-size: 11px; }

        .brand-bottom { margin-top: auto; padding-top: 35px; }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border: 1px solid rgba(255,121,0,.35);
            border-radius: 16px;
            background: rgba(255,255,255,.035);
            overflow: hidden;
        }

        .stat { padding: 16px 8px; text-align: center; }
        .stat i { color: var(--orange); font-size: 18px; margin-bottom: 8px; }
        .stat strong { display: block; font-size: 17px; }
        .stat small { display: block; margin-top: 5px; font-size: 9px; color: #aeb8c5; }

        .brand-footer {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-top: 18px;
            color: #8993a1;
            font-size: 10px;
        }

        /* LOGIN AREA */

        .main-panel {
            min-width: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 24px clamp(18px, 4vw, 60px);
            background:
                radial-gradient(ellipse at 85% 8%, rgba(255,121,0,.08), transparent 35%),
                radial-gradient(ellipse at 10% 95%, rgba(99,140,190,.08), transparent 35%),
                var(--page);
        }

        .topbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 17px;
            margin-bottom: 28px;
        }

        .language-wrap {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .language-wrap i { font-size: 14px; }

        .language-select {
            padding: 8px 5px;
            border: 0;
            outline: none;
            color: var(--text);
            background: transparent;
            font-size: 12px;
            cursor: pointer;
        }

        .theme-button {
            width: 54px;
            height: 29px;
            border: 1px solid var(--border);
            border-radius: 30px;
            padding: 3px;
            background: #e0e6ef;
            color: #364152;
            display: flex;
            align-items: center;
        }

        .theme-button span {
            width: 21px;
            height: 21px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: white;
            box-shadow: 0 2px 6px #0002;
            transition: transform .25s;
        }

        .auth-card {
            width: 100%;
            max-width: 570px;
            margin: auto;
            padding: clamp(25px, 4vw, 43px);
            background: var(--surface);
            border: 1px solid rgba(255,255,255,.8);
            border-radius: 24px;
            box-shadow: var(--shadow);
            animation: appear .6s ease both;
        }

        .auth-tabs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px;
            padding: 5px;
            margin: 0 auto 32px;
            max-width: 310px;
            border: 1px solid var(--border);
            background: var(--page);
            border-radius: 50px;
        }

        .auth-tab {
            border: 0;
            padding: 13px;
            border-radius: 40px;
            color: var(--muted);
            background: transparent;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            transition: all .25s;
        }

        .auth-tab.active {
            color: white;
            background: linear-gradient(110deg, var(--orange-dark), var(--orange));
            box-shadow: 0 5px 16px rgba(255,105,0,.24);
        }

        .auth-tab:not(.active):hover { color: var(--orange); }

        .heading { margin-bottom: 28px; }

        .heading h1 {
            margin: 0 0 12px;
            font-size: clamp(27px, 3vw, 34px);
            letter-spacing: -1.2px;
            color: var(--text);
        }

        .heading p {
            margin: 0;
            max-width: 430px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.9;
        }

        .welcome-icon {
            width: 57px;
            height: 57px;
            margin-bottom: 22px;
            border-radius: 17px;
            display: grid;
            place-items: center;
            color: var(--orange);
            font-size: 22px;
            background: linear-gradient(145deg, rgba(255,121,0,.16), rgba(255,121,0,.04));
            border: 1px solid rgba(255,121,0,.2);
            animation: float 3s ease-in-out infinite;
        }

        .field { margin-bottom: 20px; }

        .field label {
            display: block;
            margin-bottom: 9px;
            color: var(--text);
            font-size: 12px;
            font-weight: 700;
        }

        .input-wrap { position: relative; }

        .input-wrap .field-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #8190a4;
            font-size: 14px;
            pointer-events: none;
        }

        .input-wrap input {
            display: block;
            width: 100%;
            height: 50px;
            padding: 0 46px;
            color: var(--text);
            background: var(--input);
            border: 1px solid var(--border);
            border-radius: 12px;
            outline: none;
            font-size: 12px;
            transition: border-color .2s, box-shadow .2s;
        }

        .input-wrap input::placeholder { color: #9ba6b6; }

        .input-wrap input:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 4px rgba(255,121,0,.09);
        }

        .input-wrap input.is-invalid { border-color: #e5484d; }

        .password-toggle {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            padding: 6px;
            border: 0;
            background: transparent;
            color: #8491a3;
        }

        .email-suggestions {
            position: absolute;
            z-index: 5;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            display: none;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--surface);
            box-shadow: var(--shadow);
        }

        .email-suggestions.show { display: block; animation: slideUp .2s ease; }

        .email-suggestion {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 12px 14px;
            border: 0;
            text-align: left;
            background: var(--surface);
            color: var(--text);
            font-size: 12px;
        }

        .email-suggestion:hover,
        .email-suggestion.active {
            background: rgba(255,121,0,.09);
            color: var(--orange);
        }

        .suggestion-icon {
            width: 30px;
            height: 30px;
            display: grid;
            place-items: center;
            border-radius: 9px;
            background: rgba(255,121,0,.12);
            color: var(--orange);
        }

        .suggestion-hint { margin-left: auto; color: var(--muted); font-size: 10px; }

        .field-error {
            display: block;
            margin-top: 7px;
            color: #e5484d;
            font-size: 11px;
        }

        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin: 4px 0 25px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            font-size: 11px;
            cursor: pointer;
        }

        .remember input {
            width: 15px;
            height: 15px;
            accent-color: var(--orange);
        }

        .link {
            color: var(--orange-dark);
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
        }

        .link:hover { text-decoration: underline; }

        .submit-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 11px;
            width: 100%;
            min-height: 52px;
            padding: 14px 18px;
            border: 0;
            border-radius: 13px;
            color: white;
            font-size: 13px;
            font-weight: 700;
            background: linear-gradient(110deg, var(--orange-dark), var(--orange));
            box-shadow: 0 8px 20px rgba(255,105,0,.23);
            transition: transform .2s, box-shadow .2s;
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(255,105,0,.3);
        }

        .submit-button:disabled { opacity: .7; cursor: wait; }

        .bottom-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            flex-wrap: wrap;
            margin-top: 25px;
            color: var(--muted);
            font-size: 11px;
        }

        .separator { flex: 1; min-width: 15px; height: 1px; background: var(--border); }

        .alert {
            padding: 12px 14px;
            margin-bottom: 20px;
            border-radius: 11px;
            font-size: 12px;
            line-height: 1.7;
        }

        .alert-success { background: #eafaf0; color: #16713a; border: 1px solid #c6efd4; }
        .alert-error { background: #fff0f0; color: #a5262b; border: 1px solid #ffd0d0; }
        .alert ul { margin: 5px 0 0; padding-left: 20px; }

        .security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 25px;
            color: var(--muted);
            font-size: 10px;
        }

        .security-note i { color: #16a36a; }

        /* DARK THEME */

        body.dark {
            --page: #10141b;
            --surface: #191f29;
            --text: #edf2f9;
            --muted: #a2aec0;
            --border: #303948;
            --input: #141922;
            --shadow: 0 25px 80px rgba(0,0,0,.3);
        }

        body.dark .theme-button { background: #354153; color: white; }
        body.dark .theme-button span {
            transform: translateX(23px);
            background: #202938;
        }
        body.dark .alert-success { background: #183025; color: #b4f3cb; border-color: #245a3b; }
        body.dark .alert-error { background: #351d22; color: #ffb9bd; border-color: #633038; }

        /* RTL */

        body.rtl .brand-panel,
        body.rtl .main-panel { text-align: right; }

        body.rtl .eyebrow { flex-direction: row-reverse; }
        body.rtl .input-wrap .field-icon { left: auto; right: 16px; }
        body.rtl .input-wrap input { padding-left: 46px; padding-right: 46px; }
        body.rtl .password-toggle { right: auto; left: 13px; }
        body.rtl .feature { flex-direction: row-reverse; }
        body.rtl .brand-footer { flex-direction: row-reverse; }
        body.rtl .bottom-link { direction: rtl; }
        body.rtl .options-row { direction: rtl; }
        body.rtl .remember { flex-direction: row-reverse; }
        body.rtl .email-suggestion { text-align: right; }
        body.rtl .suggestion-hint { margin-left: 0; margin-right: auto; }

        @keyframes appear {
            from { opacity: 0; transform: translateY(14px) scale(.99); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        @keyframes rotate { to { transform: rotate(360deg); } }
        @keyframes backgroundMove { to { background-position: 180px 180px; } }

        @media (max-width: 1000px) {
            .app { grid-template-columns: minmax(285px, 37%) minmax(0, 63%); }
            .brand-panel { padding: 28px; }
            .hero-title { font-size: 40px; }
            .main-panel { padding: 20px; }
            .auth-card { padding: 27px; }
        }

        @media (max-width: 760px) {
            .app { display: block; }
            .brand-panel { min-height: auto; padding: 24px; }
            .logo-icon { font-size: 35px; }
            .logo strong { font-size: 23px; }
            .eyebrow { margin-top: 35px; }
            .hero-title { font-size: 38px; }
            .feature-list { grid-template-columns: 1fr 1fr; gap: 15px; }
            .feature-icon { width: 39px; height: 39px; }
            .brand-bottom { padding-top: 25px; }
            .main-panel { min-height: auto; padding: 18px 14px 35px; }
            .topbar { margin-bottom: 18px; }
            .auth-card { margin: 0 auto; }
        }

        @media (max-width: 480px) {
            .brand-panel { padding: 22px 18px; }
            .hero-title { font-size: 33px; letter-spacing: -1.4px; }
            .feature { gap: 9px; }
            .feature strong { font-size: 10px; }
            .feature small { font-size: 9px; }
            .stat { padding: 12px 4px; }
            .stat strong { font-size: 14px; }
            .stat small { font-size: 8px; }
            .auth-card { padding: 23px 17px; border-radius: 19px; }
            .heading h1 { font-size: 28px; }
            .bottom-link { gap: 7px; }
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
<div class="app">

    <!-- LEFT BRAND PANEL -->
    <aside class="brand-panel">
        <a href="{{ url('/') }}" class="logo">
            <i class="fa-solid fa-gear logo-icon"></i>
            <span>
                <strong>TAP<span>APP</span></strong>
                <small data-i18n="brand_subtitle">Maintenance Management</small>
            </span>
        </a>

        <div class="eyebrow" data-i18n="eyebrow">
            SMART MAINTENANCE • BETTER PERFORMANCE
        </div>

        <h2 class="hero-title">
            <span data-i18n="hero1">Welcome</span><br>
            <span data-i18n="hero2">back to</span><br>
            <span class="orange">TapApp.</span>
        </h2>

        <p class="hero-description" data-i18n="hero_description">
            Your maintenance workspace is waiting. Sign in to monitor equipment,
            manage PPM records and keep your team operating at its best.
        </p>

        <div class="feature-list">
            <div class="feature">
                <div class="feature-icon"><i class="fa-solid fa-clipboard-check"></i></div>
                <div>
                    <strong data-i18n="feature1">PPM Management</strong>
                    <small data-i18n="feature1_sub">Keep every intervention on schedule</small>
                </div>
            </div>

            <div class="feature">
                <div class="feature-icon"><i class="fa-solid fa-list-check"></i></div>
                <div>
                    <strong data-i18n="feature2">Smart Checklists</strong>
                    <small data-i18n="feature2_sub">Standardize maintenance inspections</small>
                </div>
            </div>

            <div class="feature">
                <div class="feature-icon"><i class="fa-solid fa-chart-line"></i></div>
                <div>
                    <strong data-i18n="feature3">Performance Reports</strong>
                    <small data-i18n="feature3_sub">Turn maintenance data into insights</small>
                </div>
            </div>

            <div class="feature">
                <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <div>
                    <strong data-i18n="feature4">Secure Workspace</strong>
                    <small data-i18n="feature4_sub">Access based on your account permissions</small>
                </div>
            </div>
        </div>

        <div class="brand-bottom">
            <div class="stats">
                <div class="stat">
                    <i class="fa-solid fa-gears"></i>
                    <strong>PPM</strong>
                    <small data-i18n="stat1">Maintenance</small>
                </div>
                <div class="stat">
                    <i class="fa-solid fa-users"></i>
                    <strong data-i18n="stat2_value">Team</strong>
                    <small data-i18n="stat2">Collaboration</small>
                </div>
                <div class="stat">
                    <i class="fa-solid fa-shield-halved"></i>
                    <strong>24/7</strong>
                    <small data-i18n="stat3">Access</small>
                </div>
            </div>

            <div class="brand-footer">
                <span data-i18n="footer">Built for a more reliable tomorrow</span>
                <span>© {{ date('Y') }} TapApp</span>
            </div>
        </div>
    </aside>

    <!-- RIGHT LOGIN PANEL -->
    <main class="main-panel">
        <header class="topbar">
            <label class="language-wrap" for="language">
                <i class="fa-solid fa-globe"></i>
                <select class="language-select" id="language" aria-label="Choose language">
                    <option value="en">English</option>
                    <option value="fr">Français</option>
                    <option value="ar">العربية</option>
                </select>
            </label>

            <button type="button" class="theme-button" id="themeToggle"
                    aria-label="Toggle dark mode" title="Toggle theme">
                <span><i class="fa-solid fa-sun"></i></span>
            </button>
        </header>

        <section class="auth-card">
            <nav class="auth-tabs" aria-label="Authentication">
                <a href="{{ route('register') }}" class="auth-tab" data-i18n="signup_tab">
                    Sign Up
                </a>
                <a href="{{ route('login') }}" class="auth-tab active" data-i18n="login_tab"
                   aria-current="page">
                    Login
                </a>
            </nav>

            <div class="welcome-icon">
                <i class="fa-solid fa-right-to-bracket"></i>
            </div>

            <div class="heading">
                <h1 data-i18n="title">Welcome back</h1>
                <p data-i18n="subtitle">
                    Sign in to your account and get back to managing your maintenance operations.
                </p>
            </div>

            <!-- Laravel session status -->
            @if (session('status'))
                <div class="alert alert-success" role="status">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Laravel validation errors -->
            @if ($errors->any())
                <div class="alert alert-error" role="alert">
                    <strong data-i18n="error_title">We couldn't sign you in.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <div class="field">
                    <label for="email" data-i18n="email_label">Email address</label>

                    <div class="input-wrap">
                        <i class="fa-regular fa-envelope field-icon"></i>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="you@company.com"
                            data-i18n-placeholder="email_placeholder"
                            autocomplete="username"
                            spellcheck="false"
                            required
                            autofocus
                            class="@error('email') is-invalid @enderror"
                            aria-describedby="email-error"
                        >

                        <div class="email-suggestions" id="emailSuggestions"
                             role="listbox" aria-label="Email suggestions"></div>
                    </div>

                    @error('email')
                        <span class="field-error" id="email-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label for="password" data-i18n="password_label">Password</label>

                    <div class="input-wrap">
                        <i class="fa-solid fa-lock field-icon"></i>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Enter your password"
                            data-i18n-placeholder="password_placeholder"
                            autocomplete="current-password"
                            required
                            class="@error('password') is-invalid @enderror"
                        >

                        <button type="button" class="password-toggle"
                                id="togglePassword" aria-label="Show password">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>

                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="options-row">
                    <label class="remember" for="remember_me">
                        <input id="remember_me" type="checkbox" name="remember" value="1">
                        <span data-i18n="remember">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="link" href="{{ route('password.request') }}"
                           data-i18n="forgot">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="submit-button" id="loginButton">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span data-i18n="submit">Sign In</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            <div class="bottom-link">
                <span class="separator"></span>
                <span data-i18n="no_account">New to TapApp?</span>
                <a href="{{ route('register') }}" class="link" data-i18n="create_account">
                    Create an account
                </a>
                <span class="separator"></span>
            </div>

            <div class="security-note">
                <i class="fa-solid fa-shield-halved"></i>
                <span data-i18n="security_note">Secure login • Your account is protected</span>
            </div>
        </section>
    </main>
</div>

<script>
    const translations = {
        en: {
            brand_subtitle: "Maintenance Management",
            eyebrow: "SMART MAINTENANCE • BETTER PERFORMANCE",
            hero1: "Welcome", hero2: "back to",
            hero_description: "Your maintenance workspace is waiting. Sign in to monitor equipment, manage PPM records and keep your team operating at its best.",
            feature1: "PPM Management", feature1_sub: "Keep every intervention on schedule",
            feature2: "Smart Checklists", feature2_sub: "Standardize maintenance inspections",
            feature3: "Performance Reports", feature3_sub: "Turn maintenance data into insights",
            feature4: "Secure Workspace", feature4_sub: "Access based on your account permissions",
            stat1: "Maintenance", stat2_value: "Team", stat2: "Collaboration",
            stat3: "Access", footer: "Built for a more reliable tomorrow",
            signup_tab: "Sign Up", login_tab: "Login",
            title: "Welcome back",
            subtitle: "Sign in to your account and get back to managing your maintenance operations.",
            error_title: "We couldn't sign you in.",
            email_label: "Email address", email_placeholder: "you@company.com",
            password_label: "Password", password_placeholder: "Enter your password",
            remember: "Remember me", forgot: "Forgot password?",
            submit: "Sign In", no_account: "New to TapApp?",
            create_account: "Create an account",
            security_note: "Secure login • Your account is protected",
            wait: "Signing in…", show_password: "Show password", hide_password: "Hide password"
        },
        fr: {
            brand_subtitle: "Gestion de la maintenance",
            eyebrow: "MAINTENANCE INTELLIGENTE • MEILLEURES PERFORMANCES",
            hero1: "Bienvenue", hero2: "sur",
            hero_description: "Votre espace de maintenance vous attend. Connectez-vous pour suivre les équipements, gérer les enregistrements PPM et collaborer avec votre équipe.",
            feature1: "Gestion PPM", feature1_sub: "Respectez le calendrier des interventions",
            feature2: "Checklists intelligentes", feature2_sub: "Standardisez les inspections",
            feature3: "Rapports de performance", feature3_sub: "Analysez les données de maintenance",
            feature4: "Espace sécurisé", feature4_sub: "Accès selon vos autorisations",
            stat1: "Maintenance", stat2_value: "Équipe", stat2: "Collaboration",
            stat3: "Accès", footer: "Pour un avenir plus fiable",
            signup_tab: "Inscription", login_tab: "Connexion",
            title: "Bon retour",
            subtitle: "Connectez-vous à votre compte pour reprendre la gestion de vos opérations de maintenance.",
            error_title: "Connexion impossible.",
            email_label: "Adresse e-mail", email_placeholder: "vous@entreprise.com",
            password_label: "Mot de passe", password_placeholder: "Saisissez votre mot de passe",
            remember: "Se souvenir de moi", forgot: "Mot de passe oublié ?",
            submit: "Se connecter", no_account: "Nouveau sur TapApp ?",
            create_account: "Créer un compte",
            security_note: "Connexion sécurisée • Votre compte est protégé",
            wait: "Connexion en cours…", show_password: "Afficher le mot de passe",
            hide_password: "Masquer le mot de passe"
        },
        ar: {
            brand_subtitle: "إدارة الصيانة",
            eyebrow: "صيانة ذكية • أداء أفضل",
            hero1: "مرحباً", hero2: "بعودتك إلى",
            hero_description: "مساحة الصيانة الخاصة بك في انتظارك. سجّل الدخول لمتابعة المعدات وإدارة سجلات الصيانة والتعاون مع فريقك.",
            feature1: "إدارة الصيانة", feature1_sub: "التزم بمواعيد التدخلات",
            feature2: "قوائم فحص ذكية", feature2_sub: "توحيد عمليات الفحص",
            feature3: "تقارير الأداء", feature3_sub: "تحليل بيانات الصيانة",
            feature4: "مساحة آمنة", feature4_sub: "الوصول حسب صلاحيات حسابك",
            stat1: "الصيانة", stat2_value: "الفريق", stat2: "التعاون",
            stat3: "الوصول", footer: "من أجل مستقبل أكثر موثوقية",
            signup_tab: "إنشاء حساب", login_tab: "تسجيل الدخول",
            title: "مرحباً بعودتك",
            subtitle: "سجّل الدخول إلى حسابك لمتابعة إدارة عمليات الصيانة.",
            error_title: "تعذّر تسجيل الدخول.",
            email_label: "البريد الإلكتروني", email_placeholder: "you@company.com",
            password_label: "كلمة المرور", password_placeholder: "أدخل كلمة المرور",
            remember: "تذكرني", forgot: "نسيت كلمة المرور؟",
            submit: "تسجيل الدخول", no_account: "مستخدم جديد في TapApp؟",
            create_account: "إنشاء حساب",
            security_note: "تسجيل دخول آمن • حسابك محمي",
            wait: "جارٍ تسجيل الدخول…", show_password: "إظهار كلمة المرور",
            hide_password: "إخفاء كلمة المرور"
        }
    };

    const languageSelect = document.getElementById('language');

    function translatePage(language) {
        const dict = translations[language] || translations.en;

        document.querySelectorAll('[data-i18n]').forEach(element => {
            const key = element.dataset.i18n;
            if (dict[key]) element.textContent = dict[key];
        });

        document.querySelectorAll('[data-i18n-placeholder]').forEach(element => {
            const key = element.dataset.i18nPlaceholder;
            if (dict[key]) element.placeholder = dict[key];
        });

        document.documentElement.lang = language;
        document.documentElement.dir = language === 'ar' ? 'rtl' : 'ltr';
        document.body.classList.toggle('rtl', language === 'ar');

        document.getElementById('email').dir = 'ltr';
        document.getElementById('password').dir = 'ltr';

        document.getElementById('togglePassword').setAttribute(
            'aria-label',
            document.getElementById('password').type === 'password'
                ? dict.show_password : dict.hide_password
        );
    }

    languageSelect.addEventListener('change', event => {
        translatePage(event.target.value);
    });

    // Password visibility
    const password = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    togglePassword.addEventListener('click', () => {
        const isHidden = password.type === 'password';
        password.type = isHidden ? 'text' : 'password';

        const icon = togglePassword.querySelector('i');
        icon.classList.toggle('fa-eye', !isHidden);
        icon.classList.toggle('fa-eye-slash', isHidden);

        const dict = translations[languageSelect.value] || translations.en;
        togglePassword.setAttribute(
            'aria-label',
            isHidden ? dict.hide_password : dict.show_password
        );
    });

    // Theme toggle for this page.
    document.getElementById('themeToggle').addEventListener('click', () => {
        document.body.classList.toggle('dark');

        const icon = document.querySelector('#themeToggle i');
        icon.classList.toggle('fa-sun');
        icon.classList.toggle('fa-moon');
    });

    // Email autocomplete with keyboard support.
    const emailInput = document.getElementById('email');
    const suggestionsBox = document.getElementById('emailSuggestions');

    const domains = [
        'gmail.com', 'outlook.com', 'hotmail.com',
        'yahoo.com', 'icloud.com'
    ];

    const corrections = {
        'gmial.com': 'gmail.com',
        'gmal.com': 'gmail.com',
        'gmai.com': 'gmail.com',
        'gmail.con': 'gmail.com',
        'yaho.com': 'yahoo.com',
        'yhoo.com': 'yahoo.com',
        'yahoo.con': 'yahoo.com',
        'outlok.com': 'outlook.com',
        'outloo.com': 'outlook.com',
        'hotmail.con': 'hotmail.com',
        'hotmial.com': 'hotmail.com',
        'icloud.con': 'icloud.com'
    };

    let currentSuggestions = [];
    let selectedIndex = -1;

    function hideSuggestions() {
        suggestionsBox.classList.remove('show');
        selectedIndex = -1;
    }

    function selectSuggestion(index) {
        if (index < 0 || index >= currentSuggestions.length) return;

        const value = emailInput.value.trim();
        const username = value.split('@')[0];

        emailInput.value = username + '@' + currentSuggestions[index];
        hideSuggestions();
        emailInput.focus();
    }

    function updateSuggestions() {
        const value = emailInput.value.trim();
        const at = value.indexOf('@');

        suggestionsBox.innerHTML = '';
        selectedIndex = -1;

        if (at <= 0) {
            hideSuggestions();
            return;
        }

        const username = value.substring(0, at);
        const typedDomain = value.substring(at + 1).toLowerCase();

        if (domains.includes(typedDomain)) {
            hideSuggestions();
            return;
        }

        if (corrections[typedDomain]) {
            currentSuggestions = [corrections[typedDomain]];
        } else if (!typedDomain) {
            currentSuggestions = [...domains];
        } else {
            currentSuggestions = domains.filter(domain =>
                domain.startsWith(typedDomain)
            );
        }

        if (!currentSuggestions.length) {
            hideSuggestions();
            return;
        }

        currentSuggestions.forEach((domain, index) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'email-suggestion';
            button.setAttribute('role', 'option');

            const icon = document.createElement('span');
            icon.className = 'suggestion-icon';
            icon.innerHTML = '<i class="fa-regular fa-envelope"></i>';

            const text = document.createElement('span');
            text.textContent = username + '@' + domain;

            button.append(icon, text);

            if (index === 0) {
                const hint = document.createElement('span');
                hint.className = 'suggestion-hint';
                hint.textContent = 'Enter';
                button.appendChild(hint);
            }

            button.addEventListener('mousedown', event => {
                event.preventDefault();
                selectSuggestion(index);
            });

            suggestionsBox.appendChild(button);
        });

        suggestionsBox.classList.add('show');
    }

    emailInput.addEventListener('input', updateSuggestions);
    emailInput.addEventListener('focus', () => {
        if (emailInput.value.includes('@')) updateSuggestions();
    });

    emailInput.addEventListener('keydown', event => {
        if (!suggestionsBox.classList.contains('show')) return;

        if (event.key === 'ArrowDown') {
            event.preventDefault();
            selectedIndex = (selectedIndex + 1) % currentSuggestions.length;
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            selectedIndex = (selectedIndex - 1 + currentSuggestions.length)
                % currentSuggestions.length;
        } else if (event.key === 'Enter' && selectedIndex >= 0) {
            event.preventDefault();
            selectSuggestion(selectedIndex);
            return;
        } else if (event.key === 'Escape') {
            hideSuggestions();
            return;
        } else {
            return;
        }

        suggestionsBox.querySelectorAll('.email-suggestion').forEach((item, index) => {
            item.classList.toggle('active', index === selectedIndex);
        });
    });

    document.addEventListener('click', event => {
        if (!emailInput.contains(event.target) &&
            !suggestionsBox.contains(event.target)) {
            hideSuggestions();
        }
    });

    // Loading state; Laravel remains responsible for authentication.
    document.getElementById('loginForm').addEventListener('submit', event => {
        const form = event.currentTarget;

        if (!form.checkValidity()) {
            event.preventDefault();
            form.reportValidity();
            return;
        }

        const button = document.getElementById('loginButton');
        const dict = translations[languageSelect.value] || translations.en;

        button.disabled = true;
        button.querySelector('span').textContent = dict.wait;
        button.querySelectorAll('i').forEach(icon => {
            icon.classList.remove('fa-arrow-right');
        });

        const icon = button.querySelector('.fa-right-to-bracket');
        if (icon) {
            icon.classList.remove('fa-right-to-bracket');
            icon.classList.add('fa-spinner', 'fa-spin');
        }
    });

    translatePage('en');
</script>
</body>
</html>
