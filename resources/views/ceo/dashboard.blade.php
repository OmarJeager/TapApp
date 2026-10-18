<x-app-layout>

    @php
        $user         = auth()->user();
        $hasPicture   = (bool) $user->profile_picture;
        $hasSignature = (bool) $user->signature;
        $doneCount    = ($hasPicture ? 1 : 0) + ($hasSignature ? 1 : 0);
        $percent      = (int) round(($doneCount / 2) * 100);
        $initial      = mb_strtoupper(mb_substr($user->name, 0, 1));
    @endphp

    {{-- Header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- Fonts + Cropper assets --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Instrument+Sans:wght@400;500;600&display=swap"
        rel="stylesheet"
    >
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">

    {{-- ========================================================= --}}
    {{-- STYLES --}}
    {{-- ========================================================= --}}
    <style>
        .pf {
            --ink: #14122b;
            --muted: #6b6a85;
            --line: #e4e2f2;
            --card: #ffffff;
            --soft: #f4f3fc;
            --violet: #5b4bff;
            --violet-deep: #4637e0;
            --mint: #19d3a2;
            --danger: #e5484d;
            --shadow: 0 1px 2px rgba(20, 18, 43, .04), 0 12px 32px -12px rgba(70, 55, 224, .18);
            font-family: 'Instrument Sans', system-ui, sans-serif;
            color: var(--ink);
        }

        .dark .pf {
            --ink: #f1f0ff;
            --muted: #a3a1c4;
            --line: #2c2a52;
            --card: #1a1833;
            --soft: #221f45;
            --shadow: 0 1px 2px rgba(0, 0, 0, .3), 0 16px 40px -16px rgba(0, 0, 0, .6);
        }

        .pf h1, .pf h2, .pf h3, .pf h4, .pf .pf-display {
            font-family: 'Bricolage Grotesque', 'Instrument Sans', system-ui, sans-serif;
            letter-spacing: -0.02em;
        }

        .pf-wrap {
            max-width: 72rem;
            margin: 0 auto;
            padding: 2rem 1rem 4rem;
        }

        @media (min-width: 640px) { .pf-wrap { padding: 2.5rem 1.5rem 5rem; } }

        .pf-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: 1fr;
            align-items: start;
        }

        @media (min-width: 1024px) {
            .pf-grid { grid-template-columns: 22rem 1fr; gap: 2rem; }
            .pf-side { position: sticky; top: 1.5rem; }
        }

        /* ---------- Page-load sequence (the one orchestrated moment) ---------- */
        .pf-rise { animation: pfRise .7s cubic-bezier(.2, .8, .2, 1) both; }
        .pf-rise.d2 { animation-delay: .12s; }
        .pf-rise.d3 { animation-delay: .24s; }

        @keyframes pfRise {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: none; }
        }

        /* ---------- Cards ---------- */
        .pf-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 28px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        /* ---------- Profile card ---------- */
        .pf-cover {
            height: 8.5rem;
            background:
                radial-gradient(60% 120% at 15% 0%, rgba(25, 211, 162, .75), transparent 60%),
                radial-gradient(70% 120% at 90% 10%, rgba(255, 255, 255, .22), transparent 55%),
                linear-gradient(120deg, #3a2bd6, #5b4bff 55%, #8b7dff);
            position: relative;
        }

        .pf-cover::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, .22) 1px, transparent 1.2px);
            background-size: 16px 16px;
            mask-image: linear-gradient(to bottom, #000, transparent 85%);
            -webkit-mask-image: linear-gradient(to bottom, #000, transparent 85%);
        }

        .pf-profile-body {
            padding: 0 1.5rem 1.75rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .pf-avatar {
            --size: 8.5rem;
            position: relative;
            width: var(--size);
            height: var(--size);
            margin-top: calc(var(--size) / -2);
            border-radius: 50%;
            padding: 5px;
            background: conic-gradient(from 0deg, var(--violet), var(--mint), var(--violet));
            cursor: pointer;
            border: 0;
            transition: transform .3s cubic-bezier(.2, .8, .2, 1), box-shadow .3s;
            box-shadow: 0 10px 30px -10px rgba(91, 75, 255, .6);
            animation: pfSpin 1.2s cubic-bezier(.3, .7, .2, 1) both;
        }

        @keyframes pfSpin {
            from { transform: rotate(-200deg) scale(.85); opacity: 0; }
            to   { transform: none; opacity: 1; }
        }

        .pf-avatar:hover { transform: scale(1.04); }
        .pf-avatar.is-drag { transform: scale(1.1); box-shadow: 0 0 0 8px rgba(25, 211, 162, .3); }

        .pf-avatar-inner {
            position: relative;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            overflow: hidden;
            background: var(--card);
            display: grid;
            place-items: center;
        }

        .pf-avatar-inner img { width: 100%; height: 100%; object-fit: cover; display: block; }

        .pf-initial {
            width: 100%;
            height: 100%;
            display: grid;
            place-items: center;
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 3.2rem;
            font-weight: 800;
            color: #fff;
            background: linear-gradient(135deg, var(--violet), #8b7dff 60%, var(--mint));
        }

        .pf-avatar-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .25rem;
            background: rgba(20, 18, 43, .62);
            color: #fff;
            font-size: .8rem;
            font-weight: 600;
            opacity: 0;
            transition: opacity .25s;
        }

        .pf-avatar:hover .pf-avatar-overlay,
        .pf-avatar:focus-visible .pf-avatar-overlay,
        .pf-avatar.is-drag .pf-avatar-overlay { opacity: 1; }

        .pf-name { margin-top: 1rem; font-size: 1.5rem; font-weight: 800; line-height: 1.15; }
        .pf-email { margin-top: .25rem; font-size: .9rem; color: var(--muted); word-break: break-all; }

        .pf-since {
            margin-top: .9rem;
            font-size: .8rem;
            color: var(--muted);
            background: var(--soft);
            border-radius: 999px;
            padding: .35rem .85rem;
        }

        /* ---------- Buttons ---------- */
        .pf-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            border: 0;
            cursor: pointer;
            font: inherit;
            font-size: .9rem;
            font-weight: 600;
            padding: .7rem 1.3rem;
            border-radius: 14px;
            transition: transform .15s, background .2s, box-shadow .2s, opacity .2s;
        }

        .pf-btn:active:not(:disabled) { transform: scale(.97); }
        .pf-btn:disabled { opacity: .45; cursor: not-allowed; }

        .pf-btn-primary {
            color: #fff;
            background: linear-gradient(135deg, var(--violet), var(--violet-deep));
            box-shadow: 0 8px 20px -8px rgba(91, 75, 255, .75);
        }

        .pf-btn-primary:hover:not(:disabled) { box-shadow: 0 12px 26px -8px rgba(91, 75, 255, .9); transform: translateY(-1px); }

        .pf-btn-ghost { color: var(--ink); background: var(--soft); }
        .pf-btn-ghost:hover:not(:disabled) { background: var(--line); }

        .pf-btn-block { width: 100%; margin-top: 1.25rem; }

        .pf a:focus-visible,
        .pf button:focus-visible,
        .pf input:focus-visible,
        .pf-modal button:focus-visible {
            outline: 3px solid var(--mint);
            outline-offset: 2px;
        }

        /* ---------- Progress ---------- */
        .pf-progress { padding: 1.5rem; border-top: 1px solid var(--line); }
        .pf-progress-head { display: flex; justify-content: space-between; align-items: baseline; font-weight: 600; font-size: .95rem; }
        .pf-progress-head span { color: var(--muted); font-weight: 500; font-size: .85rem; }

        .pf-bar { margin-top: .7rem; height: 8px; border-radius: 999px; background: var(--soft); overflow: hidden; }
        .pf-bar-fill {
            height: 100%;
            width: 0;
            border-radius: inherit;
            background: linear-gradient(90deg, var(--violet), var(--mint));
            transition: width 1.1s cubic-bezier(.2, .8, .2, 1) .5s;
        }

        .pf-tasks { margin: 1rem 0 0; padding: 0; list-style: none; display: grid; gap: .6rem; }

        .pf-task { display: flex; align-items: center; gap: .65rem; font-size: .9rem; color: var(--muted); }
        .pf-task.is-done { color: var(--ink); }

        .pf-tick {
            flex: none;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            border: 2px solid var(--line);
            transition: all .3s;
        }

        .pf-task.is-done .pf-tick { background: var(--mint); border-color: var(--mint); color: #06352a; }
        .pf-tick svg { width: 12px; height: 12px; opacity: 0; }
        .pf-task.is-done .pf-tick svg { opacity: 1; }

        /* ---------- Signature studio ---------- */
        .pf-studio-head {
            padding: 1.75rem 1.75rem 0;
            display: flex;
            flex-wrap: wrap;
            gap: .75rem 1rem;
            justify-content: space-between;
            align-items: flex-start;
        }

        .pf-studio-head h3 { font-size: 1.6rem; font-weight: 800; line-height: 1.1; }
        .pf-studio-head p { margin-top: .4rem; color: var(--muted); font-size: .95rem; max-width: 34rem; line-height: 1.55; }

        .pf-chip {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            font-size: .8rem;
            font-weight: 600;
            padding: .4rem .8rem;
            border-radius: 999px;
            background: var(--soft);
            color: var(--muted);
        }

        .pf-chip::before { content: ''; width: 8px; height: 8px; border-radius: 50%; background: #c2c0da; }
        .pf-chip.is-saved { background: rgba(25, 211, 162, .15); color: #0b8f6d; }
        .dark .pf-chip.is-saved { color: #4ff0c3; }
        .pf-chip.is-saved::before { background: var(--mint); }

        .pf-studio-body { padding: 1.5rem 1.75rem 1.75rem; display: grid; gap: 1.5rem; }

        .pf-current { display: grid; gap: .6rem; }
        .pf-label { font-size: .9rem; font-weight: 600; }

        .pf-current-box {
            background: #fff;
            border: 1px dashed var(--line);
            border-radius: 18px;
            padding: 1rem;
            display: grid;
            place-items: center;
            min-height: 7rem;
            transition: border-color .2s;
        }

        .pf-current-box:hover { border-color: var(--violet); }
        .pf-current-box img { max-height: 8rem; max-width: 100%; object-fit: contain; }

        /* Pad */
        .pf-pad {
            position: relative;
            background: #fff;
            border-radius: 20px;
            border: 2px solid var(--line);
            overflow: hidden;
            transition: border-color .2s, box-shadow .2s;
        }

        .pf-pad:focus-within, .pf-pad.is-active {
            border-color: var(--violet);
            box-shadow: 0 0 0 5px rgba(91, 75, 255, .14);
        }

        .pf-pad::after {
            content: '';
            position: absolute;
            left: 7%;
            right: 7%;
            bottom: 26%;
            border-bottom: 2px dotted #cfcde6;
            pointer-events: none;
        }

        #signatureCanvas {
            display: block;
            width: 100%;
            height: 14rem;
            touch-action: none;
            cursor: crosshair;
        }

        @media (min-width: 640px) { #signatureCanvas { height: 17rem; } }

        .pf-pad-hint {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            pointer-events: none;
            color: #a9a7c6;
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: clamp(1.1rem, 3.5vw, 1.6rem);
            font-weight: 700;
            transition: opacity .25s, transform .25s;
        }

        .pf-pad-hint.is-hidden { opacity: 0; transform: translateY(-6px); }

        .pf-pad.shake { animation: pfShake .45s; border-color: var(--danger); }

        @keyframes pfShake {
            20%, 60% { transform: translateX(-7px); }
            40%, 80% { transform: translateX(7px); }
        }

        .pf-tools { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; }

        .pf-inks { display: flex; align-items: center; gap: .65rem; }
        .pf-inks > span { font-size: .85rem; color: var(--muted); margin-right: .15rem; }

        .pf-ink {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 3px solid var(--card);
            background: var(--c);
            cursor: pointer;
            box-shadow: 0 0 0 2px var(--line);
            transition: transform .2s, box-shadow .2s;
            padding: 0;
        }

        .pf-ink:hover { transform: scale(1.12); }
        .pf-ink[aria-pressed="true"] { box-shadow: 0 0 0 2px var(--violet); transform: scale(1.15); }

        .pf-actions { display: flex; flex-wrap: wrap; gap: .6rem; }

        .pf-error { margin-top: -.5rem; color: var(--danger); font-size: .88rem; font-weight: 500; }

        @media (max-width: 520px) {
            .pf-actions { width: 100%; }
            .pf-actions .pf-btn { flex: 1; }
        }

        /* ---------- Toasts ---------- */
        .pf-toasts {
            position: fixed;
            z-index: 80;
            right: 1rem;
            left: 1rem;
            bottom: 1rem;
            display: grid;
            gap: .6rem;
            justify-items: end;
            pointer-events: none;
        }

        .pf-toast {
            pointer-events: auto;
            display: flex;
            align-items: center;
            gap: .7rem;
            max-width: 26rem;
            width: 100%;
            padding: .85rem 1rem;
            border-radius: 16px;
            background: #14122b;
            color: #fff;
            font-size: .92rem;
            box-shadow: 0 18px 40px -12px rgba(0, 0, 0, .5);
            border-left: 5px solid var(--mint);
            animation: pfToastIn .45s cubic-bezier(.2, .9, .3, 1.2) both;
        }

        .pf-toast.is-error { border-left-color: var(--danger); }
        .pf-toast.is-leaving { animation: pfToastOut .3s forwards; }
        .pf-toast button { margin-left: auto; background: none; border: 0; color: #fff; opacity: .6; cursor: pointer; font-size: 1.2rem; line-height: 1; }
        .pf-toast button:hover { opacity: 1; }

        @keyframes pfToastIn  { from { opacity: 0; transform: translateY(24px) scale(.95); } to { opacity: 1; transform: none; } }
        @keyframes pfToastOut { to { opacity: 0; transform: translateX(30px); } }

        /* ---------- Crop modal ---------- */
        .pf-modal {
            position: fixed;
            inset: 0;
            z-index: 70;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding: 0;
            background: rgba(14, 12, 34, .72);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            opacity: 0;
            visibility: hidden;
            transition: opacity .3s, visibility .3s;
            font-family: 'Instrument Sans', system-ui, sans-serif;
        }

        .pf-modal.is-open { opacity: 1; visibility: visible; }

        @media (min-width: 640px) { .pf-modal { align-items: center; padding: 1rem; } }

        .pf-sheet {
            width: 100%;
            max-width: 34rem;
            max-height: 96vh;
            overflow-y: auto;
            background: #fff;
            color: #14122b;
            border-radius: 28px 28px 0 0;
            transform: translateY(40px) scale(.98);
            transition: transform .4s cubic-bezier(.2, .9, .3, 1.1);
        }

        .dark .pf-sheet { background: #1a1833; color: #f1f0ff; }
        .pf-modal.is-open .pf-sheet { transform: none; }

        @media (min-width: 640px) { .pf-sheet { border-radius: 28px; } }

        .pf-sheet-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.1rem 1.4rem;
        }

        .pf-sheet-head h3 { font-family: 'Bricolage Grotesque', sans-serif; font-size: 1.25rem; font-weight: 800; }

        .pf-close {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 0;
            cursor: pointer;
            background: rgba(120, 118, 160, .15);
            color: inherit;
            font-size: 1.3rem;
            line-height: 1;
            transition: background .2s, transform .2s;
        }

        .pf-close:hover { background: rgba(120, 118, 160, .3); transform: rotate(90deg); }

        .crop-container { width: 100%; height: min(58vh, 26rem); overflow: hidden; background: #0e0c22; }
        .crop-container img { display: block; max-width: 100%; }

        /* Round crop window */
        .crop-container .cropper-view-box,
        .crop-container .cropper-face { border-radius: 50%; }
        .crop-container .cropper-view-box { outline: 2px solid var(--mint, #19d3a2); outline-color: #19d3a2; }

        .pf-tools-row { display: flex; justify-content: center; gap: .6rem; padding: 1rem 1.4rem .4rem; flex-wrap: wrap; }

        .pf-round {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 0;
            cursor: pointer;
            background: rgba(120, 118, 160, .15);
            color: inherit;
            font-size: 1.15rem;
            display: grid;
            place-items: center;
            transition: background .2s, transform .15s;
        }

        .pf-round:hover { background: rgba(120, 118, 160, .3); }
        .pf-round:active { transform: scale(.9); }
        .pf-round svg { width: 20px; height: 20px; }

        .pf-reset { width: auto; padding: 0 1.1rem; font-size: .88rem; font-weight: 600; border-radius: 999px; }

        .pf-sheet-foot { display: flex; gap: .7rem; justify-content: flex-end; padding: 1rem 1.4rem 1.4rem; }
        .pf-sheet .pf-btn-ghost { background: rgba(120, 118, 160, .15); color: inherit; }

        .pf-spin {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, .4);
            border-top-color: #fff;
            animation: pfRot .7s linear infinite;
        }

        @keyframes pfRot { to { transform: rotate(360deg); } }

        /* ---------- Reduced motion ---------- */
        @media (prefers-reduced-motion: reduce) {
            .pf *, .pf-modal, .pf-modal *, .pf-toast {
                animation-duration: .01ms !important;
                animation-delay: 0s !important;
                transition-duration: .01ms !important;
                transition-delay: 0s !important;
            }
        }
    </style>

    {{-- ========================================================= --}}
    {{-- PAGE --}}
    {{-- ========================================================= --}}
    <div class="pf">
        <div class="pf-wrap">

            <div class="pf-grid">

                {{-- ===================== PROFILE ===================== --}}
                <aside class="pf-side">
                    <section class="pf-card pf-rise" aria-label="Your profile">

                        <div class="pf-cover"></div>

                        <div class="pf-profile-body">

                            {{-- Avatar (click or drop an image on it) --}}
                            <button type="button" id="avatarBtn" class="pf-avatar" aria-label="Change profile picture">
                                <span class="pf-avatar-inner">
                                    @if ($hasPicture)
                                        <img
                                            src="{{ asset('storage/' . $user->profile_picture) }}?v={{ time() }}"
                                            alt="Your profile picture"
                                        >
                                    @else
                                        <span class="pf-initial" aria-hidden="true">{{ $initial }}</span>
                                    @endif

                                    <span class="pf-avatar-overlay">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                        Change
                                    </span>
                                </span>
                            </button>

                            <h3 class="pf-name">{{ $user->name }}</h3>

                            @if ($user->email)
                                <p class="pf-email">{{ $user->email }}</p>
                            @endif

                            @if ($user->created_at)
                                <p class="pf-since">Member since {{ $user->created_at->format('F Y') }}</p>
                            @endif

                            <button type="button" id="choosePhoto" class="pf-btn pf-btn-primary pf-btn-block">
                                {{ $hasPicture ? 'Change profile picture' : 'Add a profile picture' }}
                            </button>

                            {{-- Hidden File Input --}}
                            <input
                                type="file"
                                id="picture"
                                accept="image/jpeg,image/png,image/webp"
                                class="hidden"
                                style="display:none"
                            >
                        </div>

                        {{-- Setup progress --}}
                        <div class="pf-progress">
                            <div class="pf-progress-head">
                                Profile setup
                                <span>{{ $doneCount }} of 2 done</span>
                            </div>

                            <div class="pf-bar" role="progressbar" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="pf-bar-fill" id="barFill" data-width="{{ $percent }}"></div>
                            </div>

                            <ul class="pf-tasks">
                                <li class="pf-task {{ $hasPicture ? 'is-done' : '' }}">
                                    <span class="pf-tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg></span>
                                    Add a profile picture
                                </li>
                                <li class="pf-task {{ $hasSignature ? 'is-done' : '' }}">
                                    <span class="pf-tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg></span>
                                    Save your signature
                                </li>
                            </ul>
                        </div>

                    </section>
                </aside>


                {{-- ===================== SIGNATURE ===================== --}}
                <main>
                    <section class="pf-card pf-rise d2" aria-labelledby="sigTitle">

                        <div class="pf-studio-head">
                            <div>
                                <h3 id="sigTitle">My signature</h3>
                                <p>Draw your signature with a mouse, finger or stylus. We save it to your account so you can reuse it.</p>
                            </div>

                            <span class="pf-chip {{ $hasSignature ? 'is-saved' : '' }}">
                                {{ $hasSignature ? 'Signature saved' : 'Not saved yet' }}
                            </span>
                        </div>

                        <div class="pf-studio-body">

                            {{-- Existing Signature --}}
                            @if ($hasSignature)
                                <div class="pf-current">
                                    <p class="pf-label">Current signature</p>
                                    <div class="pf-current-box">
                                        <img
                                            src="{{ asset('storage/' . $user->signature) }}?v={{ time() }}"
                                            alt="Your saved signature"
                                        >
                                    </div>
                                </div>
                            @endif

                            {{-- Signature Form --}}
                            <form
                                method="POST"
                                action="{{ route('profile.signature.update') }}"
                                id="signatureForm"
                                style="display:grid; gap:1.1rem"
                            >
                                @csrf

                                <p class="pf-label">{{ $hasSignature ? 'Draw a new signature' : 'Draw your signature' }}</p>

                                {{-- Canvas --}}
                                <div class="pf-pad" id="signaturePad">
                                    <canvas id="signatureCanvas" aria-label="Signature drawing area"></canvas>
                                    <div class="pf-pad-hint" id="padHint">Sign here</div>
                                </div>

                                {{-- Hidden Signature --}}
                                <input type="hidden" name="signature" id="signatureInput">

                                {{-- Validation Error --}}
                                @error('signature')
                                    <p class="pf-error">{{ $message }}</p>
                                @enderror

                                {{-- Tools --}}
                                <div class="pf-tools">

                                    <div class="pf-inks" role="group" aria-label="Ink colour">
                                        <span>Ink</span>
                                        <button type="button" class="pf-ink" style="--c:#14122b" data-color="#14122b" aria-label="Black ink" aria-pressed="true"></button>
                                        <button type="button" class="pf-ink" style="--c:#2547ff" data-color="#2547ff" aria-label="Blue ink" aria-pressed="false"></button>
                                        <button type="button" class="pf-ink" style="--c:#0b7a5c" data-color="#0b7a5c" aria-label="Green ink" aria-pressed="false"></button>
                                    </div>

                                    <div class="pf-actions">
                                        <button type="button" id="undoSignature" class="pf-btn pf-btn-ghost" disabled>Undo</button>
                                        <button type="button" id="clearSignature" class="pf-btn pf-btn-ghost" disabled>Clear</button>
                                        <button type="submit" id="saveSignature" class="pf-btn pf-btn-primary">Save signature</button>
                                    </div>

                                </div>
                            </form>

                        </div>

                    </section>
                </main>

            </div>
        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- TOASTS --}}
    {{-- ========================================================= --}}
    <div class="pf-toasts pf" id="toasts" aria-live="polite"></div>


    {{-- ========================================================= --}}
    {{-- CROP MODAL --}}
    {{-- ========================================================= --}}
    <div id="cropModal" class="pf-modal" role="dialog" aria-modal="true" aria-labelledby="cropTitle">

        <div class="pf-sheet">

            {{-- Modal Header --}}
            <div class="pf-sheet-head">
                <h3 id="cropTitle">Edit profile picture</h3>
                <button type="button" class="pf-close" onclick="closeCropper()" aria-label="Close">&times;</button>
            </div>

            {{-- Image Area --}}
            <div class="crop-container">
                <img id="cropImage" src="" alt="Image to crop">
            </div>

            {{-- Controls --}}
            <div class="pf-tools-row">
                <button type="button" class="pf-round" onclick="zoomOut()" aria-label="Zoom out">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3M8 11h6"/></svg>
                </button>

                <button type="button" class="pf-round" onclick="zoomIn()" aria-label="Zoom in">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3M8 11h6M11 8v6"/></svg>
                </button>

                <button type="button" class="pf-round" onclick="rotateCropper(-90)" aria-label="Rotate left">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                </button>

                <button type="button" class="pf-round" onclick="rotateCropper(90)" aria-label="Rotate right">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-3-6.7L21 8"/><path d="M21 3v5h-5"/></svg>
                </button>

                <button type="button" class="pf-round pf-reset" onclick="resetCropper()">Reset</button>
            </div>

            {{-- Buttons --}}
            <div class="pf-sheet-foot">
                <button type="button" class="pf-btn pf-btn-ghost" onclick="closeCropper()">Cancel</button>

                <button type="button" id="saveButton" class="pf-btn pf-btn-primary" onclick="savePicture()">
                    Save picture
                </button>
            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- CROPPER.JS --}}
    {{-- ========================================================= --}}
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>


    {{-- ========================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================================================= --}}
    <script>

        /* ------------------------------------------------------------------
         | TOASTS
         ------------------------------------------------------------------ */

        const toastBox = document.getElementById('toasts');

        function showToast(message, type = 'success', duration = 4500) {

            const toast = document.createElement('div');

            toast.className = 'pf-toast' + (type === 'error' ? ' is-error' : '');
            toast.setAttribute('role', type === 'error' ? 'alert' : 'status');

            const text = document.createElement('span');
            text.textContent = message;

            const close = document.createElement('button');
            close.type = 'button';
            close.setAttribute('aria-label', 'Dismiss');
            close.innerHTML = '&times;';

            toast.append(text, close);
            toastBox.appendChild(toast);

            const remove = () => {
                toast.classList.add('is-leaving');
                setTimeout(() => toast.remove(), 300);
            };

            close.addEventListener('click', remove);
            setTimeout(remove, duration);

        }

        /* Server flash messages */
        const flash = {
            success: @json(session('success')),
            error: @json(session('error')),
        };

        if (flash.success) showToast(flash.success, 'success');
        if (flash.error) showToast(flash.error, 'error', 7000);


        /* ------------------------------------------------------------------
         | PROGRESS BAR (animates in once)
         ------------------------------------------------------------------ */

        const barFill = document.getElementById('barFill');

        requestAnimationFrame(() => {
            barFill.style.width = barFill.dataset.width + '%';
        });


        /* ------------------------------------------------------------------
         | PROFILE PICTURE CROPPER
         ------------------------------------------------------------------ */

        let cropper = null;
        let objectURL = null;

        const pictureInput = document.getElementById('picture');
        const cropModal    = document.getElementById('cropModal');
        const cropImage    = document.getElementById('cropImage');
        const avatarBtn    = document.getElementById('avatarBtn');
        const choosePhoto  = document.getElementById('choosePhoto');

        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

        avatarBtn.addEventListener('click', () => pictureInput.click());
        choosePhoto.addEventListener('click', () => pictureInput.click());


        /* Open the cropper with a chosen / dropped file */
        function openCropperWith(file) {

            if (!file) return;

            if (!allowedTypes.includes(file.type)) {
                showToast('Choose a JPG, PNG or WebP image.', 'error');
                pictureInput.value = '';
                return;
            }

            if (objectURL) URL.revokeObjectURL(objectURL);
            objectURL = URL.createObjectURL(file);

            if (cropper) {
                cropper.destroy();
                cropper = null;
            }

            cropImage.onload = function () {

                cropper = new Cropper(cropImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.85,
                    responsive: true,
                    background: false,
                    guides: false,
                    center: true,
                    highlight: false,
                    cropBoxMovable: false,
                    cropBoxResizable: false,
                    toggleDragModeOnDblclick: false,
                });

            };

            cropImage.src = objectURL;

            cropModal.classList.add('is-open');
            document.body.style.overflow = 'hidden';

        }

        pictureInput.addEventListener('change', function (event) {
            openCropperWith(event.target.files[0]);
        });


        /* Drag & drop onto the avatar */
        ['dragenter', 'dragover'].forEach(name => {
            avatarBtn.addEventListener(name, e => {
                e.preventDefault();
                avatarBtn.classList.add('is-drag');
            });
        });

        ['dragleave', 'drop'].forEach(name => {
            avatarBtn.addEventListener(name, e => {
                e.preventDefault();
                avatarBtn.classList.remove('is-drag');
            });
        });

        avatarBtn.addEventListener('drop', e => {
            openCropperWith(e.dataTransfer.files[0]);
        });


        /* Controls */
        function zoomIn()  { if (cropper) cropper.zoom(0.1); }
        function zoomOut() { if (cropper) cropper.zoom(-0.1); }
        function resetCropper() { if (cropper) cropper.reset(); }
        function rotateCropper(deg) { if (cropper) cropper.rotate(deg); }


        function closeCropper() {

            if (cropper) {
                cropper.destroy();
                cropper = null;
            }

            cropModal.classList.remove('is-open');
            document.body.style.overflow = '';

            if (objectURL) {
                URL.revokeObjectURL(objectURL);
                objectURL = null;
            }

            pictureInput.value = '';

        }

        /* Close with Escape or by clicking the backdrop */
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && cropModal.classList.contains('is-open')) closeCropper();
        });

        cropModal.addEventListener('click', e => {
            if (e.target === cropModal) closeCropper();
        });


        function setSaving(button, saving, idleText) {

            button.disabled = saving;

            button.innerHTML = saving
                ? '<span class="pf-spin"></span> Saving...'
                : idleText;

        }


        function savePicture() {

            if (!cropper) return;

            const saveButton = document.getElementById('saveButton');

            setSaving(saveButton, true);

            cropper.getCroppedCanvas({
                width: 500,
                height: 500,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            }).toBlob(function (blob) {

                const formData = new FormData();

                formData.append('picture', blob, 'profile-picture.jpg');
                formData.append('_token', '{{ csrf_token() }}');

                fetch('{{ route('profile.upload-picture') }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {

                    if (data.success) {

                        showToast('Profile picture saved.', 'success');

                        closeCropper();

                        setTimeout(() => window.location.reload(), 700);

                    } else {

                        showToast(data.message || 'Upload failed. Try again.', 'error');

                        setSaving(saveButton, false, 'Save picture');

                    }

                })
                .catch(error => {

                    console.error(error);

                    showToast('Something went wrong while uploading. Check your connection and try again.', 'error');

                    setSaving(saveButton, false, 'Save picture');

                });

            }, 'image/jpeg', 0.90);

        }


        /* ------------------------------------------------------------------
         | SIGNATURE PAD
         | Strokes are stored as points so the drawing survives resizes.
         ------------------------------------------------------------------ */

        const signatureCanvas = document.getElementById('signatureCanvas');
        const signatureInput  = document.getElementById('signatureInput');
        const signatureForm   = document.getElementById('signatureForm');
        const signaturePad    = document.getElementById('signaturePad');
        const padHint         = document.getElementById('padHint');
        const clearBtn        = document.getElementById('clearSignature');
        const undoBtn         = document.getElementById('undoSignature');
        const saveSigBtn      = document.getElementById('saveSignature');
        const inkButtons      = document.querySelectorAll('.pf-ink');

        const ctx = signatureCanvas.getContext('2d');

        let strokes = [];
        let currentStroke = null;
        let inkColor = '#14122b';
        let drawing = false;


        function drawStroke(stroke) {

            ctx.strokeStyle = stroke.color;
            ctx.fillStyle = stroke.color;
            ctx.lineWidth = 2.6;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';

            const pts = stroke.points;

            if (pts.length === 1) {
                ctx.beginPath();
                ctx.arc(pts[0].x, pts[0].y, 1.4, 0, Math.PI * 2);
                ctx.fill();
                return;
            }

            ctx.beginPath();
            ctx.moveTo(pts[0].x, pts[0].y);

            for (let i = 1; i < pts.length - 1; i++) {
                const midX = (pts[i].x + pts[i + 1].x) / 2;
                const midY = (pts[i].y + pts[i + 1].y) / 2;
                ctx.quadraticCurveTo(pts[i].x, pts[i].y, midX, midY);
            }

            const last = pts[pts.length - 1];
            ctx.lineTo(last.x, last.y);
            ctx.stroke();

        }


        function redraw() {

            ctx.clearRect(0, 0, signatureCanvas.clientWidth, signatureCanvas.clientHeight);

            strokes.forEach(drawStroke);

        }


        function sizeCanvas() {

            const rect  = signatureCanvas.getBoundingClientRect();
            const ratio = window.devicePixelRatio || 1;

            if (!rect.width) return;

            signatureCanvas.width  = rect.width * ratio;
            signatureCanvas.height = rect.height * ratio;

            ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

            redraw();

        }


        function refreshPadState() {

            const empty = strokes.length === 0;

            padHint.classList.toggle('is-hidden', !empty || drawing);

            clearBtn.disabled = empty;
            undoBtn.disabled = empty;

        }


        function pointFromEvent(event) {

            const rect = signatureCanvas.getBoundingClientRect();

            return {
                x: event.clientX - rect.left,
                y: event.clientY - rect.top
            };

        }


        signatureCanvas.addEventListener('pointerdown', function (event) {

            drawing = true;

            currentStroke = { color: inkColor, points: [pointFromEvent(event)] };
            strokes.push(currentStroke);

            signaturePad.classList.add('is-active');
            signatureCanvas.setPointerCapture(event.pointerId);

            redraw();
            refreshPadState();

        });

        signatureCanvas.addEventListener('pointermove', function (event) {

            if (!drawing || !currentStroke) return;

            currentStroke.points.push(pointFromEvent(event));

            redraw();

        });

        function endStroke() {

            drawing = false;
            currentStroke = null;

            signaturePad.classList.remove('is-active');

            refreshPadState();

        }

        signatureCanvas.addEventListener('pointerup', endStroke);
        signatureCanvas.addEventListener('pointercancel', endStroke);


        /* Undo / clear */
        undoBtn.addEventListener('click', function () {
            strokes.pop();
            redraw();
            refreshPadState();
        });

        clearBtn.addEventListener('click', function () {
            strokes = [];
            signatureInput.value = '';
            redraw();
            refreshPadState();
        });


        /* Ink colour */
        inkButtons.forEach(button => {
            button.addEventListener('click', function () {

                inkColor = button.dataset.color;

                inkButtons.forEach(b => b.setAttribute('aria-pressed', b === button ? 'true' : 'false'));

            });
        });


        /* Save: block empty signatures */
        signatureForm.addEventListener('submit', function (event) {

            if (strokes.length === 0) {

                event.preventDefault();

                showToast('Draw your signature before saving.', 'error');

                signaturePad.classList.remove('shake');
                void signaturePad.offsetWidth;
                signaturePad.classList.add('shake');

                return;

            }

            signatureInput.value = signatureCanvas.toDataURL('image/png');

            setSaving(saveSigBtn, true);

        });


        /* Keep the drawing crisp when the layout changes */
        if ('ResizeObserver' in window) {
            new ResizeObserver(sizeCanvas).observe(signatureCanvas);
        } else {
            window.addEventListener('resize', sizeCanvas);
        }

        sizeCanvas();
        refreshPadState();

    </script>

</x-app-layout>
