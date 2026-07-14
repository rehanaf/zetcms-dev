{{--
    Pricing (Dynamic) — Default Variant
    Fields: title, description, pricing_ids[]
    Relasi ke Model: \App\Models\Pricing
--}}
<section class="pricing pricing--default py-20 bg-slate-50">
    <div class="container mx-auto px-6">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center mb-14">
                @if(!empty($data['title']))
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-3">
                        {{ $data['title'] }}
                    </h2>
                @endif
                @if(!empty($data['description']))
                    <p class="text-slate-500 text-lg max-w-xl mx-auto">{{ $data['description'] }}</p>
                @endif
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
            <div class="grid grid-cols-1 md:grid-cols-{{ min($plans->count(), 3) }} gap-8 justify-center">
                @foreach($plans as $plan)
                    <div class="relative bg-white rounded-2xl border {{ $plan->is_featured ? 'border-indigo-500 shadow-2xl scale-105' : 'border-slate-200 shadow-md' }} p-8 flex flex-col">
                        @if($plan->is_featured ?? false)
                            <span class="absolute -top-4 left-1/2 -translate-x-1/2 bg-indigo-600 text-white text-xs font-bold px-4 py-1 rounded-full uppercase tracking-wide">
                                Populer
                            </span>
                        @endif

                        <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $plan->name }}</h3>
                        @if($plan->description ?? false)
                            <p class="text-slate-500 text-sm mb-4">{{ $plan->description }}</p>
                        @endif

                        <div class="mb-6">
                            <span class="text-4xl font-extrabold text-slate-900">{{ $plan->price }}</span>
                            @if($plan->period ?? false)
                                <span class="text-slate-400 text-sm ml-1">/ {{ $plan->period }}</span>
                            @endif
                        </div>

                        @if(!empty($plan->features))
                            <ul class="space-y-2 mb-8 flex-1">
                                @foreach((array) $plan->features as $feat)
                                    <li class="flex items-center gap-2 text-sm text-slate-600">
                                        <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ $feat }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if($plan->button_text ?? false)
                            <a href="{{ $plan->button_url ?? '#' }}"
                               class="block text-center px-6 py-3 {{ $plan->is_featured ? 'bg-indigo-600 text-white hover:bg-indigo-500' : 'bg-slate-100 text-slate-800 hover:bg-slate-200' }} font-semibold rounded-xl transition">
                                {{ $plan->button_text }}
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
