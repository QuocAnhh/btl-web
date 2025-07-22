<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'status', 'submitted_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function aspirations(): HasMany
    {
        return $this->hasMany(Aspiration::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class);
    }
}
