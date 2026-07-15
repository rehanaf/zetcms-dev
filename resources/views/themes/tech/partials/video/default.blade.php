{{--
    Video — Elegant Variant
    Fields: title, description, video_url, placeholder_image_id
--}}
<section class="py-5 bg-main">
    <div class="container py-4">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center max-w-lg mx-auto mb-5">
                @if(!empty($data['title']))
                    <h2 class="display-5 text-primary mt-1 font-serif">{{ $data['title'] }}</h2>
                @endif
                <div class="divider"></div>
                @if(!empty($data['description']))
                    <p class="text-muted mt-3 font-sans">{{ $data['description'] }}</p>
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
                $isEmbed = isset($isYoutube, $isVimeo) ? ($isYoutube || $isVimeo) : false;
                $placeholder = !empty($data['placeholder_image_id'])
                    ? \App\Models\Media::find($data['placeholder_image_id'])
                    : null;
            @endphp

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="position-relative p-3">
                        {{-- Bingkai Dekoratif --}}
                        <div class="position-absolute top-0 end-0 w-100 h-100 border border-3 border-accent rounded-4" style="transform: translate(-10px, -10px); z-index: 1;"></div>
                        <div class="position-absolute top-0 start-0 w-100 h-100 border border-3 border-accent rounded-4" style="transform: translate(10px, 10px); z-index: 1;"></div>
                        
                        <div class="position-relative rounded-4 overflow-hidden shadow-lg" style="padding-top: 56.25%; z-index: 2; background-color: var(--color-dark);">
                            @if($isEmbed)
                                <iframe src="{{ $embedUrl }}"
                                        class="position-absolute top-0 start-0 w-100 h-100"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                </iframe>
                            @else
                                {{-- File video langsung --}}
                                <video controls
                                       class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover"
                                       @if($placeholder) poster="{{ $placeholder->url }}" @endif>
                                    <source src="{{ $url }}" type="video/mp4">
                                    Browser Anda tidak mendukung pemutar video.
                                </video>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
