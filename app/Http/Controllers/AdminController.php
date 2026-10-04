<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
     *
     * Existing Job IDs are skipped.
     * New Job IDs are imported normally.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
        ]);

        try {

            $file = $request->file('file');

            /*
             * Create the importer instance first.
             *
             * We keep the same instance so we can get:
             * - importedCount
             * - duplicateJobIds
             */
            $import = new PpmRecordsImport();

            /*
             * CSV delimiter detection.
             */
            if (strtolower($file->getClientOriginalExtension()) === 'csv') {

                $handle = fopen($file->getRealPath(), 'r');

                if ($handle === false) {
                    throw new \Exception('Unable to open CSV file.');
                }

                /*
                 * Read the first line to detect delimiter.
                 */
                $firstLine = fgets($handle);

                fclose($handle);

                if ($firstLine === false) {
                    throw new \Exception('CSV file is empty.');
                }

                /*
                 * Detect delimiter:
                 *
                 * ,
                 * ;
                 * TAB
                 */
                $commaCount = substr_count($firstLine, ',');
                $semicolonCount = substr_count($firstLine, ';');
                $tabCount = substr_count($firstLine, "\t");

                if (
                    $semicolonCount > $commaCount &&
                    $semicolonCount >= $tabCount
                ) {

                    $delimiter = ';';

                } elseif ($tabCount > $commaCount) {

                    $delimiter = "\t";

                } else {

                    $delimiter = ',';
                }

                /*
                 * Create importer with detected delimiter.
                 */
                $import = new PpmRecordsImport($delimiter);
            }

            /*
             * Import the file.
             *
             * Existing Job IDs will be skipped
             * by PpmRecordsImport.
             */
            Excel::import($import, $file);

            /*
             * Get imported records count.
             */
            $importedCount = $import->importedCount;

            /*
             * Get duplicate Job IDs.
             */
            $duplicateJobIds = array_unique(
                $import->duplicateJobIds
            );

            /*
             * Remove empty values just in case.
             */
            $duplicateJobIds = array_filter(
                $duplicateJobIds,
                function ($jobId) {
                    return !empty($jobId);
                }
            );

            /*
             * If duplicate Job IDs were found.
             */
            if (count($duplicateJobIds) > 0) {

                $duplicateList = implode(
                    ', ',
                    $duplicateJobIds
                );

                return redirect()
                    ->route('ppm-records.index')
                    ->with(
                        'warning',
                        $importedCount .
                        ' record(s) imported successfully. ' .
                        count($duplicateJobIds) .
                        ' Job ID(s) already exist and were skipped: ' .
                        $duplicateList
                    );
            }

            /*
             * No duplicates.
             */
            return redirect()
                ->route('ppm-records.index')
                ->with(
                    'success',
                    $importedCount .
                    ' record(s) imported successfully.'
                );

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
