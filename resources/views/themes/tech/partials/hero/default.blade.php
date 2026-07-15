{{--
    Hero — Tech Variant
    Fields: title, subtitle, content (rich), image_id,
            primary_btn_text, primary_btn_url,
            secondary_btn_text, secondary_btn_url
--}}
@php
    $heroBgUrl = '';
    if(!empty($data['bg_image_id'])) {
        $bgMedia = \App\Models\Media::find($data['bg_image_id']);
        if($bgMedia) {
            $heroBgUrl = $bgMedia->url;
        }
    }
@endphp
<section class="py-5" style="background-color: var(--color-dark); {{ $heroBgUrl ? "background-image: linear-gradient(rgba(30,18,15,0.9), rgba(30,18,15,0.9)), url('{$heroBgUrl}'); background-size: cover; background-position: center; background-attachment: fixed;" : "" }} color: var(--color-light); border-bottom: 2px solid var(--color-accent);">
    <div class="container py-5 mt-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                @if(!empty($data['subtitle']))
                    <p class="text-primary text-gradient fw-bold m-0" style="letter-spacing: 2px;">{{ $data['subtitle'] }}</p>
                @endif
                
                @if(!empty($data['title']))
                    <h1 class="display-4 font-serif text-white mt-1">{{ $data['title'] }}</h1>
                @endif
                
                <div class="divider-left"></div>
                
                @if(!empty($data['content']))
                    <div class="text-white-50 font-sans mb-4">
                        {!! $data['content'] !!}
                    </div>
                @endif
                
                <div class="d-flex gap-3 flex-wrap">
                    @if(!empty($data['primary_btn_text']))
                        <a href="{{ $data['primary_btn_url'] ?? '#' }}" class="btn-gradient">
                            {{ $data['primary_btn_text'] }}
                        </a>
                    @endif
                    @if(!empty($data['secondary_btn_text']))
                        <a href="{{ $data['secondary_btn_url'] ?? '#' }}" class="btn btn-outline-primary">
                            {{ $data['secondary_btn_text'] }}
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                @php
                    $bgUrl = 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=800&q=80';
                    if(!empty($data['image_id'])) {
                        $media = \App\Models\Media::find($data['image_id']);
                        if($media) $bgUrl = $media->url;
                    }
                @endphp
                <div class="position-relative p-3">
                    <div class="position-absolute top-0 end-0 w-100 h-100 border border-3 border-accent rounded-3" style="transform: translate(15px, 15px); z-index: 1;"></div>
                    <img src="{{ $bgUrl }}" alt="{{ $data['title'] ?? 'Hero Image' }}" class="img-fluid rounded-3 position-relative shadow-lg w-100 object-fit-cover" style="z-index: 2; height: 400px;">
                </div>
            </div>
        </div>
    </div>
</section>
