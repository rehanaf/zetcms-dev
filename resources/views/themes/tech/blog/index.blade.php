@extends('themes.elegant.layouts.app')

@section('content')
<section class="py-5 bg-main">
    <div class="container py-4">
        {{-- Header Blog --}}
        <div class="text-center mb-5 pb-3 border-bottom border-accent border-opacity-25">
            <h1 class="display-4 font-serif text-primary fw-bold mb-3">Jurnal & Berita</h1>
            <p class="text-muted font-sans lead max-w-lg mx-auto">Kumpulan artikel, cerita inspiratif, dan wawasan eksklusif dari kami.</p>
        </div>

        <div class="row g-5">
            {{-- Main Content --}}
            <div class="col-lg-8">
                
                {{-- Search & Filter --}}
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 p-4 bg-white rounded shadow-sm border border-accent border-opacity-25">
                    <form action="{{ route('blog.index') }}" method="GET" class="w-100" style="max-width: 400px;">
                        <div class="input-group">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari jurnal..." class="form-control rounded-start bg-main border-0 focus-ring-accent" style="box-shadow: none;">
                            <button type="submit" class="btn btn-gradient"><i class="fa-solid fa-search"></i></button>
                        </div>
                    </form>

                    @if(request('q') || request('category') || request('tag'))
                        <div class="mt-3 mt-md-0">
                            <a href="{{ route('blog.index') }}" class="btn btn-sm btn-outline-dark font-sans rounded-pill px-3 py-1">
                                <i class="fa-solid fa-xmark me-1"></i> Reset Filter
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Articles List --}}
                @if($posts->isEmpty())
                    <div class="text-center py-5">
                        <i class="fa-regular fa-folder-open text-muted mb-3" style="font-size: 3rem;"></i>
                        <h4 class="font-serif text-primary">Tidak ada tulisan ditemukan</h4>
                        <p class="text-muted font-sans">Coba gunakan kata kunci pencarian yang lain.</p>
                    </div>
                @else
                    <div class="row g-4 mb-5">
                        @foreach($posts as $post)
                            <div class="col-md-6">
                                <article class="card h-100 border-0 rounded-0 bg-transparent">
                                    <div class="position-relative overflow-hidden shadow-sm mb-3">
                                        @if($post->featured_image)
                                            <a href="{{ route('page.show', $post->slug) }}" class="d-block">
                                                <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="w-100 object-fit-cover hover-scale" style="height: 220px; transition: transform 0.5s ease;">
                                            </a>
                                        @else
                                            <a href="{{ route('page.show', $post->slug) }}" class="d-block text-decoration-none">
                                                <div class="w-100 bg-main d-flex align-items-center justify-content-center text-center p-4 border border-accent border-opacity-25 hover-scale" style="height: 220px; transition: transform 0.5s ease;">
                                                    <span class="font-serif text-primary fs-4 fw-bold">{{ $post->title }}</span>
                                                </div>
                                            </a>
                                        @endif
                                        
                                        @if($post->category)
                                            <div class="position-absolute top-0 start-0 m-3">
                                                <a href="{{ route('category.show', $post->category->slug) }}" class="badge bg-accent text-dark rounded-pill py-1 px-3 shadow-sm text-decoration-none">
                                                    {{ $post->category->name }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="card-body p-0">
                                        <div class="d-flex align-items-center gap-2 text-muted small font-sans mb-2">
                                            <span><i class="fa-regular fa-calendar me-1"></i> {{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
                                            <span>•</span>
                                            <span><i class="fa-regular fa-eye me-1"></i> {{ $post->views }}</span>
                                        </div>
                                        
                                        <h3 class="font-serif fs-4 mb-2 line-clamp-2">
                                            <a href="{{ route('page.show', $post->slug) }}" class="text-primary text-decoration-none hover-text-primary text-gradient transition">{{ $post->title }}</a>
                                        </h3>
                                        
                                        <p class="text-secondary font-sans small mb-3 line-clamp-3">
                                            {{ $post->excerpt ?: strip_tags(Str::limit($post->content, 120)) }}
                                        </p>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 p-0 mt-auto">
                                        <div class="d-flex align-items-center justify-content-between pt-3 border-top border-accent border-opacity-25">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-surface-elevated text-primary text-gradient d-flex justify-content-center align-items-center fw-bold" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                    {{ substr($post->user->name ?? 'A', 0, 1) }}
                                                </div>
                                                <span class="font-sans small fw-semibold text-primary">{{ $post->user->name ?? 'Administrator' }}</span>
                                            </div>
                                            <a href="{{ route('page.show', $post->slug) }}" class="text-primary text-gradient text-decoration-none small fw-bold font-sans text-uppercase tracking-widest">
                                                Baca
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center">
                        {{ $posts->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="position-sticky" style="top: 100px;">
                    
                    {{-- Categories --}}
                    <div class="card bg-white border-0 shadow-sm rounded mb-4">
                        <div class="card-body p-4">
                            <h4 class="font-serif text-primary fs-5 mb-4 border-bottom border-accent border-opacity-25 pb-2">Kategori Tulisan</h4>
                            <ul class="list-unstyled mb-0 font-sans">
                                @foreach($categories as $category)
                                    <li class="mb-2">
                                        <a href="{{ route('category.show', $category->slug) }}" class="d-flex justify-content-between align-items-center text-decoration-none py-1 {{ request('category') === $category->slug ? 'text-primary text-gradient fw-bold' : 'text-secondary hover-text-primary text-gradient' }}">
                                            <span>{{ $category->name }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    {{-- Tags --}}
                    <div class="card bg-white border-0 shadow-sm rounded">
                        <div class="card-body p-4">
                            <h4 class="font-serif text-primary fs-5 mb-4 border-bottom border-accent border-opacity-25 pb-2">Topik Terkait</h4>
                            <div class="d-flex flex-wrap gap-2 font-sans">
                                @foreach($tags as $tag)
                                    <a href="{{ route('tag.show', $tag->slug) }}" class="badge rounded-pill fw-normal px-3 py-2 text-decoration-none border transition {{ request('tag') === $tag->slug ? 'bg-accent text-dark border-accent' : 'bg-transparent text-secondary border-secondary hover-bg-accent hover-text-dark hover-border-accent' }}">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .hover-scale:hover { transform: scale(1.05); }
        .hover-text-primary text-gradient:hover { color: var(--color-accent) !important; }
        .hover-bg-accent:hover { background-color: var(--color-accent) !important; }
        .hover-border-accent:hover { border-color: var(--color-accent) !important; }
    </style>
</section>
@endsection
