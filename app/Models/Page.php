<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Traits\HasSeo;
use App\Traits\HasTranslations;
use App\Traits\HasRevisions;

class Page extends Model
{
    use SoftDeletes, HasSeo, HasTranslations, HasRevisions;

    protected $fillable = [
        'user_id', 'layout_id', 'title', 'slug', 'content',
        'featured_image_id', 'status', 'published_at', 'expired_at', 'is_homepage',
    ];

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    protected $casts = [
        'published_at' => 'datetime',
        'expired_at'   => 'datetime',
        'is_homepage'  => 'boolean',
        'content'      => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function layout(): BelongsTo
    {
        return $this->belongsTo(Layout::class);
    }

    // Susunan komponen/partial di halaman ini, urut berdasarkan region + order
    public function components(): HasMany
    {
        return $this->hasMany(PageComponent::class)->orderBy('region')->orderBy('order');
    }

    public function approvals(): MorphMany
    {
        return $this->morphMany(Approval::class, 'approvable');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expired_at')->orWhere('expired_at', '>', now());
            });
    }
}
