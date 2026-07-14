{{-- resources/views/layouts/app.blade.php --}}
@php
    // Layout ini sendiri sudah "milik" theme default (karena file-nya ada di dalam
    // folder themes/default/), jadi partial header/footer/seo-meta yang di-include
    // di sini juga diarahkan ke folder theme yang sama. Kalau butuh switch dinamis
    // (mis. satu layout dipakai lintas theme), pakai ThemeService::layoutView().
    $themeSlug = \App\Services\ThemeService::active()->slug;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Meta SEO dinamis berdasarkan model yang dikirim controller (Post/Page/Category) --}}
    @includeWhen(isset($model), "themes.{$themeSlug}.components.seo-meta", ['model' => $model ?? null])

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800">

    @include("themes.{$themeSlug}.partials.header", ['headerMenu' => $headerMenu ?? null])

    <main class="container py-10">
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
    @include("themes.{$themeSlug}.partials.footer")

    @vite(['resources/js/app.js'])
</body>
</html>
