<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                    PPM Asset Details
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Asset information and PPM record
                </p>
            </div>

            <a
                href="{{ route('admin.dashboard') }}"
                class="
                    inline-flex
                    items-center
                    gap-2
                    px-5
                    py-3
                    rounded-xl
                    bg-blue-600
                    text-white
                    font-semibold
                    transition-all
                    duration-300
                    hover:bg-blue-700
                    hover:-translate-x-1
                    hover:shadow-lg
                "
            >

                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>

                Back

            </a>

        </div>

    </x-slot>


    <div class="py-10">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- ASSET HEADER -->

            <div
                class="
                    bg-gradient-to-r
                    from-blue-600
                    to-blue-800
                    rounded-3xl
                    p-8
                    mb-8
                    text-white
                    shadow-xl
                    animate-fade-down
                "
            >

                <div class="flex flex-col md:flex-row md:items-center gap-6">

                    <div
                        class="
                            w-20
                            h-20
                            rounded-2xl
                            bg-white/10
                            border
                            border-white/20
                            flex
                            items-center
                            justify-center
                            text-3xl
                            backdrop-blur
                        "
                    >
                        🔧
                    </div>


                    <div>

                        <p class="text-blue-100 text-sm font-semibold uppercase">
                            Asset ID
                        </p>

                        <h1 class="text-4xl font-extrabold mt-1">
                            {{ $ppmRecord->asset_id }}
                        </h1>

                        <p class="text-blue-100 mt-2">
                            PPM ID:
                            <span class="font-semibold text-white">
                                {{ $ppmRecord->ppm_id ?? 'N/A' }}
                            </span>
                        </p>

                    </div>

                </div>

            </div>


            <!-- DETAILS -->

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">


                <!-- ASSET ID -->

                <div class="detail-card">

                    <div class="icon-box">
                        🔧
                    </div>

                    <div>

                        <p class="detail-label">
                            Asset ID
                        </p>

                        <p class="detail-value">
                            {{ $ppmRecord->asset_id ?? '—' }}
                        </p>

                    </div>

                </div>


                <!-- PPM ID -->

                <div class="detail-card">

                    <div class="icon-box">
                        📋
                    </div>

                    <div>

                        <p class="detail-label">
                            PPM ID
                        </p>

                        <p class="detail-value">
                            {{ $ppmRecord->ppm_id ?? '—' }}
                        </p>

                    </div>

                </div>


                <!-- WEEK DUE -->

                <div class="detail-card">

                    <div class="icon-box">
                        📅
                    </div>

                    <div>

                        <p class="detail-label">
                            Week Due
                        </p>

                        <p class="detail-value">
                            {{ $ppmRecord->week_due ?? '—' }}
                        </p>

                    </div>

                </div>


                <!-- PLANT -->

                <div class="detail-card">

                    <div class="icon-box">
                        🏭
                    </div>

                    <div>

                        <p class="detail-label">
                            Plant
                        </p>

                        <p class="detail-value">
                            {{ $ppmRecord->plant ?? '—' }}
                        </p>

                    </div>

                </div>


                <!-- MODEL -->

                <div class="detail-card">

                    <div class="icon-box">
                        ⚙️
                    </div>

                    <div>

                        <p class="detail-label">
                            Model
                        </p>

                        <p class="detail-value">
                            {{ $ppmRecord->model ?? '—' }}
                        </p>

                    </div>

                </div>


                <!-- STATUS -->

                <div class="detail-card">

                    <div class="icon-box">
                        ✓
                    </div>

                    <div>

                        <p class="detail-label">
                            Status
                        </p>

                        <span
                            class="
                                inline-flex
                                mt-1
                                px-4
                                py-2
                                rounded-full
                                bg-green-100
                                text-green-700
                                font-bold
                                text-sm
                            "
                        >

                            {{ $ppmRecord->status ?? 'Active' }}

                        </span>

                    </div>

                </div>


            </div>


            <!-- ALL DATABASE INFORMATION -->

            <div
                class="
                    mt-8
                    bg-white
                    rounded-3xl
                    border
                    border-blue-100
                    shadow-xl
                    overflow-hidden
                    animate-fade-up
                "
            >

                <div
                    class="
                        px-7
                        py-5
                        bg-gray-50
                        border-b
                        border-gray-100
                    "
                >

                    <h2 class="text-xl font-bold text-gray-800">
                        Record Information
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Complete information stored for this asset
                    </p>

                </div>


                <div class="p-7">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        @foreach($ppmRecord->getAttributes() as $column => $value)

                            <div
                                class="
                                    p-5
                                    rounded-2xl
                                    bg-gray-50
                                    border
                                    border-gray-100
                                    transition-all
                                    duration-300
                                    hover:bg-blue-50
                                    hover:border-blue-200
                                    hover:-translate-y-1
                                "
                            >

                                <p
                                    class="
                                        text-xs
                                        font-bold
                                        uppercase
                                        tracking-wider
                                        text-gray-400
                                    "
                                >
                                    {{ str_replace('_', ' ', $column) }}
                                </p>

                                <p
                                    class="
                                        mt-2
                                        text-gray-800
                                        font-semibold
                                        break-words
                                    "
                                >
                                    {{ $value ?? '—' }}
                                </p>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>

        </div>

    </div>


    <style>

        .detail-card {

            display: flex;

            align-items: center;

            gap: 18px;

            padding: 24px;

            background: white;

            border: 1px solid #dbeafe;

            border-radius: 22px;

            box-shadow:
                0 8px 25px rgba(30, 64, 175, .06);

            transition:
                transform .3s ease,
                box-shadow .3s ease,
                border-color .3s ease;

            animation: cardAnimation .6s ease both;

        }


        .detail-card:hover {

            transform: translateY(-6px);

            border-color: #93c5fd;

            box-shadow:
                0 15px 35px rgba(37, 99, 235, .12);

        }


        .icon-box {

            width: 52px;

            height: 52px;

            flex-shrink: 0;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 16px;

            background: #eff6ff;

            font-size: 24px;

            transition:
                transform .3s ease;

        }


        .detail-card:hover .icon-box {

            transform:
                rotate(-5deg)
                scale(1.1);

        }


        .detail-label {

            font-size: 12px;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: .06em;

            color: #94a3b8;

        }


        .detail-value {

            margin-top: 4px;

            font-size: 17px;

            font-weight: 700;

            color: #1e3a8a;

            word-break: break-word;

        }


        @keyframes cardAnimation {

            from {

                opacity: 0;

                transform: translateY(20px);

            }

            to {

                opacity: 1;

                transform: translateY(0);

            }

        }


        @keyframes fadeDown {

            from {

                opacity: 0;

                transform: translateY(-20px);

            }

            to {

                opacity: 1;

                transform: translateY(0);

            }

        }


        @keyframes fadeUp {

            from {

                opacity: 0;

                transform: translateY(20px);

            }

            to {

                opacity: 1;

                transform: translateY(0);

            }

        }


        .animate-fade-down {

            animation:
                fadeDown
                .6s
                ease-out;

        }


        .animate-fade-up {

            animation:
                fadeUp
                .7s
                ease-out;

        }

    </style>

</x-app-layout>
