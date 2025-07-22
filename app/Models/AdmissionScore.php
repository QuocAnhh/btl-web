<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionScore extends Model
{
    use HasFactory;

    protected $fillable = ['admission_criterion_id', 'subject_name', 'required_score'];

    public function admissionCriterion(): BelongsTo
    {
        return $this->belongsTo(AdmissionCriterion::class);
    }
}
