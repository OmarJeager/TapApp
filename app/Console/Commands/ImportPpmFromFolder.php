<?php

namespace App\Console\Commands;

use App\Services\PpmFolderImporter;
use Illuminate\Console\Command;

class ImportPpmFromFolder extends Command
{
    protected $signature = 'ppm:import-folder';
    protected $description = 'Import new PPM Excel/CSV files from C:\Eren\weekdue';

    public function handle(PpmFolderImporter $importer): int
    {
        $r = $importer->run();

        $this->info("Imported {$r['imported']} record(s) from {$r['files']} file(s).");

        foreach ($r['failed'] as $f) {
            $this->error($f);
        }

        return self::SUCCESS;
    }
}