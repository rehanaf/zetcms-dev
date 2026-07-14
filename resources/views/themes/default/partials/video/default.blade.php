{{--
    Video — Default Variant
    Fields: title, description, video_url, placeholder_image_id
--}}
<section class="video video--default py-20 bg-slate-900">
    <div class="container mx-auto px-6 max-w-4xl">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center mb-10">
                @if(!empty($data['title']))
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">
                        {{ $data['title'] }}
                    </h2>
                @endif
                @if(!empty($data['description']))
                    <p class="text-slate-400 text-lg max-w-xl mx-auto">{{ $data['description'] }}</p>
                @endif
            </div>
        @endif

        {{-- Video player --}}
        @if(!empty($data['video_url']))
            @php
                $url = $data['video_url'];
                // Deteksi YouTube
                $isYoutube = str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be');
                if ($isYoutube) {
                    preg_match('/(?:v=|youtu\.be\/)([^&\s]+)/', $url, $m);
                    $videoId = $m[1] ?? '';
                    $embedUrl = "https://www.youtube.com/embed/{$videoId}?rel=0";
                }
                // Deteksi Vimeo
                $isVimeo = str_contains($url, 'vimeo.com');
                if ($isVimeo) {
                    preg_match('/vimeo\.com\/(\d+)/', $url, $m);
                    $videoId = $m[1] ?? '';
                    $embedUrl = "https://player.vimeo.com/video/{$videoId}";
                }
                $isEmbed = $isYoutube || $isVimeo;
                $placeholder = !empty($data['placeholder_image_id'])
                    ? \App\Models\Media::find($data['placeholder_image_id'])
                    : null;
            @endphp

            @if($isEmbed)
                <div class="relative rounded-2xl overflow-hidden shadow-2xl" style="padding-top: 56.25%;">
                    <iframe src="{{ $embedUrl }}"
                            class="absolute inset-0 w-full h-full"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                    </iframe>
                </div>
            @else
                {{-- File video langsung --}}
                <video controls
                       class="rounded-2xl shadow-2xl w-full"
                       @if($placeholder) poster="{{ $placeholder->url }}" @endif>
                    <source src="{{ $url }}" type="video/mp4">
                    Browser Anda tidak mendukung pemutar video.
                </video>
            @endif
        @endif
    </div>
</section>
