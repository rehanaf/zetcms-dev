{{--
    Price Table (Manual) — Tech Variant (Default)
    Fields: title, description,
            categories[]:
                title, icon, image_id
                items[]: name, price, description
--}}
<section class="py-5 bg-main" id="menu">
    <div class="container py-4">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center max-w-lg mx-auto mb-5" data-aos="fade-up">
                @if(!empty($data['description']))
                    <p class="text-primary text-gradient fw-bold m-0" style="letter-spacing: 2px;">{{ $data['description'] }}</p>
                @endif
                @if(!empty($data['title']))
                    <h2 class="display-5 text-primary mt-1 font-serif">{{ $data['title'] }}</h2>
                @endif
                <div class="divider"></div>
            </div>
        @endif

        @if(!empty($data['categories']))
            <div class="row g-5">
                @foreach($data['categories'] as $category)
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 150 }}">
                        {{-- Category Title --}}
                        <div class="d-flex align-items-center mb-4">
                            @if(!empty($category['image_id']))
                                @php $catImg = \App\Models\Media::find($category['image_id']); @endphp
                                @if($catImg)
                                    <img src="{{ $catImg->url }}" alt="{{ $category['title'] ?? '' }}" class="rounded-circle me-3 border border-2 border-accent" style="width: 45px; height: 45px; object-fit: cover;">
                                @endif
                            @elseif(!empty($category['icon']))
                                <div class="text-primary text-gradient fs-3 me-3"><i class="{{ $category['icon'] }}"></i></div>
                            @endif
                            
                            <h3 class="font-serif text-primary m-0">{{ $category['title'] ?? '' }}</h3>
                        </div>

                        {{-- Category Items --}}
                        @if(!empty($category['items']))
                            <div class="d-flex flex-column">
                                @foreach($category['items'] as $item)
                                    <div class="menu-item-box d-flex justify-content-between align-items-center mt-3">
                                        <div class="pe-3">
                                            <h5 class="fw-bold m-0 text-primary">{{ $item['name'] ?? '' }}</h5>
                                            @if(!empty($item['description']))
                                                <small class="text-muted font-sans">{{ $item['description'] }}</small>
                                            @endif
                                        </div>
                                        <div class="menu-price text-nowrap">{{ $item['price'] ?? '' }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
