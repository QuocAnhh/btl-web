<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aspiration extends Model
{
    use HasFactory;

    protected $fillable = ['application_id', 'major_id', 'priority'];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }
}
