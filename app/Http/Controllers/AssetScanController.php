<?php

namespace App\Http\Controllers;

use App\Models\PpmRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssetScanController extends Controller
{
    /**
     * GET /scan  -> scan page
     */
    public function index()
    {
        return view('user.scan.index');
    }

    /**
     * POST /scan/lookup (AJAX)
     *
     * Accepts what the ticket barcode contains:
     *   - PPM Job ID  (e.g. 4996258)   -> exact record
     *   - Asset ID    (e.g. PNL0000027) -> best record for that asset
     */
    public function lookup(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:60'],
        ]);

        $code = strtoupper(preg_replace('/\s+/', '', $data['code']));

        $record = $this->resolveRecord($code);

        if (!$record) {
            return response()->json([
                'ok'      => false,
                'message' => "No PPM record found for \"{$code}\".",
            ], 404);
        }

        $last = $record->lastDoneChecklist();

        $url = $record->asset_prefix === 'pnl'
            ? route('ppm-checklists.pnl.create', $record)
            : route('ppm-records.form', $record);

        return response()->json([
            'ok'  => true,
            'url' => $url,
            'record' => [
                'asset_id'          => $record->asset_id,
                'asset_description' => $record->asset_description,
                'job_id'            => $record->job_id,
                'frequency'         => $record->frequency,
                'frequency_label'   => $record->frequency_label,
                'intervention_week' => $record->intervention_week, // 202641 -> 41/26
                'next_pm_week'      => $record->next_pm_week,      // freq 4 -> 45/26
                'already_done'      => $record->checklist()->exists(),
            ],
            'last_done' => $last ? [
                'date'              => optional($last->completed_at)->format('d/m/Y'),
                'days_ago'          => optional($last->completed_at)->diffInDays(now()),
                'week'              => optional($last->ppmRecord)->intervention_week,
                'job_id'            => optional($last->ppmRecord)->job_id,
                'by'                => optional($last->completedBy)->name
                                        ?? $last->completed_by_matricule,
            ] : null,
        ]);
    }

    /**
     * Pick the right record for a scanned code.
     */
    private function resolveRecord(string $code): ?PpmRecord
    {
        // 1) Ticket barcode = Job ID -> exact record
        $byJob = PpmRecord::where('job_id', $code)->first();
        if ($byJob) {
            return $byJob;
        }

        // 2) Asset ID -> same asset has many records (different week/job)
        $currentWeek = (int) now()->format('oW'); // ISO year + week, e.g. 202641

        $base = PpmRecord::whereRaw('UPPER(TRIM(asset_id)) = ?', [$code]);

        return
            // oldest pending record that is due now or overdue
            (clone $base)->doesntHave('checklist')
                ->where('week_due', '<=', $currentWeek)
                ->orderBy('week_due')
                ->first()
            // otherwise the next upcoming pending record
            ?? (clone $base)->doesntHave('checklist')
                ->orderBy('week_due')
                ->first()
            // otherwise the most recent one (already done -> open to view)
            ?? (clone $base)->orderByDesc('week_due')->first();
    }
}