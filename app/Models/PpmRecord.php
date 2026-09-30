<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PpmRecord extends Model
{
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

    ];
    public function ppmChecklists()
    {
        return $this->hasMany(PpmChecklist::class, 'ppm_records_id');
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
     * week_due is stored like 202640 (YYYYWW).
     * Display it as "WK/YY" -> "40/26".
     */
    public function getInterventionWeekAttribute(): ?string
    {
        if (!$this->week_due) {
            return null;
        }

        $weekDue = (string) $this->week_due;
        $year = (int) substr($weekDue, 0, 4);
        $week = (int) substr($weekDue, 4, 2);

        return sprintf('%02d/%02d', $week, $year % 100);
    }
      /**
     * Next Preventative Maintenance Week = current week + frequency (weeks).
     * e.g. 38/26 + frequency 1 -> 39/26. Wraps into the next year past week 52.
     */
    public function getNextPmWeekAttribute(): ?string
    {
        if (!$this->week_due || !$this->frequency) {
            return null;
        }

        $weekDue = (string) $this->week_due;
        $year = (int) substr($weekDue, 0, 4);
        $week = (int) substr($weekDue, 4, 2);

        $nextWeek = $week + (int) $this->frequency;
        $nextYear = $year;

        while ($nextWeek > 52) {
            $nextWeek -= 52;
            $nextYear++;
        }

        return sprintf('%02d/%02d', $nextWeek, $nextYear % 100);
    }
}
