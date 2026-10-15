
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#070b14">

    <title>TAPCORE | Future-Ready Maintenance Intelligence</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])


    {{-- Database-backed overview fallbacks. Prefer controller-provided values when available. --}}
    @php
        $overviewUsers = isset($totalUsers) ? (int) $totalUsers : \App\Models\User::count();
        $overviewUsersByRole = isset($usersByRole)
            ? collect($usersByRole)
            : \App\Models\User::query()
                ->selectRaw('role, COUNT(*) as total')
                ->groupBy('role')
                ->orderBy('role')
                ->get()
                ->keyBy('role');

        $overviewPpmRecords = isset($ppmTotalRecords)
            ? (int) $ppmTotalRecords
            : (isset($totalPpm) ? (int) $totalPpm : \App\Models\PpmRecord::count());

        $overviewRecords = \App\Models\PpmRecord::query()
            ->select(['id', 'asset_id', 'asset_description', 'brief_description'])
            ->get();

        $overviewUniqueAssets = isset($ppmUniqueAssets)
            ? (int) $ppmUniqueAssets
            : $overviewRecords->pluck('asset_id')->filter(fn ($id) => trim((string) $id) !== '')->unique()->count();

        $overviewTypeStats = isset($ppmTypes)
            ? collect($ppmTypes)
            : $overviewRecords
                ->groupBy(function ($record) {
                    $type = strtolower(trim((string) ($record->asset_prefix ?? '')));
                    return $type !== '' ? $type : 'generic';
                })
                ->map(function ($group, $type) {
                    return (object) [
                        'type' => $type,
                        'label' => strtoupper($type),
                        'records' => $group->count(),
                        'asset_ids' => $group->pluck('asset_id')
                            ->filter(fn ($id) => trim((string) $id) !== '')
                            ->unique()->count(),
                    ];
                })
                ->sortBy(function ($item) {
                    $order = ['pnl', 'tst', 'khm', 'cons', 'generic'];
                    $position = array_search(strtolower((string) $item->type), $order, true);
                    return $position === false ? count($order) : $position;
                })
                ->values();

        $overviewMissingAssetIds = $overviewRecords
            ->filter(fn ($record) => trim((string) $record->asset_id) === '')
            ->count();
    @endphp

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        :root {
            --bg: #070b14;
            --surface: #0d1422;
            --surface-2: #111c2e;
            --orange: #ff9200;
            --orange2: #ffb547;
            --white: #f6f8fc;
            --muted: #8795aa;
            --green: #43e69a;
            --blue: #67a8ff;
            --line: rgba(255,255,255,.09);
            --radius: 18px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 90px;
        }

        body {
            background: var(--bg);
            color: var(--white);
            font-family: "Segoe UI", Inter, Arial, sans-serif;
            overflow-x: hidden;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            font: inherit;
        }

        ::selection {
            background: var(--orange);
            color: #080d15;
        }

        /* ============ AMBIENT BACKGROUND ============ */

        .site-shell {
            position: relative;
            overflow: clip;
            min-height: 100vh;
            isolation: isolate;
        }

        .ambient {
            position: fixed;
            inset: 0;
            z-index: -3;
            pointer-events: none;
            background:
                radial-gradient(ellipse at 10% 15%,
                    rgba(255,146,0,.10), transparent 35%),
                radial-gradient(ellipse at 90% 35%,
                    rgba(35,110,255,.10), transparent 32%),
                radial-gradient(ellipse at 50% 90%,
                    rgba(255,146,0,.045), transparent 40%),
                #070b14;
        }

        .grid-background {
            position: fixed;
            inset: 0;
            z-index: -2;
            pointer-events: none;
            opacity: .2;
            background-image:
                linear-gradient(rgba(255,255,255,.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.035) 1px, transparent 1px);
            background-size: 44px 44px;
            mask-image: linear-gradient(to bottom, black, transparent 90%);
            animation: gridDrift 25s linear infinite;
        }

        @keyframes gridDrift {
            from { background-position: 0 0; }
            to { background-position: 44px 44px; }
        }

        .orb {
            position: absolute;
            z-index: -1;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
            opacity: .16;
            animation: orbDrift 12s ease-in-out infinite alternate;
        }

        .orb-one {
            top: 100px;
            left: -200px;
            background: #ff8a00;
        }

        .orb-two {
            top: 450px;
            right: -210px;
            background: #2563eb;
            animation-delay: -5s;
        }

        .orb-three {
            top: 1100px;
            left: 45%;
            width: 240px;
            height: 240px;
            background: #f97316;
            animation-delay: -8s;
        }

        @keyframes orbDrift {
            from { transform: translate3d(0,0,0) scale(.9); }
            to { transform: translate3d(45px,-35px,0) scale(1.15); }
        }

        #particleCanvas {
            position: absolute;
            inset: 0 0 auto;
            z-index: 0;
            width: 100%;
            height: 800px;
            pointer-events: none;
            opacity: .5;
        }

        /* ============ NAVBAR ============ */

        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            min-height: 78px;
            padding: 12px 6%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            border-bottom: 1px solid rgba(255,255,255,.07);
            background: rgba(7,11,20,.76);
            backdrop-filter: blur(22px);
            -webkit-backdrop-filter: blur(22px);
            transition: background .3s, box-shadow .3s;
        }

        .navbar.scrolled {
            background: rgba(7,11,20,.94);
            box-shadow: 0 10px 40px rgba(0,0,0,.22);
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .brand img {
            display: block;
            width: 133px;
            max-height: 55px;
            object-fit: contain;
            filter: drop-shadow(0 0 10px rgba(255,146,0,.12));
            transition: transform .35s, filter .35s;
        }

        .brand:hover img {
            transform: scale(1.055);
            filter: drop-shadow(0 0 16px rgba(255,146,0,.4));
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 29px;
        }

        .nav-link {
            position: relative;
            padding: 8px 0;
            color: #a7b3c6;
            font-size: 12px;
            font-weight: 650;
            transition: color .25s;
        }

        .nav-link::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            border-radius: 2px;
            background: var(--orange);
            transition: width .3s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 13px 19px;
            border: 1px solid transparent;
            border-radius: 11px;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
            overflow: hidden;
            isolation: isolate;
            transition:
                transform .25s ease,
                border-color .25s ease,
                background .25s ease,
                box-shadow .25s ease;
        }

        .btn::before {
            content: "";
            position: absolute;
            z-index: -1;
            top: -60%;
            left: -100%;
            width: 60%;
            height: 220%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255,255,255,.24),
                transparent
            );
            transform: rotate(22deg);
            transition: left .6s ease;
        }

        .btn:hover::before {
            left: 150%;
        }

        .btn:hover {
            transform: translateY(-3px);
        }

        .btn-primary {
            color: #101016;
            background: linear-gradient(120deg, #ffb547, #ff8800);
            box-shadow: 0 7px 25px rgba(255,146,0,.19);
        }

        .btn-primary:hover {
            box-shadow: 0 12px 32px rgba(255,146,0,.34);
        }

        .btn-outline {
            color: white;
            background: rgba(255,255,255,.035);
            border-color: rgba(255,255,255,.13);
        }

        .btn-outline:hover {
            border-color: rgba(255,146,0,.55);
            background: rgba(255,146,0,.07);
        }

        .menu-toggle {
            display: none;
            width: 42px;
            height: 42px;
            border: 1px solid var(--line);
            border-radius: 11px;
            background: rgba(255,255,255,.04);
            color: white;
            cursor: pointer;
        }

        /* ============ HERO ============ */

        .hero {
            position: relative;
            z-index: 1;
            min-height: 690px;
            padding: 100px 6% 110px;
            display: grid;
            grid-template-columns: minmax(0,1.05fr) minmax(370px,.95fr);
            align-items: center;
            gap: 60px;
            max-width: 1600px;
            margin: auto;
        }

        .hero-copy {
            position: relative;
            z-index: 3;
            animation: enterLeft .9s ease both;
        }

        @keyframes enterLeft {
            from { opacity: 0; transform: translateX(-35px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .eyebrow {
            width: fit-content;
            max-width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 27px;
            padding: 10px 14px;
            border: 1px solid rgba(255,146,0,.24);
            border-radius: 50px;
            background: rgba(255,146,0,.07);
            color: #ffbd61;
            font-size: 10px;
            font-weight: 850;
            letter-spacing: 1.3px;
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--green);
            box-shadow: 0 0 13px var(--green);
            animation: pulseDot 1.5s infinite;
        }

        @keyframes pulseDot {
            50% { opacity: .4; transform: scale(.65); }
        }

        .hero h1 {
            max-width: 760px;
            font-size: clamp(47px,5.4vw,78px);
            font-weight: 850;
            line-height: 1.02;
            letter-spacing: -3.5px;
        }

        .gradient-text {
            display: inline-block;
            padding-bottom: 7px;
            color: transparent;
            background: linear-gradient(
                100deg,
                #ff8a00 0%,
                #ffd18a 36%,
                #ff9200 60%,
                #ffbd5a 100%
            );
            background-size: 250% auto;
            background-clip: text;
            -webkit-background-clip: text;
            animation: gradientFlow 5s linear infinite;
        }

        @keyframes gradientFlow {
            to { background-position: 250% center; }
        }

        .hero-description {
            max-width: 570px;
            margin-top: 25px;
            color: #98a7bc;
            font-size: 15px;
            line-height: 1.95;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 32px;
        }

        .hero-buttons .btn {
            min-height: 49px;
            padding-left: 23px;
            padding-right: 23px;
        }

        .trust-row {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            margin-top: 28px;
            color: #a4b2c4;
            font-size: 10px;
        }

        .trust-row span {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .trust-row i {
            color: var(--green);
        }

        /* ============ FLOATING DASHBOARD ============ */

        .hero-visual {
            position: relative;
            min-width: 0;
            perspective: 1200px;
            animation: enterRight .95s .15s ease both;
        }

        @keyframes enterRight {
            from { opacity: 0; transform: translateX(35px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .dashboard-glow {
            position: absolute;
            inset: 10% 4%;
            z-index: -1;
            border-radius: 50%;
            background: rgba(255,146,0,.13);
            filter: blur(65px);
            animation: dashboardGlow 5s ease-in-out infinite alternate;
        }

        @keyframes dashboardGlow {
            from { opacity: .45; transform: scale(.85); }
            to { opacity: 1; transform: scale(1.12); }
        }

        .dashboard {
            position: relative;
            padding: 22px;
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 22px;
            background: linear-gradient(
                145deg,
                rgba(18,29,47,.96),
                rgba(9,15,27,.97)
            );
            box-shadow:
                0 35px 90px rgba(0,0,0,.36),
                inset 0 1px rgba(255,255,255,.04);
            backdrop-filter: blur(22px);
            animation: floatDashboard 6s ease-in-out infinite;
            transform-style: preserve-3d;
            transition: border-color .3s, box-shadow .3s;
        }

        .dashboard:hover {
            border-color: rgba(255,146,0,.3);
            box-shadow: 0 35px 100px rgba(0,0,0,.42),
                        0 0 35px rgba(255,146,0,.05);
        }

        @keyframes floatDashboard {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-9px); }
        }

        .dash-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding-bottom: 19px;
            border-bottom: 1px solid var(--line);
        }

        .dash-brand {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .dash-logo {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(255,146,0,.23);
            border-radius: 13px;
            color: var(--orange);
            background: linear-gradient(
                135deg,
                rgba(255,146,0,.17),
                rgba(255,146,0,.04)
            );
            font-size: 16px;
        }

        .dash-title {
            font-size: 12px;
            font-weight: 850;
        }

        .dash-subtitle {
            margin-top: 5px;
            color: var(--muted);
            font-size: 9px;
        }

        .live-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 9px;
            border: 1px solid rgba(67,230,154,.17);
            border-radius: 40px;
            background: rgba(67,230,154,.06);
            color: var(--green);
            white-space: nowrap;
            font-size: 8px;
            font-weight: 850;
        }

        .live-pill i {
            font-size: 6px;
            animation: pulseDot 1.5s infinite;
        }

        .dash-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin: 20px 0 12px;
            color: #dce5f1;
            font-size: 11px;
            font-weight: 800;
        }

        .dash-label small {
            color: var(--muted);
            font-size: 9px;
            font-weight: 500;
        }

        .metric-grid {
            display: grid;
            grid-template-columns: repeat(3,minmax(0,1fr));
            gap: 9px;
        }

        .metric {
            position: relative;
            min-width: 0;
            padding: 14px 11px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.075);
            border-radius: 13px;
            background: rgba(255,255,255,.025);
            transition: transform .3s, border-color .3s, background .3s;
        }

        .metric::after {
            content: "";
            position: absolute;
            width: 55px;
            height: 55px;
            right: -25px;
            top: -28px;
            border-radius: 50%;
            background: rgba(255,146,0,.13);
            filter: blur(12px);
            transition: transform .3s;
        }

        .metric:hover {
            transform: translateY(-4px);
            border-color: rgba(255,146,0,.35);
            background: rgba(255,146,0,.035);
        }

        .metric:hover::after {
            transform: scale(1.6);
        }

        .metric-icon {
            color: var(--orange);
            font-size: 12px;
            margin-bottom: 11px;
        }

        .metric-value {
            font-size: clamp(19px,2.3vw,25px);
            font-weight: 850;
            letter-spacing: -.7px;
            overflow-wrap: anywhere;
        }

        .metric-name {
            margin-top: 5px;
            color: #8493a9;
            font-size: 8px;
            letter-spacing: .4px;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(2,minmax(0,1fr));
            gap: 9px;
        }

        .category {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            padding: 11px;
            border: 1px solid rgba(255,255,255,.065);
            border-radius: 12px;
            background: rgba(255,255,255,.022);
            transition: transform .25s, border-color .25s;
        }

        .category:hover {
            transform: translateX(3px);
            border-color: rgba(255,146,0,.3);
        }

        .category-icon {
            width: 34px;
            height: 34px;
            flex-shrink: 0;
            display: grid;
            place-items: center;
            border-radius: 10px;
            background: rgba(255,146,0,.1);
            color: var(--orange);
            font-size: 12px;
        }

        .category:nth-child(2) .category-icon {
            color: var(--blue);
            background: rgba(103,168,255,.1);
        }

        .category:nth-child(3) .category-icon {
            color: #c79aff;
            background: rgba(199,154,255,.1);
        }

        .category:nth-child(4) .category-icon {
            color: var(--green);
            background: rgba(67,230,154,.1);
        }

        .category-name {
            font-size: 10px;
            font-weight: 850;
        }

        .category-count {
            margin-top: 4px;
            color: var(--muted);
            font-size: 9px;
        }

        .completion-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin-top: 18px;
            padding: 15px;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: rgba(255,255,255,.02);
        }

        .completion-info {
            flex: 1;
            min-width: 0;
        }

        .completion-title {
            font-size: 10px;
            font-weight: 800;
        }

        .completion-subtitle {
            margin-top: 5px;
            color: var(--muted);
            font-size: 9px;
        }

        .mini-track {
            height: 5px;
            margin-top: 12px;
            border-radius: 10px;
            background: rgba(255,255,255,.08);
            overflow: hidden;
        }

        .mini-fill {
            width: 0;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg,#18b978,#70efb1);
            transition: width 1.5s cubic-bezier(.2,.8,.2,1);
        }

        .ring {
            position: relative;
            width: 70px;
            height: 70px;
            flex-shrink: 0;
            display: grid;
            place-items: center;
        }

        .ring svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }

        .ring circle {
            fill: none;
            stroke-width: 5;
        }

        .ring-bg {
            stroke: rgba(255,255,255,.08);
        }

        .ring-progress {
            stroke: var(--green);
            stroke-linecap: round;
            stroke-dasharray: 188.5;
            stroke-dashoffset: 188.5;
            transition: stroke-dashoffset 1.5s ease;
            filter: drop-shadow(0 0 4px rgba(67,230,154,.3));
        }

        .ring-text {
            font-size: 13px;
            font-weight: 850;
        }

        .dash-bottom {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 9px;
            margin-top: 14px;
            color: #91a0b5;
            font-size: 9px;
        }

        .dash-bottom i {
            margin-right: 5px;
            color: var(--green);
        }

        /* ============ GENERAL SECTION ============ */

        .section {
            position: relative;
            z-index: 1;
            padding: 95px 6%;
        }

        .section-head {
            max-width: 680px;
            margin: 0 auto 48px;
            text-align: center;
        }

        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--orange);
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 2px;
        }

        .section-tag i {
            animation: tinySpin 8s linear infinite;
        }

        @keyframes tinySpin {
            to { transform: rotate(360deg); }
        }

        .section-head h2 {
            margin-top: 15px;
            font-size: clamp(30px,4vw,43px);
            font-weight: 850;
            letter-spacing: -1.5px;
        }

        .section-head p {
            max-width: 570px;
            margin: 15px auto 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.9;
        }

        /* ============ FEATURE CARDS ============ */

        .features-section {
            border-top: 1px solid rgba(255,255,255,.035);
            background: linear-gradient(
                180deg,
                rgba(8,13,23,.8),
                rgba(9,17,29,.97)
            );
        }

        .feature-grid {
            max-width: 1250px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(4,minmax(0,1fr));
            gap: 16px;
        }

        .feature-card {
            position: relative;
            min-height: 230px;
            padding: 26px 23px;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: linear-gradient(
                145deg,
                rgba(20,32,50,.73),
                rgba(12,19,32,.75)
            );
            opacity: 0;
            transform: translateY(25px) scale(.98);
            transition:
                opacity .65s ease,
                transform .4s ease,
                border-color .3s,
                box-shadow .3s;
        }

        .feature-card.visible {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .feature-card::before {
            content: "";
            position: absolute;
            top: -80px;
            right: -80px;
            width: 170px;
            height: 170px;
            border-radius: 50%;
            background: rgba(255,146,0,.075);
            filter: blur(35px);
            transition: transform .4s, background .4s;
        }

        .feature-card:hover {
            transform: translateY(-8px) scale(1.012);
            border-color: rgba(255,146,0,.36);
            box-shadow: 0 20px 55px rgba(0,0,0,.25);
        }

        .feature-card:hover::before {
            transform: scale(1.5);
            background: rgba(255,146,0,.13);
        }

        .feature-icon {
            position: relative;
            width: 49px;
            height: 49px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(255,146,0,.17);
            border-radius: 14px;
            background: rgba(255,146,0,.09);
            color: var(--orange);
            font-size: 18px;
            transition: transform .4s, box-shadow .4s;
        }

        .feature-card:hover .feature-icon {
            transform: translateY(-3px) rotate(-5deg);
            box-shadow: 0 7px 20px rgba(255,146,0,.12);
        }

        .feature-card h3 {
            position: relative;
            margin-top: 24px;
            font-size: 14px;
            font-weight: 850;
        }

        .feature-card p {
            position: relative;
            margin-top: 11px;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.9;
        }

        .feature-index {
            position: absolute;
            top: 22px;
            right: 22px;
            color: rgba(255,255,255,.18);
            font-size: 11px;
            font-weight: 850;
        }

        /* ============ LIVE STATISTICS ============ */

        .stats-section {
            background: rgba(8,13,23,.65);
            border-top: 1px solid rgba(255,255,255,.045);
        }

        .stats-grid {
            max-width: 1250px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(3,minmax(0,1fr));
            gap: 15px;
        }

        .stats-card {
            position: relative;
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
            padding: 23px;
            border: 1px solid var(--line);
            border-radius: 16px;
            background: rgba(255,255,255,.022);
            transition: transform .3s, border-color .3s, background .3s;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255,146,0,.35);
            background: rgba(255,146,0,.025);
        }

        .stats-icon {
            width: 48px;
            height: 48px;
            flex-shrink: 0;
            display: grid;
            place-items: center;
            border-radius: 13px;
            background: rgba(255,146,0,.1);
            color: var(--orange);
            font-size: 17px;
        }

        .stats-value {
            font-size: 26px;
            font-weight: 850;
            letter-spacing: -1px;
            overflow-wrap: anywhere;
        }

        .stats-label {
            margin-top: 5px;
            color: var(--muted);
            font-size: 10px;
        }

        /* ============ FREQUENCY STRIP ============ */

        .frequency-panel {
            max-width: 1250px;
            margin: 40px auto 0;
            padding: 26px;
            border: 1px solid var(--line);
            border-radius: 20px;
            background: linear-gradient(
                135deg,
                rgba(255,146,0,.055),
                rgba(255,255,255,.015)
            );
        }

        .frequency-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 24px;
        }

        .frequency-heading h3 {
            font-size: 14px;
        }

        .frequency-heading p {
            margin-top: 6px;
            color: var(--muted);
            font-size: 11px;
        }

        .frequency-badge {
            padding: 8px 11px;
            border: 1px solid rgba(255,146,0,.2);
            border-radius: 30px;
            background: rgba(255,146,0,.07);
            color: var(--orange2);
            font-size: 9px;
            white-space: nowrap;
        }

        .frequency-grid {
            display: grid;
            grid-template-columns: repeat(4,minmax(0,1fr));
            gap: 12px;
        }

        .frequency-item {
            padding: 17px;
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 13px;
            background: rgba(7,11,20,.35);
            transition: transform .3s, border-color .3s;
        }

        .frequency-item:hover {
            transform: translateY(-4px);
            border-color: rgba(255,146,0,.32);
        }

        .frequency-item span {
            color: var(--muted);
            font-size: 10px;
        }

        .frequency-item strong {
            display: block;
            margin-top: 11px;
            font-size: 25px;
            font-weight: 850;
        }

        /* ============ CTA ============ */

        .cta-wrap {
            padding: 35px 6% 100px;
            position: relative;
            z-index: 1;
        }

        .cta {
            position: relative;
            max-width: 1250px;
            margin: auto;
            padding: 50px 45px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
            border: 1px solid rgba(255,146,0,.2);
            border-radius: 24px;
            background:
                radial-gradient(circle at 90% 10%,
                    rgba(255,146,0,.14), transparent 35%),
                linear-gradient(120deg,#111c2d,#0b111e);
        }

        .cta::after {
            content: "";
            position: absolute;
            top: -90px;
            right: 20%;
            width: 220px;
            height: 220px;
            border: 1px solid rgba(255,146,0,.08);
            border-radius: 50%;
            box-shadow: 0 0 0 30px rgba(255,146,0,.025),
                        0 0 0 60px rgba(255,146,0,.02);
            pointer-events: none;
        }

        .cta-copy {
            position: relative;
            z-index: 1;
        }

        .cta h2 {
            font-size: clamp(25px,3vw,36px);
            letter-spacing: -1px;
        }

        .cta p {
            margin-top: 12px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        .cta .btn {
            position: relative;
            z-index: 2;
            flex-shrink: 0;
        }

        /* ============ FOOTER ============ */

        .footer {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 25px 6%;
            border-top: 1px solid var(--line);
            background: rgba(5,9,16,.9);
            color: #78869a;
            font-size: 10px;
        }

        .footer strong {
            color: var(--orange);
            letter-spacing: .8px;
        }

        .footer-right {
            text-align: right;
        }

        .back-top {
            position: fixed;
            z-index: 80;
            right: 22px;
            bottom: 22px;
            width: 43px;
            height: 43px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(255,146,0,.3);
            border-radius: 13px;
            background: rgba(13,20,34,.88);
            color: var(--orange);
            box-shadow: 0 10px 25px rgba(0,0,0,.25);
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: .3s;
        }

        .back-top.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .back-top:hover {
            background: var(--orange);
            color: #07111f;
        }

        /* ============ RESPONSIVE ============ */

        @media (min-width: 1450px) {
            .hero {
                padding-top: 120px;
                padding-bottom: 120px;
            }
        }

        @media (max-width: 1150px) {
            .hero {
                gap: 35px;
                grid-template-columns: minmax(0,1fr) minmax(340px,.9fr);
            }

            .hero h1 {
                font-size: clamp(43px,5vw,63px);
            }

            .dashboard {
                padding: 17px;
            }

            .feature-grid {
                grid-template-columns: repeat(2,minmax(0,1fr));
            }
        }

        @media (max-width: 850px) {
            .navbar {
                padding: 12px 5%;
            }

            .menu-toggle {
                display: grid;
                place-items: center;
            }

            .nav-menu {
                position: absolute;
                top: calc(100% + 1px);
                left: 0;
                right: 0;
                display: flex;
                flex-direction: column;
                align-items: stretch;
                gap: 0;
                padding: 8px 5% 17px;
                border-bottom: 1px solid var(--line);
                background: rgba(7,11,20,.97);
                backdrop-filter: blur(20px);
                opacity: 0;
                visibility: hidden;
                transform: translateY(-8px);
                transition: .25s;
            }

            .nav-menu.open {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }

            .nav-link {
                padding: 14px 4px;
                border-bottom: 1px solid rgba(255,255,255,.04);
            }

            .nav-link::after {
                display: none;
            }

            .nav-actions {
                margin-top: 12px;
            }

            .nav-actions .btn {
                flex: 1;
            }

            .hero {
                min-height: auto;
                padding: 80px 6% 85px;
                display: flex;
                flex-direction: column;
                align-items: stretch;
                gap: 60px;
            }

            .hero-copy {
                max-width: 720px;
            }

            .hero h1 {
                font-size: clamp(45px,8vw,72px);
            }

            .hero-visual {
                width: 100%;
                max-width: 520px;
                margin: auto;
            }

            .dashboard {
                animation: none;
            }

            .stats-grid {
                grid-template-columns: repeat(2,minmax(0,1fr));
            }

            .frequency-grid {
                grid-template-columns: repeat(2,minmax(0,1fr));
            }

            .cta {
                padding: 35px;
            }
        }

        @media (max-width: 560px) {
            .brand img {
                width: 112px;
            }

            .navbar {
                min-height: 70px;
                gap: 10px;
            }

            .nav-actions .btn {
                padding: 11px 12px;
                font-size: 10px;
            }

            .hero {
                padding-top: 65px;
                gap: 45px;
            }

            .eyebrow {
                font-size: 8px;
                letter-spacing: .7px;
                padding: 9px 11px;
            }

            .hero h1 {
                font-size: clamp(39px,10vw,52px);
                letter-spacing: -2px;
            }

            .hero-description {
                font-size: 13px;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .hero-buttons .btn {
                width: 100%;
            }

            .trust-row {
                gap: 12px;
            }

            .dashboard {
                padding: 14px;
                border-radius: 17px;
            }

            .dash-title {
                font-size: 11px;
            }

            .live-pill {
                font-size: 7px;
                padding: 6px 7px;
            }

            .metric {
                padding: 11px 8px;
            }

            .metric-value {
                font-size: 19px;
            }

            .category {
                gap: 7px;
                padding: 9px 7px;
            }

            .category-icon {
                width: 29px;
                height: 29px;
            }

            .category-name {
                font-size: 9px;
            }

            .section {
                padding: 68px 5%;
            }

            .section-head {
                margin-bottom: 32px;
            }

            .feature-grid,
            .stats-grid,
            .frequency-grid {
                grid-template-columns: 1fr;
            }

            .feature-card {
                min-height: auto;
                padding: 24px;
            }

            .feature-card h3 {
                margin-top: 18px;
            }

            .stats-card {
                padding: 19px;
            }

            .frequency-panel {
                padding: 17px;
                margin-top: 28px;
            }

            .frequency-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .frequency-item {
                padding: 15px;
            }

            .cta-wrap {
                padding: 15px 5% 65px;
            }

            .cta {
                padding: 30px 23px;
                align-items: stretch;
                flex-direction: column;
                gap: 22px;
            }

            .cta .btn {
                width: 100%;
            }

            .footer {
                flex-direction: column;
                text-align: center;
                padding: 23px 5%;
            }

            .footer-right {
                text-align: center;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }

            .feature-card {
                opacity: 1;
                transform: none;
            }
        }

        /* ============ TAPCORE FUTURE BRAND + GLASS IMPACT ============ */
        .tapcore-brand {
            gap: 12px;
            perspective: 900px;
            transform-style: preserve-3d;
        }
        .brand-emblem {
            position: relative;
            display: grid;
            place-items: center;
            width: 53px;
            height: 53px;
            flex: 0 0 53px;
            isolation: isolate;
            transform-style: preserve-3d;
            transition: transform .45s cubic-bezier(.2,.8,.2,1), filter .4s;
        }
        .brand-emblem::before {
            content: "";
            position: absolute;
            inset: 4px;
            z-index: -1;
            border-radius: 18px;
            background: rgba(255,146,0,.2);
            filter: blur(14px);
            opacity: .7;
            transition: transform .45s, opacity .35s;
        }
        .brand-mark {
            width: 100%;
            height: 100%;
            overflow: visible;
            filter: drop-shadow(0 7px 12px rgba(255,122,0,.25));
            transform-origin: center;
            transition: transform .45s cubic-bezier(.2,.8,.2,1), filter .4s, opacity .25s;
        }
        .mark-shell {
            stroke: rgba(255,255,255,.7);
            stroke-width: 1.2;
            vector-effect: non-scaling-stroke;
        }
        .brand-wordmark { display:flex; flex-direction:column; gap:3px; line-height:1; }
        .brand-wordmark strong {
            color:#f8fbff;
            font-size:20px;
            font-weight:950;
            letter-spacing:1.5px;
            text-shadow:0 0 24px rgba(255,146,0,.12);
        }
        .brand-wordmark strong span {
            color:var(--orange);
            background:linear-gradient(100deg,#ff8a00,#ffd18a,#ff8a00);
            background-size:200% auto;
            -webkit-background-clip:text;
            background-clip:text;
            -webkit-text-fill-color:transparent;
            animation:brandShimmer 5s linear infinite;
        }
        .brand-wordmark small {
            color:#8190a6;
            font-size:7px;
            font-weight:850;
            letter-spacing:1.55px;
        }
        @keyframes brandShimmer { to { background-position:200% center; } }
        .tapcore-brand:hover .brand-emblem {
            transform: rotateY(18deg) rotateX(-12deg) translateY(-3px) scale(1.08);
            filter: drop-shadow(0 12px 17px rgba(255,146,0,.24));
        }
        .tapcore-brand:hover .brand-emblem::before { opacity:1; transform:scale(1.35); }
        .tapcore-brand:hover .brand-mark {
            animation: glassImpact .72s cubic-bezier(.16,.8,.3,1) both;
        }
        @keyframes glassImpact {
            0% { transform:scale(1) rotate(0); filter:brightness(1); }
            18% { transform:scale(1.16) rotate(-5deg); filter:brightness(1.8); }
            38% { transform:scale(.88) rotate(4deg); filter:brightness(1.35) blur(.3px); }
            58% { transform:scale(1.06) rotate(-2deg); }
            100% { transform:scale(1) rotate(0); filter:brightness(1); }
        }
        .logo-shard {
            position:absolute;
            z-index:3;
            width:11px;
            height:15px;
            top:18px;
            left:21px;
            opacity:0;
            pointer-events:none;
            background:linear-gradient(145deg,rgba(255,255,255,.95),rgba(255,146,0,.65) 55%,rgba(255,255,255,.1));
            border:1px solid rgba(255,255,255,.7);
            clip-path:polygon(50% 0,100% 35%,72% 100%,0 68%);
            filter:drop-shadow(0 0 5px rgba(255,146,0,.6));
        }
        .tapcore-brand:hover .logo-shard { animation: shardBurst .72s ease-out both; }
        .shard-one { --sx:-29px; --sy:-25px; --sr:-65deg; }
        .shard-two { --sx:24px; --sy:-29px; --sr:75deg; animation-delay:.015s!important; }
        .shard-three { --sx:31px; --sy:4px; --sr:110deg; animation-delay:.035s!important; }
        .shard-four { --sx:18px; --sy:27px; --sr:145deg; animation-delay:.02s!important; }
        .shard-five { --sx:-25px; --sy:23px; --sr:-100deg; animation-delay:.045s!important; }
        .shard-six { --sx:-31px; --sy:-2px; --sr:-130deg; animation-delay:.01s!important; }
        @keyframes shardBurst {
            0%,12% { opacity:0; transform:translate3d(0,0,0) rotate(0) scale(.2); }
            25% { opacity:.95; }
            72% { opacity:.85; transform:translate3d(var(--sx),var(--sy),20px) rotate(var(--sr)) scale(1); }
            100% { opacity:0; transform:translate3d(calc(var(--sx)*1.25),calc(var(--sy)*1.25),20px) rotate(calc(var(--sr)*1.4)) scale(.45); }
        }
        .tapcore-brand:focus-visible { outline:2px solid var(--orange); outline-offset:7px; border-radius:12px; }
        .dashboard, .feature-card, .stats-card, .frequency-item, .category, .metric {
            transform-style:preserve-3d;
            backface-visibility:hidden;
        }
        .feature-card::after {
            content:"";
            position:absolute;
            inset:0;
            pointer-events:none;
            opacity:0;
            background:linear-gradient(115deg,transparent 20%,rgba(255,255,255,.07) 45%,transparent 65%);
            transform:translateX(-120%);
            transition:opacity .25s;
        }
        .feature-card:hover::after { opacity:1; animation:glassSweep .8s ease; }
        @keyframes glassSweep { to { transform:translateX(120%); } }
        .section-tag, .eyebrow { backdrop-filter:blur(12px); }
        .btn:focus-visible, .nav-link:focus-visible, .menu-toggle:focus-visible {
            outline:2px solid var(--orange);
            outline-offset:4px;
        }
        @media(max-width:560px) {
            .brand-emblem { width:45px; height:45px; flex-basis:45px; }
            .brand-wordmark strong { font-size:17px; }
            .brand-wordmark small { font-size:6px; letter-spacing:1px; }
        }



        /* ============ EXTENDED DATABASE INTELLIGENCE ============ */
        .intel-overview {
            position: relative;
            margin-top: 30px;
            padding: clamp(20px, 3vw, 32px);
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 24px;
            background: linear-gradient(145deg, rgba(16,25,41,.88), rgba(8,13,24,.88));
            box-shadow: 0 24px 70px rgba(0,0,0,.18), inset 0 1px rgba(255,255,255,.04);
        }
        .intel-overview::before {
            content: "";
            position: absolute;
            width: 280px;
            height: 280px;
            right: -130px;
            top: -170px;
            pointer-events: none;
            background: radial-gradient(circle, rgba(255,146,0,.16), transparent 68%);
        }
        .intel-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 22px;
            position: relative;
        }
        .intel-heading h3 { font-size: clamp(1.2rem, 2vw, 1.65rem); margin: 8px 0; }
        .intel-heading p { color: var(--muted); font-size: .9rem; max-width: 620px; line-height: 1.7; }
        .intel-live {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(67,230,154,.24);
            color: var(--green);
            background: rgba(67,230,154,.07);
            border-radius: 999px;
            padding: 8px 11px;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .08em;
            white-space: nowrap;
        }
        .intel-live::before {
            content: ""; width: 7px; height: 7px; border-radius: 50%;
            background: var(--green); box-shadow: 0 0 12px rgba(67,230,154,.8);
            animation: intelPulse 1.8s ease-in-out infinite;
        }
        @keyframes intelPulse { 50% { opacity: .45; transform: scale(.75); } }
        .intel-kpis {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            position: relative;
        }
        .intel-kpi {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 18px;
            border-radius: 17px;
            border: 1px solid rgba(255,255,255,.075);
            background: rgba(255,255,255,.025);
            transition: transform .28s ease, border-color .28s ease, background .28s ease, box-shadow .28s ease;
            transform-style: preserve-3d;
        }
        .intel-kpi:hover {
            transform: translateY(-5px) rotateX(2deg);
            border-color: rgba(255,146,0,.38);
            background: rgba(255,146,0,.055);
            box-shadow: 0 16px 32px rgba(0,0,0,.2), 0 0 25px rgba(255,146,0,.055);
        }
        .intel-kpi-icon {
            width: 46px; height: 46px; flex: 0 0 46px;
            display: grid; place-items: center; border-radius: 14px;
            color: var(--orange2); font-size: 1.12rem;
            background: linear-gradient(145deg, rgba(255,146,0,.17), rgba(255,146,0,.04));
            border: 1px solid rgba(255,146,0,.14);
        }
        .intel-kpi small { display: block; color: var(--muted); font-size: .74rem; margin-bottom: 5px; }
        .intel-kpi strong { display: block; font-size: clamp(1.3rem, 2vw, 1.85rem); line-height: 1.15; letter-spacing: -.04em; }
        .intel-section-label {
            display: flex; align-items: center; gap: 9px;
            margin: 28px 0 14px; font-size: .96rem; font-weight: 800;
        }
        .intel-section-label i { color: var(--orange); }
        .intel-types {
            display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 12px;
        }
        .intel-type {
            position: relative; overflow: hidden; padding: 17px;
            border-radius: 16px; border: 1px solid rgba(255,255,255,.08);
            background: rgba(255,255,255,.025);
            transition: transform .25s ease, border-color .25s ease;
        }
        .intel-type:hover { transform: translateY(-4px); border-color: rgba(255,146,0,.38); }
        .intel-type::after {
            content: ""; position: absolute; height: 2px; left: 0; right: 100%; bottom: 0;
            background: linear-gradient(90deg, var(--orange), var(--orange2)); transition: right .35s ease;
        }
        .intel-type:hover::after { right: 0; }
        .intel-type-top { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
        .intel-type-top span { color: var(--muted); font-size: .74rem; font-weight: 800; letter-spacing: .07em; }
        .intel-type-top i { color: var(--orange2); }
        .intel-type-number { margin: 14px 0 5px; font-size: 1.75rem; font-weight: 850; letter-spacing: -.04em; }
        .intel-type-meta { color: var(--muted); font-size: .75rem; line-height: 1.6; }
        .intel-role-list { display: flex; flex-wrap: wrap; gap: 10px; }
        .intel-role {
            display: inline-flex; align-items: center; gap: 10px; padding: 10px 13px;
            border: 1px solid rgba(255,255,255,.09); border-radius: 12px;
            background: rgba(255,255,255,.025); color: #dce4f1; font-size: .83rem;
        }
        .intel-role i { color: var(--orange2); }
        .intel-role strong { color: #fff; }
        .intel-footnote { margin-top: 16px; color: var(--muted); font-size: .75rem; line-height: 1.6; }
        @media (max-width: 1050px) {
            .intel-kpis { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .intel-types { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }
        @media (max-width: 620px) {
            .intel-heading { align-items: flex-start; flex-direction: column; }
            .intel-kpis { grid-template-columns: 1fr; }
            .intel-types { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .intel-kpi { padding: 15px; }
        }
        @media (max-width: 380px) { .intel-types { grid-template-columns: 1fr; } }
        @media (prefers-reduced-motion: reduce) {
            .intel-live::before { animation: none; }
            .intel-kpi, .intel-type, .intel-type::after { transition: none; }
        }

    </style>
</head>

<body>
<div class="site-shell" id="top">

    <div class="ambient"></div>
    <div class="grid-background"></div>
    <div class="orb orb-one"></div>
    <div class="orb orb-two"></div>
    <div class="orb orb-three"></div>
    <canvas id="particleCanvas" aria-hidden="true"></canvas>

    <!-- NAVIGATION -->
    <header class="navbar" id="navbar">
        <a href="{{ url('/') }}" class="brand tapcore-brand" aria-label="TAPCORE home">
            <span class="brand-emblem" aria-hidden="true">
                <svg class="brand-mark" viewBox="0 0 64 64" role="img">
                    <defs>
                        <linearGradient id="tapcoreGradient" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#ffe0a3"/>
                            <stop offset="48%" stop-color="#ff9200"/>
                            <stop offset="100%" stop-color="#ff5b16"/>
                        </linearGradient>
                    </defs>
                    <path class="mark-shell" d="M32 3 57 17.5v29L32 61 7 46.5v-29Z" fill="url(#tapcoreGradient)"/>
                    <path d="M17 20h30v7H36v20h-8V27H17Z" fill="#101522"/>
                    <path d="m40 34 8 5-8 8" fill="none" stroke="#fff1d9" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="logo-shard shard-one"></span>
                <span class="logo-shard shard-two"></span>
                <span class="logo-shard shard-three"></span>
                <span class="logo-shard shard-four"></span>
                <span class="logo-shard shard-five"></span>
                <span class="logo-shard shard-six"></span>
            </span>
            <span class="brand-wordmark">
                <strong>TAP<span>CORE</span></strong>
                <small>MAINTENANCE INTELLIGENCE</small>
            </span>
        </a>

        <button class="menu-toggle"
                id="menuToggle"
                type="button"
                aria-label="Toggle navigation"
                aria-expanded="false"
                aria-controls="navMenu">
            <i class="fa-solid fa-bars"></i>
        </button>

        <nav class="nav-menu" id="navMenu">
            <a class="nav-link active" href="#home">Home</a>
            <a class="nav-link" href="#features">Features</a>
            <a class="nav-link" href="#statistics">Statistics</a>

            <div class="nav-actions">
                @auth
                    @php
                        $dashboardRoute = match (auth()->user()->role) {
                            'superadmin' => 'superadmin',
                            'ceo' => 'ceo.dashboard',
                            'admin' => 'admin',
                            default => 'user.index',
                        };
                    @endphp

                    @if(Route::has($dashboardRoute))
                        <a class="btn btn-primary"
                           href="{{ route($dashboardRoute) }}">
                            <i class="fa-solid fa-gauge-high"></i>
                            Dashboard
                        </a>
                    @else
                        <a class="btn btn-outline"
                           href="{{ url('/login') }}">
                            <i class="fa-solid fa-user"></i>
                            My account
                        </a>
                    @endif
                @else
                    <a class="btn btn-primary" href="{{ route('login') }}">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                        Sign in <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                @endauth
            </div>
        </nav>
    </header>

    <!-- HERO -->
    <main>
        <section class="hero" id="home">

            <div class="hero-copy">
                <div class="eyebrow">
                    <span class="pulse-dot"></span>
                    SMART MAINTENANCE MANAGEMENT
                </div>

                <h1>
                    Maintenance<br>
                    <span class="gradient-text">Without Limits.</span>
                </h1>

                <p class="hero-description">
                    Take control of preventive maintenance with TAPCORE.
                    Manage PPM records, digital checklists, equipment
                    tracking and verification in one intelligent,
                    streamlined workspace.
                </p>

                <div class="hero-buttons">
                    @auth
                        @if(Route::has($dashboardRoute))
                            <a class="btn btn-primary"
                               href="{{ route($dashboardRoute) }}">
                                Open Dashboard
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        @endif
                    @else
                        <a class="btn btn-primary" href="{{ route('login') }}">
                            Explore Your Workspace
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>

                        @if(Route::has('register'))
                            <a class="btn btn-outline"
                               href="{{ route('register') }}">
                                <i class="fa-solid fa-user-plus"></i>
                                Create Account
                            </a>
                        @endif
                    @endauth
                </div>

                <div class="trust-row">
                    <span><i class="fa-solid fa-circle-check"></i> Digital workflows</span>
                    <span><i class="fa-solid fa-circle-check"></i> Asset tracking</span>
                    <span><i class="fa-solid fa-circle-check"></i> Traceable records</span>
                </div>
            </div>

            <!-- DYNAMIC DASHBOARD -->
            <div class="hero-visual" id="heroVisual">
                <div class="dashboard-glow"></div>

                <div class="dashboard" id="dashboardCard">

                    <div class="dash-top">
                        <div class="dash-brand">
                            <div class="dash-logo">
                                <i class="fa-solid fa-screwdriver-wrench"></i>
                            </div>
                            <div>
                                <div class="dash-title">PPM Control Center</div>
                                <div class="dash-subtitle">Maintenance overview</div>
                            </div>
                        </div>

                        <div class="live-pill">
                            <i class="fa-solid fa-circle"></i>
                            SYSTEM READY
                        </div>
                    </div>

                    <div class="dash-label">
                        <span>Platform overview</span>
                        <small><i class="fa-regular fa-chart-bar"></i> Database totals</small>
                    </div>

                    <div class="metric-grid">
                        <div class="metric">
                            <div class="metric-icon">
                                <i class="fa-solid fa-gears"></i>
                            </div>
                            <div class="metric-value count-up"
                                 data-count="{{ $totalPpm }}">0</div>
                            <div class="metric-name">PPM RECORDS</div>
                        </div>

                        <div class="metric">
                            <div class="metric-icon">
                                <i class="fa-solid fa-calendar-check"></i>
                            </div>
                            <div class="metric-value count-up"
                                 data-count="{{ $publishedWeeks }}">0</div>
                            <div class="metric-name">PUBLISHED WEEKS</div>
                        </div>

                        <div class="metric">
                            <div class="metric-icon">
                                <i class="fa-solid fa-clipboard-list"></i>
                            </div>
                            <div class="metric-value count-up"
                                 data-count="{{ $totalChecklists }}">0</div>
                            <div class="metric-name">CHECKLISTS</div>
                        </div>
                    </div>

                    <div class="dash-label">
                        <span><i class="fa-solid fa-layer-group"></i> Checklist categories</span>
                        <small>Created records</small>
                    </div>

                    <div class="category-grid">
                        <div class="category">
                            <div class="category-icon">
                                <i class="fa-solid fa-bolt"></i>
                            </div>
                            <div>
                                <div class="category-name">PNL</div>
                                <div class="category-count">
                                    <span class="count-up"
                                          data-count="{{ $pnlChecklists }}">0</span>
                                    records
                                </div>
                            </div>
                        </div>

                        <div class="category">
                            <div class="category-icon">
                                <i class="fa-solid fa-industry"></i>
                            </div>
                            <div>
                                <div class="category-name">KHM / TRQ</div>
                                <div class="category-count">
                                    <span class="count-up"
                                          data-count="{{ $khmChecklists }}">0</span>
                                    records
                                </div>
                            </div>
                        </div>

                        <div class="category">
                            <div class="category-icon">
                                <i class="fa-solid fa-screwdriver-wrench"></i>
                            </div>
                            <div>
                                <div class="category-name">TST · F1</div>
                                <div class="category-count">
                                    <span class="count-up"
                                          data-count="{{ $tstFrequency1 }}">0</span>
                                    records
                                </div>
                            </div>
                        </div>

                        <div class="category">
                            <div class="category-icon">
                                <i class="fa-solid fa-gears"></i>
                            </div>
                            <div>
                                <div class="category-name">TST · F4</div>
                                <div class="category-count">
                                    <span class="count-up"
                                          data-count="{{ $tstFrequency4 }}">0</span>
                                    records
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="completion-box">
                        <div class="completion-info">
                            <div class="completion-title">Checklist completion</div>
                            <div class="completion-subtitle">
                                {{ number_format($completedChecklists) }} completed out of
                                {{ number_format($totalChecklists) }} saved checklists
                            </div>

                            <div class="mini-track">
                                <div class="mini-fill"
                                     id="completionBar"
                                     data-progress="{{ $completionRate }}"></div>
                            </div>
                        </div>

                        <div class="ring"
                             id="completionRing"
                             data-progress="{{ $completionRate }}">
                            <svg viewBox="0 0 72 72" aria-hidden="true">
                                <circle class="ring-bg"
                                        cx="36" cy="36" r="30"></circle>
                                <circle class="ring-progress"
                                        id="ringProgress"
                                        cx="36" cy="36" r="30"></circle>
                            </svg>
                            <span class="ring-text"
                                  id="ringText">{{ $completionRate }}%</span>
                        </div>
                    </div>

                    <div class="dash-bottom">
                        <span>
                            <i class="fa-solid fa-circle-check"></i>
                            {{ number_format($completedChecklists) }} completed
                        </span>
                        <span>
                            <i class="fa-regular fa-clock"
                               style="color:#ffb547"></i>
                            {{ number_format($pendingChecklists) }} pending
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURES -->
        <section class="section features-section" id="features">
            <div class="section-head reveal">
                <span class="section-tag">
                    <i class="fa-solid fa-asterisk"></i>
                    BUILT FOR YOUR WORKFLOW
                </span>

                <h2>Everything works <span class="gradient-text">together.</span></h2>

                <p>
                    From importing maintenance records to digital
                    inspections and verification, bring your workflow
                    into one organized platform.
                </p>
            </div>

            <div class="feature-grid">
                <article class="feature-card">
                    <span class="feature-index">01</span>
                    <div class="feature-icon">
                        <i class="fa-solid fa-file-import"></i>
                    </div>
                    <h3>PPM Management</h3>
                    <p>
                        Organize, import, search and filter preventive
                        maintenance records efficiently.
                    </p>
                </article>

                <article class="feature-card">
                    <span class="feature-index">02</span>
                    <div class="feature-icon">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <h3>Digital Checklists</h3>
                    <p>
                        Record responses, observations, comments and
                        maintenance inspection results digitally.
                    </p>
                </article>

                <article class="feature-card">
                    <span class="feature-index">03</span>
                    <div class="feature-icon">
                        <i class="fa-solid fa-barcode"></i>
                    </div>
                    <h3>Barcode Tracking</h3>
                    <p>
                        Identify equipment and maintenance jobs through
                        barcode-enabled workflows.
                    </p>
                </article>

                <article class="feature-card">
                    <span class="feature-index">04</span>
                    <div class="feature-icon">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3>Verification</h3>
                    <p>
                        Support traceability with maintenance
                        completion and verification records.
                    </p>
                </article>

                <article class="feature-card">
                    <span class="feature-index">05</span>
                    <div class="feature-icon">
                        <i class="fa-solid fa-calendar-week"></i>
                    </div>
                    <h3>Weekly Publishing</h3>
                    <p>
                        Manage maintenance availability through
                        published weeks and controlled work periods.
                    </p>
                </article>

                <article class="feature-card">
                    <span class="feature-index">06</span>
                    <div class="feature-icon">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <h3>PNL Operations</h3>
                    <p>
                        Track saved PNL checklists and inspection
                        progress from a centralized view.
                    </p>
                </article>

                <article class="feature-card">
                    <span class="feature-index">07</span>
                    <div class="feature-icon">
                        <i class="fa-solid fa-gears"></i>
                    </div>
                    <h3>TST Frequencies</h3>
                    <p>
                        View Frequency 1 and Frequency 4 checklist
                        totals in the maintenance overview.
                    </p>
                </article>

                <article class="feature-card">
                    <span class="feature-index">08</span>
                    <div class="feature-icon">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <h3>Operational Insights</h3>
                    <p>
                        Monitor saved records, completion totals
                        and published maintenance weeks.
                    </p>
                </article>
            </div>
        </section>

        <!-- STATISTICS -->
        <section class="section stats-section" id="statistics">
            <div class="section-head reveal">
                <span class="section-tag">
                    <i class="fa-solid fa-chart-simple"></i>
                    LIVE DATABASE OVERVIEW
                </span>

                <h2>Your maintenance, <span class="gradient-text">at a glance.</span></h2>

                <p>
                    These figures are rendered from your Laravel
                    database when the page loads.
                </p>
            </div>

            <div class="stats-grid">
                <article class="stats-card">
                    <div class="stats-icon">
                        <i class="fa-solid fa-gears"></i>
                    </div>
                    <div>
                        <div class="stats-value count-up"
                             data-count="{{ $totalPpm }}">0</div>
                        <div class="stats-label">Total PPM records</div>
                    </div>
                </article>

                <article class="stats-card">
                    <div class="stats-icon">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                    <div>
                        <div class="stats-value count-up"
                             data-count="{{ $totalChecklists }}">0</div>
                        <div class="stats-label">Saved checklists</div>
                    </div>
                </article>

                <article class="stats-card">
                    <div class="stats-icon">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div>
                        <div class="stats-value count-up"
                             data-count="{{ $publishedWeeks }}">0</div>
                        <div class="stats-label">Published weeks</div>
                    </div>
                </article>

                <article class="stats-card">
                    <div class="stats-icon">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <div class="stats-value count-up"
                             data-count="{{ $completedChecklists }}">0</div>
                        <div class="stats-label">Completed checklists</div>
                    </div>
                </article>

                <article class="stats-card">
                    <div class="stats-icon">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                    <div>
                        <div class="stats-value count-up"
                             data-count="{{ $pendingChecklists }}">0</div>
                        <div class="stats-label">Pending checklists</div>
                    </div>
                </article>

                <article class="stats-card">
                    <div class="stats-icon">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <div>
                        <div class="stats-value">{{ $completionRate }}%</div>
                        <div class="stats-label">Completion rate</div>
                    </div>
                </article>
            </div>

            <div class="frequency-panel reveal">
                <div class="frequency-heading">
                    <div>
                        <h3><i class="fa-solid fa-layer-group"
                               style="color:var(--orange)"></i>
                            Checklist breakdown</h3>
                        <p>Saved checklist instances by maintenance type.</p>
                    </div>

                    <span class="frequency-badge">
                        <i class="fa-solid fa-database"></i>
                        DATABASE TOTALS
                    </span>
                </div>

                <div class="frequency-grid">
                    <div class="frequency-item">
                        <span>PNL checklists</span>
                        <strong class="count-up"
                                data-count="{{ $pnlChecklists }}">0</strong>
                    </div>

                    <div class="frequency-item">
                        <span>KHM / TRQ checklists</span>
                        <strong class="count-up"
                                data-count="{{ $khmChecklists }}">0</strong>
                    </div>

                    <div class="frequency-item">
                        <span>TST Frequency 1</span>
                        <strong class="count-up"
                                data-count="{{ $tstFrequency1 }}">0</strong>
                    </div>

                    <div class="frequency-item">
                        <span>TST Frequency 4</span>
                        <strong class="count-up"
                                data-count="{{ $tstFrequency4 }}">0</strong>
                    </div>
                </div>
            </div>
        </section>



        <!-- EXTENDED DATABASE INTELLIGENCE -->
        <section class="intel-overview reveal" aria-labelledby="intelOverviewTitle">
            <div class="intel-heading">
                <div>
                    <span class="section-tag"><i class="fa-solid fa-microchip"></i> MAINTENANCE INTELLIGENCE</span>
                    <h3 id="intelOverviewTitle">Your system, <span class="gradient-text">in numbers.</span></h3>
                    <p>Application-wide statistics across all PPM weeks, plus a live breakdown of equipment IDs and asset categories.</p>
                </div>
                <span class="intel-live">DATABASE OVERVIEW</span>
            </div>

            <div class="intel-kpis">
                <article class="intel-kpi">
                    <div class="intel-kpi-icon"><i class="fa-solid fa-users"></i></div>
                    <div><small>Registered users</small><strong class="count-up" data-count="{{ $overviewUsers }}">0</strong></div>
                </article>
                <article class="intel-kpi">
                    <div class="intel-kpi-icon"><i class="fa-solid fa-clipboard-list"></i></div>
                    <div><small>All PPM records</small><strong class="count-up" data-count="{{ $overviewPpmRecords }}">0</strong></div>
                </article>
                <article class="intel-kpi">
                    <div class="intel-kpi-icon"><i class="fa-solid fa-barcode"></i></div>
                    <div><small>Unique asset IDs</small><strong class="count-up" data-count="{{ $overviewUniqueAssets }}">0</strong></div>
                </article>
                <article class="intel-kpi">
                    <div class="intel-kpi-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <div><small>Records without asset ID</small><strong class="count-up" data-count="{{ $overviewMissingAssetIds }}">0</strong></div>
                </article>
            </div>

            <div class="intel-section-label"><i class="fa-solid fa-industry"></i> Equipment inventory by type</div>
            <div class="intel-types">
                @forelse ($overviewTypeStats as $item)
                    @php
                        $typeKey = strtolower((string) ($item->type ?? 'generic'));
                        $typeLabel = $item->label ?? strtoupper($typeKey);
                        $typeRecords = (int) ($item->records ?? $item->total ?? 0);
                        $typeAssets = (int) ($item->asset_ids ?? 0);
                        $typeIcon = match ($typeKey) {
                            'pnl' => 'fa-bolt',
                            'tst' => 'fa-gauge-high',
                            'khm' => 'fa-fan',
                            'cons' => 'fa-boxes-stacked',
                            default => 'fa-gears',
                        };
                    @endphp
                    <article class="intel-type">
                        <div class="intel-type-top"><span>{{ $typeLabel }}</span><i class="fa-solid {{ $typeIcon }}"></i></div>
                        <div class="intel-type-number count-up" data-count="{{ $typeAssets }}">0</div>
                        <div class="intel-type-meta">Unique asset IDs<br>{{ number_format($typeRecords) }} PPM records</div>
                    </article>
                @empty
                    <p class="intel-footnote">No PPM asset types are available yet.</p>
                @endforelse
            </div>

            <div class="intel-section-label"><i class="fa-solid fa-user-shield"></i> Users by role</div>
            <div class="intel-role-list">
                @forelse ($overviewUsersByRole as $role => $roleInfo)
                    <div class="intel-role">
                        <i class="fa-solid fa-user-check"></i>
                        <span>{{ ucfirst((string) ($role ?: 'unassigned')) }}</span>
                        <strong>{{ number_format((int) ($roleInfo->total ?? 0)) }}</strong>
                    </div>
                @empty
                    <span class="intel-footnote">No registered users were found.</span>
                @endforelse
            </div>
            <p class="intel-footnote"><i class="fa-solid fa-circle-info"></i> User and inventory totals include all weeks. Existing checklist completion figures above remain separate.</p>
        </section>


        <!-- CTA -->
        <section class="cta-wrap">
            <div class="cta reveal">
                <div class="cta-copy">
                    <h2>Ready to manage maintenance smarter?</h2>
                    <p>
                        Open your TAPCORE workspace and continue
                        managing maintenance records and checklists.
                    </p>
                </div>

                @auth
                    @if(Route::has($dashboardRoute))
                        <a href="{{ route($dashboardRoute) }}"
                           class="btn btn-primary">
                            Go to Dashboard
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        Enter TAPCORE
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @endauth
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div>
            <strong>TAPCORE</strong>
            &nbsp; / &nbsp; Maintenance Management
        </div>

        <div class="footer-right">
            &copy; {{ date('Y') }} VERSIGENT / Morocco III
        </div>
    </footer>

    <a href="#top" class="back-top" id="backTop" aria-label="Back to top">
        <i class="fa-solid fa-arrow-up"></i>
    </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const reduceMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    ).matches;

    const navbar = document.getElementById('navbar');
    const backTop = document.getElementById('backTop');
    const menuToggle = document.getElementById('menuToggle');
    const navMenu = document.getElementById('navMenu');

    // Mobile navigation
    if (menuToggle && navMenu) menuToggle.addEventListener('click', () => {
        const isOpen = navMenu.classList.toggle('open');

        menuToggle.setAttribute('aria-expanded', String(isOpen));
        menuToggle.innerHTML = isOpen
            ? '<i class="fa-solid fa-xmark"></i>'
            : '<i class="fa-solid fa-bars"></i>';
    });

    navMenu?.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('open');
            menuToggle?.setAttribute('aria-expanded', 'false');
            if (menuToggle) menuToggle.innerHTML = '<i class="fa-solid fa-bars"></i>';
        });
    });

    // Header and back-to-top button
    const onScroll = () => {
        const scrollY = window.scrollY;

        navbar.classList.toggle('scrolled', scrollY > 20);
        backTop.classList.toggle('visible', scrollY > 450);

        let current = 'home';

        document.querySelectorAll('main section[id]').forEach(section => {
            if (scrollY >= section.offsetTop - 160) {
                current = section.id;
            }
        });

        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.toggle(
                'active',
                link.getAttribute('href') === '#' + current
            );
        });
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    // Reveal elements when scrolling into view
    const revealElements = document.querySelectorAll(
        '.feature-card, .reveal'
    );

    if ('IntersectionObserver' in window && !reduceMotion) {
        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;

                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.12 });

        revealElements.forEach(element => {
            revealObserver.observe(element);
        });
    } else {
        revealElements.forEach(element => {
            element.classList.add('visible');
        });
    }

    // Animate number counters when they become visible
    const counters = document.querySelectorAll('.count-up');

    function animateCount(element) {
        const target = Number(element.dataset.count || 0);

        if (!Number.isFinite(target)) {
            element.textContent = '0';
            return;
        }

        if (reduceMotion) {
            element.textContent = Math.round(target).toLocaleString();
            return;
        }

        const duration = 1200;
        const startTime = performance.now();

        function frame(now) {
            const progress = Math.min((now - startTime) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 4);

            element.textContent = Math.floor(target * eased)
                .toLocaleString();

            if (progress < 1) {
                requestAnimationFrame(frame);
            } else {
                element.textContent = Math.round(target).toLocaleString();
            }
        }

        requestAnimationFrame(frame);
    }

    if ('IntersectionObserver' in window && !reduceMotion) {
        const counterObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;

                animateCount(entry.target);
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => {
            counterObserver.observe(counter);
        });
    } else {
        counters.forEach(animateCount);
    }

    // Animate completion progress bar and circular progress
    const completionBar = document.getElementById('completionBar');
    const ring = document.getElementById('completionRing');
    const ringProgress = document.getElementById('ringProgress');

    if (completionBar && ring && ringProgress) {
        const value = Math.max(
            0,
            Math.min(100, Number(ring.dataset.progress || 0))
        );

        const circumference = 2 * Math.PI * 30;

        ringProgress.style.strokeDasharray = circumference;
        ringProgress.style.strokeDashoffset = circumference;

        const animateProgress = () => {
            completionBar.style.width = value + '%';
            ringProgress.style.strokeDashoffset =
                circumference * (1 - value / 100);
        };

        if (reduceMotion) {
            animateProgress();
        } else {
            setTimeout(animateProgress, 250);
        }
    }

    // Gentle 3D tilt on larger screens
    const visual = document.getElementById('heroVisual');
    const dashboard = document.getElementById('dashboardCard');

    if (
        visual &&
        dashboard &&
        !reduceMotion &&
        window.matchMedia('(pointer: fine)').matches &&
        window.innerWidth > 850
    ) {
        visual.addEventListener('mousemove', event => {
            const rect = visual.getBoundingClientRect();

            const x = (event.clientX - rect.left) / rect.width - 0.5;
            const y = (event.clientY - rect.top) / rect.height - 0.5;

            dashboard.style.transform =
                `rotateY(${x * 5}deg) rotateX(${-y * 5}deg)`;
        });

        visual.addEventListener('mouseleave', () => {
            dashboard.style.transform = '';
        });
    }

    // Brand glass-impact interaction: replay the shatter effect on every hover.
    const brand = document.querySelector('.tapcore-brand');
    if (brand && !reduceMotion) {
        brand.addEventListener('mouseenter', () => {
            brand.classList.remove('brand-impact');
            void brand.offsetWidth;
            brand.classList.add('brand-impact');
        });
        brand.addEventListener('mouseleave', () => brand.classList.remove('brand-impact'));
    }

    // Subtle 3D pointer response for feature cards on desktop.
    if (!reduceMotion && window.matchMedia('(pointer: fine)').matches) {
        document.querySelectorAll('.feature-card, .stats-card').forEach(card => {
            card.addEventListener('mousemove', event => {
                if (window.innerWidth < 900) return;
                const rect = card.getBoundingClientRect();
                const x = (event.clientX - rect.left) / rect.width - .5;
                const y = (event.clientY - rect.top) / rect.height - .5;
                card.style.transform = `translateY(-6px) rotateX(${-y * 3}deg) rotateY(${x * 3}deg)`;
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = '';
            });
        });
    }

    // Lightweight ambient particle animation
    const canvas = document.getElementById('particleCanvas');
    const context = canvas && canvas.getContext('2d');

    if (canvas && context && !reduceMotion) {
        let particles = [];
        let width = 0;
        let height = 0;
        let animationFrame = null;

        function resizeCanvas() {
            const ratio = Math.min(window.devicePixelRatio || 1, 2);

            width = window.innerWidth;
            height = 800;

            canvas.width = width * ratio;
            canvas.height = height * ratio;
            canvas.style.width = width + 'px';
            canvas.style.height = height + 'px';

            context.setTransform(ratio, 0, 0, ratio, 0, 0);

            const count = Math.min(48, Math.floor(width / 26));

            particles = Array.from({ length: count }, () => ({
                x: Math.random() * width,
                y: Math.random() * height,
                radius: Math.random() * 1.5 + .4,
                speedX: (Math.random() - .5) * .25,
                speedY: Math.random() * .24 + .06,
                alpha: Math.random() * .35 + .1
            }));
        }

        function drawParticles() {
            context.clearRect(0, 0, width, height);

            for (const particle of particles) {
                particle.x += particle.speedX;
                particle.y -= particle.speedY;

                if (particle.y < -5) {
                    particle.y = height + 5;
                    particle.x = Math.random() * width;
                }

                if (particle.x < -5) particle.x = width + 5;
                if (particle.x > width + 5) particle.x = -5;

                context.beginPath();
                context.arc(
                    particle.x,
                    particle.y,
                    particle.radius,
                    0,
                    Math.PI * 2
                );

                context.fillStyle =
                    `rgba(255, 170, 75, ${particle.alpha})`;

                context.fill();
            }

            animationFrame = requestAnimationFrame(drawParticles);
        }

        resizeCanvas();
        drawParticles();

        let resizeTimer;

        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);

            resizeTimer = setTimeout(() => {
                resizeCanvas();
            }, 150);
        }, { passive: true });

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                if (animationFrame) {
                    cancelAnimationFrame(animationFrame);
                    animationFrame = null;
                }
            } else if (!animationFrame) {
                drawParticles();
            }
        });
    }
});
</script>

</body>
</html>
