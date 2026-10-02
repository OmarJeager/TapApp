<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'TapApp')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    <style>

        /* =====================================================
           GLOBAL
        ===================================================== */

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {

            margin: 0;

            min-height: 100vh;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            color: #1f2937;

            background:
                linear-gradient(
                    135deg,
                    #f8fafc 0%,
                    #fffaf5 50%,
                    #f8fafc 100%
                );

            overflow-x: hidden;
        }


        /* =====================================================
           ANIMATED BACKGROUND
        ===================================================== */

        body::before {

            content: "";

            position: fixed;

            width: 400px;
            height: 400px;

            top: -180px;
            right: -150px;

            border-radius: 50%;

            background:
                radial-gradient(
                    circle,
                    rgba(249,115,22,0.10),
                    transparent 70%
                );

            animation:
                backgroundFloat 10s ease-in-out infinite;

            pointer-events: none;

            z-index: -1;
        }


        body::after {

            content: "";

            position: fixed;

            width: 300px;
            height: 300px;

            bottom: -150px;
            left: -120px;

            border-radius: 50%;

            background:
                radial-gradient(
                    circle,
                    rgba(234,88,12,0.07),
                    transparent 70%
                );

            animation:
                backgroundFloatReverse 12s ease-in-out infinite;

            pointer-events: none;

            z-index: -1;
        }


        /* =====================================================
           TOP HEADER
        ===================================================== */

        .top-header {

            position: relative;

            width: 100%;

            min-height: 125px;

            padding:
                18px
                25px;

            display: flex;

            align-items: flex-start;

            justify-content: space-between;

            gap: 20px;

            z-index: 50;
        }


        /* =====================================================
           LOGO LEFT
        ===================================================== */

        .top-logo {

            position: relative;

            display: flex;

            align-items: center;

            justify-content: flex-start;

            padding:
                4px
                8px;

            animation:
                logoEntrance
                0.9s
                cubic-bezier(.17,.67,.35,1.25)
                both;
        }


        .top-logo::before {

            content: "";

            position: absolute;

            width: 100px;
            height: 100px;

            left: -15px;
            top: -25px;

            border-radius: 50%;

            background:
                radial-gradient(
                    circle,
                    rgba(249,115,22,0.15),
                    transparent 70%
                );

            animation:
                logoGlow 3s ease-in-out infinite;

            pointer-events: none;
        }


        .top-logo img {

            position: relative;

            width: 135px;

            height: auto;

            display: block;

            filter:
                drop-shadow(
                    0 7px 14px
                    rgba(0,0,0,0.12)
                );

            transition:
                transform 0.45s ease,
                filter 0.45s ease;
        }


        .top-logo img:hover {

            transform:
                scale(1.07)
                rotate(-2deg);

            filter:
                drop-shadow(
                    0 12px 22px
                    rgba(234,88,12,0.25)
                );
        }


        /* =====================================================
           LITTLE DECORATIVE LINE UNDER LOGO
        ===================================================== */

        .logo-line {

            position: absolute;

            left: 10px;
            bottom: -5px;

            width: 55px;
            height: 3px;

            border-radius: 20px;

            background:
                linear-gradient(
                    90deg,
                    #ea580c,
                    #fb923c,
                    transparent
                );

            animation:
                logoLine 1.2s ease
                0.5s
                both;
        }


        /* =====================================================
           PROFILE AREA
        ===================================================== */

        .profile-wrapper {

            position: relative;

            width: auto;

            display: flex;

            justify-content: flex-end;

            align-items: flex-start;

            padding: 0;

            margin: 0;

            animation:
                cardEntrance
                0.8s
                cubic-bezier(.17,.67,.35,1.25)
                both;
        }


        /* =====================================================
           COMPACT BUSINESS CARD
        ===================================================== */

        .profile-card {

            position: relative;

            width: 330px;

            min-height: 185px;

            padding:
                20px
                20px;

            display: flex;

            align-items: center;

            gap: 16px;

            background:
                linear-gradient(
                    135deg,
                    rgba(255,255,255,0.98),
                    rgba(255,247,237,0.96)
                );

            border:
                1px solid
                rgba(249,115,22,0.20);

            border-radius: 22px;

            box-shadow:

                0 18px 40px
                rgba(15,23,42,0.10),

                0 5px 15px
                rgba(234,88,12,0.08),

                inset 0 1px 0
                rgba(255,255,255,0.90);

            overflow: hidden;

            isolation: isolate;

            transition:
                transform 0.4s ease,
                box-shadow 0.4s ease;
        }


        .profile-card:hover {

            transform:
                translateY(-6px)
                scale(1.015);

            box-shadow:

                0 25px 55px
                rgba(15,23,42,0.14),

                0 10px 25px
                rgba(234,88,12,0.12);
        }


        /* =====================================================
           CARD ORANGE TOP CORNER
        ===================================================== */

        .card-decoration {

            position: absolute;

            width: 150px;
            height: 150px;

            top: -85px;
            right: -75px;

            border-radius: 50%;

            background:
                linear-gradient(
                    135deg,
                    #ea580c,
                    #fb923c
                );

            opacity: 0.14;

            animation:
                decorationFloat 6s ease-in-out infinite;

            z-index: -1;
        }


        .card-decoration::after {

            content: "";

            position: absolute;

            width: 75px;
            height: 75px;

            left: -20px;
            bottom: -30px;

            border-radius: 50%;

            background:
                rgba(234,88,12,0.15);
        }


        /* =====================================================
           CARD SHINE
        ===================================================== */

        .profile-card::before {

            content: "";

            position: absolute;

            top: -100%;

            left: -80%;

            width: 55%;

            height: 300%;

            background:
                linear-gradient(
                    90deg,
                    transparent,
                    rgba(255,255,255,0.55),
                    transparent
                );

            transform:
                rotate(25deg);

            animation:
                cardShine 6s ease-in-out infinite;

            pointer-events: none;

            z-index: 10;
        }


        /* =====================================================
           PROFILE IMAGE
        ===================================================== */

        .profile-image-container {

            position: relative;

            flex-shrink: 0;

            z-index: 5;

            animation:
                imageEntrance
                0.8s
                0.25s
                both;
        }


        .profile-image-ring {

            width: 92px;
            height: 92px;

            padding: 4px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            background:
                linear-gradient(
                    135deg,
                    #ffffff,
                    #fed7aa,
                    #f97316
                );

            box-shadow:
                0 8px 22px
                rgba(234,88,12,0.20);

            animation:
                ringPulse
                4s
                ease-in-out
                infinite;
        }


        .profile-image {

            width: 84px;
            height: 84px;

            object-fit: cover;

            border-radius: 50%;

            border:
                4px solid white;

            background: white;

            display: block;

            transition:
                transform 0.4s ease,
                box-shadow 0.4s ease;
        }


        .profile-image-container:hover
        .profile-image {

            transform:
                scale(1.08);

            box-shadow:
                0 10px 25px
                rgba(234,88,12,0.25);
        }


        /* =====================================================
           ONLINE STATUS
        ===================================================== */

        .profile-status {

            position: absolute;

            width: 17px;
            height: 17px;

            right: 3px;
            bottom: 5px;

            background:
                #22c55e;

            border:
                3px solid white;

            border-radius: 50%;

            animation:
                statusPulse
                2s infinite;
        }


        /* =====================================================
           PROFILE INFORMATION
        ===================================================== */

        .profile-info {

            min-width: 0;

            flex: 1;

            position: relative;

            z-index: 5;
        }


        .profile-name {

            margin:
                0 0 5px;

            font-size:
                21px;

            line-height:
                1.15;

            font-weight:
                800;

            color:
                #1f2937;

            overflow-wrap:
                anywhere;

            animation:
                fadeUp
                0.7s
                0.3s
                both;
        }


        /* =====================================================
           ROLE
        ===================================================== */

        .profile-role {

            display: inline-flex;

            align-items: center;

            gap: 5px;

            margin-bottom: 9px;

            padding:
                4px
                9px;

            border-radius: 30px;

            background:
                rgba(249,115,22,0.09);

            color:
                #c2410c;

            font-size:
                8px;

            font-weight:
                800;

            letter-spacing:
                1px;

            text-transform:
                uppercase;

            animation:
                fadeUp
                0.7s
                0.4s
                both;
        }


        .profile-role span {

            color:
                #22c55e;

            animation:
                statusDot
                2s
                infinite;
        }


        /* =====================================================
           PROFILE LINES
        ===================================================== */

        .profile-details {

            display:
                flex;

            flex-direction:
                column;

            gap:
                5px;
        }


        .profile-line {

            display:
                flex;

            align-items:
                center;

            gap:
                6px;

            min-width:
                0;

            color:
                #64748b;

            font-size:
                10px;

            animation:
                fadeUp
                0.7s
                0.5s
                both;
        }


        .profile-line strong {

            color:
                #374151;

            font-size:
                9px;
        }


        .profile-line span {

            min-width:
                0;

            overflow-wrap:
                anywhere;
        }


        /* =====================================================
           COMPANY
        ===================================================== */

        .profile-company {

            margin-top:
                9px;

            padding-top:
                8px;

            border-top:
                1px dashed
                #fed7aa;

            color:
                #ea580c;

            font-size:
                10px;

            font-weight:
                800;

            letter-spacing:
                0.8px;

            animation:
                fadeUp
                0.7s
                0.6s
                both;
        }


        .profile-company-sub {

            margin-top:
                2px;

            color:
                #94a3b8;

            font-size:
                7px;

            letter-spacing:
                1.5px;

            font-weight:
                600;
        }


        /* =====================================================
           JOB SECTION
        ===================================================== */

        .job-section {

            margin-top:
                9px;

            padding-top:
                8px;

            border-top:
                1px solid
                #fed7aa;

            animation:
                fadeUp
                0.7s
                0.7s
                both;
        }


        .job-title {

            color:
                #9a3412;

            font-size:
                7px;

            font-weight:
                800;

            letter-spacing:
                1.4px;

            margin-bottom:
                2px;
        }


        .job-id {

            color:
                #ea580c;

            font-size:
                13px;

            font-weight:
                900;

            letter-spacing:
                0.8px;

            margin-bottom:
                4px;

            font-family:
                monospace;

            overflow-wrap:
                anywhere;
        }


        /* =====================================================
           BARCODE
        ===================================================== */

        .barcode-wrapper {

            display:
                flex;

            align-items:
                center;

            gap:
                7px;

            padding:
                4px
                7px;

            background:
                rgba(255,255,255,0.75);

            border-radius:
                7px;

            border:
                1px solid
                #f1f5f9;
        }


        .barcode {

            display:
                block;

            width:
                105px;

            height:
                25px;

            object-fit:
                contain;
        }


        .barcode-number {

            color:
                #6b7280;

            font-family:
                monospace;

            font-size:
                7px;

            letter-spacing:
                1px;

            overflow-wrap:
                anywhere;
        }


        /* =====================================================
           CARD FOOTER
        ===================================================== */

        .card-footer {

            position:
                absolute;

            bottom:
                7px;

            right:
                15px;

            color:
                #cbd5e1;

            font-size:
                6px;

            letter-spacing:
                1px;

            text-transform:
                uppercase;
        }


        /* =====================================================
           PAGE CONTENT
        ===================================================== */

        .page-content {

            width:
                100%;

            max-width:
                1400px;

            margin:
                0 auto;

            padding:
                10px
                25px
                35px;

            overflow-x:
                hidden;

            animation:
                contentAppear
                0.7s
                0.25s
                both;
        }


        /* =====================================================
           ANIMATIONS
        ===================================================== */

        @keyframes logoEntrance {

            from {

                opacity: 0;

                transform:
                    translateX(-40px)
                    scale(0.85);
            }

            to {

                opacity: 1;

                transform:
                    translateX(0)
                    scale(1);
            }
        }


        @keyframes logoGlow {

            0%,
            100% {

                transform:
                    scale(0.9);

                opacity:
                    0.6;
            }

            50% {

                transform:
                    scale(1.15);

                opacity:
                    1;
            }
        }


        @keyframes logoLine {

            from {

                width:
                    0;

                opacity:
                    0;
            }

            to {

                width:
                    55px;

                opacity:
                    1;
            }
        }


        @keyframes cardEntrance {

            from {

                opacity:
                    0;

                transform:
                    translateX(45px)
                    translateY(-15px)
                    scale(0.9);
            }

            to {

                opacity:
                    1;

                transform:
                    translateX(0)
                    translateY(0)
                    scale(1);
            }
        }


        @keyframes imageEntrance {

            from {

                opacity:
                    0;

                transform:
                    scale(0.5)
                    rotate(-15deg);
            }

            to {

                opacity:
                    1;

                transform:
                    scale(1)
                    rotate(0);
            }
        }


        @keyframes fadeUp {

            from {

                opacity:
                    0;

                transform:
                    translateY(10px);
            }

            to {

                opacity:
                    1;

                transform:
                    translateY(0);
            }
        }


        @keyframes ringPulse {

            0%,
            100% {

                transform:
                    scale(1);
            }

            50% {

                transform:
                    scale(1.04);
            }
        }


        @keyframes statusPulse {

            0% {

                box-shadow:
                    0 0 0 0
                    rgba(34,197,94,0.55);
            }

            70% {

                box-shadow:
                    0 0 0 7px
                    rgba(34,197,94,0);
            }

            100% {

                box-shadow:
                    0 0 0 0
                    rgba(34,197,94,0);
            }
        }


        @keyframes statusDot {

            0%,
            100% {

                opacity:
                    1;
            }

            50% {

                opacity:
                    0.35;
            }
        }


        @keyframes cardShine {

            0% {

                left:
                    -80%;
            }

            45%,
            100% {

                left:
                    150%;
            }
        }


        @keyframes decorationFloat {

            0%,
            100% {

                transform:
                    translate(0,0)
                    rotate(0deg);
            }

            50% {

                transform:
                    translate(-10px,10px)
                    rotate(15deg);
            }
        }


        @keyframes backgroundFloat {

            0%,
            100% {

                transform:
                    translate(0,0);
            }

            50% {

                transform:
                    translate(-30px,30px);
            }
        }


        @keyframes backgroundFloatReverse {

            0%,
            100% {

                transform:
                    translate(0,0);
            }

            50% {

                transform:
                    translate(30px,-20px);
            }
        }


        @keyframes contentAppear {

            from {

                opacity:
                    0;

                transform:
                    translateY(15px);
            }

            to {

                opacity:
                    1;

                transform:
                    translateY(0);
            }
        }


        /* =====================================================
           TABLET
        ===================================================== */

        @media (max-width: 800px) {

            .top-header {

                min-height:
                    115px;

                padding:
                    15px
                    18px;
            }


            .top-logo img {

                width:
                    115px;
            }


            .profile-card {

                width:
                    300px;

                min-height:
                    175px;

                padding:
                    17px;
            }


            .profile-image-ring {

                width:
                    80px;

                height:
                    80px;
            }


            .profile-image {

                width:
                    72px;

                height:
                    72px;
            }


            .profile-name {

                font-size:
                    18px;
            }


            .page-content {

                padding:
                    8px
                    18px
                    30px;
            }
        }


        /* =====================================================
           MOBILE
        ===================================================== */

        @media (max-width: 600px) {

            .top-header {

                min-height:
                    auto;

                padding:
                    12px;

                display:
                    flex;

                flex-direction:
                    column;

                align-items:
                    center;

                gap:
                    12px;
            }


            .top-logo {

                width:
                    100%;

                justify-content:
                    flex-start;

                padding:
                    0
                    5px;
            }


            .top-logo img {

                width:
                    105px;
            }


            .profile-wrapper {

                width:
                    100%;

                justify-content:
                    center;
            }


            .profile-card {

                width:
                    min(
                        390px,
                        100%
                    );

                min-height:
                    175px;
            }


            .page-content {

                padding:
                    8px
                    12px
                    25px;
            }
        }


        /* =====================================================
           SMALL MOBILE
        ===================================================== */

        @media (max-width: 390px) {

            .profile-card {

                gap:
                    12px;

                padding:
                    15px;

                border-radius:
                    18px;
            }


            .profile-image-ring {

                width:
                    72px;

                height:
                    72px;
            }


            .profile-image {

                width:
                    64px;

                height:
                    64px;
            }


            .profile-name {

                font-size:
                    16px;
            }


            .profile-line {

                font-size:
                    9px;
            }


            .profile-company {

                font-size:
                    8px;
            }


            .job-id {

                font-size:
                    11px;
            }


            .barcode {

                width:
                    90px;

                height:
                    22px;
            }
        }


        /* =====================================================
           REDUCED MOTION
        ===================================================== */

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {

                animation-duration:
                    0.01ms !important;

                animation-iteration-count:
                    1 !important;

                transition-duration:
                    0.01ms !important;
            }
        }

    </style>

