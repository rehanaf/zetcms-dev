{{--
    Post (Promo/Berita) — Tech Variant
    Fields: title, description, subtitle,
            category_id, tag_id, search, post_ids[], limit
--}}
<section id="promo" class="py-5 bg-white">
    <div class="container py-4">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']) || !empty($data['subtitle']))
            <div class="text-center max-w-lg mx-auto mb-5">
                @if(!empty($data['subtitle']))
                    <p class="text-primary text-gradient fw-bold m-0" style="letter-spacing: 2px;">{{ $data['subtitle'] }}</p>
                @endif
                @if(!empty($data['title']))
                    <h2 class="display-5 text-primary mt-1">{{ $data['title'] }}</h2>
                @endif
                <div class="divider"></div>
                @if(!empty($data['description']))
                    <p class="text-muted ">{{ $data['description'] }}</p>
                @endif
            </div>
        @endif

        @php
            $query = \App\Models\Post::where('status', 'published')
                ->orderBy('published_at', 'desc');

            if (!empty($data['post_ids'])) {
                $query->whereIn('id', $data['post_ids']);
            } else {
                if (!empty($data['category_id'])) {
                    $query->where('category_id', $data['category_id']);
                }
                if (!empty($data['tag_id'])) {
                    $query->whereHas('tags', fn($q) => $q->where('tags.id', $data['tag_id']));
                }
                if (!empty($data['search'])) {
                    $query->where('title', 'like', "%{$data['search']}%");
                }
            }
            $limit = (int) ($data['limit'] ?? 6);
            $posts = $query->limit($limit)->get();
        @endphp

        @if($posts->isNotEmpty())
            <div class="row g-4">
                @foreach($posts as $post)
                    @php
                        $thumb = $post->featured_image_id
                            ? \App\Models\Media::find($post->featured_image_id)
                            : null;
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="promo-card h-100 d-flex flex-column justify-content-between">
                            <div class="promo-img-wrapper">
                                @if($post->category)
                                    <span class="promo-badge">{{ $post->category->name }}</span>
                                @endif
                                @if($thumb)
                                    <img src="{{ $thumb->url }}" alt="{{ $post->title }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=600&q=80" alt="Placeholder">
                                @endif
                            </div>
                            <div class="p-4 d-flex flex-column justify-content-between flex-grow-1">
                                <div>
                                    <h4 class="fw-bolder tracking-tight display-8 text-light mb-3">{{ $post->title }}</h4>
                                    @if($post->excerpt ?? false)
                                        <p class="small text-white-50 ">{{ $post->excerpt }}</p>
                                    @endif
                                </div>
                                <div class="mt-4 pt-3 border-top border-secondary">
                                    <a href="{{ route('page.show', $post->slug) }}" class="text-primary text-gradient text-decoration-none fw-bold">Ambil Penawaran <i class="fa-solid fa-arrow-right ms-2"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-muted">Belum ada promo atau berita yang ditampilkan.</p>
        @endif
    </div>
</section>
