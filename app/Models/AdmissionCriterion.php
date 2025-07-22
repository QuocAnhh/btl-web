<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdmissionCriterion extends Model
{
    use HasFactory;

    protected $fillable = ['major_id', 'name', 'admission_method', 'year'];

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function admissionScores(): HasMany
    {
        return $this->hasMany(AdmissionScore::class);
    }
}
