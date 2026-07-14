@php
    $themeSlug = \App\Services\ThemeService::active()->slug;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @includeWhen(isset($model), "themes.{$themeSlug}.components.seo-meta", ['model' => $model ?? null])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    @stack('styles')
</head>
<body>

    @include("themes.{$themeSlug}.partials.header", ['headerMenu' => $headerMenu ?? null])

    <main>
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
    @include("themes.{$themeSlug}.partials.footer")

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>