<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpmChecklistQuestion extends Model
{
    protected $fillable = [
        'ppm_checklist_id',
        'checklist_question_id',
        'type',
        'frequency',
        'question_text',
        'order',
        'variant',
    ];

    public function checklist()
    {
        return $this->belongsTo(
            PpmChecklist::class,
            'ppm_checklist_id'
        );
    }

    public function originalQuestion()
    {
        return $this->belongsTo(
            ChecklistQuestion::class,
            'checklist_question_id'
        );
    }

    public function answers()
    {
        return $this->hasMany(
            PpmChecklistAnswer::class,
            'checklist_question_id',
            'checklist_question_id'
        );
    }
}
