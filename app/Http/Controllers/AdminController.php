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
     * Import PPM records from Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            DB::transaction(function () use ($request): void {
                Excel::import(
                    new PpmRecordsImport,
                    $request->file('file')
                );
            });
        } catch (ExcelValidationException) {
            return redirect()
                ->route('ppm-records.index')
                ->with('error', 'Job ID already exists.');
        }

        return redirect()
            ->route('ppm-records.index')
            ->with('success', 'File imported successfully.');
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
