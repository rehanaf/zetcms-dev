@extends('themes.default.layouts.app')

@section('content')
<div class="bg-white border border-slate-200 text-slate-900 py-12 mb-10 rounded-2xl shadow-sm">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl font-extrabold tracking-tight mb-2 text-slate-900">Blog & Artikel</h1>
        <p class="text-slate-600 text-lg max-w-xl mx-auto">Temukan berita terbaru, panduan, dan insight seputar teknologi dari kami.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-3 space-y-8">
        
        <!-- Search & Filter Status -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-xl shadow-sm border border-slate-100">
            <form action="{{ route('blog.index') }}" method="GET" class="relative w-full md:max-w-sm">
                <input 
                    type="text" 
                    name="q" 
                    placeholder="Cari artikel..." 
                    value="{{ request('q') }}"
                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm"
                />
                <div class="absolute left-3 top-2.5 text-slate-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </form>

            @if(request('q') || request('category') || request('tag'))
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-500">Filter Aktif:</span>
                    <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 px-3 py-1 rounded-full text-xs font-semibold hover:bg-amber-100">
                        Reset Filter
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </div>
            @endif
        </div>

        <!-- Posts List -->
        @if($posts->isEmpty())
            <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-slate-200">
                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-slate-900">Artikel tidak ditemukan</h3>
                <p class="mt-1 text-sm text-slate-500">Coba kata kunci lain atau bersihkan filter pencarian Anda.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($posts as $post)
                    <article class="bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                        <div>
                            <div class="relative aspect-video bg-slate-100 overflow-hidden">
                                @if($post->featured_image)
                                    <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="object-cover w-full h-full hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300 font-bold bg-gradient-to-br from-slate-50 to-slate-100">
                                        ZetCMS
                                    </div>
                                @endif
                                
                                @if($post->category)
                                    <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="absolute top-3 left-3 bg-amber-500 text-white text-xs px-2.5 py-1 rounded-full font-bold uppercase tracking-wider">
                                        {{ $post->category->name }}
                                    </a>
                                @endif
                            </div>

                            <div class="p-6">
                                <div class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                                    <span>{{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
                                    <span>•</span>
                                    <span>{{ $post->views }} Views</span>
                                </div>
                                <h2 class="text-xl font-bold text-slate-900 mb-2 line-clamp-2 hover:text-amber-500 transition-colors">
                                    <a href="{{ route('page.show', $post->slug) }}">{{ $post->title }}</a>
                                </h2>
                                <p class="text-sm text-slate-600 line-clamp-3 mb-4">
                                    {{ $post->excerpt ?: strip_tags(Str::limit($post->content, 120)) }}
                                </p>
                            </div>
                        </div>

                        <div class="px-6 pb-6 pt-2 border-t border-slate-50 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="h-7 w-7 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-700">
                                    {{ substr($post->user->name ?? 'A', 0, 1) }}
                                </div>
                                <span class="text-xs font-semibold text-slate-700">{{ $post->user->name ?? 'Administrator' }}</span>
                            </div>
                            <a href="{{ route('page.show', $post->slug) }}" class="text-xs font-bold text-amber-600 hover:text-amber-700 inline-flex items-center gap-1">
                                Baca Selengkapnya 
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @endif
    </div>

    <!-- Sidebar Widget -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Categories Widget -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Kategori
            </h3>
            <ul class="space-y-2">
                @foreach($categories as $category)
                    <li>
                        <a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="flex items-center justify-between text-sm text-slate-600 hover:text-amber-500 py-1 font-medium {{ request('category') === $category->slug ? 'text-amber-500 font-bold' : '' }}">
                            <span>{{ $category->name }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Tags Widget -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <h3 class="font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Tags
            </h3>
            <div class="flex flex-wrap gap-2">
                @foreach($tags as $tag)
                    <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}" class="px-3 py-1 rounded-lg text-xs font-semibold border border-slate-100 hover:border-amber-500 hover:text-amber-600 transition-colors {{ request('tag') === $tag->slug ? 'bg-amber-500 text-white border-amber-500' : 'bg-slate-50 text-slate-600' }}">
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
