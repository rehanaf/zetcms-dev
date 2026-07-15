@extends('themes.tech.layouts.app')

@section('content')
<section class="py-5 bg-main">
    <div class="container py-4">
        
        <div class="row justify-content-center">
            <div class="col-lg-9">
                
                {{-- Header Artikel --}}
                <div class="text-center mb-5">
                    @if($post->category)
                        <a href="{{ route('category.show', $post->category->slug) }}" class="badge bg-transparent border border-accent text-primary text-gradient rounded-pill py-2 px-4 mb-4 text-decoration-none text-uppercase tracking-widest font-sans shadow-sm">
                            {{ $post->category->name }}
                        </a>
                    @endif
                    
                    <h1 class="display-4 font-serif text-primary fw-bold mb-4">{{ $post->title }}</h1>
                    
                    <div class="d-flex align-items-center justify-content-center gap-3 font-sans text-muted small">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-surface-elevated text-primary text-gradient d-flex justify-content-center align-items-center fw-bold" style="width: 32px; height: 32px;">
                                {{ substr($post->user->name ?? 'A', 0, 1) }}
                            </div>
                            <span class="fw-semibold text-primary">{{ $post->user->name ?? 'Administrator' }}</span>
                        </div>
                        <span>•</span>
                        <span><i class="fa-regular fa-calendar me-1"></i> {{ $post->published_at ? $post->published_at->format('d M Y, H:i') : $post->created_at->format('d M Y, H:i') }}</span>
                        <span>•</span>
                        <span><i class="fa-regular fa-eye me-1"></i> {{ $post->views }} Views</span>
                    </div>
                </div>

                {{-- Featured Image --}}
                @if($post->featured_image)
                    <div class="rounded-4 overflow-hidden shadow-lg mb-5" style="max-height: 500px;">
                        <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="w-100 h-100 object-fit-cover">
                    </div>
                @endif

                {{-- Konten Artikel --}}
                <article class="blog-content font-sans text-secondary" style="font-size: 1.1rem; line-height: 1.8;">
                    {!! $post->content !!}
                </article>

                {{-- Tags --}}
                @if($post->tags->isNotEmpty())
                    <div class="mt-5 pt-4 border-top border-accent border-opacity-25 d-flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                            <a href="{{ route('tag.show', $tag->slug) }}" class="badge bg-white text-muted border border-secondary rounded-pill px-3 py-2 text-decoration-none hover-bg-accent hover-text-dark hover-border-accent transition font-sans fw-normal">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Related Posts --}}
                @if($post->relatedPosts->isNotEmpty())
                    <div class="mt-5 pt-5 border-top border-accent border-opacity-25">
                        <h3 class="font-serif text-primary mb-4">Baca Juga</h3>
                        <div class="row g-4">
                            @foreach($post->relatedPosts as $related)
                                <div class="col-md-6">
                                    <div class="d-flex gap-3 align-items-center bg-white p-3 rounded shadow-sm hover-shadow transition h-100">
                                        @if($related->featured_image)
                                            <img src="{{ asset($related->featured_image) }}" alt="{{ $related->title }}" class="rounded object-fit-cover" style="width: 80px; height: 80px;">
                                        @else
                                            <div class="rounded bg-main d-flex align-items-center justify-content-center text-muted fw-bold font-serif" style="width: 80px; height: 80px;">ZC</div>
                                        @endif
                                        <div>
                                            <h5 class="font-serif fs-6 mb-1 line-clamp-2">
                                                <a href="{{ route('page.show', $related->slug) }}" class="text-primary text-decoration-none hover-text-primary text-gradient transition">{{ $related->title }}</a>
                                            </h5>
                                            <span class="text-muted small font-sans"><i class="fa-regular fa-clock me-1"></i> {{ $related->published_at ? $related->published_at->format('d M Y') : $related->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Comments --}}
                <div class="mt-5 pt-5 border-top border-accent border-opacity-25">
                    <h3 class="font-serif text-primary mb-4">Diskusi & Komentar</h3>

                    @if(session('success'))
                        <div class="alert alert-success bg-white border-success text-success shadow-sm rounded mb-4 font-sans">
                            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    @php
                        $approvedComments = $post->comments()->where('status', 'approved')->whereNull('parent_id')->with('replies')->get();
                    @endphp

                    @if($approvedComments->isEmpty())
                        <p class="text-muted font-sans font-italic bg-white p-4 rounded shadow-sm text-center">Belum ada komentar. Jadilah yang pertama memberikan kesan!</p>
                    @else
                        <div class="mb-5">
                            @foreach($approvedComments as $comment)
                                <div class="bg-white p-4 rounded shadow-sm mb-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-main text-primary d-flex align-items-center justify-content-center fw-bold font-serif fs-5" style="width: 45px; height: 45px;">
                                                {{ substr($comment->author_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 font-serif fw-bold text-primary">{{ $comment->author_name }}</h6>
                                                <small class="text-muted font-sans">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        <button onclick="setParentId({{ $comment->id }}, '{{ $comment->author_name }}')" class="btn btn-sm btn-link text-primary text-gradient text-decoration-none fw-bold font-sans">Balas</button>
                                    </div>
                                    <p class="text-secondary font-sans mb-0 ps-5 ms-3">{{ $comment->content }}</p>

                                    @if($comment->replies->isNotEmpty())
                                        <div class="ps-5 ms-3 mt-4 border-start border-2 border-accent border-opacity-25 ps-4">
                                            @foreach($comment->replies->where('status', 'approved') as $reply)
                                                <div class="mb-3">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                            {{ substr($reply->author_name, 0, 1) }}
                                                        </div>
                                                        <h6 class="mb-0 font-serif fw-bold text-primary fs-6">{{ $reply->author_name }}</h6>
                                                        <small class="text-muted font-sans" style="font-size: 0.75rem;">• {{ $reply->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    <p class="text-secondary font-sans mb-0 small">{{ $reply->content }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Form Komentar --}}
                    <div class="mt-4 pt-4">
                        <h4 class="font-serif text-primary fw-bold mb-4" id="form-title">Tinggalkan Pesan</h4>
                        
                        <div id="reply-banner" class="d-none alert alert-light bg-main text-primary py-2 px-3 border-accent d-flex justify-content-between align-items-center font-sans mb-4">
                            <span class="small">Membalas pesan dari <strong id="replying-to"></strong></span>
                            <button type="button" onclick="clearParentId()" class="btn-close btn-close-dark text-muted" style="font-size: 0.75rem;"></button>
                        </div>

                        <form action="{{ route('blog.comment', $post->slug) }}" method="POST">
                            @csrf
                            <input type="hidden" name="parent_id" id="parent_id" value="">

                            @guest
                                <div class="row g-3 mb-3 font-sans">
                                    <div class="col-md-6">
                                        <label class="form-label text-secondary small fw-semibold">Nama Lengkap</label>
                                        <input type="text" name="author_name" required value="{{ old('author_name', session('comment_author_name')) }}" class="form-control bg-white text-dark border-secondary" placeholder="Nama Anda" style="box-shadow: none;">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-secondary small fw-semibold">Alamat Email</label>
                                        <input type="email" name="author_email" required value="{{ old('author_email', session('comment_author_email')) }}" class="form-control bg-white text-dark border-secondary" placeholder="nama@email.com" style="box-shadow: none;">
                                    </div>
                                </div>
                            @endguest

                            <div class="mb-4 font-sans">
                                <label class="form-label text-secondary small fw-semibold">Pesan</label>
                                <textarea name="content" rows="5" required class="form-control bg-white text-dark border-secondary" placeholder="Tulis inspirasi atau pertanyaan Anda di sini..." style="box-shadow: none;"></textarea>
                            </div>

                            <button type="submit" class="btn btn-gradient px-5 py-2 font-sans fw-bold">Kirim Pesan</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <style>
        .blog-content img { max-width: 100%; height: auto; border-radius: 0.5rem; margin-top: 1.5rem; margin-bottom: 1.5rem; }
        .blog-content h2, .blog-content h3 { font-family: var(--font-serif); color: var(--color-chocolate); margin-top: 2rem; margin-bottom: 1rem; }
        .blog-content p { margin-bottom: 1.25rem; }
        .blog-content blockquote { border-left: 4px solid var(--color-accent); padding-left: 1.5rem; font-style: italic; color: #555; background: #fffaf0; padding: 1.5rem; border-radius: 0 0.5rem 0.5rem 0; margin-y: 2rem; }
        .blog-content a { color: var(--color-accent); text-decoration: none; }
        .blog-content a:hover { text-decoration: underline; }
        
        .hover-shadow:hover { box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1) !important; transform: translateY(-3px); }
        .hover-text-primary text-gradient:hover { color: var(--color-accent) !important; }
        .hover-bg-accent:hover { background-color: var(--color-accent) !important; }
        .hover-border-accent:hover { border-color: var(--color-accent) !important; }
    </style>
    
    <script>
        function setParentId(id, name) {
            document.getElementById('parent_id').value = id;
            document.getElementById('replying-to').innerText = name;
            document.getElementById('reply-banner').classList.remove('d-none');
            document.getElementById('form-title').scrollIntoView({ behavior: 'smooth' });
        }

        function clearParentId() {
            document.getElementById('parent_id').value = '';
            document.getElementById('reply-banner').classList.add('d-none');
        }
    </script>
</section>
@endsection
