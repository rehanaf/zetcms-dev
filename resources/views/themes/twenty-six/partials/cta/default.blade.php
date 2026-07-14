{{--
    CTA — Default Variant
    Fields: title, description, subtitle,
            button_text, button_url, bg_image_id
--}}
<section class="cta cta--default relative py-24 overflow-hidden bg-indigo-700">
    {{-- Background image overlay --}}
    @if(!empty($data['bg_image_id']))
        @php $bg = \App\Models\Media::find($data['bg_image_id']); @endphp
        @if($bg)
            <div class="absolute inset-0 bg-cover bg-center opacity-20"
                 style="background-image: url('{{ $bg->url }}');"></div>
        @endif
    @endif

    <div class="relative z-10 container mx-auto px-6 text-center max-w-3xl">
        @if(!empty($data['subtitle']))
            <p class="text-indigo-200 uppercase tracking-widest text-sm font-medium mb-3">
                {{ $data['subtitle'] }}
            </p>
        @endif

        @if(!empty($data['title']))
            <h2 class="text-3xl md:text-5xl font-bold text-white mb-5 leading-tight">
                {{ $data['title'] }}
            </h2>
        @endif

        @if(!empty($data['description']))
            <p class="text-indigo-100 text-lg mb-10">{{ $data['description'] }}</p>
        @endif

        @if(!empty($data['button_text']))
            <a href="{{ $data['button_url'] ?? '#' }}"
               class="inline-block px-8 py-4 bg-white text-indigo-700 font-bold rounded-xl hover:bg-indigo-50 transition shadow-lg">
                {{ $data['button_text'] }}
            </a>
        @endif
    </div>
</section>
