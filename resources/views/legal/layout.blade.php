
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#101318">
    <meta name="description" content="@yield('meta_description', 'TapApp legal information and policies.')">

    <title>@yield('title') | TapApp</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        :root {
            --orange: #ff7900;
            --orange2: #ff4d00;
            --dark: #101318;
            --page: #f3f5f9;
            --surface: #fff;
            --text: #172033;
            --muted: #728096;
            --border: #e2e7ef;
            --soft: #fff4eb;
            --shadow: 0 18px 55px rgba(22,34,54,.07);
        }

        * { box-sizing: border-box; }

        html { scroll-behavior: smooth; scroll-padding-top: 100px; }

        body {
            margin: 0;
            background: var(--page);
            color: var(--text);
            font-family: Inter, sans-serif;
            transition: background .3s, color .3s;
        }

        body.dark {
            --page: #0d1118;
            --surface: #171d27;
            --text: #edf2fa;
            --muted: #a3afc2;
            --border: #303947;
            --soft: #302218;
            --shadow: 0 18px 55px rgba(0,0,0,.22);
        }

        a { color: inherit; }
        button, input, select { font: inherit; }
        button { cursor: pointer; }

        .legal-top-progress {
            position: fixed;
            inset: 0 auto auto 0;
            width: 0;
            height: 3px;
            z-index: 1000;
            background: linear-gradient(90deg,var(--orange2),#ffb15b);
            box-shadow: 0 0 12px #ff790066;
        }

        .navbar {
            height: 76px;
            padding: 0 clamp(18px,5vw,76px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            position: sticky;
            top: 0;
            z-index: 100;
            background: color-mix(in srgb, var(--surface) 92%, transparent);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .brand-icon {
            width: 43px;
            height: 43px;
            display: grid;
            place-items: center;
            border-radius: 13px;
            color: white;
            font-size: 20px;
            background: linear-gradient(135deg,var(--orange2),var(--orange));
            box-shadow: 0 6px 18px #ff790033;
        }

        .brand-name { font-size: 22px; font-weight: 800; letter-spacing: -1px; }
        .brand-name span { color: var(--orange); }
        .brand-sub { display: block; color: var(--muted); font-size: 9px; margin-top: 3px; }

        .nav-actions { display: flex; align-items: center; gap: 10px; }

        .nav-link {
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            padding: 11px 13px;
            border-radius: 10px;
            transition: .2s;
        }

        .nav-link:hover { background: var(--soft); color: var(--orange); }

        .nav-cta {
            color: white;
            background: linear-gradient(110deg,var(--orange2),var(--orange));
        }

        .nav-cta:hover { color: white; transform: translateY(-2px); }

        .icon-button {
            width: 39px;
            height: 39px;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text);
            border-radius: 11px;
            transition: .2s;
        }

        .icon-button:hover { color: var(--orange); border-color: var(--orange); }

        .hero {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            padding: 67px 22px 82px;
            text-align: center;
            color: white;
            background:
                radial-gradient(ellipse at 15% 90%, #ff790025, transparent 40%),
                radial-gradient(ellipse at 90% 0%, #ff790033, transparent 35%),
                linear-gradient(140deg,#191e27,#090b10 75%);
        }

        .hero::before, .hero::after {
            content: "";
            position: absolute;
            z-index: -1;
            width: 250px;
            height: 250px;
            border: 1px solid #ff790033;
            border-radius: 50%;
            animation: orbit 24s linear infinite;
        }

        .hero::before { top: -155px; left: 7%; box-shadow: 0 0 0 35px #ff790009,0 0 0 70px #ff790008; }
        .hero::after { bottom: -190px; right: 5%; animation-direction: reverse; }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 13px;
            border: 1px solid #ff790055;
            border-radius: 30px;
            color: #ffc18b;
            background: #ff790015;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            animation: rise .6s both;
        }

        .hero h1 {
            margin: 23px 0 15px;
            font-size: clamp(34px,5vw,57px);
            letter-spacing: -2.5px;
            line-height: 1.12;
            animation: rise .7s .08s both;
        }

        .hero h1 span { color: var(--orange); }

        .hero p {
            max-width: 650px;
            margin: auto;
            color: #bac3d1;
            font-size: 13px;
            line-height: 1.9;
            animation: rise .7s .16s both;
        }

        .hero-meta {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 25px;
            color: #aeb8c7;
            font-size: 10px;
        }

        .hero-meta i { color: var(--orange); margin-right: 5px; }

        .page-wrap {
            width: min(1200px, calc(100% - 36px));
            margin: -25px auto 75px;
            position: relative;
            z-index: 2;
        }

        .document-tools {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            padding: 15px 19px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow);
            margin-bottom: 22px;
            animation: rise .65s .1s both;
        }

        .breadcrumbs {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 11px;
            color: var(--muted);
        }

        .breadcrumbs a { text-decoration: none; }
        .breadcrumbs a:hover { color: var(--orange); }

        .tool-actions { display: flex; gap: 9px; align-items: center; }

        .search-wrap { position: relative; }
        .search-wrap i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 12px;
        }

        .search-input {
            width: 220px;
            max-width: 52vw;
            height: 38px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--page);
            color: var(--text);
            padding: 0 12px 0 34px;
            outline: none;
            font-size: 11px;
        }

        .search-input:focus { border-color: var(--orange); box-shadow: 0 0 0 3px #ff790018; }

        .layout {
            display: grid;
            grid-template-columns: 255px minmax(0,1fr);
            gap: 22px;
            align-items: start;
        }

        .toc {
            position: sticky;
            top: 96px;
            padding: 22px 16px;
            border: 1px solid var(--border);
            border-radius: 17px;
            background: var(--surface);
            box-shadow: var(--shadow);
        }

        .toc-heading {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 12px;
            font-weight: 800;
            margin: 0 0 17px;
        }

        .toc-heading i { color: var(--orange); }

        .toc a {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 10px;
            margin: 3px 0;
            text-decoration: none;
            color: var(--muted);
            font-size: 10px;
            line-height: 1.5;
            border-radius: 9px;
            border-left: 2px solid transparent;
            transition: .2s;
        }

        .toc a:hover, .toc a.active {
            background: var(--soft);
            color: var(--orange);
            border-left-color: var(--orange);
        }

        .toc a span { color: var(--orange); font-weight: 800; min-width: 17px; }

        .toc-note {
            margin-top: 20px;
            padding: 13px;
            border-radius: 11px;
            background: var(--soft);
            color: var(--muted);
            font-size: 10px;
            line-height: 1.7;
        }

        .toc-note i { color: var(--orange); margin-right: 5px; }

        .document {
            min-width: 0;
            padding: clamp(22px,4vw,43px);
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 19px;
            box-shadow: var(--shadow);
            animation: rise .7s .15s both;
        }

        .document-intro {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ff79002e;
            border-radius: 13px;
            background: linear-gradient(120deg,var(--soft),transparent);
            color: var(--muted);
            font-size: 12px;
            line-height: 1.9;
        }

        .document-intro strong { color: var(--text); }

        .legal-section {
            padding: 25px 0;
            border-bottom: 1px solid var(--border);
            scroll-margin-top: 100px;
        }

        .legal-section:last-child { border-bottom: 0; padding-bottom: 0; }

        .section-title {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 14px;
        }

        .section-number {
            flex-shrink: 0;
            width: 37px;
            height: 37px;
            display: grid;
            place-items: center;
            border-radius: 11px;
            background: var(--soft);
            color: var(--orange);
            font-size: 11px;
            font-weight: 800;
        }

        .section-title h2 {
            margin: 5px 0 0;
            font-size: 17px;
            line-height: 1.45;
            letter-spacing: -.4px;
        }

        .legal-section p, .legal-section li {
            color: var(--muted);
            font-size: 12px;
            line-height: 2;
        }

        .legal-section p { margin: 10px 0; }
        .legal-section ul { padding-left: 21px; margin: 10px 0; }
        .legal-section li { padding: 2px 0; }
        .legal-section li::marker { color: var(--orange); }

        .legal-section strong { color: var(--text); }

        .inline-link {
            color: var(--orange);
            text-decoration: none;
            font-weight: 700;
        }

        .inline-link:hover { text-decoration: underline; }

        .notice {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-top: 17px;
            padding: 15px;
            border-radius: 12px;
            border: 1px solid var(--border);
            font-size: 11px;
            line-height: 1.8;
            color: var(--muted);
        }

        .notice i { color: var(--orange); font-size: 16px; margin-top: 3px; }

        .not-found {
            display: none;
            text-align: center;
            padding: 35px 15px;
            color: var(--muted);
            font-size: 12px;
        }

        .not-found i { display: block; font-size: 25px; color: var(--orange); margin-bottom: 12px; }

        .legal-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 22px;
            padding: 22px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
        }

        .legal-bottom strong { display: block; font-size: 13px; }
        .legal-bottom p { color: var(--muted); font-size: 11px; margin: 7px 0 0; }

        .bottom-links { display: flex; flex-wrap: wrap; gap: 9px; }

        .bottom-link {
            text-decoration: none;
            font-size: 11px;
            font-weight: 700;
            padding: 11px 14px;
            border-radius: 10px;
            border: 1px solid var(--border);
            transition: .2s;
        }

        .bottom-link:hover { border-color: var(--orange); color: var(--orange); transform: translateY(-2px); }
        .bottom-link.primary { background: var(--orange); color: white; border-color: var(--orange); }

        .footer {
            padding: 27px 20px;
            text-align: center;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 10px;
            line-height: 2;
        }

        .footer a { color: var(--orange); text-decoration: none; margin: 0 7px; }

        .reveal { opacity: 0; transform: translateY(14px); transition: opacity .5s, transform .5s; }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        @keyframes rise {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes orbit { to { transform: rotate(360deg); } }

        @media(max-width: 850px) {
            .layout { grid-template-columns: 1fr; }
            .toc { position: static; }
            .toc-list { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 3px; }
            .toc-note { margin-top: 12px; }
        }

        @media(max-width: 560px) {
            .navbar { height: 66px; padding: 0 14px; }
            .brand-name { font-size: 19px; }
            .brand-icon { width: 38px; height: 38px; }
            .nav-link { padding: 9px; font-size: 10px; }
            .nav-actions { gap: 4px; }
            .hero { padding: 53px 18px 67px; }
            .hero h1 { letter-spacing: -1.5px; }
            .hero-meta { gap: 10px 15px; }
            .page-wrap { width: calc(100% - 22px); margin-bottom: 35px; }
            .document-tools { padding: 13px; }
            .breadcrumbs { width: 100%; }
            .tool-actions { width: 100%; }
            .search-wrap { flex: 1; }
            .search-input { width: 100%; max-width: none; }
            .toc-list { grid-template-columns: 1fr; }
            .document { padding: 20px 16px; }
            .section-title h2 { font-size: 15px; }
            .legal-section p, .legal-section li { font-size: 11px; }
            .legal-bottom { padding: 18px; }
        }

        @media(prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
                scroll-behavior: auto !important;
            }
            .reveal { opacity: 1; transform: none; }
        }

        @media print {
            .navbar, .hero, .document-tools, .toc, .legal-bottom, .footer,
            .legal-top-progress { display: none !important; }
            body { background: white; color: black; }
            .page-wrap { width: 100%; margin: 0; }
            .layout { display: block; }
            .document { border: 0; box-shadow: none; padding: 0; }
            .legal-section { break-inside: avoid; }
            .legal-section p, .legal-section li { color: #333; }
        }
    </style>
    @stack('styles')
</head>

<body>
<div class="legal-top-progress" id="readingProgress"></div>

<header class="navbar">
    <a href="{{ url('/') }}" class="brand" aria-label="TapApp home">
        <span class="brand-icon"><i class="fa-solid fa-gear"></i></span>
        <span>
            <span class="brand-name">TAP<span>APP</span></span>
            <span class="brand-sub" data-label="subtitle">Maintenance Management</span>
        </span>
    </a>

    <nav class="nav-actions" aria-label="Main navigation">
        <a class="nav-link" href="{{ url('/') }}" data-label="home">Home</a>
        <a class="nav-link" href="{{ url('/terms') }}" data-label="terms">Terms</a>
        <a class="nav-link" href="{{ url('/privacy') }}" data-label="privacy">Privacy</a>

        <select id="legalLanguage" class="icon-button" aria-label="Choose language"
                style="width:75px;padding:0 5px;font-size:10px">
            <option value="en">EN</option>
            <option value="fr">FR</option>
            <option value="ar">AR</option>
        </select>

        <button class="icon-button" id="legalTheme" type="button" aria-label="Toggle dark mode">
            <i class="fa-solid fa-moon"></i>
        </button>
    </nav>
</header>

<section class="hero">
    <div class="hero-badge">
        <i class="fa-solid fa-shield-halved"></i>
        <span data-label="eyebrow">TRANSPARENCY & TRUST</span>
    </div>

    <h1>@yield('hero_title')</h1>
    <p>@yield('hero_description')</p>

    <div class="hero-meta">
        <span><i class="fa-regular fa-calendar"></i>
            <span data-label="updated">Last updated</span>: @yield('updated_date', '15 October 2026')
        </span>
        <span><i class="fa-solid fa-file-shield"></i>
            <span data-label="document">Official policy document</span>
        </span>
        <span><i class="fa-solid fa-globe"></i> English · Français · العربية</span>
    </div>
</section>

<main class="page-wrap">
    <div class="document-tools">
        <div class="breadcrumbs">
            <a href="{{ url('/') }}"><i class="fa-solid fa-house"></i> <span data-label="home">Home</span></a>
            <i class="fa-solid fa-chevron-right" style="font-size:9px"></i>
            <span>@yield('breadcrumb')</span>
        </div>

        <div class="tool-actions">
            <div class="search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input class="search-input" id="policySearch" type="search"
                       placeholder="Search this document..." data-placeholder="search"
                       aria-label="Search this document">
            </div>
            <button class="icon-button" id="printPolicy" type="button" aria-label="Print policy"
                    title="Print or save as PDF">
                <i class="fa-solid fa-print"></i>
            </button>
        </div>
    </div>

    <div class="layout">
        <aside class="toc">
            <h2 class="toc-heading">
                <i class="fa-solid fa-list-ul"></i>
                <span data-label="contents">On this page</span>
            </h2>

            <nav class="toc-list" id="tableOfContents" aria-label="Document sections">
                @yield('toc')
            </nav>

            <div class="toc-note">
                <i class="fa-solid fa-circle-info"></i>
                <span data-label="toc_note">Use the section links to navigate this document. You can also search its text.</span>
            </div>
        </aside>

        <article class="document" id="policyDocument">
            <div class="document-intro">
                <strong><i class="fa-solid fa-circle-check" style="color:var(--orange)"></i>
                    <span data-label="important">Important information</span>
                </strong>
                <p style="margin:7px 0 0">@yield('intro')</p>
            </div>

            @yield('content')

            <div class="not-found" id="noSearchResults">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span data-label="no_results">No matching sections found. Try another search.</span>
            </div>
        </article>
    </div>

    <section class="legal-bottom reveal">
        <div>
            <strong data-label="questions_title">Have questions about this policy?</strong>
            <p data-label="questions_text">Contact your TapApp administrator for help or clarification.</p>
        </div>

        <div class="bottom-links">
            @hasSection('terms_link')
                @yield('terms_link')
            @else
                <a class="bottom-link" href="{{ url('/terms') }}">
                    <i class="fa-solid fa-file-contract"></i> <span data-label="terms">Terms</span>
                </a>
            @endif

            @hasSection('privacy_link')
                @yield('privacy_link')
            @else
                <a class="bottom-link" href="{{ url('/privacy') }}">
                    <i class="fa-solid fa-user-shield"></i> <span data-label="privacy">Privacy</span>
                </a>
            @endif

            <a class="bottom-link primary" href="{{ url('/register') }}">
                <i class="fa-solid fa-arrow-right"></i> <span data-label="create_account">Create account</span>
            </a>
        </div>
    </section>
</main>

<footer class="footer">
    <div>© {{ date('Y') }} <strong>TapApp</strong> · <span data-label="footer">Maintenance Management System</span></div>
    <div style="margin-top:7px">
        <a href="{{ url('/terms') }}" data-label="terms">Terms of Service</a>
        <a href="{{ url('/privacy') }}" data-label="privacy">Privacy Policy</a>
        <a href="{{ url('/') }}" data-label="home">Home</a>
    </div>
</footer>

<script>
    const labels = {
        en: {
            subtitle: 'Maintenance Management', home: 'Home', terms: 'Terms',
            privacy: 'Privacy', eyebrow: 'TRANSPARENCY & TRUST',
            updated: 'Last updated', document: 'Official policy document',
            search: 'Search this document...', contents: 'On this page',
            toc_note: 'Use the section links to navigate this document. You can also search its text.',
            important: 'Important information', no_results: 'No matching sections found. Try another search.',
            questions_title: 'Have questions about this policy?',
            questions_text: 'Contact your TapApp administrator for help or clarification.',
            create_account: 'Create account', footer: 'Maintenance Management System'
        },
        fr: {
            subtitle: 'Gestion de la maintenance', home: 'Accueil',
            terms: 'Conditions', privacy: 'Confidentialité',
            eyebrow: 'TRANSPARENCE ET CONFIANCE', updated: 'Dernière mise à jour',
            document: 'Document officiel', search: 'Rechercher dans ce document...',
            contents: 'Dans cette page',
            toc_note: 'Utilisez les liens pour naviguer dans le document ou recherchez un mot.',
            important: 'Informations importantes',
            no_results: 'Aucune section correspondante. Essayez une autre recherche.',
            questions_title: 'Des questions sur cette politique ?',
            questions_text: 'Contactez votre administrateur TapApp pour obtenir de l’aide.',
            create_account: 'Créer un compte', footer: 'Système de gestion de la maintenance'
        },
        ar: {
            subtitle: 'إدارة الصيانة', home: 'الرئيسية',
            terms: 'الشروط', privacy: 'الخصوصية',
            eyebrow: 'الشفافية والثقة', updated: 'آخر تحديث',
            document: 'وثيقة سياسة رسمية', search: 'ابحث في هذه الوثيقة...',
            contents: 'محتويات الصفحة',
            toc_note: 'استخدم روابط الأقسام للتنقل أو ابحث عن نص داخل الوثيقة.',
            important: 'معلومات مهمة',
            no_results: 'لم يتم العثور على أقسام مطابقة. جرب بحثاً آخر.',
            questions_title: 'هل لديك أسئلة حول هذه السياسة؟',
            questions_text: 'تواصل مع مسؤول TapApp للحصول على المساعدة.',
            create_account: 'إنشاء حساب', footer: 'نظام إدارة الصيانة'
        }
    };

    const langSelect = document.getElementById('legalLanguage');

    function setLegalLanguage(lang) {
        const dict = labels[lang] || labels.en;

        document.querySelectorAll('[data-label]').forEach(el => {
            const key = el.dataset.label;
            if (dict[key]) el.textContent = dict[key];
        });

        document.querySelectorAll('[data-placeholder]').forEach(el => {
            const key = el.dataset.placeholder;
            if (dict[key]) el.placeholder = dict[key];
        });

        document.documentElement.lang = lang;
        document.documentElement.dir = lang === 'ar' ? 'rtl' : 'ltr';
        document.body.classList.toggle('rtl', lang === 'ar');

        // Policy clauses remain in the authored language; only the shared UI is translated.
    }

    langSelect.addEventListener('change', () => setLegalLanguage(langSelect.value));

    const themeButton = document.getElementById('legalTheme');
    themeButton.addEventListener('click', () => {
        document.body.classList.toggle('dark');
        const icon = themeButton.querySelector('i');
        icon.classList.toggle('fa-moon');
        icon.classList.toggle('fa-sun');
    });

    document.getElementById('printPolicy').addEventListener('click', () => window.print());

    const progress = document.getElementById('readingProgress');

    function updateProgress() {
        const scrollable = document.documentElement.scrollHeight - window.innerHeight;
        const percent = scrollable > 0 ? (window.scrollY / scrollable) * 100 : 0;
        progress.style.width = Math.min(100, Math.max(0, percent)) + '%';
    }

    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();

    // Search only authored policy sections; matching text remains visible.
    const search = document.getElementById('policySearch');
    const sections = [...document.querySelectorAll('.legal-section')];
    const noResults = document.getElementById('noSearchResults');

    search.addEventListener('input', () => {
        const query = search.value.trim().toLocaleLowerCase();
        let count = 0;

        sections.forEach(section => {
            const match = !query || section.textContent.toLocaleLowerCase().includes(query);
            section.style.display = match ? '' : 'none';
            if (match) count++;
        });

        noResults.style.display = count ? 'none' : 'block';
    });

    // Active table-of-contents section.
    const tocLinks = [...document.querySelectorAll('#tableOfContents a[href^="#"]')];

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    tocLinks.forEach(link => {
                        link.classList.toggle(
                            'active',
                            link.getAttribute('href') === '#' + entry.target.id
                        );
                    });
                }
            });
        }, { rootMargin: '-18% 0px -70% 0px' });

        sections.forEach(section => observer.observe(section));
    }

    // Subtle reveal animation.
    if ('IntersectionObserver' in window &&
        !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        const revealObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08 });

        document.querySelectorAll('.legal-section, .legal-bottom').forEach(el => {
            el.classList.add('reveal');
            revealObserver.observe(el);
        });
    }

    setLegalLanguage('en');
</script>

@stack('scripts')
</body>
</html>
