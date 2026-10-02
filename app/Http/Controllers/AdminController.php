<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;
use App\Imports\PpmRecordsImport;
use App\Models\PpmRecord;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    /**
     * Display PPM records.
     */
    public function index()
    {
        $ppmRecords = PpmRecord::latest()->paginate(20);

        return view('admin.index', compact('ppmRecords'));
    }

    /**
     * Import PPM records from Excel or CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
        ]);

        try {

            DB::transaction(function () use ($request) {

                $file = $request->file('file');

                /*
                 * CSV files exported by Excel can use:
                 *     ,  comma
                 *     ;  semicolon
                 *
                 * Detect the delimiter automatically.
                 */
                if ($file->getClientOriginalExtension() === 'csv') {

                    $handle = fopen($file->getRealPath(), 'r');

                    if ($handle === false) {
                        throw new \Exception('Unable to open CSV file.');
                    }

                    // Read first line
                    $firstLine = fgets($handle);

                    fclose($handle);

                    if ($firstLine === false) {
                        throw new \Exception('CSV file is empty.');
                    }

                    // Detect delimiter
                    $commaCount = substr_count($firstLine, ',');
                    $semicolonCount = substr_count($firstLine, ';');
                    $tabCount = substr_count($firstLine, "\t");

                    if ($semicolonCount > $commaCount && $semicolonCount >= $tabCount) {
                        $delimiter = ';';
                    } elseif ($tabCount > $commaCount) {
                        $delimiter = "\t";
                    } else {
                        $delimiter = ',';
                    }

                    /*
                     * Import CSV using detected delimiter.
                     */
                    Excel::import(
                        new PpmRecordsImport($delimiter),
                        $file
                    );

                } else {

                    /*
                     * Normal XLSX / XLS import.
                     */
                    Excel::import(
                        new PpmRecordsImport(),
                        $file
                    );
                }
            });

        } catch (ExcelValidationException $e) {

            return redirect()
                ->route('ppm-records.index')
                ->with(
                    'error',
                    'Import validation failed. Check the Job ID or CSV data.'
                );

        } catch (\Throwable $e) {

            return redirect()
                ->route('ppm-records.index')
                ->with(
                    'error',
                    'Import failed: ' . $e->getMessage()
                );
        }

        return redirect()
            ->route('ppm-records.index')
            ->with(
                'success',
                'File imported successfully.'
            );
    }

    /**
     * Display one PPM record.
     */
    public function show(PpmRecord $ppmRecord)
    {
        return view(
            'admin.ppm-details',
            compact('ppmRecord')
        );
    }
    public function showdetails(Request $request)
    {
        $year = $request->input('year', now()->year); // default = this year

        $query = PpmRecord::query()
            ->with(['checklist.completedBy', 'checklist.verifiedBy', 'checklist.verifiedByQuality']);

        // Year filter (week_due = YYYYWW, e.g. 202640)
        if ($year !== 'all') {
            $year = (int) $year;
            $query->whereBetween('week_due', [$year * 100, $year * 100 + 53]);
        }

        if ($request->filled('job_id')) {
            $query->where('job_id', 'like', '%' . $request->job_id . '%');
        }

        if ($request->filled('asset_id')) {
            $query->where('asset_id', 'like', '%' . $request->asset_id . '%');
        }

        if ($request->filled('week')) {
            $query->whereRaw('week_due % 100 = ?', [(int) $request->week]);
        }

        if ($request->filled('frequency')) {
            $query->where('frequency', $request->frequency);
        }

        if ($request->filled('completed_by')) {
            $term = $request->completed_by;
            $query->whereHas('checklist.completedBy', fn ($q) => $q->where('name', 'like', "%{$term}%"));
        }

        // Status: verified | not_verified | no_checklist
        switch ($request->status) {
            case 'no_checklist':
                $query->whereDoesntHave('checklist');
                break;
            case 'verified':
                $query->whereHas('checklist', fn ($q) => $q
                    ->whereNotNull('verified_by_matricule')
                    ->whereNotNull('verified_by_quality_matricule'));
                break;
            case 'not_verified':
                $query->whereHas('checklist', fn ($q) => $q->where(fn ($w) => $w
                    ->whereNull('verified_by_matricule')
                    ->orWhereNull('verified_by_quality_matricule')));
                break;
        }

        $records = $query->orderByDesc('week_due')->orderBy('job_id')->paginate(25)->withQueryString();

        // AJAX request -> return only rows + pagination meta
        if ($request->ajax()) {
            return response()->json([
                'html'  => view('admin.partials.ppm-rows', compact('records'))->render(),
                'total' => $records->total(),
                'page'  => $records->currentPage(),
                'last'  => $records->lastPage(),
            ]);
        }

        $years = PpmRecord::query()
            ->selectRaw('DISTINCT FLOOR(week_due / 100) as y')
            ->pluck('y')
            ->map(fn ($y) => (int) $y)
            ->push((int) now()->year)
            ->unique()->sortDesc()->values();

        $frequencies = PpmRecord::query()->whereNotNull('frequency')
            ->distinct()->orderBy('frequency')->pluck('frequency');

        return view('admin.showdetails', [
            'records'     => $records,
            'years'       => $years,
            'frequencies' => $frequencies,
            'year'        => $year,
        ]);
    }
}
