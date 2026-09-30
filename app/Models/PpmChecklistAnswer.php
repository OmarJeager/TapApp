<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpmChecklistAnswer extends Model
{
    protected $fillable = ['ppm_checklist_id', 'checklist_question_id', 'response', 'comment', 'dpn', 'observation'];

    public function checklist()
    {
        return $this->belongsTo(PpmChecklist::class, 'ppm_checklist_id');
    }

    public function question()
    {
        return $this->belongsTo(ChecklistQuestion::class, 'checklist_question_id');
    }
}
