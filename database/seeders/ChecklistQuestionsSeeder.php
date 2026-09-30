<?php

namespace Database\Seeders;

use App\Models\ChecklistQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ChecklistQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        // Wipe and reseed so re-running this seeder doesn't duplicate rows.
        Schema::disableForeignKeyConstraints();
        ChecklistQuestion::truncate();
        Schema::enableForeignKeyConstraints();

        /*
        |--------------------------------------------------------------------------
        | PNL Questions
        |--------------------------------------------------------------------------
        */

        $this->seedSet('PNL', null, [
            // Equipment
            'Print and check the test label',
            'Remove labels and tonner/ribbon',
            'Clean equipment',

            // Print area
            'Clean rollers',
            'Clean label photocell',
            'Clean printhead',

            // Mechanization area
            'Check motors',
            'Check belts',
            'Check battery (if applicable, change every 2 years)',
            'Check foil keyboard',

            // Cutter device
            'Clean and check cutter device',

            // Test
            'Reload labels and tonner/ribbon',
            'Print and check the test label and paste the label (check legibility according to VPS C-8)X',
        ]);

        /*
        |--------------------------------------------------------------------------
        | TRQ Questions
        |--------------------------------------------------------------------------
        */

        $this->seedSet('TRQ', null, [
            'Torque values match the specified settings',
            'No loose fixings found',
            'Torque wrench calibration within date',
        ]);

        /*
        |--------------------------------------------------------------------------
        | TST - Frequency 1
        |--------------------------------------------------------------------------
        */

        $this->seedSet('TST', 1, [
            'Functional test passed (frequency 1)',
            'Alarm/trip signal verified (frequency 1)',
        ]);

        /*
        |--------------------------------------------------------------------------
        | TST - Frequency 4
        |--------------------------------------------------------------------------
        */

        $this->seedSet('TST', 4, [
            'Functional test passed (frequency 4)',
            'Full load test completed (frequency 4)',
            'Backup/standby changeover verified (frequency 4)',
        ]);
    }

    /**
     * Seed a group of checklist questions.
     *
     * @param string $type
     * @param int|null $frequency
     * @param string[] $questions
     */
    private function seedSet(
        string $type,
        ?int $frequency,
        array $questions
    ): void {
        foreach ($questions as $order => $text) {
            ChecklistQuestion::create([
                'type' => $type,
                'frequency' => $frequency,
                'question_text' => $text,
                'order' => $order + 1,
                'is_active' => true,
            ]);
        }
    }
}
