{{-- Pricing Variant 1: 3 kolom, satu paket bisa di-highlight --}}
<section class="pricing-table pricing-table--variant1 py-16">
    <div class="container text-center">
        <h2 class="text-3xl font-bold mb-2">{{ $settings['heading'] ?? '' }}</h2>
        <p class="text-gray-500 mb-10">{{ $settings['subheading'] ?? '' }}</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($settings['plans'] ?? [] as $plan)
                <div class="plan-card p-8 rounded-xl border {{ ($plan['is_highlighted'] ?? false) ? 'border-blue-600 shadow-lg scale-105' : 'border-gray-200' }}">
                    <h3 class="text-xl font-semibold">{{ $plan['name'] }}</h3>
                    <p class="text-gray-500 text-sm mb-4">{{ $plan['description'] }}</p>

                    <div class="text-3xl font-bold mb-4">
                        {{ $plan['price'] }}
                        <span class="text-sm font-normal text-gray-400">{{ $plan['period'] ?? '' }}</span>
                    </div>

                    <ul class="text-left mb-6 space-y-2">
                        @foreach($plan['features'] ?? [] as $feature)
                            <li class="flex items-center gap-2"><span>✓</span> {{ $feature }}</li>
                        @endforeach
                    </ul>

                    <a href="{{ $plan['button_url'] ?? '#' }}"
                       class="btn {{ ($plan['is_highlighted'] ?? false) ? 'btn-primary' : 'btn-outline' }} w-full">
                        {{ $plan['button_text'] ?? 'Pilih' }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
