<?php

namespace App\Imports;

use App\Models\PpmRecord;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Carbon\Carbon;

class PpmRecordsImport implements
    ToModel,
    WithHeadingRow,
    WithBatchInserts,
    WithChunkReading
{
    /**
     * Job IDs that already exist in the database.
     */
    public array $duplicateJobIds = [];

    /**
     * Number of new records imported.
     */
    public int $importedCount = 0;

    protected ?string $delimiter;

    public function __construct(?string $delimiter = null)
    {
        $this->delimiter = $delimiter;
    }

    /**
     * CSV settings.
     */
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => $this->delimiter ?? ',',
            'enclosure' => '"',
            'input_encoding' => 'UTF-8',
        ];
    }

    /**
     * Import one row.
     */
    public function model(array $row): ?Model
    {
        /*
         * Get Job ID from Excel/CSV.
         */
        $jobId = trim((string) ($row['job_id'] ?? ''));

        /*
         * If Job ID is empty, skip the row.
         */
        if ($jobId === '') {
            return null;
        }

        /*
         * Check if this Job ID already exists
         * in the ppm_records table.
         */
        $alreadyExists = PpmRecord::where('job_id', $jobId)->exists();

        if ($alreadyExists) {

            /*
             * Save the duplicate Job ID so the
             * controller can show it to the admin.
             */
            $this->duplicateJobIds[] = $jobId;

            /*
             * Returning null means:
             * DO NOT INSERT THIS ROW.
             */
            return null;
        }

        /*
         * This is a new Job ID.
         */
        $this->importedCount++;

        /*
         * Insert the new record.
         */
        return new PpmRecord([

            'ppm_id'                     => $row['ppm_id'] ?? null,

            'week_due'                   => $row['week_due'] ?? null,

            'date_time_created'          => $this->parseDate(
                $row['date_time_created'] ?? null
            ),

            'job_id'                     => $jobId,

            'asset_description'          => $row['asset_description'] ?? null,

            'asset_id'                   => $row['asset_id'] ?? null,

            'position_3'                 => $row['position_3'] ?? null,

            'manufacturer_serial_number' => $row['manufacturer_serial_number'] ?? null,

            /*
             * Your source sometimes has "Freauency"
             * instead of "Frequency".
             */
            'frequency'                 => $row['freauency']
                ?? $row['frequency']
                ?? null,

            'est_resource_minutes'       => $row['est_resource_minutes'] ?? null,

            'trade'                      => $row['trade'] ?? null,

            'position_2'                 => $row['position_2'] ?? null,

            'brief_description'          => $row['brief_description'] ?? null,

            'frequency_text'             => $row['frequency_text'] ?? null,

            'asset_position'             => $row['asset_position'] ?? null,

            'est_duration'               => $row['est_duration_ddhhmm'] ?? null,

            'est_resource_time'          => $row['est_resource_time'] ?? null,

            'system'                     => $row['system'] ?? null,

            'risk_id'                    => $row['risk_id'] ?? null,

            'plant_group'                => $row['plant_group'] ?? null,

            'position'                   => $row['position'] ?? null,
        ]);
    }

    /**
     * Parse Excel/CSV date.
     */
    private function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }

        /*
         * Excel dates can arrive as serial numbers.
         */
        if (is_numeric($value)) {

            return \PhpOffice\PhpSpreadsheet\Shared\Date
                ::excelToDateTimeObject($value);
        }

        try {

            return Carbon::parse($value);

        } catch (\Exception $e) {

            return null;
        }
    }

    /**
     * Batch size.
     */
    public function batchSize(): int
    {
        return 500;
    }

    /**
     * Chunk size.
     */
    public function chunkSize(): int
    {
        return 500;
    }
}
