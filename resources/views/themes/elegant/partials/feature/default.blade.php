{{--
    Feature — Elegant Variant
    Fields: title, subtitle,
            features[]: title, description, icon, image_id, url
--}}
<section class="py-5 bg-white">
    <div class="container py-4">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['subtitle']))
            <div class="text-center max-w-lg mx-auto mb-5" data-aos="fade-up">
                @if(!empty($data['subtitle']))
                    <p class="text-accent fw-bold m-0" style="letter-spacing: 2px;">{{ $data['subtitle'] }}</p>
                @endif
                @if(!empty($data['title']))
                    <h2 class="display-5 text-chocolate mt-1">{{ $data['title'] }}</h2>
                @endif
                <div class="divider"></div>
            </div>
        @endif

        {{-- Grid Fitur --}}
        @if(!empty($data['features']))
            <div class="row g-4">
                @foreach($data['features'] as $feature)
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="feature-card">
                            <div class="feature-icon-wrapper">
                                @if(!empty($feature['image_id']))
                                    @php $media = \App\Models\Media::find($feature['image_id']); @endphp
                                    @if($media)
                                        <img src="{{ $media->url }}" alt="{{ $feature['title'] ?? '' }}" class="img-fluid" style="max-height: 40px;">
                                    @endif
                                @elseif(!empty($feature['icon']))
                                    <i class="fa-solid fa-{{ str_replace('fa-', '', $feature['icon']) }}"></i>
                                @else
                                    <i class="fa-solid fa-star"></i>
                                @endif
                            </div>
                            
                            @if(!empty($feature['title']))
                                <h3 class="h4 text-chocolate mb-3">{{ $feature['title'] }}</h3>
                            @endif
                            
                            @if(!empty($feature['description']))
                                <p class="text-muted font-sans mb-3 small">{{ $feature['description'] }}</p>
                            @endif

                            @if(!empty($feature['url']))
                                <div class="mt-4 pt-2 border-top border-secondary border-opacity-25">
                                    <a href="{{ $feature['url'] }}" class="btn-elegant py-2 px-3 w-100 text-center d-block fs-7">
                                        Selengkapnya
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
