{{--
    Logo Cloud — Default Variant
    Fields: title, description,
            logos[]: image_id, name, url
--}}
<section class="logo-cloud logo-cloud--default py-16 bg-slate-50">
    <div class="container mx-auto px-6">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center mb-10">
                @if(!empty($data['title']))
                    <h2 class="text-2xl md:text-3xl font-bold text-slate-700 mb-2">
                        {{ $data['title'] }}
                    </h2>
                @endif
                @if(!empty($data['description']))
                    <p class="text-slate-400 text-base max-w-xl mx-auto">{{ $data['description'] }}</p>
                @endif
            </div>
        @endif

        @if(!empty($data['logos']))
            <div class="flex flex-wrap items-center justify-center gap-8 md:gap-12">
                @foreach($data['logos'] as $logo)
                    @php $media = !empty($logo['image_id']) ? \App\Models\Media::find($logo['image_id']) : null; @endphp
                    @if($media)
                        @if(!empty($logo['url']))
                            <a href="{{ $logo['url'] }}" target="_blank" rel="noopener"
                               title="{{ $logo['name'] ?? '' }}">
                        @endif
                            <img src="{{ $media->url }}" alt="{{ $logo['name'] ?? 'Logo' }}"
                                 class="h-10 md:h-12 object-contain grayscale hover:grayscale-0 transition duration-300 opacity-70 hover:opacity-100">
                        @if(!empty($logo['url']))
                            </a>
                        @endif
                    @elseif(!empty($logo['name']))
                        <span class="text-slate-400 font-semibold text-lg">{{ $logo['name'] }}</span>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</section>
