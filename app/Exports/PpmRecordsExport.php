<?php

namespace App\Exports;

use App\Models\PpmRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PpmRecordsExport implements FromCollection, WithHeadings, WithMapping
{
    private Collection $rows;
    private int $position = 0;

    public function __construct(private array $filters = [])
    {
    }

    public function collection(): Collection
    {
        $query = PpmRecord::query()
            ->with([
                'ppmChecklists.answers.question',
                'ppmChecklists.completedBy',
                'ppmChecklists.verifiedBy',
                'ppmChecklists.verifiedByQuality',
            ]);

        $this->applyFilters($query);

        $records = $query->orderBy('ppm_records.week_due')
            ->orderBy('ppm_records.asset_id')
            ->get();

        $this->rows = collect();

        foreach ($records as $record) {
            // Export all checklists belonging to this maintenance record.
            $checklists = $record->ppmChecklists;

            if ($checklists->isEmpty()) {
                $this->rows->push([
                    'record' => $record,
                    'checklist' => null,
                    'answer' => null,
                ]);

                continue;
            }

            foreach ($checklists as $checklist) {
                $answers = $checklist->answers;

                if ($answers->isEmpty()) {
                    $this->rows->push([
                        'record' => $record,
                        'checklist' => $checklist,
                        'answer' => null,
                    ]);

                    continue;
                }

                foreach ($answers as $answer) {
                    $this->rows->push([
                        'record' => $record,
                        'checklist' => $checklist,
                        'answer' => $answer,
                    ]);
                }
            }
        }

        return $this->rows;
    }

    private function applyFilters(Builder $query): void
    {
        $filters = $this->filters;

        // Exact intervention week, e.g. 202640.
        if (!empty($filters['week_due'])) {
            $query->where('ppm_records.week_due', $filters['week_due']);
        }

        // Year derived from YYYYWW; no separate year column required.
        if (!empty($filters['year'])) {
            $query->whereRaw(
                'LEFT(CAST(ppm_records.week_due AS CHAR), 4) = ?',
                [(string) $filters['year']]
            );
        }

        // Month and date range use date_time_created.
        if (!empty($filters['month'])) {
            $date = \Carbon\Carbon::createFromFormat(
                '!Y-m',
                $filters['month']
            );

            $query->whereBetween('ppm_records.date_time_created', [
                $date->copy()->startOfMonth(),
                $date->copy()->endOfMonth(),
            ]);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate(
                'ppm_records.date_time_created',
                '>=',
                $filters['date_from']
            );
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate(
                'ppm_records.date_time_created',
                '<=',
                $filters['date_to']
            );
        }

        // Asset type is determined by the asset_id prefix.
        if (!empty($filters['type'])) {
            $prefix = match (strtoupper($filters['type'])) {
                'PNL' => 'pnl',
                'TST' => 'tst',
                'KHM', 'TRQ' => strtolower($filters['type']),
                'CONS' => 'cons',
                default => null,
            };

            if ($prefix !== null) {
                $query->whereRaw(
                    'LOWER(ppm_records.asset_id) LIKE ?',
                    [$prefix . '%']
                );
            } elseif ($filters['type'] === 'generic') {
                $query->whereRaw(
                    "LOWER(COALESCE(ppm_records.asset_id, '')) NOT LIKE 'pnl%'"
                )->whereRaw(
                    "LOWER(COALESCE(ppm_records.asset_id, '')) NOT LIKE 'tst%'"
                )->whereRaw(
                    "LOWER(COALESCE(ppm_records.asset_id, '')) NOT LIKE 'khm%'"
                )->whereRaw(
                    "LOWER(COALESCE(ppm_records.asset_id, '')) NOT LIKE 'trq%'"
                )->whereRaw(
                    "LOWER(COALESCE(ppm_records.asset_id, '')) NOT LIKE 'cons%'"
                );
            }
        }

        foreach ([
            'asset_id',
            'job_id',
            'risk_id',
            'frequency',
        ] as $field) {
            if (isset($filters[$field]) && $filters[$field] !== '') {
                $query->where("ppm_records.$field", $filters[$field]);
            }
        }

        // Checklist completion status.
        if (($filters['status'] ?? '') === 'completed') {
            $query->whereHas('ppmChecklists', function ($q) {
                $q->whereNotNull('completed_at');
            });
        } elseif (($filters['status'] ?? '') === 'pending') {
            $query->whereDoesntHave('ppmChecklists', function ($q) {
                $q->whereNotNull('completed_at');
            });
        } elseif (($filters['status'] ?? '') === 'quality_verified') {
            $query->whereHas('ppmChecklists', function ($q) {
                $q->where('status_quality', 'verified');
            });
        }
    }

    public function headings(): array
    {
        return [
            'PPM Record ID',
            'PPM ID',
            'Week Due',
            'Intervention Week',
            'Next PM Week',
            'Date Time Created',
            'Job ID',
            'Asset ID',
            'Asset Description',
            'Asset Type',
            'Position',
            'Position 2',
            'Position 3',
            'Asset Position',
            'Manufacturer Serial Number',
            'Frequency',
            'Frequency Label',
            'Estimated Resource Minutes',
            'Trade',
            'Brief Description',
            'Frequency Text',
            'Estimated Duration',
            'Estimated Resource Time',
            'System',
            'Risk ID',
            'Plant Group',
            'Year',
            'Checklist ID',
            'Checklist Status',
            'Start Time',
            'End Time',
            'Total Time Minutes',
            'Completed By Matricule',
            'Completed By Name',
            'Completed At',
            'Admin Verification Status',
            'Admin Verified By',
            'Admin Verified At',
            'Quality Verification Status',
            'Quality Verified By',
            'Quality Verified At',
            'Question ID',
            'Question Type',
            'Question Frequency',
            'Question Variant',
            'Question Order',
            'Question',
            'Response',
            'Comment',
            'DPN',
            'Observation',
        ];
    }

    public function map($row): array
    {
        $record = $row['record']?? null;
        $checklist = $row['checklist']?? null;
        $answer = $row['answer']?? null;
        $question = $answer?->question;

        return [
            $record->id,
            $record->ppm_id,
            $record->week_due,
            $record->intervention_week,
            $record->next_pm_week,
            $record->date_time_created,
            $record->job_id,
            $record->asset_id,
            $record->asset_description,
            $record->asset_prefix,
            $record->position,
            $record->position_2,
            $record->position_3,
            $record->asset_position,
            $record->manufacturer_serial_number,
            $record->frequency,
            $record->frequency_label,
            $record->est_resource_minutes,
            $record->trade,
            $record->brief_description,
            $record->frequency_text,
            $record->est_duration,
            $record->est_resource_time,
            $record->system,
            $record->risk_id,
            $record->plant_group,
            substr((string) $record->week_due, 0, 4),
            $checklist?->id,
            $checklist?->status_admin,
            $checklist?->start_time?->format('Y-m-d H:i:s'),
            $checklist?->end_time?->format('Y-m-d H:i:s'),
            $checklist?->total_time_minutes,
            $checklist?->completed_by_matricule,
            $checklist?->completedBy?->name,
            $checklist?->completed_at?->format('Y-m-d'),
            $checklist?->status_admin,
            $checklist?->verified_by_matricule,
            $checklist?->verified_at?->format('Y-m-d'),
            $checklist?->status_quality,
            $checklist?->verified_by_quality_matricule,
            $checklist?->verified_quality_at?->format('Y-m-d'),
            $question?->id,
            $question?->type,
            $question?->frequency,
            $question?->variant,
            $question?->order,
            $question?->question_text,
            $answer?->response,
            $answer?->comment,
            $answer?->dpn,
            $answer?->observation,
        ];
    }
}
