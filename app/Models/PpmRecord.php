<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PpmRecord extends Model
{
    protected $guarded = [];
    protected $table = 'ppm_records';

    protected $fillable = [
        'ppm_id',
        'week_due',
        'date_time_created',
        'job_id',
        'asset_description',
        'asset_id',
        'position_3',
        'manufacturer_serial_number',
        'frequency',
        'est_resource_minutes',
        'trade',
        'position_2',
        'brief_description',
        'frequency_text',
        'asset_position',
        'est_duration',
        'est_resource_time',
        'system',
        'risk_id',
        'plant_group',
        'position',
        'year',
    ];

    public function ppmChecklists()
    {
        return $this->hasMany(PpmChecklist::class, 'ppm_records_id');
    }

    public function checklist()
    {
        return $this->hasOne(PpmChecklist::class, 'ppm_records_id');
    }

    /**
     * Resolve the asset prefix (pnl, tst, khm, cons, etc.)
     */
    public function getAssetPrefixAttribute(): string
    {
        $id = strtolower($this->asset_id ?? '');

        if (str_starts_with($id, 'pnl'))  return 'pnl';
        if (str_starts_with($id, 'tst'))  return 'tst';
        if (str_starts_with($id, 'khm'))  return 'khm';
        if (str_starts_with($id, 'cons')) return 'cons';

        return 'generic';
    }

    /**
     * ISO weeks in a year (52 or 53). 2026 => 53.
     * Dec 28 is always in the last ISO week of its year.
     */
    public static function weeksInYear(int $year): int
    {
        return Carbon::create($year, 12, 28)->isoWeek;
    }

    /**
     * Split week_due (stored as YYYYWW, e.g. 202640) into [week, year].
     */
    protected function splitWeekDue(): ?array
    {
        $value = preg_replace('/\D/', '', (string) $this->week_due);

        if (strlen($value) !== 6) {
            return null;
        }

        return [(int) substr($value, 4, 2), (int) substr($value, 0, 4)];
    }

    /**
     * Intervention WK: 202640 -> "40/26"
     */
    public function getInterventionWeekAttribute(): ?string
    {
        $parts = $this->splitWeekDue();

        if (!$parts) {
            return null;
        }

        [$week, $year] = $parts;

        return sprintf('%02d/%02d', $week, $year % 100);
    }

    /**
     * Next PM week = week_due + frequency (weeks), rolling into the next year
     * using the real number of weeks in each year (2026 = 53).
     */
    public function getNextPmWeekAttribute(): ?string
    {
        $parts = $this->splitWeekDue();

        if (!$parts || !is_numeric($this->frequency) || (int) $this->frequency <= 0) {
            return null;
        }

        [$week, $year] = $parts;

        $week += (int) $this->frequency;

        while ($week > self::weeksInYear($year)) {
            $week -= self::weeksInYear($year);
            $year++;
        }

        return sprintf('%02d/%02d', $week, $year % 100);
    }

    /** Last completed preventive for the same asset (other records). */
    public function lastDoneChecklist()
    {
        return PpmChecklist::with(['ppmRecord', 'completedBy'])
            ->whereNotNull('completed_at')
            ->whereHas('ppmRecord', fn ($q) => $q
                ->where('asset_id', $this->asset_id)
                ->where('id', '!=', $this->id))
            ->orderByDesc('completed_at')
            ->orderByDesc('id')
            ->first();
    }

    /** 4 -> Monthly, 1 -> Weekly ... */
    public function getFrequencyLabelAttribute(): ?string
    {
        return match ((int) $this->frequency) {
            0       => null,
            1       => 'Weekly',
            2       => 'Bi-weekly',
            4       => 'Monthly',
            13      => 'Quarterly',
            26      => 'Semi-annual',
            52      => 'Annual',
            default => $this->frequency . ' weeks',
        };
    }
}
