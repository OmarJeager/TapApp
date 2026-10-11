<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PpmChecklist;
use App\Models\PpmRecord;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PpmExportController extends Controller
{
    public function index(Request $request)
    {
        $records = $this->filteredRecords($request)
            ->withCount('ppmChecklists')
            ->orderByDesc('week_due')
            ->paginate(20)
            ->withQueryString();

        return view('admin.ppm-exports.index', compact('records'));
    }

    /**
     * Apply the same optional filters to the preview and both exports.
     */
    private function filteredRecords(Request $request)
    {
        return PpmRecord::query()
            ->when($request->filled('week_due'), function ($query) use ($request) {
                $query->where('week_due', $request->week_due);
            })
            ->when($request->filled('asset_id'), function ($query) use ($request) {
                $query->where('asset_id', 'like', '%' . $request->asset_id . '%');
            })
            ->when($request->filled('job_id'), function ($query) use ($request) {
                $query->where('job_id', 'like', '%' . $request->job_id . '%');
            });
    }

    /**
     * CSV: one row per checklist answer.
     * Checklists with no answers still produce one row.
     */
    public function csv(Request $request): StreamedResponse
    {
        $records = $this->filteredRecords($request)
            ->with([
                'ppmChecklists.answers.question',
            ])
            ->orderBy('week_due')
            ->orderBy('asset_id')
            ->get();

        // Include every actual column in ppm_checklists.
        $checklistColumns = \Illuminate\Support\Facades\Schema::getColumnListing(
            'ppm_checklists'
        );

        $headers = array_merge([
            'ppm_id',
            'job_id',
            'asset_id',
            'asset_description',
            'week_due',
            'frequency',
            'checklist_id',
            'question_id',
            'question_text',
            'response',
            'comment',
            'dpn',
            'observation',
        ], array_map(fn ($column) => 'checklist_' . $column, $checklistColumns));

        // Include every actual column in ppm_records as well.
        $recordColumns = \Illuminate\Support\Facades\Schema::getColumnListing(
            'ppm_records'
        );

        $headers = array_merge(
            $headers,
            array_map(fn ($column) => 'record_' . $column, $recordColumns)
        );

        $filename = 'ppm-export-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use (
            $records,
            $headers,
            $checklistColumns,
            $recordColumns
        ) {
            $output = fopen('php://output', 'w');

            // UTF-8 BOM helps Excel open accented text correctly.
            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, $headers);

            foreach ($records as $record) {
                $checklists = $record->ppmChecklists;

                // Preserve records even when no checklist exists.
                if ($checklists->isEmpty()) {
                    $row = [
                        'ppm_id' => $record->ppm_id,
                        'job_id' => $record->job_id,
                        'asset_id' => $record->asset_id,
                        'asset_description' => $record->asset_description,
                        'week_due' => $record->week_due,
                        'frequency' => $record->frequency,
                        'checklist_id' => '',
                        'question_id' => '',
                        'question_text' => '',
                        'response' => '',
                        'comment' => '',
                        'dpn' => '',
                        'observation' => '',
                    ];

                    foreach ($checklistColumns as $column) {
                        $row['checklist_' . $column] = '';
                    }

                    foreach ($recordColumns as $column) {
                        $row['record_' . $column] = $record->getAttribute($column);
                    }

                    fputcsv($output, array_map(
                        fn ($header) => $row[$header] ?? '',
                        $headers
                    ));

                    continue;
                }

                foreach ($checklists as $checklist) {
                    $answers = $checklist->answers;

                    // Preserve checklists that have no question answers.
                    if ($answers->isEmpty()) {
                        $answers = collect([null]);
                    }

                    foreach ($answers as $answer) {
                        $row = [
                            'ppm_id' => $record->ppm_id,
                            'job_id' => $record->job_id,
                            'asset_id' => $record->asset_id,
                            'asset_description' => $record->asset_description,
                            'week_due' => $record->week_due,
                            'frequency' => $record->frequency,
                            'checklist_id' => $checklist->getKey(),
                            'question_id' => $answer?->checklist_question_id ?? '',
                            'question_text' => $answer?->question?->question_text ?? '',
                            'response' => $answer?->response ?? '',
                            'comment' => $answer?->comment ?? '',
                            'dpn' => $answer?->dpn ?? '',
                            'observation' => $answer?->observation ?? '',
                        ];

                        foreach ($checklistColumns as $column) {
                            $row['checklist_' . $column] =
                                $checklist->getAttribute($column);
                        }

                        foreach ($recordColumns as $column) {
                            $row['record_' . $column] =
                                $record->getAttribute($column);
                        }

                        fputcsv($output, array_map(
                            fn ($header) => $row[$header] ?? '',
                            $headers
                        ));
                    }
                }
            }

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * PDF: readable report containing PPM details, all checklist
     * database fields, and each question answer.
     */
    public function pdf(Request $request)
    {
        $records = $this->filteredRecords($request)
            ->with([
                'ppmChecklists.answers.question',
            ])
            ->orderBy('week_due')
            ->orderBy('asset_id')
            ->get();

        $checklistColumns = \Illuminate\Support\Facades\Schema::getColumnListing(
            'ppm_checklists'
        );

        $recordColumns = \Illuminate\Support\Facades\Schema::getColumnListing(
            'ppm_records'
        );

        $pdf = Pdf::loadView('admin.ppm-exports.pdf', [
            'records' => $records,
            'checklistColumns' => $checklistColumns,
            'recordColumns' => $recordColumns,
            'filters' => $request->only([
                'week_due',
                'asset_id',
                'job_id',
            ]),
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download(
            'ppm-export-' . now()->format('Y-m-d-His') . '.pdf'
        );
    }
    public function printTicket(PpmRecord $ppmRecord)
{
    $ppmRecord->load([
        'ppmChecklists.answers.question',
        'ppmChecklists.completedBy',
        'ppmChecklists.verifiedBy',
        'ppmChecklists.verifiedByQuality',
    ]);

    $checklist = $ppmRecord->ppmChecklists
        ->sortByDesc('completed_at')
        ->first();

    $questions = $checklist
        ? $checklist->answers
            ->sortBy(fn ($answer) => $answer->question?->order ?? 999)
            ->values()
        : collect();

    $pdf = Pdf::loadView('admin.ppm-tickets.print', [
        'record' => $ppmRecord,
        'checklist' => $checklist,
        'answers' => $questions,
    ])->setPaper('a4', 'portrait');

    return $pdf->stream(
        'PPM-' . $ppmRecord->job_id . '.pdf'
    );
}
}
