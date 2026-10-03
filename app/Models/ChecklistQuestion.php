<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistQuestion extends Model
{
    protected $fillable = [
        'type',
        'frequency',
        'question_text',
        'order',
        'is_active',
        'variant',
    ];

    public function answers()
    {
        return $this->hasMany(PpmChecklistAnswer::class);
    }

    /**
     * $type = 'PNL' | 'TRQ' | 'TST'
     *
     * For TST:
     * - frequency 1 + normal
     * - frequency 4 + normal
     * - frequency 4 + HV
     */
    public static function forAsset(
        string $type,
        ?int $frequency = null,
        ?string $variant = null
    ) {
        return static::query()
            ->where('type', $type)

            ->when(
                $type === 'TST',
                fn ($q) => $q->where('frequency', $frequency)
            )

            ->when(
                $type === 'TST' && $variant !== null,
                fn ($q) => $q->where('variant', $variant)
            )

            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }
}
