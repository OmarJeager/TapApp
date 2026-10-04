<x-app-layout>

    <div class="weeks-container">

        <div class="page-header">
            <div>
                <h1>PPM Weeks</h1>
                <p>Publish or hide complete PPM weeks for users.</p>
            </div>

            <a href="{{ route('admin.index') }}" class="back-btn">
                ← Back
            </a>
        </div>

        {{-- Success message --}}
        @if(session('success'))
            <div class="success-message">
                <span>✓</span>
                {{ session('success') }}
            </div>
        @endif


        <div class="weeks-card">

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>Week Due</th>
                            <th>PPM Records</th>
                            <th>Status</th>
                            <th>Published At</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($weeks as $week)

                            <tr>

                                <td>
                                    <strong>
                                        Week {{ $week['week_due'] }}
                                    </strong>
                                </td>

                                <td>
                                    <span class="record-count">
                                        {{ $week['record_count'] }}
                                    </span>
                                </td>

                                <td>

                                    @if($week['is_published'])

                                        <span class="status published">
                                            ● Published
                                        </span>

                                    @else

                                        <span class="status hidden">
                                            ● Hidden
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    @if($week['published_at'])
                                        {{ $week['published_at']->format('d/m/Y H:i') }}
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.ppm-weeks.toggle',
                                            $week['week_due']
                                        ) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        @if($week['is_published'])

                                            <button
                                                type="submit"
                                                class="week-btn hide-btn"
                                            >
                                                👁 Hide Week
                                            </button>

                                        @else

                                            <button
                                                type="submit"
                                                class="week-btn publish-btn"
                                            >
                                                ✓ Publish Week
                                            </button>

                                        @endif

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="empty">
                                    No PPM weeks found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <style>

        .weeks-container {
            width: 95%;
            max-width: 1400px;
            margin: 40px auto;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
        }

        .page-header p {
            color: #777;
            margin-top: 5px;
        }

        .back-btn {
            background: #333;
            color: white;
            padding: 9px 15px;
            border-radius: 7px;
            text-decoration: none;
            transition: .25s;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            background: #111;
        }

        .weeks-card {
            background: white;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,.08);
            overflow: hidden;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 14px;
            background: #f7f7f7;
            font-size: 13px;
            text-transform: uppercase;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        tr {
            transition: .2s;
        }

        tbody tr:hover {
            background: #fffaf5;
        }

        .record-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            min-width: 32px;
            height: 28px;

            padding: 0 8px;

            background: #f97316;
            color: white;

            border-radius: 20px;
            font-weight: 700;
        }

        .status {
            font-size: 13px;
            font-weight: 700;
        }

        .status.published {
            color: #16a34a;
        }

        .status.hidden {
            color: #dc2626;
        }

        .week-btn {
            border: none;
            color: white;
            padding: 8px 13px;
            border-radius: 7px;
            cursor: pointer;
            font-weight: 600;
            transition: all .25s ease;
        }

        .publish-btn {
            background: #f97316;
        }

        .hide-btn {
            background: #dc2626;
        }

        .week-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(0,0,0,.18);
        }

        .week-btn:active {
            transform: scale(.96);
        }

        .success-message {
            display: flex;
            gap: 10px;
            align-items: center;

            background: #ecfdf5;
            color: #047857;

            border: 1px solid #a7f3d0;

            padding: 12px 15px;
            border-radius: 8px;

            margin-bottom: 20px;

            animation: slideDown .35s ease;
        }

        .success-message span {
            font-size: 20px;
            font-weight: bold;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #777;
        }

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

        @media(max-width: 700px) {

            .weeks-container {
                width: 94%;
                margin: 20px auto;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .weeks-card {
                padding: 10px;
            }

            th,
            td {
                padding: 10px;
                white-space: nowrap;
            }

        }

    </style>

</x-app-layout>
