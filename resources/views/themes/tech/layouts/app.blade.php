{{-- resources/views/layouts/app.blade.php --}}
@php
    $themeSlug = \App\Services\ThemeService::active()->slug;
@endphp
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Script in head to prevent dark mode flicker --}}
    <script>
        const storedTheme = localStorage.getItem('theme');
        const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        document.documentElement.setAttribute('data-bs-theme', storedTheme || systemTheme);
    </script>

    {{-- Meta SEO dinamis --}}
    @includeWhen(isset($model), "themes.{$themeSlug}.components.seo-meta", ['model' => $model ?? null])

    @include("themes.{$themeSlug}.components.styles")
</head>
<body>

    @include("themes.{$themeSlug}.partials.header", ['headerMenu' => $headerMenu ?? null])

    <main class="py-10">
        {{-- Region: content --}}
        @if(isset($page) && is_array($page->content))
            @foreach($page->content as $block)
                @include('partials.dynamic-component', ['block' => $block])
            @endforeach
        @elseif(isset($page) && is_string($page->content))
            {!! $page->content !!}
        @else
            {{ $slot ?? '' }}
            @yield('content')
        @endif
    </main>

    @include("themes.{$themeSlug}.partials.pre-footer")
    @include("themes.{$themeSlug}.partials.footer", ['footerMenu' => $footerMenu ?? null])

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @include("themes.{$themeSlug}.components.scripts")
</body>
</html>
