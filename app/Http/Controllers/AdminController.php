<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;
use App\Imports\PpmRecordsImport;
use App\Models\PpmRecord;
use App\Models\PpmWeekControl;
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
     * Show all PPM weeks.
     */
    public function weeks(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Default year = current year
    |--------------------------------------------------------------------------
    */

    $year = $request->input('year', now()->year);


    /*
    |--------------------------------------------------------------------------
    | Get PPM weeks
    |--------------------------------------------------------------------------
    */

    $weeks = PpmRecord::query()
        ->select('week_due')
        ->whereNotNull('week_due')
        ->distinct()
        ->orderBy('week_due')
        ->get()
        ->map(function ($record) use ($year) {

            /*
             * Get/create control for this YEAR + WEEK
             */
            $year = (int) substr((string) $record->week_due, 0, 4);
            $control = PpmWeekControl::firstOrCreate(
                [
                    'year' => $year,
                    'week_due' => $record->week_due,
                ],
                [
                    'status' => 'draft',
                    'is_published' => false,
                ]
            );


            /*
             * Get records for this week
             */
            $recordsQuery = PpmRecord::where(
                'week_due',
                $record->week_due
            );


            /*
             * Total records
             */
            $totalRecords = (clone $recordsQuery)->count();


            /*
             * Progress
             *
             * Keep your existing checklist relationship here.
             */
            $completedRecords = (clone $recordsQuery)
                ->whereHas('checklist', function ($query) {
                    $query->whereNotNull('completed_at');
                })
                ->count();


            $progress = $totalRecords > 0
                ? round(($completedRecords / $totalRecords) * 100)
                : 0;


            return [
                'year' => $year,

                'week_due' => $record->week_due,

                'status' => $control->status,

                'is_published' => $control->is_published,

                'published_at' => $control->published_at,

                'last_pushed_at' => $control->last_pushed_at,

                'completed_at' => $control->completed_at,

                'archived_at' => $control->archived_at,

                'record_count' => $totalRecords,

                'completed_count' => $completedRecords,

                'progress' => $progress,
            ];
        });


    /*
    |--------------------------------------------------------------------------
    | Years for filter
    |--------------------------------------------------------------------------
    */

    $years = PpmWeekControl::query()
        ->select('year')
        ->distinct()
        ->orderByDesc('year')
        ->pluck('year');


    /*
    |--------------------------------------------------------------------------
    | Always make current year available
    |--------------------------------------------------------------------------
    */

    if (!$years->contains(now()->year)) {
        $years->prepend(now()->year);
    }


    return view('admin.weeks.weeks', compact(
        'weeks',
        'years',
        'year'
    ));
}


    /**
     * Publish a week for users.
     */
    public function publishWeek($week)
    {
        $control = PpmWeekControl::firstOrCreate(
            [
                'week_due' => $week,
            ],
            [
                'status' => 'draft',
                'is_published' => false,
            ]
        );

        $control->update([
            'status' => 'published',

            'is_published' => true,

            'published_at' =>
                $control->published_at ?? now(),

            'last_pushed_at' => now(),

            'archived_at' => null,
        ]);

        return back()->with(
            'success',
            "Week {$week} has been published successfully."
        );
    }


    /**
     * Push an already published week again.
     *
     * This updates only last_pushed_at.
     */
    public function pushWeek($week)
    {
        $control = PpmWeekControl::where(
            'week_due',
            $week
        )->firstOrFail();

        if (!$control->is_published) {
            return back()->with(
                'error',
                "Week {$week} must be published before it can be pushed."
            );
        }

        $control->update([
            'last_pushed_at' => now(),
        ]);

        return back()->with(
            'success',
            "Week {$week} has been pushed again successfully."
        );
    }


    /**
     * Hide a published week from users.
     */
    public function hideWeek($week)
    {
        $control = PpmWeekControl::where(
            'week_due',
            $week
        )->firstOrFail();

        $control->update([
            'status' => 'draft',
            'is_published' => false,
        ]);

        return back()->with(
            'success',
            "Week {$week} is now hidden from users."
        );
    }


    /**
     * Mark week as completed.
     */
    public function completeWeek($week)
    {
        $control = PpmWeekControl::where(
            'week_due',
            $week
        )->firstOrFail();

        $control->update([
            'status' => 'completed',
            'is_published' => true,
            'completed_at' => now(),
        ]);

        return back()->with(
            'success',
            "Week {$week} has been marked as completed."
        );
    }


    /**
     * Archive a week.
     */
    public function archiveWeek($week)
    {
        $control = PpmWeekControl::where(
            'week_due',
            $week
        )->firstOrFail();

        $control->update([
            'status' => 'archived',
            'is_published' => false,
            'archived_at' => now(),
        ]);

        return back()->with(
            'success',
            "Week {$week} has been archived."
        );
    }


    /**
     * Reopen an archived/completed week.
     */
    public function reopenWeek($week)
    {
        $control = PpmWeekControl::where(
            'week_due',
            $week
        )->firstOrFail();

        $control->update([
            'status' => 'published',
            'is_published' => true,
            'archived_at' => null,
        ]);

        return back()->with(
            'success',
            "Week {$week} has been reopened."
        );
    }
    
}