</head>


<body>


    {{-- =====================================================
         TOP HEADER
    ===================================================== --}}

    <header class="top-header">


        {{-- =================================================
             LOGO LEFT
        ================================================= --}}

        <div class="top-logo">

            <img
                src="{{ asset('pictures/logo.png') }}"
                alt="Company Logo"
            >

            <div class="logo-line"></div>

        </div>


        {{-- =================================================
             PROFILE CARD RIGHT
        ================================================= --}}

        @auth

            <div class="profile-wrapper">

                <div class="profile-card">


                    {{-- DECORATION --}}

                    <div class="card-decoration"></div>


                    {{-- =================================================
                         PROFILE IMAGE
                    ================================================= --}}

                    <div class="profile-image-container">

                        <div class="profile-image-ring">

                            @if(auth()->user()->profile_picture)

                                <img
                                    src="{{ asset('storage/' . auth()->user()->profile_picture) }}"
                                    alt="Profile"
                                    class="profile-image"
                                >

                            @else

                                <img
                                    src="{{ asset('images/default-profile.png') }}"
                                    alt="Profile"
                                    class="profile-image"
                                >

                            @endif

                        </div>


                        <span class="profile-status"></span>

                    </div>


                    {{-- =================================================
                         PROFILE INFORMATION
                    ================================================= --}}

                    <div class="profile-info">


                        {{-- NAME --}}

                        <h2 class="profile-name">

                            {{ auth()->user()->name }}

                        </h2>


                        {{-- ROLE --}}

                        <div class="profile-role">

                            <span>●</span>

                            {{ ucfirst(auth()->user()->role ?? 'User') }}

                        </div>


                        {{-- DETAILS --}}

                        <div class="profile-details">


                            {{-- MATRICULE --}}

                            <div class="profile-line">

                                <strong>
                                    ID:
                                </strong>

                                <span>
                                    {{ auth()->user()->matricule }}
                                </span>

                            </div>


                            {{-- EMAIL --}}

                            <div class="profile-line">

                                <strong>
                                    Email:
                                </strong>

                                <span>
                                    {{ auth()->user()->email }}
                                </span>

                            </div>


                        </div>


                        {{-- COMPANY --}}

                        <div class="profile-company">

                            VERSIGENT / Morocco III

                            <div class="profile-company-sub">

                                TAP APPLICATION

                            </div>

                        </div>


                        {{-- =================================================
                             JOB ID
                        ================================================= --}}

                        @if(isset($ppmRecord) && $ppmRecord->job_id)

                            <div class="job-section">


                                <div class="job-title">

                                    JOB ID

                                </div>


                                <div class="job-id">

                                    {{ $ppmRecord->job_id }}

                                </div>


                                <div class="barcode-wrapper">

                                    <img
                                        class="barcode"

                                        src="data:image/png;base64,{{
                                            DNS1D::getBarcodePNG(
                                                $ppmRecord->job_id,
                                                'C128',
                                                2,
                                                40
                                            )
                                        }}"

                                        alt="Job ID Barcode"
                                    >


                                    <div class="barcode-number">

                                        {{ $ppmRecord->job_id }}

                                    </div>

                                </div>

                            </div>

                        @endif

                    </div>


                    {{-- FOOTER --}}

                    <div class="card-footer">

                        TAPAPP • VERSIGENT

                    </div>

                </div>

            </div>

        @endauth

    </header>


    {{-- =====================================================
         MAIN PAGE CONTENT
    ===================================================== --}}

    <main class="page-content">

        @yield('content')

    </main>


</body>

</html>

