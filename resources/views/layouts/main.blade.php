<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'TapApp')
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <style>

        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;

            min-height: 100vh;

            background: #f8fafc;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            color: #1f2937;
        }


        /* =========================================
           COMPANY LOGO
        ========================================= */

        .top-logo {

            position: relative;

            padding: 20px 30px;

            animation: logoFade 0.8s ease;

            width: 100%;
        }


        .top-logo img {

            width: 125px;

            height: auto;

            display: block;

            transition:
                transform 0.3s ease;
        }


        .top-logo img:hover {

            transform: scale(1.05);
        }


        @keyframes logoFade {

            from {

                opacity: 0;

                transform:
                    translateY(-15px);
            }

            to {

                opacity: 1;

                transform:
                    translateY(0);
            }
        }


        /* =========================================
           PROFILE WRAPPER
        ========================================= */

        .profile-wrapper {

            width: 100%;

            display: flex;

            justify-content: center;

            padding:
                5px
                20px
                30px;
        }


        /* =========================================
           PROFILE CARD
        ========================================= */

        .profile-card {

            position: relative;

            width: min(680px, 100%);

            min-height: 270px;

            display: flex;

            align-items: center;

            gap: 25px;

            padding:
                28px
                30px;

            background:
                linear-gradient(
                    135deg,
                    #fff7ed 0%,
                    #ffffff 48%,
                    #fffaf5 100%
                );

            border-radius: 20px;

            border:
                1px solid #fed7aa;

            box-shadow:
                0 15px 35px
                rgba(234, 88, 12, 0.12),

                0 5px 12px
                rgba(0, 0, 0, 0.06);

            overflow: hidden;

            animation:
                cardAppear 0.8s ease;

            transition:
                transform 0.3s ease,
                box-shadow 0.3s ease;
        }


        .profile-card:hover {

            transform:
                translateY(-5px);

            box-shadow:
                0 22px 45px
                rgba(234, 88, 12, 0.20),

                0 8px 18px
                rgba(0, 0, 0, 0.08);
        }


        /* =========================================
           ORANGE DECORATION
        ========================================= */

        .orange-decoration {

            position: absolute;

            top: -80px;

            right: -80px;

            width: 230px;

            height: 230px;

            background: #f97316;

            border-radius: 50%;

            opacity: 0.08;
        }


        .orange-decoration::after {

            content: "";

            position: absolute;

            width: 150px;

            height: 150px;

            top: 80px;

            left: -50px;

            background: #ea580c;

            border-radius: 50%;

            opacity: 0.5;
        }


        /* =========================================
           PROFILE IMAGE
        ========================================= */

        .profile-image-container {

            position: relative;

            flex-shrink: 0;

            z-index: 2;
        }


        .profile-image {

            width: 110px;

            height: 110px;

            object-fit: cover;

            border-radius: 50%;

            border:
                5px solid white;

            box-shadow:
                0 8px 22px
                rgba(234, 88, 12, 0.22);

            transition:
                transform 0.3s ease,
                box-shadow 0.3s ease;
        }


        .profile-image:hover {

            transform:
                scale(1.06);

            box-shadow:
                0 12px 30px
                rgba(234, 88, 12, 0.30);
        }


        /* =========================================
           STATUS
        ========================================= */

        .profile-status {

            position: absolute;

            width: 17px;

            height: 17px;

            right: 5px;

            bottom: 8px;

            background: #22c55e;

            border:
                3px solid white;

            border-radius: 50%;

            animation:
                statusPulse 2s infinite;
        }


        /* =========================================
           PROFILE INFORMATION
        ========================================= */

        .profile-info {

            position: relative;

            z-index: 2;

            flex: 1;
        }


        .profile-name {

            margin:
                0 0 8px;

            font-size: 25px;

            font-weight: 700;

            color: #1f2937;
        }


        /* =========================================
           PROFILE LINES
        ========================================= */

        .profile-line {

            display: flex;

            gap: 7px;

            margin:
                6px 0;

            font-size: 13px;

            color: #64748b;

            min-width: 0;

            overflow-wrap: anywhere;
        }


        .profile-line strong {

            color: #374151;
        }


        /* =========================================
           COMPANY
        ========================================= */

        .profile-company {

            margin-top: 10px;

            color: #ea580c;

            font-size: 14px;

            font-weight: 700;

            letter-spacing: 0.5px;
        }


        /* =========================================
           JOB ID
        ========================================= */

        .job-section {

            margin-top: 13px;

            padding-top: 11px;

            border-top:
                1px solid #fed7aa;
        }


        .job-title {

            color: #9a3412;

            font-size: 9px;

            font-weight: 700;

            letter-spacing: 1.5px;

            margin-bottom: 2px;
        }


        .job-id {

            color: #ea580c;

            font-size: 19px;

            font-weight: 800;

            letter-spacing: 1px;

            margin-bottom: 4px;
        }


        /* =========================================
           BARCODE
        ========================================= */

        .barcode {

            display: block;

            width: 155px;

            height: 38px;

            object-fit: contain;

            object-position: left;

            margin: 0;
        }


        .barcode-number {

            margin-top: 2px;

            color: #6b7280;

            font-family: monospace;

            font-size: 10px;

            letter-spacing: 2px;
        }


        /* =========================================
           PAGE CONTENT
        ========================================= */

        .page-content {

            width: 100%;

            max-width: 1200px;

            margin: auto;

            padding:
                20px;

            overflow-x: hidden;
        }


        /* =========================================
           ANIMATIONS
        ========================================= */

        @keyframes cardAppear {

            from {

                opacity: 0;

                transform:
                    translateY(25px)
                    scale(0.97);
            }

            to {

                opacity: 1;

                transform:
                    translateY(0)
                    scale(1);
            }
        }


        @keyframes statusPulse {

            0% {

                box-shadow:
                    0 0 0 0
                    rgba(34, 197, 94, 0.5);
            }

            70% {

                box-shadow:
                    0 0 0 8px
                    rgba(34, 197, 94, 0);
            }

            100% {

                box-shadow:
                    0 0 0 0
                    rgba(34, 197, 94, 0);
            }
        }


        /* =========================================
           MOBILE
        ========================================= */

        @media (max-width: 700px) {

            body {
                overflow-x: hidden;
            }

            .top-logo {
                position: absolute;
                top: 0;
                left: 0;
                z-index: 5;
                width: auto;
                padding: 12px 15px;
            }


            .top-logo img {

                width: clamp(72px, 20vw, 100px);
            }

            .profile-wrapper {
                justify-content: flex-end;
                padding: 12px 14px 20px;
                margin-top: 74px;
            }


            .profile-card {

                width: min(100%, 420px);

                flex-direction: column;

                text-align: center;

                padding:
                    25px
                    20px;

                gap: 15px;
            }


            .profile-line {

                justify-content: center;
            }


            .profile-company {

                margin-top: 12px;
            }


            .job-section {

                text-align: center;

                width: 100%;
            }


            .barcode {

                margin:
                    0 auto;
            }


            .page-content {

                padding:
                    10px;
            }

            .profile-name {

                font-size: 22px;

                overflow-wrap: anywhere;
            }

            .profile-line {

                flex-wrap: wrap;

                gap: 3px 7px;
            }

            .profile-company {

                overflow-wrap: anywhere;
            }
        }


        @media (max-width: 380px) {

            .top-logo {

                padding: 10px 12px;
            }

            .profile-wrapper {

                padding: 8px 10px 18px;
                margin-top: 64px;
            }

            .profile-card {

                padding:
                    20px
                    12px;
            }

            .profile-image {

                width: 90px;

                height: 90px;
            }

            .job-id {

                font-size: 17px;

                overflow-wrap: anywhere;
            }
        }

    </style>

