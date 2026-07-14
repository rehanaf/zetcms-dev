<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    protected $fillable = [
        'name', 'role', 'company', 'content', 'avatar_id', 'rating', 'is_active'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_active' => 'boolean',
    ];

    public function avatarMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'avatar_id');
    }
}
