<?php

namespace App\Http\Controllers;

use App\Models\PpmRecord;
use App\Models\PpmWeekControl;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /** Display order of asset types (PNL and TST are the featured ones). */
    private const TYPE_ORDER = ['pnl', 'tst', 'khm', 'cons', 'generic'];

    public function index(Request $request)
    {
        $week = $request->input(
            'week',
            PpmWeekControl::where('is_published', true)->latest('published_at')->value('week_due')
                ?? PpmWeekControl::max('week_due')
        );

        // One query: every PPM record of the week + its checklist + question/answer counts
        $records = PpmRecord::query()
            ->when($week, fn ($q) => $q->where('week_due', $week))
            ->with(['checklist' => fn ($q) => $q
                ->withCount([
                    'questions',
                    'answers as answered_count' => fn ($a) => $a->whereNotNull('response')->where('response', '<>', ''),
                ])
                ->with('completedBy')])
            ->orderBy('asset_id')
            ->get();

        // ---- One normalized row per record ---------------------------------
        $assets = $records->map(function ($r) {
            $c       = $r->checklist;
            $qTotal  = (int) ($c->questions_count ?? 0);
            $qDone   = (int) ($c->answered_count ?? 0);
            $isDone  = (bool) ($c && $c->completed_at);

            $status = match (true) {
                (bool) ($c && $c->verified_quality_at) => 'quality',
                (bool) ($c && $c->verified_at)         => 'verified',
                $isDone                                => 'completed',
                (bool) $c                              => 'in_progress',
                default                                => 'pending',
            };

            return (object) [
                'asset_id'       => $r->asset_id,
                'description'    => $r->asset_description ?: $r->brief_description,
                'type'           => $r->asset_prefix,
                'status'         => $status,
                'is_done'        => $isDone,
                'q_done'         => $qDone,
                'q_total'        => $qTotal,
                'pct'            => $qTotal ? min(100, (int) round($qDone / $qTotal * 100)) : ($isDone ? 100 : 0),
                // finished the checklist AND answered every question
                'fully_answered' => $isDone && ($qTotal === 0 || $qDone >= $qTotal),
                'user'           => $c?->completedBy,
                'finished_at'    => $c?->end_time,
                'completed_at'   => $c?->completed_at,
                'minutes'        => $c?->total_time_minutes,
            ];
        });

        $firstOf = fn ($group) => $group
            ->filter(fn ($a) => $a->fully_answered && $a->finished_at && $a->user)
            ->sortBy('finished_at')
            ->first();

        // ---- Progress per asset type ---------------------------------------
        $types = $assets->groupBy('type')->map(function ($g, $type) use ($firstOf) {
            $total      = $g->count();
            $completed  = $g->where('is_done', true)->count();
            $inProgress = $g->where('status', 'in_progress')->count();
            $pct        = fn ($n) => $total ? round($n / $total * 100, 1) : 0;

            return (object) [
                'type'            => $type,
                'total'           => $total,
                'asset_ids'       => $g->pluck('asset_id')->unique()->count(),
                'completed'       => $completed,
                'pending'         => $total - $completed,       // everything not completed yet
                'in_progress'     => $inProgress,               // part of "pending": started, not finished
                'verified'        => $g->whereIn('status', ['verified', 'quality'])->count(),
                'quality'         => $g->where('status', 'quality')->count(),
                'completed_pct'   => $pct($completed),
                'in_progress_pct' => $pct($inProgress),
                'first'           => $firstOf($g),
            ];
        })
            ->sortBy(fn ($t) => array_search($t->type, self::TYPE_ORDER))
            ->values();

        // ---- Global numbers -------------------------------------------------
        $kpis = [
            'assets'      => $assets->pluck('asset_id')->unique()->count(),
            'total'       => $assets->count(),
            'completed'   => $assets->where('is_done', true)->count(),
            'verified'    => $assets->whereIn('status', ['verified', 'quality'])->count(),
            'quality'     => $assets->where('status', 'quality')->count(),
            'avg_minutes' => (int) round($assets->where('is_done', true)->avg('minutes') ?? 0),
        ];
        $kpis['pending'] = $kpis['total'] - $kpis['completed'];

        $waiting = [
            'admin'   => $assets->where('status', 'completed')->count(),
            'quality' => $assets->where('status', 'verified')->count(),
        ];

        $firstFinisher = $firstOf($assets);

        $leaderboard = $assets
            ->filter(fn ($a) => $a->is_done && $a->user)
            ->groupBy(fn ($a) => $a->user->matricule)
            ->map(fn ($g) => (object) ['user' => $g->first()->user, 'total' => $g->count()])
            ->sortByDesc('total')->take(5)->values();

        // ---- Completions per day -------------------------------------------
        $daily = collect();
        if ($week) {
            $start  = Carbon::now()->setISODate((int) substr($week, 0, 4), (int) substr($week, 4, 2))->startOfWeek();
            $counts = $assets->filter(fn ($a) => $a->completed_at)
                ->countBy(fn ($a) => $a->completed_at->toDateString());

            for ($i = 0; $i < 7; $i++) {
                $day = $start->copy()->addDays($i);
                $daily->push([
                    'label' => $day->locale(app()->getLocale())->translatedFormat('D'),
                    'count' => (int) ($counts[$day->toDateString()] ?? 0),
                ]);
            }
        }

        $weeks = PpmWeekControl::orderByDesc('week_due')->pluck('week_due');

        return view('dashboard.dashboard', compact(
            'assets', 'types', 'kpis', 'waiting', 'firstFinisher', 'leaderboard', 'daily', 'week', 'weeks'
        ));
    }
}
