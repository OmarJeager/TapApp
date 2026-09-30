<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TAP APP | Maintenance Management</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #07111f;
            color: white;
            overflow-x: hidden;
        }

        /* =========================================
           BACKGROUND
        ========================================= */

        .page {
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .background {
            position: absolute;
            inset: 0;

            background:
                linear-gradient(
                    90deg,
                    rgba(3, 12, 25, 0.98) 0%,
                    rgba(5, 18, 35, 0.94) 42%,
                    rgba(5, 18, 35, 0.72) 70%,
                    rgba(5, 18, 35, 0.90) 100%
                ),
                url("{{ asset('pictures/maintenance.jpg') }}");

            background-size: cover;
            background-position: center;

            animation: backgroundZoom 18s ease-in-out infinite alternate;
            z-index: 0;
        }

        @keyframes backgroundZoom {
            from {
                transform: scale(1);
            }

            to {
                transform: scale(1.08);
            }
        }

        /* =========================================
           GLOWING CIRCLES
        ========================================= */

        .glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(5px);
            pointer-events: none;
            z-index: 1;
        }

        .glow-1 {
            width: 450px;
            height: 450px;
            background: rgba(255, 132, 0, 0.10);
            top: -180px;
            right: -100px;
            animation: float1 8s ease-in-out infinite;
        }

        .glow-2 {
            width: 350px;
            height: 350px;
            background: rgba(0, 132, 255, 0.12);
            bottom: -150px;
            left: -100px;
            animation: float2 10s ease-in-out infinite;
        }

        .glow-3 {
            width: 180px;
            height: 180px;
            background: rgba(255, 145, 0, 0.08);
            top: 45%;
            right: 42%;
            animation: pulse 5s ease-in-out infinite;
        }

        @keyframes float1 {
            0%, 100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(-30px, 40px);
            }
        }

        @keyframes float2 {
            0%, 100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(40px, -30px);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: .5;
            }

            50% {
                transform: scale(1.35);
                opacity: 1;
            }
        }

        /* =========================================
           NAVBAR
        ========================================= */

        .navbar {
            position: relative;
            z-index: 10;

            width: 100%;
            height: 90px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 7%;

            border-bottom: 1px solid rgba(255,255,255,.08);

            background: rgba(3, 12, 25, .35);
            backdrop-filter: blur(12px);

            animation: navbarDown .8s ease forwards;
        }

        @keyframes navbarDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo-container img {
            width: 150px;
            max-height: 65px;
            object-fit: contain;

            filter: drop-shadow(0 0 12px rgba(255, 140, 0, .25));

            transition: .4s ease;
        }

        .logo-container img:hover {
            transform: scale(1.06);
            filter: drop-shadow(0 0 20px rgba(255, 140, 0, .65));
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
        }

        .nav-links a {
            color: rgba(255,255,255,.82);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;

            transition: .3s ease;
        }

        .nav-links a:hover {
            color: #ff9200;
        }

        .login-btn {
            padding: 11px 23px;

            border: 1px solid rgba(255,145,0,.6);
            border-radius: 9px;

            color: white !important;

            background: rgba(255,145,0,.10);

            transition: .3s ease;
        }

        .login-btn:hover {
            background: #ff9200;
            color: #07111f !important;
            box-shadow: 0 0 25px rgba(255,145,0,.35);
        }

        /* =========================================
           HERO
        ========================================= */

        .hero {
            position: relative;
            z-index: 5;

            min-height: calc(100vh - 90px);

            display: flex;
            align-items: center;

            padding: 70px 7%;
        }

        .hero-content {
            width: 58%;
            max-width: 750px;

            animation: heroAppear 1.1s ease forwards;
        }

        @keyframes heroAppear {
            from {
                opacity: 0;
                transform: translateX(-60px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .small-title {
            display: inline-flex;
            align-items: center;
            gap: 10px;

            padding: 9px 15px;

            border-radius: 30px;

            background: rgba(255,145,0,.10);
            border: 1px solid rgba(255,145,0,.30);

            color: #ff9d19;

            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;

            margin-bottom: 25px;
        }

        .small-title span {
            width: 8px;
            height: 8px;

            border-radius: 50%;

            background: #ff9200;

            box-shadow: 0 0 12px #ff9200;

            animation: onlinePulse 1.5s infinite;
        }

        @keyframes onlinePulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .4;
                transform: scale(.7);
            }
        }

        .hero h1 {
            font-size: clamp(48px, 6vw, 86px);
            line-height: .98;
            letter-spacing: -3px;

            margin-bottom: 25px;

            font-weight: 800;
        }

        .hero h1 .orange {
            color: #ff9200;

            text-shadow:
                0 0 25px rgba(255,146,0,.20);
        }

        .hero-description {
            max-width: 650px;

            font-size: 18px;
            line-height: 1.8;

            color: rgba(255,255,255,.70);

            margin-bottom: 35px;
        }

        /* =========================================
           BUTTONS
        ========================================= */

        .hero-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .primary-btn,
        .secondary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            min-width: 155px;

            padding: 14px 25px;

            border-radius: 10px;

            text-decoration: none;

            font-size: 14px;
            font-weight: 700;

            transition: .35s ease;
        }

        .primary-btn {
            background: #ff9200;
            color: #07111f;

            box-shadow:
                0 10px 30px rgba(255,146,0,.20);
        }

        .primary-btn:hover {
            transform: translateY(-4px);
            background: #ffa51f;

            box-shadow:
                0 15px 35px rgba(255,146,0,.35);
        }

        .secondary-btn {
            border: 1px solid rgba(255,255,255,.18);
            background: rgba(255,255,255,.06);

            color: white;

            backdrop-filter: blur(8px);
        }

        .secondary-btn:hover {
            transform: translateY(-4px);
            border-color: rgba(255,145,0,.5);
            background: rgba(255,145,0,.10);
        }

        /* =========================================
           DASHBOARD CARD
        ========================================= */

        .dashboard-wrapper {
            position: absolute;
            z-index: 5;

            right: 6%;
            top: 50%;

            transform: translateY(-45%);

            width: 400px;

            animation:
                dashboardAppear 1.2s ease forwards,
                dashboardFloat 6s ease-in-out 1.2s infinite;
        }

        @keyframes dashboardAppear {
            from {
                opacity: 0;
                transform: translate(80px, -45%);
            }

            to {
                opacity: 1;
                transform: translate(0, -45%);
            }
        }

        @keyframes dashboardFloat {
            0%, 100% {
                margin-top: 0;
            }

            50% {
                margin-top: -12px;
            }
        }

        .dashboard {
            padding: 22px;

            border-radius: 20px;

            background:
                linear-gradient(
                    145deg,
                    rgba(19,35,55,.92),
                    rgba(7,18,32,.94)
                );

            border: 1px solid rgba(255,255,255,.12);

            box-shadow:
                0 30px 80px rgba(0,0,0,.45),
                0 0 40px rgba(255,145,0,.07);

            backdrop-filter: blur(20px);
        }

        .dashboard-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            margin-bottom: 20px;
        }

        .dashboard-title {
            font-size: 15px;
            font-weight: 700;
        }

        .dashboard-status {
            font-size: 11px;

            padding: 6px 10px;

            border-radius: 20px;

            background: rgba(35,197,94,.10);
            color: #4ade80;

            border: 1px solid rgba(35,197,94,.20);
        }

        /* =========================================
           STAT CARDS
        ========================================= */

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;

            margin-bottom: 18px;
        }

        .stat {
            padding: 14px 10px;

            border-radius: 12px;

            background: rgba(255,255,255,.045);

            border: 1px solid rgba(255,255,255,.06);

            text-align: center;

            transition: .3s ease;
        }

        .stat:hover {
            transform: translateY(-4px);
            border-color: rgba(255,145,0,.3);
        }

        .stat-number {
            font-size: 23px;
            font-weight: 800;

            margin-bottom: 4px;
        }

        .stat-label {
            color: rgba(255,255,255,.48);
            font-size: 10px;
        }

        /* =========================================
           PPM LIST
        ========================================= */

        .records {
            display: flex;
            flex-direction: column;
            gap: 9px;
        }

        .record {
            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 13px;

            border-radius: 11px;

            background: rgba(255,255,255,.035);

            border: 1px solid rgba(255,255,255,.055);

            transition: .3s ease;
        }

        .record:hover {
            transform: translateX(5px);
            background: rgba(255,145,0,.07);
        }

        .record-left {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .asset-icon {
            width: 34px;
            height: 34px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 9px;

            background: rgba(255,145,0,.12);
            color: #ff9200;

            font-size: 14px;
        }

        .asset-name {
            font-size: 12px;
            font-weight: 700;
        }

        .asset-id {
            font-size: 9px;
            color: rgba(255,255,255,.40);

            margin-top: 3px;
        }

        .complete {
            color: #4ade80;
            font-size: 10px;
        }

        .pending {
            color: #ffb84d;
            font-size: 10px;
        }

        /* =========================================
           FEATURES
        ========================================= */

        .features {
            position: relative;
            z-index: 6;

            padding: 80px 7% 100px;

            background:
                linear-gradient(
                    180deg,
                    #07111f 0%,
                    #091827 100%
                );
        }

        .section-title {
            text-align: center;
            margin-bottom: 45px;
        }

        .section-title span {
            color: #ff9200;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .section-title h2 {
            margin-top: 10px;

            font-size: 38px;
        }

        .section-title p {
            color: rgba(255,255,255,.55);
            margin-top: 12px;
        }

        .feature-grid {
            max-width: 1150px;
            margin: auto;

            display: grid;
            grid-template-columns: repeat(4, 1fr);

            gap: 18px;
        }

        .feature {
            padding: 28px 22px;

            border-radius: 16px;

            background: rgba(255,255,255,.035);

            border: 1px solid rgba(255,255,255,.07);

            transition: .4s ease;
        }

        .feature:hover {
            transform: translateY(-8px);

            border-color: rgba(255,145,0,.35);

            box-shadow:
                0 20px 50px rgba(0,0,0,.25);
        }

        .feature-icon {
            width: 50px;
            height: 50px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 13px;

            background: rgba(255,145,0,.10);

            color: #ff9200;

            font-size: 22px;

            margin-bottom: 20px;
        }

        .feature h3 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .feature p {
            color: rgba(255,255,255,.48);

            font-size: 13px;
            line-height: 1.7;
        }

        /* =========================================
           FOOTER
        ========================================= */

        footer {
            position: relative;
            z-index: 5;

            padding: 25px 7%;

            border-top: 1px solid rgba(255,255,255,.07);

            background: #050d18;

            display: flex;
            justify-content: space-between;
            align-items: center;

            color: rgba(255,255,255,.40);

            font-size: 12px;
        }

        .footer-brand {
            color: #ff9200;
            font-weight: 800;
        }

        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 1100px) {

            .hero-content {
                width: 60%;
            }

            .dashboard-wrapper {
                right: 3%;
                width: 350px;
            }

            .feature-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 850px) {

            .navbar {
                padding: 0 5%;
            }

            .nav-links a:not(.login-btn) {
                display: none;
            }

            .hero {
                padding: 70px 6%;
                min-height: auto;
            }

            .hero-content {
                width: 100%;
                max-width: 650px;
            }

            .hero h1 {
                font-size: 55px;
            }

            .dashboard-wrapper {
                display: none;
            }

            .background {
                background:
                    linear-gradient(
                        90deg,
                        rgba(3,12,25,.96),
                        rgba(3,12,25,.90)
                    ),
                    url("{{ asset('pictures/maintenance.jpg') }}");

                background-position: center;
            }
        }

        @media (max-width: 600px) {

            .navbar {
                height: 75px;
            }

            .logo-container img {
                width: 120px;
            }

            .hero {
                padding: 65px 6%;
            }

            .hero h1 {
                font-size: 43px;
                letter-spacing: -1.5px;
            }

            .hero-description {
                font-size: 15px;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .primary-btn,
            .secondary-btn {
                width: 100%;
            }

            .feature-grid {
                grid-template-columns: 1fr;
            }

            .features {
                padding: 65px 6%;
            }

            .section-title h2 {
                font-size: 30px;
            }

            footer {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <!-- Background -->
    <div class="background"></div>

    <!-- Animated glowing circles -->
    <div class="glow glow-1"></div>
    <div class="glow glow-2"></div>
    <div class="glow glow-3"></div>

    <!-- =====================================
         NAVBAR
    ====================================== -->

    <nav class="navbar">

        <a href="{{ url('/') }}" class="logo-container">
            <img
                src="{{ asset('pictures/logo.png') }}"
                alt="Company Logo"
            >
        </a>

        <div class="nav-links">

            <a href="#home">Home</a>

            <a href="#features">Features</a>

            @auth
                <a href="{{ route('user.index') }}" class="login-btn">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="login-btn">
                    Login
                </a>
            @endauth

        </div>

    </nav>


    <!-- =====================================
         HERO
    ====================================== -->

    <section class="hero" id="home">

        <div class="hero-content">

            <div class="small-title">
                <span></span>
                MAINTENANCE MANAGEMENT SYSTEM
            </div>

            <h1>
                Maintenance
                <br>

                <span class="orange">
                    Made Simple.
                </span>
            </h1>

            <p class="hero-description">
                TAP APP brings preventive maintenance records,
                digital checklists, equipment tracking and
                verification together in one powerful platform.
            </p>

            <div class="hero-buttons">

                @auth

                    <a
                        href="{{ route('user.index') }}"
                        class="primary-btn"
                    >
                        Open Dashboard
                    </a>

                @else

                    <a
                        href="{{ route('login') }}"
                        class="primary-btn"
                    >
                        Get Started
                    </a>

                    @if(Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="secondary-btn"
                        >
                            Create Account
                        </a>
                    @endif

                @endauth

            </div>

        </div>


        <!-- =====================================
             DASHBOARD PREVIEW
        ====================================== -->

        <div class="dashboard-wrapper">

            <div class="dashboard">

                <div class="dashboard-header">

                    <div class="dashboard-title">
                        PPM Control Center
                    </div>

                    <div class="dashboard-status">
                        ● System Online
                    </div>

                </div>


                <div class="stats">

                    <div class="stat">
                        <div class="stat-number">{{ \App\Models\PpmRecord::count() }}</div>
                        <div class="stat-label">TOTAL PPM</div>
                    </div>

                    <div class="stat">
                        <div class="stat-number">42</div>
                        <div class="stat-label">PENDING</div>
                    </div>

                    <div class="stat">
                        <div class="stat-number">86</div>
                        <div class="stat-label">DONE</div>
                    </div>

                </div>


                <div class="records">

                    <div class="record">

                        <div class="record-left">

                            <div class="asset-icon">
                                ⚙
                            </div>

                            <div>
                                <div class="asset-name">
                                    PNL Equipment
                                </div>

                                <div class="asset-id">
                                    Asset: PNL001
                                </div>
                            </div>

                        </div>

                        <div class="complete">
                            ✓ Complete
                        </div>

                    </div>


                    <div class="record">

                        <div class="record-left">

                            <div class="asset-icon">
                                🔧
                            </div>

                            <div>
                                <div class="asset-name">
                                    TST Equipment
                                </div>

                                <div class="asset-id">
                                    Asset: TST024
                                </div>
                            </div>

                        </div>

                        <div class="pending">
                            ● Pending
                        </div>

                    </div>


                    <div class="record">

                        <div class="record-left">

                            <div class="asset-icon">
                                ✓
                            </div>

                            <div>
                                <div class="asset-name">
                                    Maintenance Verified
                                </div>

                                <div class="asset-id">
                                    Job ID: 595000
                                </div>
                            </div>

                        </div>

                        <div class="complete">
                            ✓ Verified
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================
         FEATURES
    ====================================== -->

    <section class="features" id="features">

        <div class="section-title">

            <span>POWERFUL TOOLS</span>

            <h2>
                Everything You Need
            </h2>

            <p>
                A centralized platform for modern maintenance operations.
            </p>

        </div>


        <div class="feature-grid">


            <div class="feature">

                <div class="feature-icon">
                    📋
                </div>

                <h3>
                    PPM Management
                </h3>

                <p>
                    Import, organize and manage preventive
                    maintenance records efficiently.
                </p>

            </div>


            <div class="feature">

                <div class="feature-icon">
                    🔧
                </div>

                <h3>
                    Digital Checklists
                </h3>

                <p>
                    Complete maintenance checklists digitally
                    with observations, comments and responses.
                </p>

            </div>


            <div class="feature">

                <div class="feature-icon">
                    ▦
                </div>

                <h3>
                    Barcode Tracking
                </h3>

                <p>
                    Identify maintenance jobs quickly using
                    Job IDs and barcode technology.
                </p>

            </div>


            <div class="feature">

                <div class="feature-icon">
                    ✓
                </div>

                <h3>
                    Verification
                </h3>

                <p>
                    Track completion and verification to improve
                    maintenance traceability.
                </p>

            </div>


        </div>

    </section>


    <!-- =====================================
         FOOTER
    ====================================== -->

    <footer>

        <div>
            <span class="footer-brand">
                TAP APP
            </span>

            &nbsp; | &nbsp;

            Maintenance Management
        </div>

        <div>
            © {{ date('Y') }} VERSIGENT / Morocco III
        </div>

    </footer>

</div>

</body>
</html>
