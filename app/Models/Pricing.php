<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pricing extends Model
{
    protected $table = 'pricings';

    protected $fillable = [
        'name', 'price', 'billing_period', 'description', 'features', 
        'button_text', 'button_url', 'is_featured', 'is_active', 'order',
        'image_id', 'icon'
    ];

    protected $casts = [
        'features' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function imageMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }
}
