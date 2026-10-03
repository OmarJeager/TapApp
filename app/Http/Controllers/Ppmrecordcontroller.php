<?php

namespace App\Http\Controllers;
use App\Imports\PpmRecordsImport;
use App\Models\PpmRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PpmRecordController extends Controller
{
     /**
     * Display all PPM records.
     */
    public function index(Request $request)
{
    $query = PpmRecord::query();

    foreach (['week_due', 'job_id', 'frequency', 'asset_description', 'asset_id'] as $field) {
        if ($request->filled($field)) {
            $query->where($field, 'like', '%' . trim($request->input($field)) . '%');
        }
    }

    $records = $query->latest()->paginate(20)->withQueryString();

    return view('user.index', compact('records'));
}
      /**
     * Open the correct checklist form according to asset_id.
     */
    public function form(PpmRecord $ppmRecord)
    {
        $assetId = strtolower(trim($ppmRecord->asset_id));
        $frequency = (int) $ppmRecord->frequency;

        // PNL
        if (str_starts_with($assetId, 'pnl')) {
            return redirect()->route(
                'ppm-checklists.pnl.create',
                $ppmRecord
            );
        }

        // KHM
        if (str_starts_with($assetId, 'khm')) {
            return redirect()->route(
                'ppm-checklists.khm.create',
                $ppmRecord
            );
        }

   // =========================
// TST
// =========================
if (str_starts_with($assetId, 'tst')) {

    // TST Frequency 1
    if ($frequency === 1) {
        return redirect()->route(
            'ppm-checklists.tst.frequency1',
            $ppmRecord
        );
    }

    // TST Frequency 4
    if ($frequency === 4) {

        // Get asset description
        $description = strtolower(trim($ppmRecord->asset_description ?? ''));

        // If description contains HV anywhere
        if (str_contains($description, 'hv')) {
            return redirect()->route(
                'ppm-checklists.tst.frequency4hv',
                $ppmRecord
            );
        }

        // Normal Frequency 4
        return redirect()->route(
            'ppm-checklists.tst.frequency4',
            $ppmRecord
        );
    }

        abort(
            404,
            'TST frequency not supported: ' . $ppmRecord->frequency
        );
    }

        // TRQ
        if (str_starts_with($assetId, 'trq')) {
            return redirect()->route(
                'ppm-checklists.trq.create',
                $ppmRecord
            );
        }

        abort(404, 'Unknown asset type: ' . $ppmRecord->asset_id);
    }

    /**
     * Show the record according to asset type.
     */
    /**
     * Show the upload form and the currently stored records.
     */
    public function show($id)
{
    $user = Auth::user();

    $record = PpmRecord::findOrFail($id);

    $assetId = strtolower(trim($record->asset_id));
    $frequency = (int) $record->frequency;

    // =========================
    // PNL
    // =========================
    if (str_starts_with($assetId, 'pnl')) {
        return view('user.pnl.create', compact('user', 'record'));
    }

    // =========================
    // TST
    // =========================
    if (str_starts_with($assetId, 'tst')) {

        if ($frequency === 1) {
            return view('user.tst.frequency1', compact('user', 'record'));
        }

        if ($frequency === 4) {
            return view('user.tst.frequency4', compact('user', 'record'));
        }

        // Other TST frequencies
        abort(404, 'TST frequency not supported: ' . $record->frequency);
    }

    // =========================
    // KHM
    // =========================
    if (str_starts_with($assetId, 'khm')) {
        return view('user.khm.show', compact('user', 'record'));
    }
 // =========================
    // KHM
    // =========================
    if (str_starts_with($assetId, 'trq')) {
        return view('user.trq.show', compact('user', 'record'));
    }
    // Unknown asset
    abort(404, 'Unknown asset type: ' . $record->asset_id);
}

}
