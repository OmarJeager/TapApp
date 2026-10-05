@php
    $locale = app()->getLocale();
    $isRtl  = $locale === 'ar';
    $weekLabel = fn ($w) => substr($w, 4, 2) . '/' . substr($w, 2, 2);

    $typeIcons = ['pnl' => 'panel-top', 'tst' => 'zap', 'khm' => 'cpu', 'cons' => 'package', 'generic' => 'wrench'];
    $statusMeta = [
        'pending'     => ['clock',        '--pend'],
        'in_progress' => ['loader',       '--c1'],
        'completed'   => ['check',        '--c1'],
        'verified'    => ['shield-check', '--c2'],
        'quality'     => ['badge-check',  '--c3'],
    ];
    $avatar = fn ($u) => $u->profile_picture
        ? asset('storage/' . $u->profile_picture)
        : 'https://ui-avatars.com/api/?background=e7e2d3&color=2b3236&name=' . urlencode($u->name);

    $featured = $types->whereIn('type', ['pnl', 'tst']);
    $others   = $types->whereNotIn('type', ['pnl', 'tst']);
    $maxDaily = max(1, $daily->max('count') ?? 1);
    $maxLead  = max(1, $leaderboard->max('total') ?? 1);
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('dashboard.title') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600&family=Barlow+Condensed:wght@500;600;700&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@0.469.0"></script>
    <script>
        // Apply the saved theme before first paint (no light/dark flash)
        document.documentElement.dataset.theme = localStorage.getItem('theme')
            || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    </script>

    <style>
        :root {
            --bg: #eceae2; --card: #fbfaf6; --ink: #1f2629; --muted: #66726f; --line: #d9d5c6;
            --c1: #2f78a3; --c2: #cf8a12; --c3: #2a8a58; --pend: #c0612f; --gold: #e0a41a;
        }
        :root[data-theme="dark"] {
            --bg: #12171a; --card: #1b2226; --ink: #e9ece8; --muted: #8e9b98; --line: #2a343a;
            --c1: #5aa8d6; --c2: #e8aa3a; --c3: #4cc283; --pend: #f0905a; --gold: #f0bc3c;
        }
        html { background: var(--bg); color: var(--ink); }
        body { font-family: 'Barlow', 'Tajawal', system-ui, sans-serif; }
        [dir="rtl"] body { font-family: 'Tajawal', 'Barlow', sans-serif; }
        .num { font-family: 'Barlow Condensed', 'Tajawal', sans-serif; font-variant-numeric: tabular-nums; letter-spacing: .01em; }
        .card { background: var(--card); border: 1px solid var(--line); border-radius: 14px; }
        .muted { color: var(--muted); }
        .tint { background: color-mix(in srgb, var(--t) 15%, transparent); color: var(--t); }
        :focus-visible { outline: 2px solid var(--c1); outline-offset: 2px; border-radius: 6px; }

        /* Segments of the stacked bars */
        .seg-done { background: var(--c1); }
        .seg-prog { background: repeating-linear-gradient(135deg, var(--c1) 0 4px, color-mix(in srgb, var(--c1) 35%, transparent) 4px 8px); }
        .seg-pend { background: color-mix(in srgb, var(--pend) 40%, transparent); }

        /* Fill animations run once, when .ready is added */
        .ring { stroke-dasharray: var(--c); stroke-dashoffset: var(--c); transition: stroke-dashoffset 1.3s cubic-bezier(.2,.7,.2,1); }
        .ready .ring { stroke-dashoffset: var(--off); }
        .bar { transform: scaleY(0); transform-origin: bottom; transition: transform .8s cubic-bezier(.2,.7,.2,1); }
        .ready .bar { transform: scaleY(1); }
        .hbar { transform: scaleX(0); transform-origin: left; transition: transform 1s cubic-bezier(.2,.7,.2,1); }
        [dir="rtl"] .hbar { transform-origin: right; }
        .ready .hbar { transform: scaleX(1); }

        /* The one signature moment: the first-finisher photo pulses once */
        .ready .halo { animation: halo 1.6s ease-out .9s 1; }
        @keyframes halo { 0% { box-shadow: 0 0 0 0 color-mix(in srgb, var(--gold) 55%, transparent); } 100% { box-shadow: 0 0 0 22px transparent; } }

        @media (prefers-reduced-motion: reduce) {
            .ring, .bar, .hbar { transition: none; }
            .ready .halo { animation: none; }
        }
    </style>
