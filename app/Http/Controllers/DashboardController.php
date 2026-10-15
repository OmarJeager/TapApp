<?php

namespace App\Http\Controllers;

use App\Models\PpmRecord;
use App\Models\PpmWeekControl;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Featured asset types appear first in the dashboard.
     */
    private const TYPE_ORDER = ['pnl', 'tst', 'khm', 'cons', 'generic'];

    public function index(Request $request)
    {
        // ---------------------------------------------------------
        // 1. Selected week
        // ---------------------------------------------------------
        $week = $request->input(
            'week',
            PpmWeekControl::where('is_published', true)
                ->latest('published_at')
                ->value('week_due')
                ?? PpmWeekControl::max('week_due')
        );

        // ---------------------------------------------------------
        // 2. Global application statistics
        // ---------------------------------------------------------

        // Total registered users
        $totalUsers = User::count();

        // Optional breakdown by role
        $usersByRole = User::query()
            ->selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->orderBy('role')
            ->get()
            ->keyBy('role');

        // All PPM records, regardless of the selected week
        $ppmTotalRecords = PpmRecord::count();

        // Number of distinct asset IDs across all PPM records
        $ppmUniqueAssets = PpmRecord::query()
            ->whereNotNull('asset_id')
            ->where('asset_id', '<>', '')
            ->distinct()
            ->count('asset_id');

        // Count PPM records by asset prefix across all weeks.
        // asset_prefix is assumed to be an accessor on PpmRecord.
        $allRecords = PpmRecord::query()
            ->select(['id', 'asset_id', 'asset_description', 'brief_description'])
            ->get();

        $ppmTypes = $allRecords
            ->groupBy(function ($record) {
                $prefix = strtolower(trim((string) $record->asset_prefix));

                return $prefix !== '' ? $prefix : 'generic';
            })
            ->map(function ($group, $type) {
                return (object) [
                    'type'       => $type,
                    'label'      => strtoupper($type),
                    'records'    => $group->count(),
                    'asset_ids'  => $group
                        ->pluck('asset_id')
                        ->filter(fn ($id) => trim((string) $id) !== '')
                        ->unique()
                        ->count(),
                ];
            })
            ->sortBy(function ($item) {
                $position = array_search($item->type, self::TYPE_ORDER, true);

                return $position === false
                    ? count(self::TYPE_ORDER)
                    : $position;
            })
            ->values();

        // ---------------------------------------------------------
        // 3. PPM records for the selected week
        // ---------------------------------------------------------
        $records = PpmRecord::query()
            ->when(
                $week,
                fn ($query) => $query->where('week_due', $week)
            )
            ->with([
                'checklist' => fn ($query) => $query
                    ->withCount([
                        'questions',
                        'answers as answered_count' => fn ($answers) => $answers
                            ->whereNotNull('response')
                            ->where('response', '<>', ''),
                    ])
                    ->with('completedBy'),
            ])
            ->orderBy('asset_id')
            ->get();

        // ---------------------------------------------------------
        // 4. Normalize each record for dashboard calculations
        // ---------------------------------------------------------
        $assets = $records->map(function ($record) {
            $checklist = $record->checklist;

            $questionTotal = (int) ($checklist->questions_count ?? 0);
            $questionDone  = (int) ($checklist->answered_count ?? 0);
            $isDone        = (bool) ($checklist && $checklist->completed_at);

            $status = match (true) {
                (bool) ($checklist && $checklist->verified_quality_at)
                    => 'quality',

                (bool) ($checklist && $checklist->verified_at)
                    => 'verified',

                $isDone => 'completed',

                (bool) $checklist => 'in_progress',

                default => 'pending',
            };

            return (object) [
                'asset_id'       => $record->asset_id,
                'description'    => $record->asset_description
                    ?: $record->brief_description,
                'type'           => $record->asset_prefix,
                'status'         => $status,
                'is_done'        => $isDone,
                'q_done'         => $questionDone,
                'q_total'        => $questionTotal,

                'pct' => $questionTotal
                    ? min(100, (int) round(
                        $questionDone / $questionTotal * 100
                    ))
                    : ($isDone ? 100 : 0),

                'fully_answered' => $isDone
                    && ($questionTotal === 0 || $questionDone >= $questionTotal),

                'user'         => $checklist?->completedBy,
                'finished_at'  => $checklist?->end_time,
                'completed_at' => $checklist?->completed_at,
                'minutes'      => $checklist?->total_time_minutes,
            ];
        });

        // ---------------------------------------------------------
        // 5. First fully answered completion
        // ---------------------------------------------------------
        $firstOf = fn ($group) => $group
            ->filter(fn ($asset) =>
                $asset->fully_answered
                && $asset->finished_at
                && $asset->user
            )
            ->sortBy('finished_at')
            ->first();

        // ---------------------------------------------------------
        // 6. Progress per asset type for the selected week
        // ---------------------------------------------------------
        $types = $assets
            ->groupBy(function ($asset) {
                $type = strtolower(trim((string) $asset->type));

                return $type !== '' ? $type : 'generic';
            })
            ->map(function ($group, $type) use ($firstOf) {
                $total      = $group->count();
                $completed  = $group->where('is_done', true)->count();
                $inProgress = $group->where('status', 'in_progress')->count();

                $percentage = fn ($number) => $total
                    ? round($number / $total * 100, 1)
                    : 0;

                return (object) [
                    'type'            => $type,
                    'total'           => $total,
                    'asset_ids'       => $group->pluck('asset_id')->filter()
                        ->unique()->count(),
                    'completed'       => $completed,
                    'pending'         => $total - $completed,
                    'in_progress'     => $inProgress,
                    'verified'        => $group
                        ->whereIn('status', ['verified', 'quality'])->count(),
                    'quality'         => $group->where('status', 'quality')->count(),
                    'completed_pct'   => $percentage($completed),
                    'in_progress_pct' => $percentage($inProgress),
                    'first'            => $firstOf($group),
                ];
            })
            ->sortBy(function ($item) {
                $position = array_search($item->type, self::TYPE_ORDER, true);

                return $position === false
                    ? count(self::TYPE_ORDER)
                    : $position;
            })
            ->values();

        // ---------------------------------------------------------
        // 7. Global KPIs for the selected week
        // ---------------------------------------------------------
        $kpis = [
            'assets' => $assets
                ->pluck('asset_id')
                ->filter()
                ->unique()
                ->count(),

            'total' => $assets->count(),

            'completed' => $assets
                ->where('is_done', true)
                ->count(),

            'verified' => $assets
                ->whereIn('status', ['verified', 'quality'])
                ->count(),

            'quality' => $assets
                ->where('status', 'quality')
                ->count(),

            'avg_minutes' => (int) round(
                $assets->where('is_done', true)->avg('minutes') ?? 0
            ),
        ];

        $kpis['pending'] = $kpis['total'] - $kpis['completed'];

        // ---------------------------------------------------------
        // 8. Items waiting for verification
        // ---------------------------------------------------------
        $waiting = [
            'admin' => $assets
                ->where('status', 'completed')
                ->count(),

            'quality' => $assets
                ->where('status', 'verified')
                ->count(),
        ];

        // ---------------------------------------------------------
        // 9. First finisher
        // ---------------------------------------------------------
        $firstFinisher = $firstOf($assets);

        // ---------------------------------------------------------
        // 10. Leaderboard
        // ---------------------------------------------------------
        $leaderboard = $assets
            ->filter(fn ($asset) => $asset->is_done && $asset->user)
            ->groupBy(fn ($asset) => $asset->user->matricule)
            ->map(fn ($group) => (object) [
                'user'  => $group->first()->user,
                'total' => $group->count(),
            ])
            ->sortByDesc('total')
            ->take(5)
            ->values();

        // ---------------------------------------------------------
        // 11. Completions per day
        // ---------------------------------------------------------
        $daily = collect();

        if ($week && preg_match('/^\d{6}$/', (string) $week)) {
            $year = (int) substr((string) $week, 0, 4);
            $weekNumber = (int) substr((string) $week, 4, 2);

            if ($weekNumber >= 1 && $weekNumber <= 53) {
                $start = Carbon::now()
                    ->setISODate($year, $weekNumber)
                    ->startOfWeek();

                $counts = $assets
                    ->filter(fn ($asset) => $asset->completed_at)
                    ->countBy(fn ($asset) =>
                        Carbon::parse($asset->completed_at)->toDateString()
                    );

                for ($i = 0; $i < 7; $i++) {
                    $day = $start->copy()->addDays($i);

                    $daily->push([
                        'label' => $day
                            ->locale(app()->getLocale())
                            ->translatedFormat('D'),

                        'count' => (int) ($counts[$day->toDateString()] ?? 0),
                    ]);
                }
            }
        }

        // ---------------------------------------------------------
        // 12. Available weeks
        // ---------------------------------------------------------
        $weeks = PpmWeekControl::query()
            ->orderByDesc('week_due')
            ->pluck('week_due');

        // ---------------------------------------------------------
        // 13. Send all statistics to the dashboard view
        // ---------------------------------------------------------
        return view('dashboard.dashboard', compact(
            'assets',
            'types',
            'kpis',
            'waiting',
            'firstFinisher',
            'leaderboard',
            'daily',
            'week',
            'weeks',
            'totalUsers',
            'usersByRole',
            'ppmTotalRecords',
            'ppmUniqueAssets',
            'ppmTypes'
        ));
    }
}
