{{--
    Stats — Default Variant
    Fields: title, description,
            stats[]: number, label, icon
--}}
<section class="stats stats--default py-20 bg-indigo-700">
    <div class="container mx-auto px-6">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center mb-14">
                @if(!empty($data['title']))
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">
                        {{ $data['title'] }}
                    </h2>
                @endif
                @if(!empty($data['description']))
                    <p class="text-indigo-200 text-lg max-w-xl mx-auto">{{ $data['description'] }}</p>
                @endif
            </div>
        @endif

        @if(!empty($data['stats']))
            <div class="grid grid-cols-2 md:grid-cols-{{ min(count($data['stats']), 4) }} gap-8">
                @foreach($data['stats'] as $stat)
                    <div class="text-center p-6 bg-white/10 backdrop-blur rounded-2xl">
                        @if(!empty($stat['icon']))
                            <div class="text-4xl mb-3">{{ $stat['icon'] }}</div>
                        @endif
                        <div class="text-4xl md:text-5xl font-extrabold text-white mb-2">
                            {{ $stat['number'] ?? '0' }}
                        </div>
                        <div class="text-indigo-200 text-sm uppercase tracking-widest">
                            {{ $stat['label'] ?? '' }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
