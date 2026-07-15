{{--
    Team Member — Tech Variant
    Fields: title, description, subtitle,
            members[]: name, role, image_id, bio,
                       facebook_url, twitter_url, linkedin_url, instagram_url
--}}
<section class="py-5 bg-main">
    <div class="container py-4">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center max-w-lg mx-auto mb-5">
                @if(!empty($data['subtitle']))
                    <p class="text-primary text-gradient fw-bold m-0" style="letter-spacing: 2px;">{{ $data['subtitle'] }}</p>
                @endif
                @if(!empty($data['title']))
                    <h2 class="display-5 text-primary mt-1 fw-bolder tracking-tight">{{ $data['title'] }}</h2>
                @endif
                <div class="divider"></div>
                @if(!empty($data['description']))
                    <p class="text-muted mt-3 ">{{ $data['description'] }}</p>
                @endif
            </div>
        @endif

        {{-- Grid anggota --}}
        @if(!empty($data['members']))
            <div class="row g-4 justify-content-center">
                @foreach($data['members'] as $member)
                    @php
                        $media = !empty($member['image_id']) ? \App\Models\Media::find($member['image_id']) : null;
                    @endphp
                    <div class="col-lg-3 col-md-6">
                        <div class="card h-100 border-0 bg-white rounded-4 shadow-sm text-center p-4 feature-card">
                            {{-- Avatar --}}
                            @if($media)
                                <img src="{{ $media->url }}" alt="{{ $member['name'] ?? '' }}"
                                     class="rounded-circle object-fit-cover mx-auto mb-4 border border-2 border-primary" style="width: 120px; height: 120px;">
                            @else
                                <div class="rounded-circle bg-surface-elevated d-flex align-items-center justify-content-center mx-auto mb-4 border border-2 border-primary" style="width: 120px; height: 120px;">
                                    <span class="text-primary text-gradient fw-bolder tracking-tight fs-1">
                                        {{ strtoupper(substr($member['name'] ?? 'A', 0, 1)) }}
                                    </span>
                                </div>
                            @endif

                            @if(!empty($member['name']))
                                <h4 class="fw-bolder tracking-tight text-primary fw-bold m-0">{{ $member['name'] }}</h4>
                            @endif
                            @if(!empty($member['role']))
                                <p class="text-primary text-gradient  small fw-semibold mb-3">{{ $member['role'] }}</p>
                            @endif
                            @if(!empty($member['bio']))
                                <p class="text-muted small  mb-4">{{ $member['bio'] }}</p>
                            @endif

                            {{-- Sosial media --}}
                            <div class="d-flex justify-content-center gap-3 mt-auto">
                                @if(!empty($member['facebook_url']))
                                    <a href="{{ $member['facebook_url'] }}" target="_blank" class="text-muted hover-text-primary text-gradient transition">
                                        <i class="fa-brands fa-facebook fs-5"></i>
                                    </a>
                                @endif
                                @if(!empty($member['twitter_url']))
                                    <a href="{{ $member['twitter_url'] }}" target="_blank" class="text-muted hover-text-primary text-gradient transition">
                                        <i class="fa-brands fa-twitter fs-5"></i>
                                    </a>
                                @endif
                                @if(!empty($member['linkedin_url']))
                                    <a href="{{ $member['linkedin_url'] }}" target="_blank" class="text-muted hover-text-primary text-gradient transition">
                                        <i class="fa-brands fa-linkedin fs-5"></i>
                                    </a>
                                @endif
                                @if(!empty($member['instagram_url']))
                                    <a href="{{ $member['instagram_url'] }}" target="_blank" class="text-muted hover-text-primary text-gradient transition">
                                        <i class="fa-brands fa-instagram fs-5"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <style>
                .hover-text-primary text-gradient:hover { color: var(--color-primary) !important; }
            </style>
        @endif
    </div>
</section>
