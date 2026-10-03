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
            // General
            'Clean and degrease all connector holders (take care about deeply hidden places inside) - remove all dust, dirt and grease; uninstall holder covers if needed. Degrease by the cleaning agent.',
            'Check visually if terminal alignment grids are not worn, replace if necessary X',
            'Check the connector holders (holder housing)',
            'Check if all holders cavities have pins, the same type of pins and in the same level',
            'Check for condition and functionality of the pins, change if necessary X',
            'Check if all pins/springs travel well',
            'Check the connectors fixation in the holders, the activation and the expulsion',
            "Check if the connector once locked, doesn't play in the connector holder",
            'Check if all holders are identified properly (connector ID on front of holder)',
            'Test harness lock and barcode reader',
            'Inspect and clean cooling fans + empty vacuum cleaner bag',
            'Check the filter of HV tester, if needed clean it Only HV ROB',

            // Operation
            'Verify all special tests (leak test, sealing, colour, fiber optic) - all with sensors X',
            'Check if Light Barrier is working properly X',
            'Check correct functionality of detection after adjustment X',
            'Verify all special tests (push test force measurement, sigma secondary lock closer module etc.) X',
            'Check the frequency of changeover of push test pins / springs X',
            'Check the continuity with tested harness X',
            'Verify pin-table vs. electrification (Only if rework of holder was done) X',

            // Control
            'Test with Golden and dummy harness after all controls X',
        ]);

        /*
        |--------------------------------------------------------------------------
        | TST - Frequency 4
        |--------------------------------------------------------------------------
        */

        $this->seedSet('TST', 4, [
            // Electrical System
            'Clean testers/cards, computer and all cooling fans',
            'Clean power supply and verify overheating',
            'Tight all connection points',
            'Check if connectors are properly fitted',
            "Check wiring routes of flat cables and holder's wiring",
            'Check if Light Barrier is working properly',
            'Check all lights',

            // Pneumatic System
            'Check incoming pressure (acc. to ROB manual)',
            'Check the level in water trap / empty if necessary',
            'Check the pneumatic oil / replenish if necessary',
            'Change filter if necessary',
            'Check condition and connection of air distributors',
            'Check condition and connection of pipes',
            'Check condition and connection of valves',
            'Check condition and connection of air distributors',
            'Check the air maintenance unit',
            'Eliminate leakages',

            // Printer
            'Verify label printer',
            'Verify time, date on label and in test equipment',
            'Verify contrast',
            'Verify printing head',
            'Verify feeding motors',
            'Perform maintenance routine according to Packaging Requirements',

            // ATEQ
            'Test the ATEC equipment with a calibrated leak - Leak Jet according to the WI DPEW MEN-MEC 00.27-07.003 9',

            // PC
            'Backup the software to the APTIV server',
            'Check the socket, if it is loosen fix it.',

            // Maintenance Plan for WEETECH WKx40 - General
            'Clean tester cover with clean, dry cloth',
            'Clean cards (interface cards and test point cards in matrix box)',
            'Check if connectors are properly fitted (holders to cards, flat cables to tester, communication cables, power supplies, remote interface, etc.)',
            'Check proper connection of peripherals (scanner, printer, etc.)',
            'Clean power supply and verify overheating',
            // Diagnostics and measurements
            'Perform matrix diagnostics',
            'Verify voltage of power supply and compare with nominal values',
            'Check display, assess contrast and brightness, adjust if necessary',
            // Operation
            'Verify pin-table vs. electrification (only if rework of holder was done) X',
            'Perform surveillance routine (dummy harness test) X',
            // Printer and scanner
            'Perform maintenance routine according to Packaging and WH Traceability requirements',

            // Maintenance Plan for WEETECH WK260 PC - General
            'Clean tester cover with clean, dry cloth',
            'Clean cards (interface cards and test point cards in matrix box)',
            'Check if connectors are properly fitted (holders to cards, flat cables to tester, ethernet cable, communication cables, ground potential, power supplies, remote interface, etc.)',
            'Check proper connection of peripherals (scanner, printer, ethernet switch, etc.)',
            'Clean power supply and verify overheating',
            // Diagnostics and measurements
            'Perform external voltage diagnostics',
            'Perform matrix diagnostics',
            'Perform LEDs diagnostics',
            'Verify voltage of power supply and compare with nominal values',
            'Verify voltage of external power supply (U1) and compare with nominal values (applicable for functional test stations)',
            // Operation
            'Verify pin-table vs. electrification (only if rework of holder was done) X',
            'Perform surveillance routine (dummy harness test) X',
            // Printer and scanner
            'Perform maintenance routine according to Packaging and WH Traceability requirements',
        ]);

        /*
        |--------------------------------------------------------------------------
        | TST - Frequency 4 HV
        |--------------------------------------------------------------------------
        */

        // If your seedSet supports a third parameter/identifier for HV:
        $this->seedSet('TST', 4, [
            // Electrical System
            'Clean testers/cards, computer and all cooling fans',
            'Clean power supply and verify overheating',
            'Tight all connection points',
            'Check if connectors are properly fitted',
            "Check wiring routes of flat cables and holder's wiring",
            'Check if Light Barrier is working properly',
            'Check all lights',

            // Pneumatic System
            'Check incoming pressure (acc. to ROB manual)',
            'Check the level in water trap / empty if necessary',
            'Check the pneumatic oil / replenish if necessary',
            'Change filter if necessary',
            'Check condition and connection of air distributors',
            'Check condition and connection of pipes',
            'Check condition and connection of valves',
            'Check condition and connection of air distributors',
            'Check the air maintenance unit',
            'Eliminate leakages',

            // Printer
            'Verify label printer',
            'Verify time, date on label and in test equipment',
            'Verify contrast',
            'Verify printing head',
            'Verify feeding motors',
            'Perform maintenance routine according to Packaging Requirements',

            // ATEQ
            'Test the ATEC equipment with a calibrated leak - Leak Jet according to the WI DPEW MEN-MEC 00.27-07.003 9',

            // PC
            'Backup the software to the APTIV server',
            'Check the socket , if it is loosen fix it. ',
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
