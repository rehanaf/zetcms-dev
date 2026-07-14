{{--
    Render satu blok komponen dari database (dipakai oleh page builder).
    File ini SENGAJA ditaruh di luar folder themes/ karena logikanya universal
    untuk semua theme — dia yang menentukan file variant mana yang di-load.

    Contoh: @include('partials.dynamic-component', ['block' => $block])
--}}
@php
    $type = $block['type'] ?? null;
    $data = $block['data'] ?? [];
    $variant = $data['variant'] ?? '';
    
    // Resolve theme slug
    $themeSlug = \App\Services\ThemeService::active()->slug;
    
    // Path view yang diinginkan
    $viewPath = "themes.{$themeSlug}.partials.{$type}.{$variant}";
    
    // Jika variant yang dipilih tidak ada, cari fallback dinamis
    if (empty($variant) || !\Illuminate\Support\Facades\View::exists($viewPath)) {
        $themePath = resource_path("views/themes/{$themeSlug}/partials/{$type}");
        $found = false;
        
        // 1. Cari file blade apa saja yang tersedia di tema aktif
        if (is_dir($themePath)) {
            $files = glob($themePath . '/*.blade.php');
            if (!empty($files)) {
                $firstFile = basename($files[0], '.blade.php');
                $viewPath = "themes.{$themeSlug}.partials.{$type}.{$firstFile}";
                $found = true;
            }
        }
        
        // 2. Jika di tema aktif kosong, cari file blade apa saja di tema default
        if (!$found && $themeSlug !== 'default') {
            $defaultPath = resource_path("views/themes/default/partials/{$type}");
            if (is_dir($defaultPath)) {
                $files = glob($defaultPath . '/*.blade.php');
                if (!empty($files)) {
                    $firstFile = basename($files[0], '.blade.php');
                    $viewPath = "themes.default.partials.{$type}.{$firstFile}";
                }
            }
        }
    }
    
    // Check if page post or other components need query/filter/relation data
    if ($type === 'post') {
        $query = \App\Models\Post::published();
        if (!empty($data['post_ids'])) {
            $query->whereIn('id', $data['post_ids']);
        }
        if (!empty($data['category_id'])) {
            $query->where('category_id', $data['category_id']);
        }
        if (!empty($data['tag_id'])) {
            $query->whereHas('tags', function($q) use ($data) {
                $q->where('tags.id', $data['tag_id']);
            });
        }
        if (!empty($data['search'])) {
            $searchTerm = $data['search'];
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%");
            });
        }
        $limit = $data['limit'] ?? 6;
        $posts = $query->latest()->take($limit)->get();
        $data['posts'] = $posts;
    }
    
    // testimonial component query
    if ($type === 'testimonial') {
        $query = \App\Models\Testimonial::where('is_active', true);
        if (!empty($data['testimonial_ids'])) {
            $query->whereIn('id', $data['testimonial_ids']);
        }
        $limit = $data['limit'] ?? 6;
        $testimonials = $query->take($limit)->get();
        $data['testimonials'] = $testimonials;
    }

    // pricing component query
    if ($type === 'pricing') {
        $query = \App\Models\Pricing::where('is_active', true)->orderBy('order');
        if (!empty($data['pricing_ids'])) {
            $query->whereIn('id', $data['pricing_ids']);
        }
        $pricings = $query->get();
        $data['pricings'] = $pricings;
    }
@endphp

@if(\Illuminate\Support\Facades\View::exists($viewPath))
    @include($viewPath, ['data' => $data])
@else
    @include('partials.component-missing', ['type' => $type, 'variant' => $variant])
@endif
