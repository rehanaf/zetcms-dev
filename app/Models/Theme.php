<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theme extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'screenshot_id', 'version', 'author', 'is_active',
    ];

    public function screenshotMedia(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Media::class, 'screenshot_id');
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function layouts(): HasMany
    {
        return $this->hasMany(Layout::class);
    }

    public function components(): HasMany
    {
        return $this->hasMany(Component::class);
    }

    /**
     * Ambil theme yang sedang aktif.
     */
    public static function active(): self
    {
        return static::where('is_active', true)->first() 
            ?? static::where('slug', 'default')->firstOrFail();
    }

    /**
     * Aktifkan theme ini dan nonaktifkan theme lainnya.
     */
    public function activate(): void
    {
        static::query()->update(['is_active' => false]);
        $this->update(['is_active' => true]);
    }

    // Path folder view theme ini, mis. "themes.default"
    public function getViewNamespaceAttribute(): string
    {
        return "themes.{$this->slug}";
    }
}
