<?php

namespace App\Imports;

use App\Models\PpmRecord;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Carbon\Carbon;

class PpmRecordsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    /**
     * WithHeadingRow turns "PPM ID" into 'ppm_id', "Week Due" into
     * 'week_due', etc. automatically (lowercase, spaces -> underscores).
     */
    public function model(array $row): Model
    {
        return new PpmRecord([
            'ppm_id'                       => $row['ppm_id'] ?? null,
            'week_due'                     => $row['week_due'] ?? null,
            'date_time_created'            => $this->parseDate($row['date_time_created'] ?? null),
            'job_id'                       => $row['job_id'] ?? null,
            'asset_description'            => $row['asset_description'] ?? null,
            'asset_id'                     => $row['asset_id'] ?? null,
            'position_3'                   => $row['position_3'] ?? null,
            'manufacturer_serial_number'   => $row['manufacturer_serial_number'] ?? null,
            'frequency'                    => $row['freauency'] ?? $row['frequency'] ?? null, // note: source header is misspelled "Freauency"
            'est_resource_minutes'         => $row['est_resource_minutes'] ?? null,
            'trade'                        => $row['trade'] ?? null,
            'position_2'                   => $row['position_2'] ?? null,
            'brief_description'            => $row['brief_description'] ?? null,
            'frequency_text'               => $row['frequency_text'] ?? null,
            'asset_position'               => $row['asset_position'] ?? null,
            'est_duration'                 => $row['est_duration_ddhhmm'] ?? null,
            'est_resource_time'            => $row['est_resource_time'] ?? null,
            'system'                       => $row['system'] ?? null,
            'risk_id'                      => $row['risk_id'] ?? null,
            'plant_group'                  => $row['plant_group'] ?? null,
            'position'                     => $row['position'] ?? null,

        ]);
    }

    private function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }

        // Excel dates sometimes arrive as serial numbers instead of strings
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
        }

        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
