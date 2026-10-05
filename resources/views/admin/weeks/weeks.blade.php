<x-app-layout>

    <div class="weeks-container">

        {{-- HEADER --}}
        <div class="page-header">

            <div>
                <div class="title-row">
                    <span class="title-icon">📅</span>

                    <div>
                        <h1>PPM Week Management</h1>

                        <p>
                            Manage, publish and track PPM weeks.
                        </p>
                    </div>
                </div>
            </div>

            <a
                href="{{ route('admin.index') }}"
                class="back-btn"
            >
                ← Back
            </a>

        </div>


        {{-- SUCCESS --}}
        @if(session('success'))

            <div class="alert success-alert">

                <span class="alert-icon">✓</span>

                <span>
                    {{ session('success') }}
                </span>

            </div>

        @endif


        {{-- ERROR --}}
        @if(session('error'))

            <div class="alert error-alert">

                <span class="alert-icon">!</span>

                <span>
                    {{ session('error') }}
                </span>

            </div>

        @endif


        {{-- STATISTICS --}}
        <div class="stats-grid">

            <div class="stat-card">
                <div class="stat-icon">📅</div>

                <div>
                    <span>Total Weeks</span>
                    <strong>{{ $weeks->count() }}</strong>
                </div>
            </div>


            <div class="stat-card">
                <div class="stat-icon green">✓</div>

                <div>
                    <span>Published</span>

                    <strong>
                        {{ $weeks->where('status', 'published')->count() }}
                    </strong>
                </div>
            </div>


            <div class="stat-card">
                <div class="stat-icon orange">◷</div>

                <div>
                    <span>Draft</span>

                    <strong>
                        {{ $weeks->where('status', 'draft')->count() }}
                    </strong>
                </div>
            </div>


            <div class="stat-card">
                <div class="stat-icon blue">✓</div>

                <div>
                    <span>Completed</span>

                    <strong>
                        {{ $weeks->where('status', 'completed')->count() }}
                    </strong>
                </div>
            </div>

        </div>


        {{-- WEEKS TABLE --}}
        <div class="weeks-card">

            <div class="card-header">

                <div>
                    <h2>PPM Weeks</h2>

                    <p>
                        Publishing a week makes all its PNL, TST and KHM
                        records available to users.
                    </p>
                </div>

            </div>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>
                            <th>Week</th>

                            <th>Records</th>

                            <th>Progress</th>

                            <th>Status</th>

                            <th>Published</th>

                            <th>Last Pushed</th>

                            <th>Actions</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse($weeks as $week)

                            <tr>

                                {{-- WEEK --}}
                                <td>

                                    <div class="week-number">

                                        <span class="calendar-icon">
                                            📅
                                        </span>

                                        <strong>
                                            Week {{ $week['week_due'] }}
                                        </strong>

                                    </div>

                                </td>


                                {{-- RECORD COUNT --}}
                                <td>

                                    <span class="record-count">
                                        {{ $week['record_count'] }}
                                    </span>

                                </td>


                                {{-- PROGRESS --}}
                                <td>

                                    <div class="progress-wrapper">

                                        <div class="progress-top">

                                            <span>
                                                {{ $week['progress'] }}%
                                            </span>

                                            <small>
                                                {{ $week['completed_count'] }}
                                                /
                                                {{ $week['record_count'] }}
                                            </small>

                                        </div>


                                        <div class="progress-bar">

                                            <div
                                                class="progress-fill"
                                                style="width: {{ $week['progress'] }}%"
                                            ></div>

                                        </div>

                                    </div>

                                </td>


                                {{-- STATUS --}}
                                <td>

                                    @if($week['status'] === 'draft')

                                        <span class="status draft">
                                            ● Draft
                                        </span>

                                    @elseif($week['status'] === 'published')

                                        <span class="status published">
                                            ● Published
                                        </span>

                                    @elseif($week['status'] === 'completed')

                                        <span class="status completed">
                                            ● Completed
                                        </span>

                                    @elseif($week['status'] === 'archived')

                                        <span class="status archived">
                                            ● Archived
                                        </span>

                                    @else

                                        <span class="status">
                                            {{ ucfirst($week['status']) }}
                                        </span>

                                    @endif

                                </td>


                                {{-- PUBLISHED AT --}}
                                <td>

                                    @if($week['published_at'])

                                        <div class="date-info">

                                            <strong>
                                                {{ $week['published_at']->format('d/m/Y') }}
                                            </strong>

                                            <small>
                                                {{ $week['published_at']->format('H:i') }}
                                            </small>

                                        </div>

                                    @else

                                        <span class="not-available">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- LAST PUSHED --}}
                                <td>

                                    @if($week['last_pushed_at'])

                                        <div class="date-info">

                                            <strong>
                                                {{ $week['last_pushed_at']->format('d/m/Y') }}
                                            </strong>

                                            <small>
                                                {{ $week['last_pushed_at']->format('H:i') }}
                                            </small>

                                        </div>

                                    @else

                                        <span class="not-available">
                                            Never
                                        </span>

                                    @endif

                                </td>


                                {{-- ACTIONS --}}
                                <td>

                                    <div class="actions">


                                        {{-- DRAFT --}}
                                        @if($week['status'] === 'draft')

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.ppm-weeks.publish',
                                                    $week['week_due']
                                                ) }}"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="action-btn publish"
                                                    onclick="return confirm(
                                                        'Publish Week {{ $week['week_due'] }} for users?'
                                                    )"
                                                >
                                                    ✓ Publish
                                                </button>

                                            </form>


                                        {{-- PUBLISHED --}}
                                        @elseif($week['status'] === 'published')

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.ppm-weeks.push',
                                                    $week['week_due']
                                                ) }}"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="action-btn push"
                                                    onclick="return confirm(
                                                        'Push Week {{ $week['week_due'] }} again?'
                                                    )"
                                                >
                                                    ↻ Push Again
                                                </button>

                                            </form>


                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.ppm-weeks.complete',
                                                    $week['week_due']
                                                ) }}"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="action-btn complete"
                                                    onclick="return confirm(
                                                        'Mark Week {{ $week['week_due'] }} as completed?'
                                                    )"
                                                >
                                                    ✓ Complete
                                                </button>

                                            </form>


                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.ppm-weeks.hide',
                                                    $week['week_due']
                                                ) }}"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="action-btn hide"
                                                    onclick="return confirm(
                                                        'Hide Week {{ $week['week_due'] }} from users?'
                                                    )"
                                                >
                                                    ◉ Hide
                                                </button>

                                            </form>


                                        {{-- COMPLETED --}}
                                        @elseif($week['status'] === 'completed')

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.ppm-weeks.archive',
                                                    $week['week_due']
                                                ) }}"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="action-btn archive"
                                                    onclick="return confirm(
                                                        'Archive Week {{ $week['week_due'] }}?'
                                                    )"
                                                >
                                                    ▣ Archive
                                                </button>

                                            </form>


                                        {{-- ARCHIVED --}}
                                        @elseif($week['status'] === 'archived')

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.ppm-weeks.reopen',
                                                    $week['week_due']
                                                ) }}"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="action-btn reopen"
                                                >
                                                    ↻ Reopen
                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="empty"
                                >

                                    <div class="empty-icon">
                                        📅
                                    </div>

                                    <strong>
                                        No PPM weeks found
                                    </strong>

                                    <p>
                                        Import PPM records to create weeks.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <style>

        * {
            box-sizing: border-box;
        }

        .weeks-container {
            width: 95%;
            max-width: 1500px;
            margin: 35px auto;
        }


        /* HEADER */

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .title-row {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .title-icon {
            width: 52px;
            height: 52px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #fff7ed;
            border: 1px solid #fed7aa;

            border-radius: 13px;

            font-size: 25px;

            animation: floating 3s ease-in-out infinite;
        }

        .page-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            color: #1f2937;
        }

        .page-header p {
            margin: 4px 0 0;
            color: #6b7280;
        }


        .back-btn {
            background: #1f2937;
            color: white;

            padding: 9px 15px;

            border-radius: 8px;

            text-decoration: none;

            transition: .25s;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            background: #111827;
        }


        /* ALERTS */

        .alert {
            display: flex;
            align-items: center;
            gap: 10px;

            padding: 13px 16px;

            border-radius: 9px;

            margin-bottom: 20px;

            animation: slideDown .35s ease;
        }

        .success-alert {
            color: #047857;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
        }

        .error-alert {
            color: #b91c1c;
            background: #fef2f2;
            border: 1px solid #fecaca;
        }

        .alert-icon {
            font-weight: 800;
            font-size: 18px;
        }


        /* STATISTICS */

        .stats-grid {
            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 15px;

            margin-bottom: 22px;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 13px;

            background: white;

            padding: 17px;

            border-radius: 12px;

            box-shadow:
                0 5px 18px rgba(0,0,0,.06);

            transition: .25s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-icon {
            width: 43px;
            height: 43px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 10px;

            background: #fff7ed;

            font-size: 20px;
        }

        .stat-icon.green {
            background: #ecfdf5;
        }

        .stat-icon.orange {
            background: #fff7ed;
        }

        .stat-icon.blue {
            background: #eff6ff;
        }

        .stat-card span {
            display: block;

            color: #6b7280;

            font-size: 12px;
        }

        .stat-card strong {
            display: block;

            font-size: 22px;

            margin-top: 2px;

            color: #1f2937;
        }


        /* MAIN CARD */

        .weeks-card {
            background: white;

            border-radius: 14px;

            box-shadow:
                0 8px 30px rgba(0,0,0,.07);

            overflow: hidden;
        }

        .card-header {
            padding: 20px 22px;

            border-bottom: 1px solid #eee;
        }

        .card-header h2 {
            margin: 0;

            font-size: 19px;

            color: #1f2937;
        }

        .card-header p {
            margin: 5px 0 0;

            color: #6b7280;

            font-size: 13px;
        }


        /* TABLE */

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 1100px;

            border-collapse: collapse;
        }

        th {
            padding: 14px;

            text-align: left;

            background: #f9fafb;

            color: #6b7280;

            font-size: 11px;

            text-transform: uppercase;

            letter-spacing: .04em;
        }

        td {
            padding: 15px 14px;

            border-bottom: 1px solid #f0f0f0;

            color: #374151;

            vertical-align: middle;
        }

        tbody tr {
            transition: .2s;
        }

        tbody tr:hover {
            background: #fffaf5;
        }


        /* WEEK */

        .week-number {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .calendar-icon {
            width: 34px;
            height: 34px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 8px;

            background: #fff7ed;
        }


        /* RECORD COUNT */

        .record-count {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            min-width: 32px;
            height: 28px;

            padding: 0 9px;

            border-radius: 20px;

            background: #f97316;

            color: white;

            font-size: 12px;

            font-weight: 800;
        }


        /* PROGRESS */

        .progress-wrapper {
            width: 130px;
        }

        .progress-top {
            display: flex;
            justify-content: space-between;

            margin-bottom: 5px;

            font-size: 11px;
        }

        .progress-top span {
            font-weight: 800;
        }

        .progress-top small {
            color: #9ca3af;
        }

        .progress-bar {
            width: 100%;
            height: 6px;

            background: #e5e7eb;

            border-radius: 20px;

            overflow: hidden;
        }

        .progress-fill {
            height: 100%;

            background: #f97316;

            border-radius: 20px;

            transition: width .6s ease;
        }


        /* STATUS */

        .status {
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status.draft {
            color: #d97706;
        }

        .status.published {
            color: #16a34a;
        }

        .status.completed {
            color: #2563eb;
        }

        .status.archived {
            color: #6b7280;
        }


        /* DATES */

        .date-info strong {
            display: block;

            font-size: 12px;
        }

        .date-info small {
            display: block;

            color: #9ca3af;

            font-size: 11px;

            margin-top: 2px;
        }

        .not-available {
            color: #9ca3af;
            font-size: 12px;
        }


        /* ACTIONS */

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .action-btn {
            border: none;

            padding: 7px 10px;

            border-radius: 7px;

            color: white;

            font-size: 11px;

            font-weight: 700;

            cursor: pointer;

            transition: all .2s ease;

            white-space: nowrap;
        }

        .action-btn:hover {
            transform: translateY(-2px);

            box-shadow:
                0 4px 10px rgba(0,0,0,.15);
        }

        .action-btn:active {
            transform: scale(.96);
        }

        .publish {
            background: #f97316;
        }

        .push {
            background: #2563eb;
        }

        .complete {
            background: #16a34a;
        }

        .hide {
            background: #dc2626;
        }

        .archive {
            background: #6b7280;
        }

        .reopen {
            background: #7c3aed;
        }


        /* EMPTY */

        .empty {
            text-align: center;

            padding: 60px 20px;

            color: #6b7280;
        }

        .empty-icon {
            font-size: 38px;
            margin-bottom: 8px;
        }

        .empty strong {
            display: block;

            color: #374151;

            font-size: 16px;
        }

        .empty p {
            margin-top: 5px;
        }


        /* ANIMATIONS */

        @keyframes slideDown {

            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }

        @keyframes floating {

            0%, 100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-4px);
            }

        }


        /* MOBILE */

        @media(max-width: 900px) {

            .stats-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }

        }


        @media(max-width: 600px) {

            .weeks-container {
                width: 94%;
                margin: 20px auto;
            }

            .page-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 15px;
            }

            .page-header h1 {
                font-size: 23px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .weeks-card {
                border-radius: 10px;
            }

        }

    </style>

</x-app-layout>
