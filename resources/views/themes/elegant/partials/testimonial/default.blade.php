{{--
    Testimonial — Elegant Variant
    Fields: title, description, subtitle,
            source: 'dynamic' | 'manual'
            -- dynamic: testimonial_ids[], limit
            -- manual: manual_testimonials[]: name, role, company, content, avatar_id, rating
--}}
@php
    $testiBgUrl = '';
    if(!empty($data['bg_image_id'])) {
        $bgMedia = \App\Models\Media::find($data['bg_image_id']);
        if($bgMedia) {
            $testiBgUrl = $bgMedia->url;
        }
    }
@endphp
<section class="quote-section py-5 text-center position-relative" style="{{ $testiBgUrl ? "background-image: linear-gradient(rgba(30,18,15,0.9), rgba(30,18,15,0.9)), url('{$testiBgUrl}'); background-size: cover; background-position: center; background-attachment: fixed;" : "" }}">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <i class="fa-solid fa-quote-left text-accent display-4 mb-3"></i>
                
                @if(!empty($data['title']))
                    <h2 class="display-6 text-white mb-4">{{ $data['title'] }}</h2>
                @endif
                
                @php
                    $testimonials = collect();
                    if (($data['source'] ?? 'dynamic') === 'dynamic') {
                        $query = \App\Models\Testimonial::where('is_active', true);
                        if (!empty($data['testimonial_ids'])) {
                            $query->whereIn('id', $data['testimonial_ids']);
                        }
                        $limit = (int) ($data['limit'] ?? 6);
                        $testimonials = $query->limit($limit)->get();
                    } else {
                        $testimonials = collect($data['manual_testimonials'] ?? []);
                    }
                @endphp

                @if($testimonials->isNotEmpty())
                    <div id="elegantCarouselTestimonial" class="carousel slide mt-4" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($testimonials as $index => $item)
                                @php
                                    $isModel = $item instanceof \Illuminate\Database\Eloquent\Model;
                                    $name    = $isModel ? $item->name    : ($item['name'] ?? '');
                                    $role    = $isModel ? $item->role    : ($item['role'] ?? '');
                                    $company = $isModel ? $item->company : ($item['company'] ?? '');
                                    $text    = $isModel ? $item->content : ($item['content'] ?? '');
                                    $rating  = $isModel ? $item->rating  : ($item['rating'] ?? 5);
                                @endphp
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <div class="text-accent mb-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa-solid fa-star {{ $i > $rating ? 'text-secondary' : '' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="font-serif fs-3 italic text-light mb-4">"{{ $text }}"</p>
                                    <div class="divider"></div>
                                    <p class="font-sans fw-bold m-0 tracking-widest text-accent fs-6">{{ $name }}</p>
                                    <small class="text-white-50">{{ implode(' - ', array_filter([$role, $company])) }}</small>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="testimonial-indicators d-flex justify-content-center gap-2 mt-4">
                            @foreach($testimonials as $index => $item)
                                <button type="button" data-bs-target="#elegantCarouselTestimonial" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active border-0 rounded-circle bg-accent' : 'border-0 rounded-circle bg-secondary' }}" style="width: 10px; height: 10px; cursor: pointer;"></button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