</head>
<body class="min-h-screen antialiased">
<main id="app" class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">

    {{-- Header --}}
    <header class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="num text-3xl font-bold sm:text-4xl">{{ __('dashboard.title') }}</h1>
            <p class="muted text-sm">{{ __('dashboard.subtitle') }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <form method="GET" class="relative">
                <label class="sr-only" for="week">{{ __('dashboard.week_label') }}</label>
                <i data-lucide="calendar-days" class="pointer-events-none absolute start-3 top-1/2 h-4 w-4 -translate-y-1/2 muted"></i>
                <select id="week" name="week" onchange="this.form.submit()" class="card cursor-pointer py-2 ps-9 pe-8 text-sm font-medium">
                    @foreach($weeks as $w)
                        <option value="{{ $w }}" @selected($w == $week)>{{ __('dashboard.week', ['week' => $weekLabel($w)]) }}</option>
                    @endforeach
                </select>
            </form>
            <nav class="card flex p-1" aria-label="{{ __('dashboard.language') }}">
                @foreach(['en' => 'EN', 'fr' => 'FR', 'ar' => 'ع'] as $code => $label)
                    <a href="{{ route('lang.switch', $code) }}" @if($locale === $code) aria-current="true" @endif
                       class="rounded-lg px-3 py-1.5 text-sm font-semibold transition-colors {{ $locale === $code ? 'bg-[var(--ink)] text-[var(--card)]' : 'muted hover:text-[var(--ink)]' }}">{{ $label }}</a>
                @endforeach
            </nav>
            <button type="button" id="theme" class="card p-2.5" aria-label="{{ __('dashboard.theme') }}" title="{{ __('dashboard.theme') }}">
                <i data-lucide="sun-moon" class="h-4 w-4"></i>
            </button>
        </div>
    </header>

    @if($kpis['total'] === 0)
        <div class="card flex flex-col items-center gap-3 p-12 text-center">
            <i data-lucide="calendar-x" class="h-10 w-10 muted"></i>
            <p class="muted">{{ __('dashboard.empty_week') }}</p>
        </div>
    @else

    {{-- KPI strip --}}
    <section class="grid grid-cols-2 gap-3 md:grid-cols-3 xl:grid-cols-6">
        @foreach([
            ['assets',    'hash',         'var(--ink)',  $kpis['assets']],
            ['completed', 'check',        'var(--c1)',   $kpis['completed']],
            ['pending',   'clock',        'var(--pend)', $kpis['pending']],
            ['verified',  'shield-check', 'var(--c2)',   $kpis['verified']],
            ['quality',   'badge-check',  'var(--c3)',   $kpis['quality']],
        ] as [$k, $icon, $color, $value])
            <div class="card p-4">
                <div class="flex items-center justify-between">
                    <span class="muted text-sm">{{ __("dashboard.kpi.$k") }}</span>
                    <i data-lucide="{{ $icon }}" class="h-4 w-4" style="color: {{ $color }}"></i>
                </div>
                <p class="num mt-2 text-4xl font-bold" style="color: {{ $color }}" data-count="{{ $value }}">{{ $value }}</p>
            </div>
        @endforeach
        <div class="card p-4">
            <div class="flex items-center justify-between">
                <span class="muted text-sm">{{ __('dashboard.kpi.avg_time') }}</span>
                <i data-lucide="timer" class="h-4 w-4 muted"></i>
            </div>
            <p class="num mt-2 text-4xl font-bold"><span data-count="{{ $kpis['avg_minutes'] }}">{{ $kpis['avg_minutes'] }}</span>
                <span class="muted text-lg">{{ __('dashboard.kpi.min') }}</span></p>
        </div>
    </section>

    {{-- First to finish (all questions answered) + waiting for verification --}}
    <section class="grid gap-4 lg:grid-cols-3">
        <div class="lg:col-span-2">
            @if($firstFinisher)
                @php $u = $firstFinisher->user; @endphp
                <article class="card flex h-full items-center gap-5 p-5 sm:p-6"
                         style="background: linear-gradient(135deg, color-mix(in srgb, var(--gold) 14%, var(--card)), var(--card) 60%)">
                    <div class="relative shrink-0">
                        <img src="{{ $avatar($u) }}" alt="{{ $u->name }}" class="halo h-20 w-20 rounded-full object-cover ring-4 sm:h-24 sm:w-24" style="--tw-ring-color: var(--gold)">
                        <span class="absolute -bottom-1 -end-1 grid h-8 w-8 place-items-center rounded-full text-[var(--card)]" style="background: var(--gold)">
                            <i data-lucide="trophy" class="h-4 w-4"></i>
                        </span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold" style="color: var(--gold)">{{ __('dashboard.first.label') }}</p>
                        <h2 class="num truncate text-3xl font-bold">{{ $u->name }}</h2>
                        <p class="muted flex items-center gap-2 truncate text-sm"><i data-lucide="mail" class="h-4 w-4 shrink-0"></i>{{ $u->email }}</p>
                        <p class="muted mt-1 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm">
                            <span class="inline-flex items-center gap-1.5"><i data-lucide="hash" class="h-4 w-4"></i>{{ __('dashboard.first.asset', ['id' => $firstFinisher->asset_id]) }}</span>
                            <span class="inline-flex items-center gap-1.5"><i data-lucide="calendar-check" class="h-4 w-4"></i>{{ __('dashboard.first.finished', ['date' => $firstFinisher->finished_at->locale($locale)->translatedFormat('d M Y, H:i')]) }}</span>
                            @if($firstFinisher->minutes)
                                <span class="inline-flex items-center gap-1.5"><i data-lucide="timer" class="h-4 w-4"></i>{{ __('dashboard.first.took', ['minutes' => $firstFinisher->minutes]) }}</span>
                            @endif
                            @if($firstFinisher->q_total)
                                <span class="inline-flex items-center gap-1.5"><i data-lucide="list-checks" class="h-4 w-4"></i>{{ __('dashboard.first.questions', ['count' => $firstFinisher->q_total]) }}</span>
                            @endif
                        </p>
                    </div>
                </article>
            @else
                <div class="card flex h-full flex-col items-center justify-center gap-2 p-8 text-center">
                    <i data-lucide="trophy" class="h-8 w-8 muted"></i>
                    <p class="font-medium">{{ __('dashboard.first.empty') }}</p>
                    <p class="muted text-sm">{{ __('dashboard.first.empty_cta') }}</p>
                </div>
            @endif
        </div>

        <div class="card p-5">
            <h3 class="mb-4 flex items-center gap-2 font-semibold"><i data-lucide="hourglass" class="h-4 w-4 muted"></i>{{ __('dashboard.waiting.title') }}</h3>
            @foreach([['admin', 'shield-check', '--c2'], ['quality', 'badge-check', '--c3']] as [$k, $icon, $var])
                <div class="flex items-center gap-3 border-b py-3 last:border-0" style="border-color: var(--line)">
                    <span class="tint grid h-9 w-9 place-items-center rounded-lg" style="--t: var({{ $var }})"><i data-lucide="{{ $icon }}" class="h-4 w-4"></i></span>
                    <span class="flex-1 text-sm">{{ __("dashboard.waiting.$k") }}</span>
                    @if($waiting[$k] > 0)
                        <span class="num text-2xl font-bold" data-count="{{ $waiting[$k] }}">{{ $waiting[$k] }}</span>
                    @else
                        <span class="muted inline-flex items-center gap-1 text-sm"><i data-lucide="check" class="h-4 w-4" style="color: var(--c3)"></i>{{ __('dashboard.waiting.clear') }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    {{-- Progress per asset type --}}
    <section>
        <h2 class="num mb-3 text-xl font-semibold">{{ __('dashboard.overview.title') }}</h2>

        {{-- Featured: PNL and TST --}}
        <div class="grid gap-4 lg:grid-cols-2">
            @foreach($featured as $t)
                @php
                    $r = 42; $c = round(2 * M_PI * $r, 2); $off = round($c * (1 - $t->completed_pct / 100), 2);
                    $notStarted = max(0, 100 - $t->completed_pct - $t->in_progress_pct);
                @endphp
                <article class="card p-5">
                    <header class="mb-4 flex items-center justify-between">
                        <h3 class="flex items-center gap-2 text-lg font-semibold">
                            <span class="tint grid h-9 w-9 place-items-center rounded-lg" style="--t: var(--c1)"><i data-lucide="{{ $typeIcons[$t->type] }}" class="h-5 w-5"></i></span>
                            {{ __("dashboard.types.{$t->type}") }}
                        </h3>
                        <button type="button" data-filter-type="{{ $t->type }}" class="muted inline-flex items-center gap-1 text-sm hover:text-[var(--ink)]">
                            {{ __('dashboard.overview.view_assets') }}<i data-lucide="chevron-down" class="h-4 w-4"></i>
                        </button>
                    </header>

                    <div class="flex items-center gap-5">
                        <div class="relative h-32 w-32 shrink-0">
                            <svg viewBox="0 0 100 100" class="h-full w-full -rotate-90" role="img" aria-label="{{ $t->completed }} / {{ $t->total }}">
                                <circle cx="50" cy="50" r="{{ $r }}" fill="none" stroke="color-mix(in srgb, var(--pend) 30%, transparent)" stroke-width="9"/>
                                <circle class="ring" cx="50" cy="50" r="{{ $r }}" fill="none" stroke="var(--c1)" stroke-width="9" stroke-linecap="round" style="--c: {{ $c }}; --off: {{ $off }}"/>
                            </svg>
                            <span class="num absolute inset-0 grid place-items-center text-3xl font-bold">{{ round($t->completed_pct) }}%</span>
                        </div>
                        <div class="grid flex-1 grid-cols-2 gap-3">
                            <div>
                                <p class="num text-5xl font-bold leading-none" style="color: var(--c1)" data-count="{{ $t->completed }}">{{ $t->completed }}</p>
                                <p class="muted mt-1 flex items-center gap-1.5 text-sm"><i data-lucide="check" class="h-4 w-4"></i>{{ __('dashboard.overview.completed') }}</p>
                            </div>
                            <div>
                                <p class="num text-5xl font-bold leading-none" style="color: var(--pend)" data-count="{{ $t->pending }}">{{ $t->pending }}</p>
                                <p class="muted mt-1 flex items-center gap-1.5 text-sm"><i data-lucide="clock" class="h-4 w-4"></i>{{ __('dashboard.overview.pending') }}</p>
                            </div>
                            <p class="muted col-span-2 text-xs">{{ __('dashboard.overview.of_total', ['total' => $t->total]) }}
                                @if($t->in_progress) · {{ __('dashboard.overview.in_progress', ['count' => $t->in_progress]) }} @endif</p>
                        </div>
                    </div>

                    <div class="hbar mt-4 flex h-2.5 overflow-hidden rounded-full" style="background: var(--line)">
                        <div class="seg-done" style="width: {{ $t->completed_pct }}%"></div>
                        <div class="seg-prog" style="width: {{ $t->in_progress_pct }}%"></div>
                        <div class="seg-pend" style="width: {{ $notStarted }}%"></div>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm">
                        <span class="inline-flex items-center gap-1.5"><i data-lucide="shield-check" class="h-4 w-4" style="color: var(--c2)"></i>{{ __('dashboard.overview.verified') }} <b class="num text-base">{{ $t->verified }}</b><span class="muted">/{{ $t->total }}</span></span>
                        <span class="inline-flex items-center gap-1.5"><i data-lucide="badge-check" class="h-4 w-4" style="color: var(--c3)"></i>{{ __('dashboard.overview.quality') }} <b class="num text-base">{{ $t->quality }}</b><span class="muted">/{{ $t->total }}</span></span>
                    </div>

                    <footer class="mt-4 flex items-center gap-3 border-t pt-4" style="border-color: var(--line)">
                        <i data-lucide="trophy" class="h-4 w-4 shrink-0" style="color: var(--gold)"></i>
                        @if($t->first)
                            @php $u = $t->first->user; @endphp
                            <img src="{{ $avatar($u) }}" alt="" class="h-9 w-9 rounded-full object-cover">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium">{{ $u->name }}</p>
                                <p class="muted truncate text-xs">{{ $u->email }}</p>
                            </div>
                            <span class="muted ms-auto shrink-0 text-xs">{{ $t->first->finished_at->locale($locale)->translatedFormat('d M, H:i') }}</span>
                        @else
                            <span class="muted text-sm">{{ __('dashboard.overview.nobody') }}</span>
                        @endif
                    </footer>
                </article>
            @endforeach
        </div>

        {{-- Other types: compact cards --}}
        @if($others->isNotEmpty())
            <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($others as $t)
                    @php $notStarted = max(0, 100 - $t->completed_pct - $t->in_progress_pct); @endphp
                    <article class="card p-4">
                        <header class="mb-3 flex items-center justify-between">
                            <h3 class="flex items-center gap-2 font-semibold"><i data-lucide="{{ $typeIcons[$t->type] ?? 'wrench' }}" class="h-5 w-5 muted"></i>{{ __("dashboard.types.{$t->type}") }}</h3>
                            <button type="button" data-filter-type="{{ $t->type }}" class="muted p-1 hover:text-[var(--ink)]" aria-label="{{ __('dashboard.overview.view_assets') }}" title="{{ __('dashboard.overview.view_assets') }}"><i data-lucide="chevron-down" class="h-4 w-4"></i></button>
                        </header>
                        <div class="flex items-end gap-5">
                            <p class="num text-4xl font-bold leading-none" style="color: var(--c1)"><span data-count="{{ $t->completed }}">{{ $t->completed }}</span><span class="muted text-lg">/{{ $t->total }}</span></p>
                            <p class="num text-2xl font-bold leading-none" style="color: var(--pend)"><span data-count="{{ $t->pending }}">{{ $t->pending }}</span> <span class="muted text-sm font-medium" style="font-family: inherit">{{ __('dashboard.overview.pending') }}</span></p>
                        </div>
                        <div class="hbar mt-3 flex h-2 overflow-hidden rounded-full" style="background: var(--line)">
                            <div class="seg-done" style="width: {{ $t->completed_pct }}%"></div>
                            <div class="seg-prog" style="width: {{ $t->in_progress_pct }}%"></div>
                            <div class="seg-pend" style="width: {{ $notStarted }}%"></div>
                        </div>
                        <div class="mt-3 flex items-center gap-2 text-sm">
                            <i data-lucide="trophy" class="h-4 w-4 shrink-0" style="color: var(--gold)"></i>
                            @if($t->first)
                                <img src="{{ $avatar($t->first->user) }}" alt="" class="h-6 w-6 rounded-full object-cover">
                                <span class="truncate">{{ $t->first->user->name }}</span>
                            @else
                                <span class="muted">{{ __('dashboard.overview.nobody') }}</span>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Leaderboard + completions per day --}}
    <section class="grid gap-4 lg:grid-cols-2">
        <div class="card p-5">
            <h3 class="mb-4 flex items-center gap-2 font-semibold"><i data-lucide="users" class="h-4 w-4 muted"></i>{{ __('dashboard.leaderboard.title') }}</h3>
            @forelse($leaderboard as $row)
                @php $u = $row->user; @endphp
                <div class="flex items-center gap-3 py-2.5">
                    <span class="grid h-6 w-6 place-items-center">
                        @if($loop->first)<i data-lucide="medal" class="h-5 w-5" style="color: var(--gold)"></i>@else<span class="num muted text-lg">{{ $loop->iteration }}</span>@endif
                    </span>
                    <img src="{{ $avatar($u) }}" alt="" class="h-10 w-10 rounded-full object-cover">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-baseline justify-between gap-2">
                            <p class="truncate text-sm font-medium">{{ $u->name }}</p>
                            <p class="num text-lg font-semibold">{{ $row->total }}</p>
                        </div>
                        <p class="muted truncate text-xs">{{ $u->email }}</p>
                        <div class="mt-1.5 h-1.5 overflow-hidden rounded-full" style="background: var(--line)">
                            <div class="hbar h-full rounded-full" style="width: {{ round($row->total / $maxLead * 100) }}%; background: var(--c1)"></div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="muted py-6 text-center text-sm">{{ __('dashboard.leaderboard.empty') }}</p>
            @endforelse
        </div>

        <div class="card p-5">
            <h3 class="mb-4 flex items-center gap-2 font-semibold"><i data-lucide="bar-chart-3" class="h-4 w-4 muted"></i>{{ __('dashboard.daily.title') }}</h3>
            <div class="flex h-48 items-end gap-2">
                @foreach($daily as $d)
                    <div class="flex h-full flex-1 flex-col items-center justify-end gap-1.5">
                        <span class="num text-sm font-semibold">{{ $d['count'] }}</span>
                        <div class="bar w-full rounded-t-md" title="{{ $d['label'] }}: {{ $d['count'] }}" style="height: {{ max(4, round($d['count'] / $maxDaily * 100)) }}%; background: {{ $d['count'] ? 'var(--c1)' : 'var(--line)' }}"></div>
                        <span class="muted text-xs">{{ $d['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Every asset ID --}}
    <section id="assets" class="card scroll-mt-4 p-5">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h2 class="num text-xl font-semibold">{{ __('dashboard.assets.title') }}</h2>
            <p id="assets-count" class="muted text-sm" data-template="{{ __('dashboard.assets.count', ['shown' => ':shown', 'total' => ':total']) }}"></p>
        </div>

        <div class="mb-4 flex flex-wrap gap-2">
            <div class="relative min-w-48 flex-1">
                <i data-lucide="search" class="pointer-events-none absolute start-3 top-1/2 h-4 w-4 -translate-y-1/2 muted"></i>
                <input id="f-search" type="search" placeholder="{{ __('dashboard.assets.search') }}" class="w-full rounded-lg border bg-transparent py-2 ps-9 pe-3 text-sm" style="border-color: var(--line)">
            </div>
            <select id="f-type" class="rounded-lg border bg-[var(--card)] px-3 py-2 text-sm" style="border-color: var(--line)">
                <option value="all">{{ __('dashboard.assets.all_types') }}</option>
                @foreach($types as $t)<option value="{{ $t->type }}">{{ __("dashboard.types.{$t->type}") }} ({{ $t->total }})</option>@endforeach
            </select>
            <select id="f-status" class="rounded-lg border bg-[var(--card)] px-3 py-2 text-sm" style="border-color: var(--line)">
                <option value="all">{{ __('dashboard.assets.all_status') }}</option>
                @foreach($statusMeta as $s => $m)<option value="{{ $s }}">{{ __("dashboard.status.$s") }}</option>@endforeach
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-sm">
                <thead class="muted text-start">
                    <tr class="border-b" style="border-color: var(--line)">
                        <th class="py-2 pe-3 text-start font-medium">{{ __('dashboard.assets.col_asset') }}</th>
                        <th class="px-3 text-start font-medium">{{ __('dashboard.assets.col_type') }}</th>
                        <th class="px-3 text-start font-medium">{{ __('dashboard.assets.col_status') }}</th>
                        <th class="px-3 text-start font-medium">{{ __('dashboard.assets.col_progress') }}</th>
                        <th class="ps-3 text-start font-medium">{{ __('dashboard.assets.col_by') }}</th>
                    </tr>
                </thead>
                <tbody id="assets-body">
                    @foreach($assets as $a)
                        @php [$sIcon, $sVar] = $statusMeta[$a->status]; @endphp
                        <tr class="border-b last:border-0" style="border-color: var(--line)"
                            data-type="{{ $a->type }}" data-status="{{ $a->status }}" data-search="{{ strtolower($a->asset_id . ' ' . $a->description) }}">
                            <td class="py-3 pe-3">
                                <p class="num text-base font-semibold">{{ $a->asset_id }}</p>
                                @if($a->description)<p class="muted max-w-56 truncate text-xs">{{ $a->description }}</p>@endif
                            </td>
                            <td class="px-3"><span class="inline-flex items-center gap-1.5"><i data-lucide="{{ $typeIcons[$a->type] ?? 'wrench' }}" class="h-4 w-4 muted"></i>{{ __("dashboard.types.{$a->type}") }}</span></td>
                            <td class="px-3">
                                <span class="tint inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold" style="--t: var({{ $sVar }})">
                                    <i data-lucide="{{ $sIcon }}" class="h-3.5 w-3.5"></i>{{ __("dashboard.status.{$a->status}") }}
                                </span>
                            </td>
                            <td class="px-3">
                                @if($a->status === 'pending')
                                    <span class="muted text-xs">{{ __('dashboard.assets.not_started') }}</span>
                                @else
                                    <div class="flex items-center gap-2">
                                        <div class="h-1.5 w-24 overflow-hidden rounded-full" style="background: var(--line)">
                                            <div class="hbar h-full rounded-full" style="width: {{ $a->pct }}%; background: var({{ $a->is_done ? '--c3' : '--c1' }})"></div>
                                        </div>
                                        <span class="num text-sm">{{ $a->q_total ? $a->q_done . '/' . $a->q_total : $a->pct . '%' }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="ps-3">
                                @if($a->user)
                                    <span class="inline-flex items-center gap-2"><img src="{{ $avatar($a->user) }}" alt="" class="h-6 w-6 rounded-full object-cover"><span class="max-w-32 truncate">{{ $a->user->name }}</span></span>
                                @else<span class="muted">—</span>@endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p id="assets-none" class="muted hidden py-8 text-center text-sm">{{ __('dashboard.assets.none') }}</p>
        <div class="mt-4 text-center">
            <button type="button" id="assets-more" class="card px-4 py-2 text-sm font-medium">{{ __('dashboard.assets.show_more') }}</button>
        </div>
    </section>
    @endif
</main>

<script>
    lucide.createIcons();

    // Theme toggle (saved choice applied in <head>)
    const root = document.documentElement;
    document.getElementById('theme').addEventListener('click', () => {
        root.dataset.theme = root.dataset.theme === 'dark' ? 'light' : 'dark';
        localStorage.setItem('theme', root.dataset.theme);
    });

    // Entrance: fill rings/bars, then count numbers up once
    const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
    requestAnimationFrame(() => requestAnimationFrame(() => document.getElementById('app').classList.add('ready')));
    if (!reduce) {
        document.querySelectorAll('[data-count]').forEach(el => {
            const target = +el.dataset.count, start = performance.now(), dur = 900;
            el.textContent = 0;
            const tick = now => {
                const p = Math.min((now - start) / dur, 1);
                el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3)));
                if (p < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        });
    }

    // Asset ID list: search + type + status filters, "show more" paging
    (() => {
        const rows = [...document.querySelectorAll('#assets-body tr')];
        if (!rows.length) return;
        const $ = id => document.getElementById(id);
        const STEP = 15; let limit = STEP;

        function apply() {
            const q = $('f-search').value.trim().toLowerCase(), type = $('f-type').value, status = $('f-status').value;
            let matched = 0, shown = 0;
            rows.forEach(r => {
                const ok = (type === 'all' || r.dataset.type === type)
                        && (status === 'all' || r.dataset.status === status)
                        && r.dataset.search.includes(q);
                if (ok) matched++;
                const visible = ok && matched <= limit;
                r.hidden = !visible;
                if (visible) shown++;
            });
            $('assets-count').textContent = $('assets-count').dataset.template.replace(':shown', shown).replace(':total', matched);
            $('assets-more').parentElement.hidden = matched <= limit;
            $('assets-none').classList.toggle('hidden', matched > 0);
        }

        ['f-search', 'f-type', 'f-status'].forEach(id => $(id).addEventListener('input', () => { limit = STEP; apply(); }));
        $('assets-more').addEventListener('click', () => { limit += STEP; apply(); });

        // Clicking a type card filters the list and scrolls to it
        document.querySelectorAll('[data-filter-type]').forEach(btn => btn.addEventListener('click', () => {
            $('f-type').value = btn.dataset.filterType; limit = STEP; apply();
            $('assets').scrollIntoView({ behavior: reduce ? 'auto' : 'smooth' });
        }));

        apply();
    })();
</script>
</body>
</html>
