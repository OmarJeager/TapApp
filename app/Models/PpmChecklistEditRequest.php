<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpmChecklistEditRequest extends Model
{
    //
    protected $fillable = [
        'ppm_checklist_id', 'requested_by_matricule', 'request_reason',
        'status', 'decided_by_matricule', 'admin_note', 'decided_at',
    ];
    protected $casts = ['decided_at' => 'datetime'];

    public function checklist() { return $this->belongsTo(PpmChecklist::class, 'ppm_checklist_id'); }
}
