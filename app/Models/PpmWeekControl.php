<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpmWeekControl extends Model
{
    use HasFactory;

    protected $fillable = [
        'week_due',
        'is_published',
        'published_at',
        'status',
        'last_pushed_at',
        'completed_at',
        'archived_at',
         'year',

    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'last_pushed_at' => 'datetime',
        'completed_at' => 'datetime',
        'archived_at' => 'datetime',
    ];
}
