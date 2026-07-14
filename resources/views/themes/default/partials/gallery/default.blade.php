{{--
    Gallery — Default Variant
    Fields: title, description, subtitle,
            images[]: image_id, caption, url
--}}
<section class="gallery gallery--default py-20 bg-white">
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

        @if(!empty($data['images']))
            <div class="columns-1 sm:columns-2 lg:columns-3 gap-4 space-y-4">
                @foreach($data['images'] as $item)
                    @php $media = !empty($item['image_id']) ? \App\Models\Media::find($item['image_id']) : null; @endphp
                    @if($media)
                        <div class="break-inside-avoid group relative overflow-hidden rounded-xl shadow-md">
                            @if(!empty($item['url']))
                                <a href="{{ $item['url'] }}" target="_blank" rel="noopener">
                            @endif
                                <img src="{{ $media->url }}" alt="{{ $item['caption'] ?? '' }}"
                                     class="w-full object-cover transition duration-300 group-hover:scale-105">
                                @if(!empty($item['caption']))
                                    <div class="absolute bottom-0 inset-x-0 bg-black/50 text-white text-sm px-4 py-2 translate-y-full group-hover:translate-y-0 transition duration-300">
                                        {{ $item['caption'] }}
                                    </div>
                                @endif
                            @if(!empty($item['url']))
                                </a>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</section>
