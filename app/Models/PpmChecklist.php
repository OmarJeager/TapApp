<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpmChecklist extends Model
{
    protected $fillable = [
        'ppm_records_id', 'start_time', 'end_time', 'total_time_minutes',
        'completed_by_matricule', 'completed_at',
        'verified_by_matricule', 'verified_at',
        'verified_by_quality_matricule', 'verified_quality_at',
        'verified_by_matricule', 'verified_at', 'status_admin',
        'verified_by_quality_matricule', 'verified_quality_at', 'status_quality',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'completed_at' => 'date',
        'verified_at' => 'date',
        'verified_quality_at' => 'date',
    ];

    public function ppmRecord()
    {
        return $this->belongsTo(PpmRecord::class, 'ppm_records_id');
    }

    public function answers()
    {
        return $this->hasMany(PpmChecklistAnswer::class);
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by_matricule', 'matricule');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by_matricule', 'matricule');
    }

    public function verifiedByQuality()
    {
        return $this->belongsTo(User::class, 'verified_by_quality_matricule', 'matricule');
    }

    /** Recompute total_time_minutes from start_time/end_time. Call before save(). */
    public function refreshTotalTime(): void
    {
        if ($this->start_time && $this->end_time) {
            $this->total_time_minutes = $this->start_time->diffInMinutes($this->end_time);
        }
    }
}
