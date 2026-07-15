<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Traits\HasSeo;
use App\Traits\HasTranslations;
use App\Traits\HasRevisions;

class Post extends Model
{
    use SoftDeletes, HasSeo, HasTranslations, HasRevisions;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'excerpt', 'content',
        'featured_image_id', 'status', 'published_at', 'expired_at', 'is_featured', 'views',
    ];

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    public function getFeaturedImageAttribute()
    {
        return $this->featuredImage ? 'storage/' . $this->featuredImage->file_path : null;
    }

    protected $casts = [
        'published_at' => 'datetime',
        'expired_at'   => 'datetime',
        'is_featured'  => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // Related content manual (kurasi editor)
    public function relatedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_related', 'post_id', 'related_post_id')
            ->withPivot('order')
            ->orderBy('post_related.order');
    }

    // Statistik view harian (untuk grafik trend)
    public function dailyViews(): HasMany
    {
        return $this->hasMany(PostView::class);
    }

    // Approval workflow
    public function approvals(): MorphMany
    {
        return $this->morphMany(Approval::class, 'approvable');
    }

    public function latestApproval(): MorphOne
    {
        return $this->morphOne(Approval::class, 'approvable')->latestOfMany();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expired_at')->orWhere('expired_at', '>', now());
            });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
