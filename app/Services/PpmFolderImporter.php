<?php

namespace App\Services;

use App\Imports\PpmRecordsImport;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class PpmFolderImporter
{

    public const FOLDER = 'C:/Eren/weekdue';

    public function run(): array
    {
        $result = [
            'imported'   => 0,   // records
            'files'      => 0,   // files imported
            'skipped'    => [],  // files already imported
            'failed'     => [],
            'duplicates' => [],  // existing Job IDs
        ];

        if (!is_dir(self::FOLDER)) {
            Log::warning('PPM auto-import: folder not found ' . self::FOLDER);
            return $result;
        }

        $files = glob(self::FOLDER . '/*.{xlsx,xls,csv,XLSX,XLS,CSV}', GLOB_BRACE) ?: [];

        foreach ($files as $path) {

            $name = basename($path);

            // Excel temp file while the file is open
            if (str_starts_with($name, '~$')) continue;

            // File still being copied/saved? wait for next run
            if (time() - filemtime($path) < 10) continue;

            $hash = md5_file($path);
            if ($hash === false) continue; // locked by another program

            // Already imported -> skip silently
            if (DB::table('ppm_imported_files')->where('file_hash', $hash)->exists()) {
                $result['skipped'][] = $name;
                continue;
            }

            try {
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                $import = $ext === 'csv'
                    ? new PpmRecordsImport($this->detectDelimiter($path))
                    : new PpmRecordsImport();

                Excel::import($import, new UploadedFile($path, $name, null, null, true));

                $result['imported']  += $import->importedCount;
                $result['duplicates'] = array_merge($result['duplicates'], $import->duplicateJobIds);
                $result['files']++;

                DB::table('ppm_imported_files')->insert([
                    'file_name'      => $name,
                    'file_hash'      => $hash,
                    'imported_count' => $import->importedCount,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

            } catch (\Throwable $e) {
                $result['failed'][] = $name . ' (' . $e->getMessage() . ')';
                Log::error('PPM auto-import failed: ' . $name . ' - ' . $e->getMessage());
            }
        }

        $result['duplicates'] = array_values(array_filter(array_unique($result['duplicates'])));

        // Only keep a notice when something actually happened
        if ($result['files'] > 0 || $result['failed']) {
            Cache::put('ppm_auto_import_notice', $result, now()->addHours(12));
        }

        return $result;
    }

    private function detectDelimiter(string $path): string
    {
        $h = fopen($path, 'r');
        $line = $h ? (fgets($h) ?: '') : '';
        if ($h) fclose($h);

        $comma = substr_count($line, ',');
        $semi  = substr_count($line, ';');
        $tab   = substr_count($line, "\t");

        if ($semi > $comma && $semi >= $tab) return ';';
        if ($tab > $comma) return "\t";
        return ',';
    }
}