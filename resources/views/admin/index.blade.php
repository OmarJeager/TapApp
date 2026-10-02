<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200">
                {{ __('PPM Records') }}
            </h2>
        </div>
    </x-slot>

    @include('layouts.main')

    <div class="py-10">

        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8">

            {{-- =========================================================
                PAGE HEADER
            ========================================================== --}}
            <div class="mb-8 animate-fade-down">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                    <div>
                        <div class="flex items-center gap-3">

                            <div class="page-icon">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v10a2 2 0 01-2 2z"/>
                                </svg>
                            </div>

                            <div>
                                <h1 class="text-3xl font-extrabold text-gray-800">
                                    PPM Records
                                </h1>

                                <p class="mt-1 text-gray-500">
                                    Search, import and manage your PPM assets
                                </p>
                            </div>

                        </div>
                    </div>


                    {{-- TOTAL --}}
                    <div class="stats-card">

                        <div class="stats-icon">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v10a2 2 0 01-2 2z"/>
                            </svg>
                        </div>

                        <div>
                            <span class="block text-xs uppercase tracking-wider text-gray-400 font-bold">
                                Total Assets
                            </span>

                            <span class="block text-2xl font-extrabold text-blue-600">
                                {{ number_format($ppmRecords->total()) }}
                            </span>
                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================================
                SEARCH
            ========================================================== --}}
            <div class="relative max-w-3xl mb-8">

                <div class="relative group">

                    <div class="absolute inset-y-0 left-0 flex items-center pl-5 pointer-events-none">

                        <svg
                            class="w-6 h-6 text-blue-500 transition-transform duration-300 group-focus-within:scale-110"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"/>

                        </svg>

                    </div>

                    <input
                        type="text"
                        id="jobSearch"
                        autocomplete="off"
                        placeholder="Search by Job ID..."
                        class="
                            w-full
                            pl-14
                            pr-14
                            py-5
                            rounded-2xl
                            border
                            border-blue-100
                            bg-white
                            text-gray-800
                            placeholder-gray-400
                            shadow-sm
                            outline-none
                            transition-all
                            duration-300
                            hover:border-blue-300
                            hover:shadow-md
                            focus:border-blue-500
                            focus:ring-4
                            focus:ring-blue-100
                            focus:shadow-xl
                        "
                    />

                    <button
                        type="button"
                        id="clearSearch"
                        class="
                            hidden
                            absolute
                            right-5
                            top-1/2
                            -translate-y-1/2
                            w-8
                            h-8
                            rounded-full
                            flex
                            items-center
                            justify-center
                            text-gray-400
                            hover:text-blue-600
                            hover:bg-blue-50
                            transition-all
                            duration-200
                        ">

                        ✕

                    </button>

                </div>


                <div
                    id="searchResults"
                    class="
                        hidden
                        absolute
                        z-50
                        left-0
                        right-0
                        mt-3
                        bg-white
                        border
                        border-blue-100
                        rounded-2xl
                        shadow-2xl
                        overflow-hidden
                        animate-search
                    ">
                </div>

            </div>


            {{-- =========================================================
                IMPORT CARD
            ========================================================== --}}
            <form
                id="importForm"
                action="{{ route('ppm-records.import') }}"
                method="POST"
                enctype="multipart/form-data"
                class="import-card mb-10">

                @csrf

                {{-- Header --}}
                <div class="import-header">

                    <div class="flex items-center gap-4">

                        <div class="import-main-icon">

                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 16V4m0 0L8 8m4-4l4 4M5 20h14a2 2 0 002-2v-3a2 2 0 00-2-2h-1m-12 0H5a2 2 0 00-2 2v3a2 2 0 002 2h14"/>
                            </svg>

                        </div>

                        <div>
                            <h3 class="text-xl font-extrabold text-gray-800">
                                Import PPM Records
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Upload your maintenance records file
                            </p>
                        </div>

                    </div>


                    <div class="format-badges">

                        <span class="format-badge excel">
                            XLSX
                        </span>

                        <span class="format-badge excel">
                            XLS
                        </span>

                        <span class="format-badge csv">
                            CSV
                        </span>

                    </div>

                </div>


                {{-- Drop zone --}}
                <label
                    for="ppm-file"
                    id="dropZone"
                    class="drop-zone">

                    <input
                        id="ppm-file"
                        type="file"
                        name="file"
                        accept=".xlsx,.xls,.csv"
                        required
                        class="hidden"
                    />

                    <div class="upload-icon-wrapper">

                        <svg
                            id="uploadIcon"
                            class="w-9 h-9 text-blue-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 0115.9 6H16a5 5 0 011 9.9M12 12v8m0-8l-3 3m3-3l3 3"/>

                        </svg>

                    </div>


                    <div class="mt-4">

                        <p class="text-lg font-bold text-gray-700">
                            Drop your file here
                        </p>

                        <p class="text-sm text-gray-400 mt-1">
                            or
                            <span class="text-blue-600 font-bold">
                                browse from your computer
                            </span>
                        </p>

                    </div>


                    <div class="mt-4 flex justify-center gap-2 flex-wrap">

                        <span class="small-format">
                            Excel
                        </span>

                        <span class="small-format">
                            CSV
                        </span>

                        <span class="small-format">
                            Max 10 MB
                        </span>

                    </div>

                </label>


                {{-- Selected file --}}
                <div
                    id="selectedFile"
                    class="selected-file hidden">

                    <div class="flex items-center gap-4 min-w-0">

                        <div class="file-icon">

                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"/>
                            </svg>

                        </div>

                        <div class="min-w-0">

                            <p
                                id="fileName"
                                class="font-bold text-gray-700 truncate">
                            </p>

                            <p
                                id="fileSize"
                                class="text-xs text-gray-400 mt-1">
                            </p>

                        </div>

                    </div>


                    <button
                        type="button"
                        id="removeFile"
                        class="remove-file">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"/>
                        </svg>

                    </button>

                </div>


                {{-- Upload button --}}
                <div class="import-footer">

                    <div class="flex items-center gap-2 text-sm text-gray-400">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>

                        <span>
                            Your data will be imported securely
                        </span>

                    </div>


                    <button
                        type="submit"
                        id="uploadButton"
                        class="
                            upload-button
                            disabled:opacity-60
                            disabled:cursor-not-allowed
                        "
                        disabled>

                        <span id="buttonNormal">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 16V4m0 0l-4 4m4-4l4 4M5 20h14"/>
                            </svg>

                            Upload & Import

                        </span>


                        <span
                            id="buttonLoading"
                            class="hidden">

                            <span class="loading-spinner"></span>

                            Importing...

                        </span>

                    </button>

                </div>

            </form>


            {{-- =========================================================
                TABLE
            ========================================================== --}}
            <div class="table-container">

                {{-- Table top --}}
                <div class="table-top">

                    <div class="flex items-center gap-4">

                        <div class="table-icon">

                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 10h18M3 14h18M7 3v18M17 3v18"/>
                            </svg>

                        </div>

                        <div>

                            <h3 class="text-xl font-extrabold text-white">
                                PPM Assets
                            </h3>

                            <p class="text-blue-100 text-sm mt-1">
                                All registered maintenance assets
                            </p>

                        </div>

                    </div>


                    <div class="records-counter">

                        <span class="counter-dot"></span>

                        {{ number_format($ppmRecords->total()) }}

                        <span class="font-normal opacity-80">
                            records
                        </span>

                    </div>

                </div>


                {{-- Table --}}
                <div class="overflow-x-auto table-scroll">

                    <table class="ppm-table">

                        <thead>

                            <tr>

                                <th>
                                    <div class="th-content">
                                        <span class="th-icon">◈</span>
                                        Asset ID
                                    </div>
                                </th>

                                <th>
                                    <div class="th-content">
                                        <span class="th-icon">#</span>
                                        PPM ID
                                    </div>
                                </th>

                                <th>
                                    <div class="th-content">
                                        <span class="th-icon">◷</span>
                                        Week Due
                                    </div>
                                </th>

                                <th>
                                    <div class="th-content">
                                        <span class="th-icon">⌂</span>
                                        Plant
                                    </div>
                                </th>

                                <th>
                                    <div class="th-content">
                                        <span class="th-icon">▣</span>
                                        Model
                                    </div>
                                </th>

                                <th>
                                    <div class="th-content">
                                        <span class="th-icon">●</span>
                                        Status
                                    </div>
                                </th>

                                <th>
                                    <div class="th-content">
                                        <span class="th-icon">→</span>
                                        Actions
                                    </div>
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($ppmRecords as $record)

                                <tr class="ppm-row">

                                    {{-- ASSET --}}
                                    <td>

                                        <div class="asset-cell">

                                            <div class="asset-avatar">
                                                {{ strtoupper(substr($record->asset_id ?? 'A', 0, 1)) }}
                                            </div>

                                            <div>

                                                <span class="asset-id">
                                                    {{ $record->asset_id ?? '—' }}
                                                </span>

                                                <span class="asset-label">
                                                    Asset
                                                </span>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- PPM --}}
                                    <td>

                                        <span class="ppm-id-badge">
                                            {{ $record->ppm_id ?? '—' }}
                                        </span>

                                    </td>


                                    {{-- WEEK --}}
                                    <td>

                                        <div class="week-cell">

                                            <span class="week-icon">
                                                📅
                                            </span>

                                            <span>
                                                {{ $record->week_due ?? '—' }}
                                            </span>

                                        </div>

                                    </td>


                                    {{-- PLANT --}}
                                    <td>

                                        <span class="data-value">
                                            {{ $record->plant_group ?? '—' }}
                                        </span>

                                    </td>


                                    {{-- MODEL --}}
                                    <td>

                                        <span class="model-badge">
                                            {{ $record->model ?? '—' }}
                                        </span>

                                    </td>


                                    {{-- STATUS --}}
                                    <td>

                                        @php
                                            $status = $record->status ?? 'Active';

                                            $statusClass = match(strtolower($status)) {
                                                'active', 'completed', 'ok' => 'status-success',
                                                'pending', 'waiting' => 'status-warning',
                                                'inactive', 'cancelled', 'failed' => 'status-danger',
                                                default => 'status-neutral',
                                            };
                                        @endphp

                                        <span class="status-badge {{ $statusClass }}">

                                            <span class="status-dot"></span>

                                            {{ $status }}

                                        </span>

                                    </td>


                                    {{-- ACTION --}}
                                    <td>

                                        <a
                                            href="{{ route('ppm-records.show', $record->id) }}"
                                            class="view-button">

                                            <span>
                                                View Details
                                            </span>

                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 5l7 7-7 7"/>

                                            </svg>

                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7">

                                        <div class="empty-state">

                                            <div class="empty-icon">

                                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.7"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4"/>
                                                </svg>

                                            </div>

                                            <h3>
                                                No PPM records found
                                            </h3>

                                            <p>
                                                Upload an Excel or CSV file to add maintenance records.
                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if($ppmRecords->hasPages())

                    <div class="pagination-container">

                        {{ $ppmRecords->links() }}

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
        IMPORT LOADING OVERLAY
    ========================================================== --}}
    <div
        id="importLoading"
        class="loading-overlay hidden">

        <div class="loading-card">

            <div class="loading-animation">

                <div class="loading-ring"></div>

                <div class="loading-upload-icon">

                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 16V4m0 0l-4 4m4-4l4 4M5 20h14"/>
                    </svg>

                </div>

            </div>


            <h3>
                Importing PPM Records
            </h3>

            <p id="loadingFileName">
                Please wait while your file is being processed...
            </p>


            <div class="progress-container">

                <div class="progress-bar">

                    <div class="progress-animation"></div>

                </div>

            </div>


            <div class="loading-status">

                <span class="loading-dot"></span>

                Processing your data...

            </div>

        </div>

    </div>


    {{-- =========================================================
        STYLES
    ========================================================== --}}
    <style>

        * {
            box-sizing: border-box;
        }


        /* =========================================
           PAGE ICON
        ========================================= */

        .page-icon {

            width: 56px;
            height: 56px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 18px;

            color: #2563eb;

            background: linear-gradient(
                135deg,
                #eff6ff,
                #dbeafe
            );

            border: 1px solid #bfdbfe;

            box-shadow:
                0 8px 25px rgba(37,99,235,.10);

        }


        /* =========================================
           STATS
        ========================================= */

        .stats-card {

            display: flex;
            align-items: center;
            gap: 14px;

            padding: 14px 20px;

            border-radius: 20px;

            background: white;

            border: 1px solid #dbeafe;

            box-shadow:
                0 10px 30px rgba(15,23,42,.06);

            transition:
                transform .3s ease,
                box-shadow .3s ease;

        }

        .stats-card:hover {

            transform: translateY(-3px);

            box-shadow:
                0 15px 35px rgba(37,99,235,.12);

        }


        .stats-icon {

            width: 45px;
            height: 45px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 14px;

            color: #2563eb;

            background: #eff6ff;

        }


        /* =========================================
           IMPORT CARD
        ========================================= */

        .import-card {

            position: relative;

            padding: 28px;

            border-radius: 28px;

            background:
                linear-gradient(
                    145deg,
                    #ffffff,
                    #f8fbff
                );

            border: 1px solid #dbeafe;

            box-shadow:
                0 20px 50px rgba(15,23,42,.07);

            overflow: hidden;

        }


        .import-card::before {

            content: "";

            position: absolute;

            width: 240px;
            height: 240px;

            top: -130px;
            right: -80px;

            border-radius: 50%;

            background: #dbeafe;

            opacity: .35;

            filter: blur(2px);

            pointer-events: none;

        }


        .import-header {

            position: relative;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 24px;

        }


        .import-main-icon {

            width: 55px;
            height: 55px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 17px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #1d4ed8
                );

            box-shadow:
                0 10px 25px rgba(37,99,235,.25);

            animation: floatingIcon 3s ease-in-out infinite;

        }


        .format-badges {

            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }


        .format-badge {

            padding: 7px 11px;

            border-radius: 10px;

            font-size: 10px;

            font-weight: 900;

            letter-spacing: .06em;

        }


        .format-badge.excel {

            color: #15803d;
            background: #dcfce7;
            border: 1px solid #bbf7d0;

        }


        .format-badge.csv {

            color: #1d4ed8;
            background: #dbeafe;
            border: 1px solid #bfdbfe;

        }


        /* =========================================
           DROP ZONE
        ========================================= */

        .drop-zone {

            position: relative;

            display: flex;

            flex-direction: column;

            align-items: center;

            justify-content: center;

            min-height: 210px;

            padding: 30px;

            border-radius: 22px;

            border: 2px dashed #bfdbfe;

            background:
                linear-gradient(
                    145deg,
                    #f8fbff,
                    #eff6ff
                );

            cursor: pointer;

            transition:
                border-color .3s ease,
                background .3s ease,
                transform .3s ease,
                box-shadow .3s ease;

        }


        .drop-zone:hover {

            border-color: #60a5fa;

            background:
                linear-gradient(
                    145deg,
                    #eff6ff,
                    #dbeafe
                );

            transform: translateY(-2px);

            box-shadow:
                0 15px 35px rgba(37,99,235,.08);

        }


        .drop-zone.dragover {

            border-color: #2563eb;

            background: #dbeafe;

            transform: scale(1.01);

            box-shadow:
                0 20px 40px rgba(37,99,235,.14);

        }


        .upload-icon-wrapper {

            width: 72px;
            height: 72px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 22px;

            background: white;

            border: 1px solid #dbeafe;

            box-shadow:
                0 10px 25px rgba(37,99,235,.10);

            transition:
                transform .3s ease;

        }


        .drop-zone:hover .upload-icon-wrapper {

            transform: translateY(-6px) scale(1.05);

        }


        .small-format {

            padding: 5px 10px;

            border-radius: 8px;

            background: white;

            color: #64748b;

            border: 1px solid #e2e8f0;

            font-size: 11px;

            font-weight: 700;

        }


        /* =========================================
           SELECTED FILE
        ========================================= */

        .selected-file {

            margin-top: 16px;

            padding: 15px 18px;

            border-radius: 17px;

            background: #f0fdf4;

            border: 1px solid #bbf7d0;

            align-items: center;

            justify-content: space-between;

        }


        .file-icon {

            width: 45px;
            height: 45px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 13px;

            color: #15803d;

            background: #dcfce7;

        }


        .remove-file {

            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 11px;

            color: #94a3b8;

            transition: .2s ease;

        }


        .remove-file:hover {

            color: #dc2626;
            background: #fee2e2;

        }


        /* =========================================
           IMPORT FOOTER
        ========================================= */

        .import-footer {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            margin-top: 22px;

            padding-top: 22px;

            border-top: 1px solid #e5edf7;

        }


        .upload-button {

            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 10px;

            min-width: 190px;

            padding: 14px 22px;

            border: none;

            border-radius: 15px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #1d4ed8
                );

            font-size: 14px;
            font-weight: 800;

            cursor: pointer;

            box-shadow:
                0 10px 25px rgba(37,99,235,.22);

            transition:
                transform .25s ease,
                box-shadow .25s ease;

        }


        .upload-button:not(:disabled):hover {

            transform: translateY(-3px);

            box-shadow:
                0 15px 35px rgba(37,99,235,.30);

        }


        .upload-button:not(:disabled):active {

            transform: translateY(0);

        }


        #buttonNormal,
        #buttonLoading {

            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 9px;

        }


        .loading-spinner {

            width: 18px;
            height: 18px;

            border: 2px solid rgba(255,255,255,.35);

            border-top-color: white;

            border-radius: 50%;

            animation: spin .8s linear infinite;

        }


        /* =========================================
           TABLE
        ========================================= */

        .table-container {

            background: white;

            border-radius: 28px;

            border: 1px solid #dbeafe;

            box-shadow:
                0 20px 50px rgba(15,23,42,.08);

            overflow: hidden;

            animation: fadeDown .7s ease-out;

        }


        .table-top {

            padding: 22px 28px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #1e3a8a
                );

        }


        .table-icon {

            width: 48px;
            height: 48px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 15px;

            color: white;

            background: rgba(255,255,255,.14);

            border: 1px solid rgba(255,255,255,.20);

            backdrop-filter: blur(8px);

        }


        .records-counter {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding: 10px 15px;

            border-radius: 13px;

            color: white;

            background: rgba(255,255,255,.12);

            border: 1px solid rgba(255,255,255,.18);

            font-size: 13px;

            font-weight: 800;

        }


        .counter-dot {

            width: 8px;
            height: 8px;

            border-radius: 50%;

            background: #4ade80;

            box-shadow:
                0 0 0 4px rgba(74,222,128,.15);

            animation: pulseDot 2s infinite;

        }


        /* =========================================
           TABLE HEADER
        ========================================= */

        .ppm-table {

            width: 100%;

            min-width: 1200px;

            border-collapse: separate;

            border-spacing: 0;

        }


        .ppm-table thead th {

            padding: 17px 24px;

            color: #475569;

            background: #f8fafc;

            border-bottom: 1px solid #e2e8f0;

            font-size: 11px;

            font-weight: 900;

            text-transform: uppercase;

            letter-spacing: .07em;

            white-space: nowrap;

        }


        .th-content {

            display: flex;

            align-items: center;

            gap: 8px;

        }


        .th-icon {

            color: #3b82f6;

            font-size: 13px;

        }


        /* =========================================
           TABLE ROWS
        ========================================= */

        .ppm-table tbody tr {

            background: white;

            transition:
                background .25s ease,
                transform .25s ease,
                box-shadow .25s ease;

            animation: ppmRow .45s ease both;

        }


        .ppm-table tbody tr:nth-child(even) {

            background: #fbfdff;

        }


        .ppm-table tbody tr:hover {

            background: #eff6ff;

            transform: scale(1.001);

            box-shadow:
                inset 4px 0 0 #3b82f6;

        }


        .ppm-table tbody td {

            padding: 18px 24px;

            color: #334155;

            font-size: 14px;

            white-space: nowrap;

            vertical-align: middle;

            border-bottom: 1px solid #edf2f7;

        }


        .ppm-table tbody tr:last-child td {

            border-bottom: none;

        }


        /* =========================================
           ASSET CELL
        ========================================= */

        .asset-cell {

            display: flex;

            align-items: center;

            gap: 12px;

        }


        .asset-avatar {

            width: 42px;
            height: 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 13px;

            color: #2563eb;

            background: linear-gradient(
                135deg,
                #dbeafe,
                #eff6ff
            );

            border: 1px solid #bfdbfe;

            font-size: 14px;
            font-weight: 900;

            transition: transform .25s ease;

        }


        .ppm-row:hover .asset-avatar {

            transform: rotate(-5deg) scale(1.08);

        }


        .asset-id {

            display: block;

            color: #1d4ed8;

            font-weight: 900;

        }


        .asset-label {

            display: block;

            margin-top: 2px;

            color: #94a3b8;

            font-size: 10px;

            text-transform: uppercase;

            font-weight: 700;

            letter-spacing: .05em;

        }


        /* =========================================
           DATA BADGES
        ========================================= */

        .ppm-id-badge {

            display: inline-flex;

            padding: 7px 11px;

            border-radius: 10px;

            color: #475569;

            background: #f1f5f9;

            border: 1px solid #e2e8f0;

            font-weight: 800;

            font-size: 12px;

        }


        .week-cell {

            display: flex;

            align-items: center;

            gap: 8px;

            font-weight: 700;

        }


        .week-icon {

            font-size: 14px;

        }


        .data-value {

            color: #475569;

            font-weight: 600;

        }


        .model-badge {

            display: inline-flex;

            padding: 7px 12px;

            border-radius: 10px;

            color: #475569;

            background: #f8fafc;

            border: 1px solid #e2e8f0;

            font-size: 12px;

            font-weight: 700;

        }


        /* =========================================
           STATUS
        ========================================= */

        .status-badge {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding: 8px 12px;

            border-radius: 999px;

            font-size: 11px;

            font-weight: 900;

        }


        .status-dot {

            width: 7px;
            height: 7px;

            border-radius: 50%;

        }


        .status-success {

            color: #15803d;

            background: #dcfce7;

        }


        .status-success .status-dot {

            background: #22c55e;

            box-shadow: 0 0 0 3px rgba(34,197,94,.13);

        }


        .status-warning {

            color: #b45309;

            background: #fef3c7;

        }


        .status-warning .status-dot {

            background: #f59e0b;

        }


        .status-danger {

            color: #b91c1c;

            background: #fee2e2;

        }


        .status-danger .status-dot {

            background: #ef4444;

        }


        .status-neutral {

            color: #475569;

            background: #f1f5f9;

        }


        .status-neutral .status-dot {

            background: #94a3b8;

        }


        /* =========================================
           VIEW BUTTON
        ========================================= */

        .view-button {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding: 10px 15px;

            border-radius: 12px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #1d4ed8
                );

            font-size: 12px;

            font-weight: 800;

            box-shadow:
                0 6px 15px rgba(37,99,235,.16);

            transition:
                transform .25s ease,
                box-shadow .25s ease;

        }


        .view-button:hover {

            transform: translateY(-2px);

            box-shadow:
                0 10px 22px rgba(37,99,235,.25);

        }


        .view-button svg {

            transition: transform .25s ease;

        }


        .view-button:hover svg {

            transform: translateX(3px);

        }


        /* =========================================
           EMPTY
        ========================================= */

        .empty-state {

            padding: 80px 20px;

            display: flex;

            flex-direction: column;

            align-items: center;

            text-align: center;

        }


        .empty-icon {

            width: 78px;
            height: 78px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 24px;

            color: #60a5fa;

            background: #eff6ff;

            margin-bottom: 18px;

            animation: floatingIcon 3s ease-in-out infinite;

        }


        .empty-state h3 {

            color: #334155;

            font-size: 18px;

            font-weight: 900;

        }


        .empty-state p {

            color: #94a3b8;

            margin-top: 5px;

            font-size: 14px;

        }


        /* =========================================
           PAGINATION
        ========================================= */

        .pagination-container {

            padding: 20px 24px;

            border-top: 1px solid #edf2f7;

            background: #fbfdff;

        }


        /* =========================================
           SCROLLBAR
        ========================================= */

        .table-scroll {

            scrollbar-width: thin;

            scrollbar-color:
                #60a5fa
                #eff6ff;

        }


        .table-scroll::-webkit-scrollbar {

            height: 9px;

        }


        .table-scroll::-webkit-scrollbar-track {

            background: #eff6ff;

        }


        .table-scroll::-webkit-scrollbar-thumb {

            background: #60a5fa;

            border-radius: 20px;

        }


        .table-scroll::-webkit-scrollbar-thumb:hover {

            background: #2563eb;

        }


        /* =========================================
           LOADING OVERLAY
        ========================================= */

        .loading-overlay {

            position: fixed;

            inset: 0;

            z-index: 9999;

            display: flex;

            align-items: center;
            justify-content: center;

            padding: 20px;

            background:
                rgba(15,23,42,.55);

            backdrop-filter: blur(8px);

            animation: overlayIn .25s ease;

        }


        .loading-overlay.hidden {

            display: none;

        }


        .loading-card {

            width: min(440px, 100%);

            padding: 40px 35px;

            text-align: center;

            border-radius: 28px;

            background: white;

            border: 1px solid #dbeafe;

            box-shadow:
                0 30px 80px rgba(15,23,42,.25);

            animation: loadingCardIn .4s ease;

        }


        .loading-animation {

            position: relative;

            width: 90px;
            height: 90px;

            margin: 0 auto 24px;

            display: flex;
            align-items: center;
            justify-content: center;

        }


        .loading-ring {

            position: absolute;

            inset: 0;

            border-radius: 50%;

            border: 4px solid #dbeafe;

            border-top-color: #2563eb;

            border-right-color: #60a5fa;

            animation: spin 1.1s linear infinite;

        }


        .loading-upload-icon {

            width: 58px;
            height: 58px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 18px;

            color: #2563eb;

            background: #eff6ff;

            animation: floatingIcon 2s ease-in-out infinite;

        }


        .loading-card h3 {

            color: #1e293b;

            font-size: 21px;

            font-weight: 900;

        }


        .loading-card p {

            color: #94a3b8;

            font-size: 13px;

            margin-top: 7px;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;

        }


        .progress-container {

            margin-top: 25px;

            height: 8px;

            overflow: hidden;

            border-radius: 999px;

            background: #e2e8f0;

        }


        .progress-bar {

            width: 100%;

            height: 100%;

            overflow: hidden;

            border-radius: inherit;

        }


        .progress-animation {

            width: 45%;

            height: 100%;

            border-radius: inherit;

            background:
                linear-gradient(
                    90deg,
                    #2563eb,
                    #60a5fa,
                    #2563eb
                );

            animation: progressMove 1.3s ease-in-out infinite;

        }


        .loading-status {

            display: flex;

            align-items: center;
            justify-content: center;

            gap: 8px;

            margin-top: 17px;

            color: #64748b;

            font-size: 12px;

            font-weight: 700;

        }


        .loading-dot {

            width: 7px;
            height: 7px;

            border-radius: 50%;

            background: #2563eb;

            animation: pulseDot 1s infinite;

        }


        /* =========================================
           ANIMATIONS
        ========================================= */

        @keyframes spin {

            to {
                transform: rotate(360deg);
            }

        }


        @keyframes progressMove {

            0% {
                transform: translateX(-110%);
            }

            50% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(240%);
            }

        }


        @keyframes pulseDot {

            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .45;
                transform: scale(.75);
            }

        }


        @keyframes floatingIcon {

            0%, 100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }

        }


        @keyframes fadeDown {

            from {
                opacity: 0;
                transform: translateY(-15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }


        @keyframes ppmRow {

            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }


        @keyframes searchAnimation {

            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }


        @keyframes overlayIn {

            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }

        }


        @keyframes loadingCardIn {

            from {
                opacity: 0;
                transform: translateY(20px) scale(.96);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

        }


        .animate-fade-down {
            animation: fadeDown .6s ease-out;
        }


        .animate-search {
            animation: searchAnimation .25s ease-out;
        }


        .ppm-row:nth-child(1) {
            animation-delay: .05s;
        }

        .ppm-row:nth-child(2) {
            animation-delay: .10s;
        }

        .ppm-row:nth-child(3) {
            animation-delay: .15s;
        }

        .ppm-row:nth-child(4) {
            animation-delay: .20s;
        }

        .ppm-row:nth-child(5) {
            animation-delay: .25s;
        }

        .ppm-row:nth-child(6) {
            animation-delay: .30s;
        }

        .ppm-row:nth-child(7) {
            animation-delay: .35s;
        }


        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 768px) {

            .import-card {
                padding: 20px;
            }

            .import-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .import-footer {
                flex-direction: column;
                align-items: stretch;
            }

            .upload-button {
                width: 100%;
            }

            .table-top {
                flex-direction: column;
                align-items: flex-start;
            }

            .records-counter {
                align-self: stretch;
                justify-content: center;
            }

        }


        @media (max-width: 480px) {

            .drop-zone {
                min-height: 180px;
                padding: 20px;
            }

            .format-badges {
                display: none;
            }

            .loading-card {
                padding: 30px 22px;
            }

        }

    </style>


    {{-- =========================================================
        JAVASCRIPT
    ========================================================== --}}
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            /* =====================================================
               SEARCH
            ====================================================== */

            const searchInput =
                document.getElementById('jobSearch');

            const searchResults =
                document.getElementById('searchResults');

            const clearSearch =
                document.getElementById('clearSearch');

            let searchTimeout = null;


            searchInput.addEventListener('input', function () {

                const jobId = this.value.trim();

                clearTimeout(searchTimeout);


                if (jobId.length === 0) {

                    searchResults.innerHTML = '';

                    searchResults.classList.add('hidden');

                    clearSearch.classList.add('hidden');

                    return;
                }


                clearSearch.classList.remove('hidden');


                searchTimeout = setTimeout(function () {

                    fetch(
                        `{{ route('ppm-records.search') }}?job_id=${encodeURIComponent(jobId)}`
                    )

                    .then(response => {

                        if (!response.ok) {
                            throw new Error('Search request failed');
                        }

                        return response.json();

                    })

                    .then(data => {

                        searchResults.innerHTML = '';


                        if (data.length === 0) {

                            searchResults.innerHTML = `

                                <div class="px-6 py-8 text-center">

                                    <div class="text-blue-300 text-3xl mb-3">
                                        🔍
                                    </div>

                                    <p class="text-gray-700 font-bold">
                                        No job found
                                    </p>

                                    <p class="text-sm text-gray-400 mt-1">
                                        No Job ID matches "${jobId}"
                                    </p>

                                </div>

                            `;

                            searchResults.classList.remove('hidden');

                            return;
                        }


                        data.forEach(function (record) {

                            const item =
                                document.createElement('a');

                            item.href = record.url;

                            item.className = `
                                flex
                                items-center
                                justify-between
                                px-6
                                py-5
                                border-b
                                border-gray-100
                                hover:bg-blue-50
                                transition-all
                                duration-200
                                group
                            `;


                            item.innerHTML = `

                                <div class="flex items-center gap-4">

                                    <div
                                        class="
                                            w-11
                                            h-11
                                            rounded-xl
                                            bg-blue-100
                                            text-blue-600
                                            flex
                                            items-center
                                            justify-center
                                            group-hover:scale-110
                                            transition-transform
                                            duration-200
                                        "
                                    >

                                        🔧

                                    </div>


                                    <div>

                                        <div
                                            class="
                                                font-bold
                                                text-blue-600
                                            "
                                        >
                                            Job ID:
                                            ${record.job_id}
                                        </div>


                                        <div
                                            class="
                                                text-sm
                                                text-gray-500
                                                mt-1
                                            "
                                        >
                                            Asset ID:
                                            ${record.asset_id ?? 'N/A'}
                                        </div>

                                    </div>

                                </div>


                                <div
                                    class="
                                        flex
                                        items-center
                                        gap-2
                                        text-blue-600
                                        font-semibold
                                        text-sm
                                        opacity-60
                                        group-hover:opacity-100
                                        group-hover:translate-x-1
                                        transition-all
                                        duration-200
                                    "
                                >

                                    Details

                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 5l7 7-7 7"
                                        />

                                    </svg>

                                </div>

                            `;


                            searchResults.appendChild(item);

                        });


                        searchResults.classList.remove('hidden');

                    })

                    .catch(error => {

                        console.error(error);

                        searchResults.innerHTML = `

                            <div class="p-6 text-center text-red-500">

                                Error while searching.

                            </div>

                        `;

                        searchResults.classList.remove('hidden');

                    });

                }, 250);

            });


            clearSearch.addEventListener('click', function () {

                searchInput.value = '';

                searchResults.innerHTML = '';

                searchResults.classList.add('hidden');

                clearSearch.classList.add('hidden');

                searchInput.focus();

            });


            document.addEventListener('click', function (event) {

                if (
                    !searchInput.contains(event.target) &&
                    !searchResults.contains(event.target)
                ) {

                    searchResults.classList.add('hidden');

                }

            });


            /* =====================================================
               FILE IMPORT
            ====================================================== */

            const fileInput =
                document.getElementById('ppm-file');

            const dropZone =
                document.getElementById('dropZone');

            const selectedFile =
                document.getElementById('selectedFile');

            const fileName =
                document.getElementById('fileName');

            const fileSize =
                document.getElementById('fileSize');

            const removeFile =
                document.getElementById('removeFile');

            const uploadButton =
                document.getElementById('uploadButton');

            const importForm =
                document.getElementById('importForm');

            const importLoading =
                document.getElementById('importLoading');

            const loadingFileName =
                document.getElementById('loadingFileName');

            const buttonNormal =
                document.getElementById('buttonNormal');

            const buttonLoading =
                document.getElementById('buttonLoading');


            /* File size */
            function formatFileSize(bytes) {

                if (bytes === 0) {
                    return '0 Bytes';
                }

                const units = [
                    'Bytes',
                    'KB',
                    'MB',
                    'GB'
                ];

                const index =
                    Math.floor(
                        Math.log(bytes) /
                        Math.log(1024)
                    );

                return (
                    parseFloat(
                        (bytes / Math.pow(1024, index))
                        .toFixed(2)
                    ) +
                    ' ' +
                    units[index]
                );

            }


            /* Show file */
            function showFile(file) {

                if (!file) {
                    return;
                }


                const allowedExtensions = [
                    'xlsx',
                    'xls',
                    'csv'
                ];

                const extension =
                    file.name
                        .split('.')
                        .pop()
                        .toLowerCase();


                if (!allowedExtensions.includes(extension)) {

                    alert(
                        'Please select an XLSX, XLS, or CSV file.'
                    );

                    fileInput.value = '';

                    return;

                }


                if (file.size > 10 * 1024 * 1024) {

                    alert(
                        'The selected file is larger than 10 MB.'
                    );

                    fileInput.value = '';

                    return;

                }


                fileName.textContent =
                    file.name;

                fileSize.textContent =
                    formatFileSize(file.size);


                selectedFile.classList.remove('hidden');

                selectedFile.classList.add('flex');

                uploadButton.disabled = false;


                dropZone.classList.add('file-selected');

            }


            fileInput.addEventListener('change', function () {

                if (this.files.length > 0) {

                    showFile(this.files[0]);

                }

            });


            /* Drag over */
            [
                'dragenter',
                'dragover'
            ].forEach(eventName => {

                dropZone.addEventListener(
                    eventName,
                    function (event) {

                        event.preventDefault();

                        dropZone.classList.add('dragover');

                    }
                );

            });


            /* Drag leave */
            [
                'dragleave',
                'drop'
            ].forEach(eventName => {

                dropZone.addEventListener(
                    eventName,
                    function (event) {

                        event.preventDefault();

                        dropZone.classList.remove('dragover');

                    }
                );

            });


            /* Drop */
            dropZone.addEventListener('drop', function (event) {

                const files =
                    event.dataTransfer.files;

                if (files.length > 0) {

                    fileInput.files = files;

                    showFile(files[0]);

                }

            });


            /* Remove */
            removeFile.addEventListener('click', function (event) {

                event.preventDefault();

                event.stopPropagation();

                fileInput.value = '';

                selectedFile.classList.add('hidden');

                selectedFile.classList.remove('flex');

                uploadButton.disabled = true;

                dropZone.classList.remove('file-selected');

            });


            /* Submit */
            importForm.addEventListener('submit', function (event) {

                if (!fileInput.files.length) {

                    event.preventDefault();

                    return;

                }


                uploadButton.disabled = true;


                buttonNormal.classList.add('hidden');

                buttonLoading.classList.remove('hidden');


                loadingFileName.textContent =
                    'Processing: ' +
                    fileInput.files[0].name;


                importLoading.classList.remove('hidden');


                /*
                 * Prevent accidental navigation/back interaction
                 * while the import is being submitted.
                 */
                document.body.style.overflow = 'hidden';

            });

        });

    </script>

</x-app-layout>
