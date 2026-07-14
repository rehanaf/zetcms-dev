{{--
    Post — Default Variant
    Fields: title, description, subtitle,
            category_id, tag_id, search, post_ids[], limit
--}}
<section class="post post--default py-20 bg-slate-50">
    <div class="container mx-auto px-6">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center mb-14">
                @if(!empty($data['title']))
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-3">
                        {{ $data['title'] }}
                    </h2>
                @endif
                @if(!empty($data['description']))
                    <p class="text-slate-500 text-lg max-w-xl mx-auto mb-2">{{ $data['description'] }}</p>
                @endif
                @if(!empty($data['subtitle']))
                    <p class="text-slate-400 text-sm">{{ $data['subtitle'] }}</p>
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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    @php
                        $thumb = $post->featured_image_id
                            ? \App\Models\Media::find($post->featured_image_id)
                            : null;
                    @endphp
                    <article class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-lg transition group">
                        {{-- Thumbnail --}}
                        @if($thumb)
                            <a href="{{ route('page.show', $post->slug) }}" class="block overflow-hidden">
                                <img src="{{ $thumb->url }}" alt="{{ $post->title }}"
                                     class="w-full h-52 object-cover group-hover:scale-105 transition duration-300">
                            </a>
                        @endif

                        <div class="p-6">
                            {{-- Kategori --}}
                            @if($post->category)
                                <span class="inline-block text-xs font-medium text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full mb-3">
                                    {{ $post->category->name }}
                                </span>
                            @endif

                            <h3 class="text-lg font-bold text-slate-800 mb-2 leading-snug">
                                <a href="{{ route('page.show', $post->slug) }}"
                                   class="hover:text-indigo-600 transition">
                                    {{ $post->title }}
                                </a>
                            </h3>

                            @if($post->excerpt ?? false)
                                <p class="text-slate-500 text-sm line-clamp-3 mb-4">{{ $post->excerpt }}</p>
                            @endif

                            <div class="flex items-center justify-between text-xs text-slate-400 mt-4">
                                <span>{{ $post->published_at?->translatedFormat('d M Y') }}</span>
                                <a href="{{ route('page.show', $post->slug) }}"
                                   class="text-indigo-600 font-medium hover:underline">
                                    Baca &rarr;
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p class="text-center text-slate-400">Belum ada post yang ditampilkan.</p>
        @endif
    </div>
</section>
