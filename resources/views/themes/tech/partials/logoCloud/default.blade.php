{{--
    Logo Cloud — Elegant Variant
    Fields: title, description,
            logos[]: image_id, name, url
--}}
<section class="py-5 bg-white border-top border-bottom" style="border-color: rgba(212,175,55,0.2) !important;">
    <div class="container py-4">
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center mb-5">
                @if(!empty($data['title']))
                    <h4 class="font-serif text-primary mb-2">{{ $data['title'] }}</h4>
                @endif
                @if(!empty($data['description']))
                    <p class="text-muted font-sans small m-0">{{ $data['description'] }}</p>
                @endif
            </div>
        @endif

        @if(!empty($data['logos']))
            <div class="d-flex flex-wrap justify-content-center align-items-center gap-4 gap-md-5">
                @foreach($data['logos'] as $logo)
                    @php $media = !empty($logo['image_id']) ? \App\Models\Media::find($logo['image_id']) : null; @endphp
                    @if($media)
                        <div class="logo-item opacity-50 hover-opacity-100 transition" style="transition: all 0.4s ease; filter: grayscale(100%);">
                            @if(!empty($logo['url']))
                                <a href="{{ $logo['url'] }}" target="_blank" rel="noopener">
                            @endif
                                <img src="{{ $media->url }}" alt="{{ $logo['name'] ?? 'Partner Logo' }}" class="img-fluid" style="height: 50px; object-fit: contain;">
                            @if(!empty($logo['url']))
                                </a>
                            @endif
                        </div>
                    @elseif(!empty($logo['name']))
                        <span class="font-sans fw-bold text-muted fs-5 opacity-50">{{ $logo['name'] }}</span>
                    @endif
                @endforeach
            </div>
            <style>
                .logo-item:hover {
                    opacity: 1 !important;
                    filter: grayscale(0%) !important;
                    transform: scale(1.05);
                }
            </style>
        @endif
    </div>
</section>
