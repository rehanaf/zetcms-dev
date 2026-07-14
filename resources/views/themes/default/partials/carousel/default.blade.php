{{--
    Carousel — Default Variant
    Fields: title, description,
            items[]: image_id, title, subtitle, description, button_text, button_url
    Menggunakan Alpine.js untuk navigasi slide.
--}}
<section class="carousel carousel--default py-16 bg-slate-900">
    <div class="container mx-auto px-6">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center mb-10">
                @if(!empty($data['title']))
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">
                        {{ $data['title'] }}
                    </h2>
                @endif
                @if(!empty($data['description']))
                    <p class="text-slate-400 text-lg max-w-xl mx-auto">{{ $data['description'] }}</p>
                @endif
            </div>
        @endif

        @if(!empty($data['items']))
            @php $totalItems = count($data['items']); @endphp
            <div x-data="{ current: 0, total: {{ $totalItems }} }"
                 class="relative overflow-hidden rounded-2xl">

                {{-- Slides --}}
                <div class="flex transition-transform duration-500"
                     :style="`transform: translateX(-${current * 100}%)`">
                    @foreach($data['items'] as $item)
                        @php $media = !empty($item['image_id']) ? \App\Models\Media::find($item['image_id']) : null; @endphp
                        <div class="min-w-full relative" style="min-height: 400px;">
                            {{-- Background gambar --}}
                            @if($media)
                                <div class="absolute inset-0 bg-cover bg-center"
                                     style="background-image: url('{{ $media->url }}');"></div>
                                <div class="absolute inset-0 bg-black/50"></div>
                            @else
                                <div class="absolute inset-0 bg-slate-700"></div>
                            @endif

                            {{-- Konten slide --}}
                            <div class="relative z-10 flex flex-col items-center justify-center text-center h-full px-8 py-20">
                                @if(!empty($item['subtitle']))
                                    <p class="text-slate-300 uppercase tracking-widest text-sm mb-3">{{ $item['subtitle'] }}</p>
                                @endif
                                @if(!empty($item['title']))
                                    <h3 class="text-2xl md:text-4xl font-bold text-white mb-4">{{ $item['title'] }}</h3>
                                @endif
                                @if(!empty($item['description']))
                                    <p class="text-slate-200 text-base max-w-lg mb-6">{{ $item['description'] }}</p>
                                @endif
                                @if(!empty($item['button_text']))
                                    <a href="{{ $item['button_url'] ?? '#' }}"
                                       class="inline-block px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl transition">
                                        {{ $item['button_text'] }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Tombol navigasi --}}
                @if($totalItems > 1)
                    <button @click="current = current > 0 ? current - 1 : total - 1"
                            class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/20 hover:bg-white/40 rounded-full flex items-center justify-center text-white transition z-20">
                        &larr;
                    </button>
                    <button @click="current = current < total - 1 ? current + 1 : 0"
                            class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/20 hover:bg-white/40 rounded-full flex items-center justify-center text-white transition z-20">
                        &rarr;
                    </button>

                    {{-- Dots --}}
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
                        @for($i = 0; $i < $totalItems; $i++)
                            <button @click="current = {{ $i }}"
                                    class="w-2.5 h-2.5 rounded-full transition"
                                    :class="current === {{ $i }} ? 'bg-white' : 'bg-white/40'">
                            </button>
                        @endfor
                    </div>
                @endif
            </div>
        @endif
    </div>
</section>
