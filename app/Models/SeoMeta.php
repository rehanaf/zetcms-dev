<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $fillable = [
        'seo_metable_id', 'seo_metable_type', 'locale', 'hreflang_group',
        'meta_title', 'meta_description', 'meta_keywords', 'focus_keyword', 'canonical_url',
        'robots_index', 'robots_follow',
        'og_title', 'og_description', 'og_image', 'og_type',
        'twitter_card', 'twitter_title', 'twitter_description', 'twitter_image',
        'schema_markup', 'sitemap_include', 'sitemap_priority', 'sitemap_change_freq',
    ];

    protected $casts = [
        'robots_index' => 'boolean',
        'robots_follow' => 'boolean',
        'schema_markup' => 'array',
        'sitemap_include' => 'boolean',
        'sitemap_priority' => 'float',
    ];

    public function seoMetable(): MorphTo
    {
        return $this->morphTo();
    }

    // "index, follow" atau "noindex, nofollow" untuk meta tag robots
    public function getRobotsContentAttribute(): string
    {
        return ($this->robots_index ? 'index' : 'noindex') . ', ' .
               ($this->robots_follow ? 'follow' : 'nofollow');
    }
}
