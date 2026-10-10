
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#070b14">

    <title>TAP APP | Smart Maintenance</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
        <a href="{{ url('/') }}" class="brand" aria-label="TAP APP home">
            <img src="{{ asset('pictures/logo.png') }}" alt="TAP APP logo">
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
                    Take control of preventive maintenance with TAP APP.
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

        <!-- CTA -->
        <section class="cta-wrap">
            <div class="cta reveal">
                <div class="cta-copy">
                    <h2>Ready to manage maintenance smarter?</h2>
                    <p>
                        Open your TAP APP workspace and continue
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
                        Enter TAP APP
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @endauth
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div>
            <strong>TAP APP</strong>
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
    menuToggle.addEventListener('click', () => {
        const isOpen = navMenu.classList.toggle('open');

        menuToggle.setAttribute('aria-expanded', String(isOpen));
        menuToggle.innerHTML = isOpen
            ? '<i class="fa-solid fa-xmark"></i>'
            : '<i class="fa-solid fa-bars"></i>';
    });

    navMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('open');
            menuToggle.setAttribute('aria-expanded', 'false');
            menuToggle.innerHTML = '<i class="fa-solid fa-bars"></i>';
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