</head>


<body>


    {{-- =========================================
         COMPANY LOGO
    ========================================= --}}

    <div class="top-logo">

        <img
            src="{{ asset('pictures/logo.png') }}"
            alt="Company Logo"
        >

    </div>


    {{-- =========================================
         PROFILE CARD
    ========================================= --}}

    @auth

        <div class="profile-wrapper">

            <div class="profile-card">


                {{-- ORANGE DECORATION --}}

                <div class="orange-decoration"></div>


                {{-- =================================
                     PROFILE IMAGE
                ================================= --}}

                <div class="profile-image-container">

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


                    <span class="profile-status"></span>

                </div>


                {{-- =================================
                     USER INFORMATION
                ================================= --}}

                <div class="profile-info">


                    {{-- NAME --}}

                    <h2 class="profile-name">

                        {{ auth()->user()->name }}

                    </h2>


                    {{-- MATRICULE --}}

                    <div class="profile-line">

                        <strong>
                            Matricule:
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


                    {{-- =================================
                         COMPANY
                    ================================= --}}

                    <div class="profile-company">

                        VERSIGENT / Morocco III

                    </div>


                    {{-- =================================
                         JOB ID + BARCODE
                    ================================= --}}

                    @if(isset($ppmRecord) && $ppmRecord->job_id)

                        <div class="job-section">


                            {{-- JOB LABEL --}}

                            <div class="job-title">

                                JOB ID

                            </div>


                            {{-- JOB NUMBER --}}

                            <div class="job-id">

                                {{ $ppmRecord->job_id }}

                            </div>


                            {{-- BARCODE --}}

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


                            {{-- BARCODE NUMBER --}}

                            <div class="barcode-number">

                                {{ $ppmRecord->job_id }}

                            </div>


                        </div>

                    @endif


                </div>

            </div>

        </div>

    @endauth


    {{-- =========================================
         CHILD PAGE CONTENT
    ========================================= --}}

    <main class="page-content">

        @yield('content')

    </main>


</body>

</html>
