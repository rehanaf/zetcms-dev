{{--
    Hero Variant 1: Simpel, teks center, satu tombol.
    Cocok untuk landing page produk sederhana.
--}}
<section class="hero hero--variant1 py-20 text-center" style="background-color: {{ $settings['bg_color'] ?? '#1e293b' }};">
    <div class="container max-w-2xl mx-auto">
        <h1 class="text-4xl font-bold text-white mb-4">
            {{ $settings['heading'] ?? 'Judul Default' }}
        </h1>
        <p class="text-lg text-white/80 mb-6">
            {{ $content ?? $settings['subheading'] ?? '' }}
        </p>
        @if(!empty($settings['button_text']))
            <a href="{{ $settings['button_url'] ?? '#' }}" class="btn btn-primary">
                {{ $settings['button_text'] }}
            </a>
        @endif
    </div>
</section>
