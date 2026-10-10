<?php

namespace App\Http\Controllers;

use App\Models\PpmRecord;
use App\Models\PpmWeekControl;
use Illuminate\Support\Facades\DB;

class LandingPageController extends Controller
{
    public function index()
    {
        $totalPpm = PpmRecord::count();

        $publishedWeeks = PpmWeekControl::where('is_published', true)
            ->count();

        /*
         * Count actual checklist instances, not checklist questions.
         * Assumes ppm_checklists.ppm_records_id references ppm_records.id.
         */
        $checklists = DB::table('ppm_checklists')
            ->join(
                'ppm_records',
                'ppm_records.id',
                '=',
                'ppm_checklists.ppm_records_id'
            );

        $checklistCounts = (clone $checklists)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN ppm_records.asset_id LIKE 'PNL%'
                    THEN 1 ELSE 0 END) as pnl,
                SUM(CASE WHEN ppm_records.asset_id LIKE 'KHM%'
                    OR ppm_records.asset_id LIKE 'TRQ%'
                    THEN 1 ELSE 0 END) as khm,
                SUM(CASE WHEN ppm_records.asset_id LIKE 'TST%'
                    THEN 1 ELSE 0 END) as tst,
                SUM(CASE WHEN ppm_records.asset_id LIKE 'TST%'
                    AND ppm_records.frequency = 1
                    THEN 1 ELSE 0 END) as tst_frequency_1,
                SUM(CASE WHEN ppm_records.asset_id LIKE 'TST%'
                    AND ppm_records.frequency = 4
                    THEN 1 ELSE 0 END) as tst_frequency_4
            ")
            ->first();

        $completedChecklists = (clone $checklists)
            ->whereNotNull('ppm_checklists.completed_at')
            ->count();

        $totalChecklists = (int) ($checklistCounts->total ?? 0);

        $pendingChecklists = max(
            0,
            $totalChecklists - $completedChecklists
        );

        return view('welcome', [
            'totalPpm' => $totalPpm,
            'publishedWeeks' => $publishedWeeks,

            'totalChecklists' => $totalChecklists,
            'pnlChecklists' => (int) ($checklistCounts->pnl ?? 0),
            'khmChecklists' => (int) ($checklistCounts->khm ?? 0),
            'tstChecklists' => (int) ($checklistCounts->tst ?? 0),

            'tstFrequency1' => (int) (
                $checklistCounts->tst_frequency_1 ?? 0
            ),

            'tstFrequency4' => (int) (
                $checklistCounts->tst_frequency_4 ?? 0
            ),

            'completedChecklists' => $completedChecklists,
            'pendingChecklists' => $pendingChecklists,

            'completionRate' => $totalChecklists > 0
                ? round(
                    ($completedChecklists / $totalChecklists) * 100
                )
                : 0,
        ]);
    }
}
