<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Component extends Model
{
    protected $fillable = [
        'theme_id', 'name', 'slug', 'type', 'variant', 'view_path',
        'thumbnail', 'content', 'settings', 'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    public function pageComponents(): HasMany
    {
        return $this->hasMany(PageComponent::class);
    }

    /**
     * Path Blade view komponen ini, relatif terhadap folder theme.
     * Contoh: type=hero, variant=variant2 -> "partials.hero.variant2"
     * Kalau view_path diisi manual di database, itu yang dipakai (untuk komponen custom).
     */
    public function getResolvedViewPathAttribute(): string
    {
        return $this->view_path ?: "partials.{$this->type}.{$this->variant}";
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
