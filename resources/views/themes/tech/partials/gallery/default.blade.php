{{--
    Gallery — Elegant Variant
    Fields: title, description, subtitle,
            images[]: image_id, caption, url
--}}
<section class="py-5 bg-white">
    <div class="container py-4">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center max-w-lg mx-auto mb-5">
                @if(!empty($data['subtitle']))
                    <p class="text-primary text-gradient fw-bold m-0" style="letter-spacing: 2px;">{{ $data['subtitle'] }}</p>
                @endif
                @if(!empty($data['title']))
                    <h2 class="display-5 text-primary mt-1 font-serif">{{ $data['title'] }}</h2>
                @endif
                <div class="divider"></div>
                @if(!empty($data['description']))
                    <p class="text-muted mt-3 font-sans">{{ $data['description'] }}</p>
                @endif
            </div>
        @endif

        @if(!empty($data['images']))
            <div class="row g-4">
                @foreach($data['images'] as $item)
                    @php $media = !empty($item['image_id']) ? \App\Models\Media::find($item['image_id']) : null; @endphp
                    @if($media)
                        <div class="col-sm-6 col-lg-4">
                            <div class="position-relative overflow-hidden rounded shadow-sm gallery-card">
                                @if(!empty($item['url']))
                                    <a href="{{ $item['url'] }}" target="_blank" class="d-block w-100 h-100">
                                @endif
                                    <img src="{{ $media->url }}" alt="{{ $item['caption'] ?? '' }}"
                                         class="w-100 object-fit-cover" style="height: 300px; transition: transform 0.5s ease;">
                                    
                                    <div class="gallery-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end p-4" style="background: linear-gradient(to top, rgba(30,18,15,0.9) 0%, rgba(30,18,15,0) 70%); opacity: 0; transition: opacity 0.4s ease;">
                                        @if(!empty($item['caption']))
                                            <p class="text-white font-serif fs-5 m-0" style="transform: translateY(20px); transition: transform 0.4s ease;">{{ $item['caption'] }}</p>
                                        @endif
                                    </div>
                                @if(!empty($item['url']))
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            
            <style>
                .gallery-card:hover img {
                    transform: scale(1.1);
                }
                .gallery-card:hover .gallery-overlay {
                    opacity: 1;
                }
                .gallery-card:hover .gallery-overlay p {
                    transform: translateY(0);
                }
            </style>
        @endif
    </div>
</section>
