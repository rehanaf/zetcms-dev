{{--
    Pricing (Table) — Elegant Variant (Default)
    Fields: title, description, pricing_ids[]
    Relasi ke Model: \App\Models\Pricing
--}}
<section class="py-5 bg-white">
    <div class="container py-4">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center max-w-lg mx-auto mb-5">
                @if(!empty($data['description']))
                    <p class="text-accent fw-bold m-0" style="letter-spacing: 2px;">{{ $data['description'] }}</p>
                @endif
                @if(!empty($data['title']))
                    <h2 class="display-5 text-chocolate mt-1">{{ $data['title'] }}</h2>
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
            <div class="row justify-content-center g-4">
                @foreach($plans as $plan)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 rounded-4 {{ $plan->is_featured ? 'shadow-lg bg-chocolate-dark text-light' : 'shadow-sm bg-cream' }}" style="transition: transform 0.3s ease; {{ $plan->is_featured ? 'transform: scale(1.05); z-index: 2;' : '' }}">
                            <div class="card-body p-5 d-flex flex-column position-relative">
                                
                                <h4 class="font-serif text-center {{ $plan->is_featured ? 'text-accent mt-3' : 'text-chocolate' }} mb-2">{{ $plan->name }}</h4>
                                
                                @if($plan->description)
                                    <p class="text-center {{ $plan->is_featured ? 'text-white-50' : 'text-muted' }} small mb-4">{{ $plan->description }}</p>
                                @endif
                                
                                <div class="text-center mb-4">
                                    <span class="display-6 fw-bold font-sans {{ $plan->is_featured ? 'text-white' : 'text-dark' }}">{{ $plan->price }}</span>
                                    @if($plan->period)
                                        <span class="{{ $plan->is_featured ? 'text-white-50' : 'text-muted' }}">/ {{ $plan->period }}</span>
                                    @endif
                                </div>
                                
                                @if(!empty($plan->features))
                                    <ul class="list-unstyled mb-5 flex-grow-1">
                                        @foreach((array) $plan->features as $feat)
                                            <li class="mb-3 d-flex align-items-center {{ $plan->is_featured ? 'text-light' : 'text-secondary' }}">
                                                <i class="fa-solid fa-check text-accent me-3"></i> {{ is_array($feat) ? ($feat['name'] ?? json_encode($feat)) : $feat }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                
                                @if($plan->button_text)
                                    <div class="mt-auto">
                                        @if($plan->is_featured)
                                            <a href="{{ $plan->button_url ?? '#' }}" class="btn-elegant w-100 text-center d-block">
                                                {{ $plan->button_text }}
                                            </a>
                                        @else
                                            <a href="{{ $plan->button_url ?? '#' }}" class="btn btn-outline-dark w-100 text-center d-block py-2 fw-semibold font-sans" style="border-radius: 4px;">
                                                {{ $plan->button_text }}
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
