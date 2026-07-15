{{--
    Pricing (Menu) — Elegant Variant
    Fields: title, description, pricing_ids[]
    Relasi ke Model: \App\Models\Pricing
--}}
<section class="py-5 bg-main">
    <div class="container py-4">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center max-w-lg mx-auto mb-5">
                @if(!empty($data['description']))
                    <p class="text-primary text-gradient fw-bold m-0" style="letter-spacing: 2px;">{{ $data['description'] }}</p>
                @endif
                @if(!empty($data['title']))
                    <h2 class="display-5 text-primary mt-1">{{ $data['title'] }}</h2>
                @endif
                <div class="divider"></div>
            </div>
        @endif

        @php
            $plans = collect();
            if (!empty($data['pricing_ids'])) {
                $plans = \App\Models\Pricing::where('is_active', true)
                    ->whereIn('id', $data['pricing_ids'])
                    ->orderBy('order')
                    ->get();
            }
        @endphp

        @if($plans->isNotEmpty())
            <div class="row g-4">
                @foreach($plans as $plan)
                    <div class="col-lg-6">
                        <div class="menu-item-box d-flex justify-content-between align-items-center mt-3">
                            <div class="pe-3">
                                <h5 class="fw-bold m-0 text-primary">{{ $plan->name }}</h5>
                                @if($plan->description ?? false)
                                    <small class="text-muted font-sans">{{ $plan->description }}</small>
                                @endif
                            </div>
                            <div class="menu-price text-nowrap">{{ $plan->price }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
