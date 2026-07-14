{{--
    Partial meta tag SEO — pakai di <head> layout utama.
    Contoh: @include('components.seo-meta', ['model' => $post])
--}}
@php
    $seo = $model->seo ?? null;
    $siteName = \App\Models\Setting::get('site_name', config('app.name'));
@endphp

<title>{{ $model->meta_title ?? $siteName }} - {{ $siteName }}</title>
@php
    $siteFavicon = \App\Models\Setting::get('site_favicon');
@endphp
@if($siteFavicon)
    <link rel="icon" href="{{ \Illuminate\Support\Facades\Storage::url($siteFavicon) }}">
@endif
<meta name="description" content="{{ $model->meta_description ?? '' }}">
@if($seo?->meta_keywords)
    <meta name="keywords" content="{{ $seo->meta_keywords }}">
@endif
<meta name="robots" content="{{ $seo?->robots_content ?? 'index, follow' }}">
@if($seo?->canonical_url)
    <link rel="canonical" href="{{ $seo->canonical_url }}">
@else
    <link rel="canonical" href="{{ url()->current() }}">
@endif

{{-- Open Graph --}}
<meta property="og:type" content="{{ $seo?->og_type ?? 'website' }}">
<meta property="og:title" content="{{ $seo?->og_title ?? $model->meta_title }}">
<meta property="og:description" content="{{ $seo?->og_description ?? $model->meta_description }}">
<meta property="og:image" content="{{ $model->og_image ? asset($model->og_image) : '' }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="{{ $siteName }}">

{{-- Twitter Card --}}
<meta name="twitter:card" content="{{ $seo?->twitter_card ?? 'summary_large_image' }}">
<meta name="twitter:title" content="{{ $seo?->twitter_title ?? $model->meta_title }}">
<meta name="twitter:description" content="{{ $seo?->twitter_description ?? $model->meta_description }}">
<meta name="twitter:image" content="{{ $seo?->twitter_image ? asset($seo->twitter_image) : '' }}">

{{-- Structured Data (Schema.org) --}}
@if($seo?->schema_markup)
    <script type="application/ld+json">
        {!! json_encode($seo->schema_markup, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endif
