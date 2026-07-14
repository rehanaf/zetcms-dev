<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layout extends Model
{
    protected $fillable = ['theme_id', 'name', 'slug', 'view_path', 'description', 'is_default'];

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    // Path Blade lengkap dengan namespace theme, mis. "themes.default.layouts.app"
    public function getResolvedViewPathAttribute(): string
    {
        return "themes.{$this->theme->slug}.{$this->view_path}";
    }
}
