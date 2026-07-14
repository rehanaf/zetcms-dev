{{--
    About — Default Variant
    Fields: title, subtitle, content (rich), image_id,
            button_text, button_url
--}}
<section class="about about--default py-20 bg-slate-50">
    <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-14 items-center">
        {{-- Gambar --}}
        @if(!empty($data['image_id']))
            @php $media = \App\Models\Media::find($data['image_id']); @endphp
            @if($media)
                <div>
                    <img src="{{ $media->url }}" alt="{{ $data['title'] ?? 'About' }}"
                         class="rounded-2xl shadow-xl w-full object-cover">
                </div>
            @endif
        @endif

        {{-- Konten --}}
        <div>
            @if(!empty($data['title']))
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-3">
                    {{ $data['title'] }}
                </h2>
            @endif

            @if(!empty($data['subtitle']))
                <p class="text-indigo-600 font-medium mb-4">{{ $data['subtitle'] }}</p>
            @endif

            @if(!empty($data['content']))
                <div class="prose prose-slate prose-lg max-w-none mb-8">
                    {!! $data['content'] !!}
                </div>
            @endif

            @if(!empty($data['button_text']))
                <a href="{{ $data['button_url'] ?? '#' }}"
                   class="inline-block px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-lg transition">
                    {{ $data['button_text'] }}
                </a>
            @endif
        </div>
    </div>
</section>
