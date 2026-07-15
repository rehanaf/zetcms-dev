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
<section class="py-5 position-relative overflow-hidden" style="background-color: var(--bg-main); {{ $heroBgUrl ? "background-image: linear-gradient(rgba(15,23,42,0.85), rgba(15,23,42,0.95)), url('{$heroBgUrl}'); background-size: cover; background-position: center; background-attachment: fixed;" : "" }}">
    <!-- Tech decorative elements -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 20% 30%, rgba(var(--color-primary-rgb), 0.15) 0%, transparent 50%); z-index: 0;"></div>
    
    <div class="container py-5 mt-5 position-relative" style="z-index: 2;">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                @if(!empty($data['subtitle']))
                    <div class="d-inline-flex align-items-center px-3 py-1 rounded-pill mb-3" style="background: rgba(var(--color-primary-rgb), 0.1); border: 1px solid rgba(var(--color-primary-rgb), 0.2);">
                        <span class="text-primary fw-bold" style="letter-spacing: 1px; font-size: 0.85rem;"><i class="fa-solid fa-microchip me-2"></i>{{ $data['subtitle'] }}</span>
                    </div>
                @endif
                
                @if(!empty($data['title']))
                    <h1 class="display-4 fw-bolder tracking-tight mb-4" style="{{ $heroBgUrl ? 'color: #fff;' : 'color: var(--text-main);' }}">{{ $data['title'] }}</h1>
                @endif
                
                @if(!empty($data['content']))
                    <div class="fs-5 mb-5" style="{{ $heroBgUrl ? 'color: rgba(255,255,255,0.7);' : 'color: var(--text-muted);' }} line-height: 1.8;">
                        {!! $data['content'] !!}
                    </div>
                @endif
                
                <div class="d-flex gap-3 flex-wrap">
                    @if(!empty($data['primary_btn_text']))
                        <a href="{{ $data['primary_btn_url'] ?? '#' }}" class="btn-gradient px-4 py-3 d-flex align-items-center">
                            {{ $data['primary_btn_text'] }} <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    @endif
                    @if(!empty($data['secondary_btn_text']))
                        <a href="{{ $data['secondary_btn_url'] ?? '#' }}" class="btn btn-outline-primary px-4 py-3 d-flex align-items-center" style="border-width: 2px;">
                            {{ $data['secondary_btn_text'] }}
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                @php
                    $bgUrl = 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80';
                    if(!empty($data['image_id'])) {
                        $media = \App\Models\Media::find($data['image_id']);
                        if($media) $bgUrl = $media->url;
                    }
                @endphp
                <div class="position-relative p-3">
                    <!-- Glassmorphism Tech Frame -->
                    <div class="position-absolute w-100 h-100 rounded-4" style="top: 20px; right: -20px; background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0)); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.18); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3); z-index: 1;"></div>
                    
                    <!-- Decorative Dots -->
                    <div class="position-absolute" style="top: -20px; left: -20px; z-index: 3; color: var(--color-primary); opacity: 0.5;">
                        <i class="fa-solid fa-braille fs-1"></i>
                    </div>

                    <img src="{{ $bgUrl }}" alt="{{ $data['title'] ?? 'Hero Image' }}" class="img-fluid rounded-4 position-relative w-100 object-fit-cover shadow" style="z-index: 2; height: 450px; animation: float 6s ease-in-out infinite;">
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
    100% { transform: translateY(0px); }
}
</style>
