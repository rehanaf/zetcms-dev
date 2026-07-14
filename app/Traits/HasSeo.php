<?php

namespace App\Traits;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seo_metable');
    }

    /**
     * Ambil meta title dengan fallback ke judul konten itu sendiri.
     */
    public function getMetaTitleAttribute(): string
    {
        return $this->seo?->meta_title ?: $this->title ?? '';
    }

    /**
     * Ambil meta description dengan fallback ke excerpt/potongan content.
     */
    public function getMetaDescriptionAttribute(): string
    {
        if ($this->seo?->meta_description) {
            return $this->seo->meta_description;
        }

        $source = $this->excerpt ?? $this->content ?? '';
        
        if (is_array($source)) {
            $source = collect($source)->flatten()->implode(' ');
        }
        
        return \Illuminate\Support\Str::limit(strip_tags((string) $source), 160);
    }

    public function getOgImageAttribute(): ?string
    {
        return $this->seo?->og_image ?: $this->featured_image ?? null;
    }
}
