{{--
    Feature — Tech Variant
    Fields: title, subtitle,
            features[]: title, description, icon, image_id, url
--}}
<section class="py-5" style="background-color: var(--bg-surface-elevated);">
    <div class="container py-4">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['subtitle']))
            <div class="text-center max-w-lg mx-auto mb-5" data-aos="fade-up">
                @if(!empty($data['subtitle']))
                    <p class="text-primary fw-bold m-0 text-uppercase tracking-wider" style="letter-spacing: 2px;">{{ $data['subtitle'] }}</p>
                @endif
                @if(!empty($data['title']))
                    <h2 class="display-5 fw-bolder mt-2 mb-3" style="color: var(--text-main);">{{ $data['title'] }}</h2>
                @endif
                <div class="divider"></div>
            </div>
        @endif

        {{-- Grid Fitur --}}
        @if(!empty($data['features']))
            <div class="row g-4 justify-content-center">
                @foreach($data['features'] as $feature)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="tech-feature-card h-100 p-4 rounded-4 position-relative overflow-hidden" style="background: var(--bg-surface); border: 1px solid rgba(var(--color-primary-rgb), 0.15); box-shadow: 0 10px 30px rgba(0,0,0,0.03); transition: all 0.3s ease; z-index: 1;">
                            <!-- Glowing background effect -->
                            <div class="position-absolute top-0 end-0 rounded-circle" style="width: 100px; height: 100px; background: radial-gradient(circle, rgba(var(--color-primary-rgb),0.1) 0%, rgba(var(--color-primary-rgb),0) 70%); transform: translate(30%, -30%); z-index: -1;"></div>

                            <div class="d-flex align-items-center mb-4">
                                <div class="feature-icon-wrapper rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, rgba(var(--color-primary-rgb), 0.1), rgba(var(--color-primary-rgb), 0.05)); color: var(--color-primary); border: 1px solid rgba(var(--color-primary-rgb), 0.2);">
                                    @if(!empty($feature['image_id']))
                                        @php $media = \App\Models\Media::find($feature['image_id']); @endphp
                                        @if($media)
                                            <img src="{{ $media->url }}" alt="{{ $feature['title'] ?? '' }}" class="img-fluid" style="max-height: 30px;">
                                        @endif
                                    @elseif(!empty($feature['icon']))
                                        <i class="fa-solid fa-{{ str_replace('fa-', '', $feature['icon']) }} fs-3"></i>
                                    @else
                                        <i class="fa-solid fa-microchip fs-3"></i>
                                    @endif
                                </div>
                                @if(!empty($feature['title']))
                                    <h3 class="h5 fw-bold mb-0" style="color: var(--text-main);">{{ $feature['title'] }}</h3>
                                @endif
                            </div>
                            
                            @if(!empty($feature['description']))
                                <p class="text-muted mb-4" style="line-height: 1.6;">{{ $feature['description'] }}</p>
                            @endif

                            @if(!empty($feature['url']))
                                <div class="mt-auto pt-3">
                                    <a href="{{ $feature['url'] }}" class="text-primary fw-semibold d-inline-flex align-items-center text-decoration-none feature-link">
                                        Explore <i class="fa-solid fa-arrow-right ms-2 transition-transform"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<style>
    .tech-feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.08) !important;
        border-color: var(--color-primary) !important;
    }
    .tech-feature-card:hover .feature-link i {
        transform: translateX(5px);
    }
    .transition-transform {
        transition: transform 0.3s ease;
    }
</style>
