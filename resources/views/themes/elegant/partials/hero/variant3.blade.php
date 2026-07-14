{{--
    Hero Variant 3: Fullscreen dengan background video/gambar dan overlay gelap.
    Cocok untuk landing page yang butuh kesan dramatis (agency, event, dll).
--}}
<section class="hero hero--variant3 relative min-h-[80vh] flex items-center justify-center overflow-hidden">
    @if(!empty($settings['video_url']))
        <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover">
            <source src="{{ $settings['video_url'] }}" type="video/mp4">
        </video>
    @elseif(!empty($settings['image']))
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $settings['image'] }}');"></div>
    @endif

    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative z-10 text-center container max-w-3xl mx-auto">
        <h1 class="text-5xl font-bold text-white mb-4">
            {{ $settings['heading'] ?? 'Judul Default' }}
        </h1>
        <p class="text-xl text-white/80 mb-8">
            {{ $content ?? $settings['subheading'] ?? '' }}
        </p>
        @if(!empty($settings['button_text']))
            <a href="{{ $settings['button_url'] ?? '#' }}" class="btn btn-primary btn-lg">
                {{ $settings['button_text'] }}
            </a>
        @endif
    </div>
</section>
