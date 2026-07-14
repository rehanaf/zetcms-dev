{{--
    About — Elegant Variant
    Fields: title, subtitle, content (rich), image_id,
            button_text, button_url
--}}
<section class="py-5 bg-cream">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                @if(!empty($data['subtitle']))
                    <p class="text-accent fw-bold mb-2 fs-6" style="letter-spacing: 2px;">{{ $data['subtitle'] }}</p>
                @endif
                
                @if(!empty($data['title']))
                    <h2 class="display-5 text-chocolate mb-3">{{ $data['title'] }}</h2>
                @endif
                
                <div class="divider-left"></div>
                
                @if(!empty($data['content']))
                    <div class="text-muted font-sans fs-6 mb-4">
                        {!! $data['content'] !!}
                    </div>
                @endif
                
                @if(!empty($data['button_text']))
                    <a href="{{ $data['button_url'] ?? '#' }}" class="btn-elegant">
                        {{ $data['button_text'] }}
                    </a>
                @endif
            </div>
            <div class="col-lg-6">
                @if(!empty($data['image_id']))
                    @php $media = \App\Models\Media::find($data['image_id']); @endphp
                    @if($media)
                        <div class="position-relative p-4">
                            <div class="position-absolute top-0 start-0 w-100 h-100 border border-3 border-accent rounded-3" style="transform: translate(-15px, -15px); z-index: 1;"></div>
                            <img src="{{ $media->url }}" alt="{{ $data['title'] ?? 'About' }}" class="img-fluid rounded-3 position-relative shadow-lg" style="z-index: 2; object-fit: cover; height: 450px; width: 100%;">
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</section>
