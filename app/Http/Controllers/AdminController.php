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
}
