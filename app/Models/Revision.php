<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Revision extends Model
{
    const UPDATED_AT = null; // revisi tidak pernah diupdate, cuma dibuat

    protected $fillable = ['revisionable_type', 'revisionable_id', 'user_id', 'data', 'note'];

    protected $casts = ['data' => 'array'];

    public function revisionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
