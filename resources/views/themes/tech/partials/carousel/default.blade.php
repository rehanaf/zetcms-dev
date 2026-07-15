{{--
    Carousel — Tech Variant
    Fields: title, description,
            items[]: image_id, title, subtitle, description, button_text, button_url
--}}
<section id="hero" class="hero-carousel position-relative" style="height: 80vh; min-height: 600px;">
    <div class="carousel-inner-custom w-100 h-100">
        @if(!empty($data['items']))
            @foreach($data['items'] as $index => $item)
                @php
                    $bgUrl = 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=1920&q=80';
                    if(!empty($item['image_id'])) {
                        $media = \App\Models\Media::find($item['image_id']);
                        if($media) $bgUrl = $media->url;
                    }
                @endphp
                <div class="carousel-item-custom h-100 {{ $index === 0 ? 'active' : '' }}" style="background-image: url('{{ $bgUrl }}');">
                    <div class="carousel-caption-custom h-100 d-flex flex-column justify-content-center align-items-center">
                        @if(!empty($item['subtitle']))
                            <p class="text-primary text-gradient fw-semibold m-0 fs-5 mb-2">{{ $item['subtitle'] }}</p>
                        @endif
                        
                        @if(!empty($item['title']))
                            <h1 class="hero-title text-white">{{ $item['title'] }}</h1>
                        @endif
                        
                        @if(!empty($item['description']))
                            <div class="lead text-white-50 max-w-2xl mx-auto mb-4 font-sans fs-5">
                                {{ $item['description'] }}
                            </div>
                        @endif
                        
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            @if(!empty($item['button_text']))
                                <a href="{{ $item['button_url'] ?? '#' }}" class="btn-gradient">
                                    {{ $item['button_text'] }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    @if(!empty($data['items']) && count($data['items']) > 1)
        <!-- Controls -->
        <div class="carousel-control-custom carousel-control-prev-custom">
            <i class="fa-solid fa-chevron-left"></i>
        </div>
        <div class="carousel-control-custom carousel-control-next-custom">
            <i class="fa-solid fa-chevron-right"></i>
        </div>

        <!-- Indicators -->
        <div class="carousel-indicators-custom">
            @foreach($data['items'] as $index => $item)
                <button class="{{ $index === 0 ? 'active' : '' }}"></button>
            @endforeach
        </div>
    @endif
</section>
