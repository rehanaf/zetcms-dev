<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id', 'parent_id', 'page_id', 'post_id',
        'title', 'url', 'icon', 'target', 'order',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    // Resolusi URL final: kalau terhubung ke page/post, pakai slug-nya; kalau tidak, pakai url manual
    public function getResolvedUrlAttribute(): string
    {
        if ($this->page_id && $this->page) {
            return url('/' . $this->page->slug);
        }
        if ($this->post_id && $this->post) {
            return url('/blog/' . $this->post->slug);
        }
        return $this->url ?? '#';
    }
}
