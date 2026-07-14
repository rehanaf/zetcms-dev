<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageComponent extends Model
{
    protected $fillable = [
        'page_id', 'component_id', 'region', 'order', 'settings_override', 'is_active',
    ];

    protected $casts = [
        'settings_override' => 'array',
        'is_active' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }

    // Gabungkan settings default komponen dengan override khusus di halaman ini
    public function getResolvedSettingsAttribute(): array
    {
        return array_merge(
            $this->component->settings ?? [],
            $this->settings_override ?? []
        );
    }
}
