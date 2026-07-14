<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ZetCMS - Selamat Datang</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800 min-h-screen flex flex-col justify-between selection:bg-amber-500 selection:text-slate-900">

    {{-- Simple Header --}}
    <header class="bg-white border-b border-slate-200 py-4 shadow-sm">
        <div class="max-w-5xl mx-auto px-6 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-xl font-bold tracking-tight text-slate-900">
                Zet<span class="text-amber-600">CMS</span>
            </a>
            
            <nav class="flex items-center gap-6">
                @if(isset($headerMenu) && $headerMenu->items->count() > 0)
                    @foreach($headerMenu->items as $item)
                        @php
                            $url = $item->type === 'page' && $item->page ? url($item->page->slug) : 
                                  ($item->type === 'post' && $item->post ? url('blog/' . $item->post->slug) : $item->custom_url);
                        @endphp
                        <a href="{{ $url }}" target="{{ $item->target }}" class="text-sm font-medium text-slate-600 hover:text-amber-600 transition-colors">
                            {{ $item->label }}
                        </a>
                    @endforeach
                @else
                    <a href="{{ url('/') }}" class="text-sm font-medium text-amber-600">Home</a>
                    <a href="{{ url('/blog') }}" class="text-sm font-medium text-slate-600 hover:text-amber-600">Blog</a>
                @endif
                <a href="{{ url('/admin') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded shadow transition-all">
                    Admin Panel
                </a>
            </nav>
        </div>
    </header>

    {{-- Main Container --}}
    <main class="max-w-4xl mx-auto px-6 py-12 flex-grow w-full">
        
        {{-- Hero --}}
        <div class="text-center py-12 border-b border-slate-200">
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight mb-4">
                Selamat Datang di ZetCMS
            </h1>
            <p class="text-lg text-slate-600 max-w-xl mx-auto leading-relaxed mb-6">
                Sistem manajemen konten dinamis berbasis Laravel dan Filament. Halaman utama Anda siap dikonfigurasi melalui Admin Panel.
            </p>
            
            {{-- Alert --}}
            <div class="inline-block bg-amber-50 border border-amber-200 rounded-lg p-4 text-left max-w-md mx-auto">
                <p class="text-xs text-amber-800">
                    💡 <strong>Cara set Halaman Utama:</strong> Buat halaman baru di Admin Panel, pilih opsi <strong>"Set as Homepage"</strong>, ubah status ke <strong>"Published"</strong> dan simpan!
                </p>
            </div>
        </div>

        {{-- Latest Articles Section --}}
        <div class="py-12">
            <h2 class="text-2xl font-bold text-slate-950 mb-6 tracking-tight">Artikel Terbaru</h2>

            @if(isset($posts) && $posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach($posts as $post)
                        <article class="bg-white border border-slate-200 rounded-lg p-6 hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <span class="text-xs text-slate-500 block mb-2">
                                    {{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}
                                </span>
                                <h3 class="text-lg font-bold text-slate-900 mb-2">
                                    <a href="{{ url('blog/' . $post->slug) }}" class="hover:text-amber-600 transition-colors">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                <p class="text-sm text-slate-600 line-clamp-3 mb-4">
                                    {{ $post->excerpt ?: strip_tags($post->content) }}
                                </p>
                            </div>
                            <a href="{{ url('blog/' . $post->slug) }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700">
                                Baca Selengkapnya →
                            </a>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 bg-white border border-dashed border-slate-200 rounded-lg">
                    <p class="text-sm text-slate-500">Belum ada artikel yang dipublikasikan.</p>
                </div>
            @endif
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <p>© {{ date('Y') }} ZetCMS. Built with Laravel, Tailwind, & Filament (Albert Sans Font).</p>
    </footer>

</body>
</html>
