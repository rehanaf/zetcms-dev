{{--
    Feature — Default Variant
    Fields: title, subtitle,
            features[]: title, description, icon, image_id, url
--}}
<section class="feature feature--default py-20 bg-white">
    <div class="container mx-auto px-6">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['subtitle']))
            <div class="text-center mb-14">
                @if(!empty($data['title']))
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-3">
                        {{ $data['title'] }}
                    </h2>
                @endif
                @if(!empty($data['subtitle']))
                    <p class="text-slate-500 text-lg max-w-xl mx-auto">{{ $data['subtitle'] }}</p>
                @endif
            </div>
        @endif

        {{-- Grid Fitur --}}
        @if(!empty($data['features']))
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($data['features'] as $feature)
                    <div class="group bg-slate-50 hover:bg-indigo-50 border border-slate-100 rounded-2xl p-7 transition">
                        {{-- Ikon atau gambar --}}
                        @if(!empty($feature['image_id']))
                            @php $media = \App\Models\Media::find($feature['image_id']); @endphp
                            @if($media)
                                <img src="{{ $media->url }}" alt="{{ $feature['title'] ?? '' }}"
                                     class="w-14 h-14 object-contain mb-4 rounded-lg">
                            @endif
                        @elseif(!empty($feature['icon']))
                            <div class="text-4xl mb-4">{{ $feature['icon'] }}</div>
                        @endif

                        @if(!empty($feature['title']))
                            <h3 class="text-xl font-semibold text-slate-800 mb-2">
                                {{ $feature['title'] }}
                            </h3>
                        @endif

                        @if(!empty($feature['description']))
                            <p class="text-slate-500 text-sm leading-relaxed">
                                {{ $feature['description'] }}
                            </p>
                        @endif

                        @if(!empty($feature['url']))
                            <a href="{{ $feature['url'] }}"
                               class="inline-block mt-4 text-indigo-600 text-sm font-medium hover:underline">
                                Selengkapnya &rarr;
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
