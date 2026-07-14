{{--
    Stats — Elegant Variant
    Fields: title, description,
            stats[]: number, label, icon
--}}
@php
    $bgUrl = 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1920&q=80';
    if(!empty($data['bg_image_id'])) {
        $bgMedia = \App\Models\Media::find($data['bg_image_id']);
        if($bgMedia) {
            $bgUrl = $bgMedia->url;
        }
    }
@endphp
<section class="py-5 bg-dark position-relative" style="background-image: linear-gradient(rgba(30,18,15,0.85), rgba(30,18,15,0.85)), url('{{ $bgUrl }}'); background-size: cover; background-position: center; background-attachment: fixed; border-top: 2px solid var(--color-accent); border-bottom: 2px solid var(--color-accent);">
    <div class="container py-5 text-center text-light position-relative" style="z-index: 2;">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="mb-5">
                @if(!empty($data['title']))
                    <h2 class="display-5 font-serif text-accent mb-3">{{ $data['title'] }}</h2>
                @endif
                @if(!empty($data['description']))
                    <p class="text-white-50 font-sans max-w-lg mx-auto">{{ $data['description'] }}</p>
                @endif
            </div>
        @endif

        @if(!empty($data['stats']))
            <div class="row justify-content-center g-4">
                @foreach($data['stats'] as $stat)
                    <div class="col-6 col-md-3">
                        <div class="stat-item p-3">
                            @if(!empty($stat['icon']))
                                <div class="text-accent fs-1 mb-3">
                                    <i class="{{ $stat['icon'] }}"></i>
                                </div>
                            @endif
                            <h3 class="display-4 font-serif text-white fw-bold mb-1">{{ $stat['number'] ?? '0' }}</h3>
                            <p class="text-accent font-sans small text-uppercase tracking-widest m-0" style="letter-spacing: 2px;">{{ $stat['label'] ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <style>
                .stat-item {
                    transition: transform 0.3s ease;
                }
                .stat-item:hover {
                    transform: translateY(-10px);
                }
            </style>
        @endif
    </div>
</section>
