<?php

namespace App\Services;

use App\Models\Component;
use App\Models\Theme;
use Illuminate\Support\Facades\View;

class ThemeService
{
    public static function active(): Theme
    {
        return Theme::active();
    }

    /**
     * Resolve path Blade untuk sebuah komponen, dengan fallback ke theme "default"
     * kalau variant tersebut belum dibuat di theme yang sedang aktif.
     *
     * Contoh hasil: "themes.minimal.partials.hero.variant2"
     */
    public static function componentView(Component $component): string
    {
        $theme = self::active();
        $path  = "themes.{$theme->slug}.{$component->resolved_view_path}";

        if (View::exists($path)) {
            return $path;
        }

        // Fallback: coba cari di theme default
        $fallback = "themes.default.{$component->resolved_view_path}";

        if (View::exists($fallback)) {
            return $fallback;
        }

        // Fallback terakhir: placeholder biar tidak error 500 di production
        return 'partials.component-missing';
    }

    /**
     * Resolve path Blade untuk sebuah layout.
     */
    public static function layoutView(string $layoutViewPath, ?string $themeSlug = null): string
    {
        $slug = $themeSlug ?? self::active()->slug;
        return "themes.{$slug}.{$layoutViewPath}";
    }

    /**
     * Daftar variant yang tersedia untuk satu type komponen di theme aktif,
     * berguna untuk dropdown "pilih variant" di admin panel.
     */
    public static function availableVariants(string $type): \Illuminate\Support\Collection
    {
        return Component::ofType($type)
            ->where('theme_id', self::active()->id)
            ->where('is_active', true)
            ->get(['id', 'name', 'variant', 'thumbnail']);
    }
}
