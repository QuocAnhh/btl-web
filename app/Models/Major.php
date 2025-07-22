<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Major extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description'];

    public function admissionCriteria(): HasMany
    {
        return $this->hasMany(AdmissionCriterion::class);
    }
}
