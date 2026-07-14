{{--
    Hero — Default Variant
    Fields: title, subtitle, content (rich), image_id,
            primary_btn_text, primary_btn_url,
            secondary_btn_text, secondary_btn_url
--}}
<section class="hero hero--default py-24 bg-gradient-to-br from-slate-900 to-slate-700">
    <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        {{-- Teks --}}
        <div>
            @if(!empty($data['title']))
                <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight mb-4">
                    {{ $data['title'] }}
                </h1>
            @endif

            @if(!empty($data['subtitle']))
                <p class="text-xl text-slate-300 mb-3">{{ $data['subtitle'] }}</p>
            @endif

            @if(!empty($data['content']))
                <div class="prose prose-invert prose-lg mb-8 max-w-none">
                    {!! $data['content'] !!}
                </div>
            @endif

            <div class="flex flex-wrap gap-4">
                @if(!empty($data['primary_btn_text']))
                    <a href="{{ $data['primary_btn_url'] ?? '#' }}"
                       class="inline-block px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-lg transition">
                        {{ $data['primary_btn_text'] }}
                    </a>
                @endif

                @if(!empty($data['secondary_btn_text']))
                    <a href="{{ $data['secondary_btn_url'] ?? '#' }}"
                       class="inline-block px-6 py-3 border border-white/40 hover:bg-white/10 text-white font-semibold rounded-lg transition">
                        {{ $data['secondary_btn_text'] }}
                    </a>
                @endif
            </div>
        </div>

        {{-- Gambar --}}
        @if(!empty($data['image_id']))
            @php $media = \App\Models\Media::find($data['image_id']); @endphp
            @if($media)
                <div class="flex justify-center">
                    <img src="{{ $media->url }}" alt="{{ $data['title'] ?? '' }}"
                         class="rounded-2xl shadow-2xl w-full max-w-md object-cover">
                </div>
            @endif
        @endif
    </div>
</section>
