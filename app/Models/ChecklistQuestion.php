<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistQuestion extends Model
{
    protected $fillable = ['type', 'frequency', 'question_text', 'order', 'is_active'];

    public function answers()
    {
        return $this->hasMany(PpmChecklistAnswer::class);
    }

    /**
     * $type = 'PNL' | 'TRQ' | 'TST'
     * $frequency only matters when $type is 'TST' (e.g. 1 or 4)
     */
    public static function forAsset(string $type, ?int $frequency = null)
    {
        return static::query()
            ->where('type', $type)
            ->when($type === 'TST', fn ($q) => $q->where('frequency', $frequency))
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }
    
}
