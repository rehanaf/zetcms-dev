{{--
    Hero Variant 2: Dua kolom — teks di kiri, gambar di kanan.
    Cocok untuk halaman perkenalan produk/fitur.
--}}
<section class="hero hero--variant2 py-20" style="background-color: {{ $settings['bg_color'] ?? '#f8fafc' }};">
    <div class="container grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
        <div>
            <h1 class="text-4xl font-bold mb-4">
                {{ $settings['heading'] ?? 'Judul Default' }}
            </h1>
            <p class="text-lg text-gray-600 mb-6">
                {{ $content ?? $settings['subheading'] ?? '' }}
            </p>
            <div class="flex gap-3">
                @if(!empty($settings['button_text']))
                    <a href="{{ $settings['button_url'] ?? '#' }}" class="btn btn-primary">
                        {{ $settings['button_text'] }}
                    </a>
                @endif
                @if(!empty($settings['secondary_button_text']))
                    <a href="{{ $settings['secondary_button_url'] ?? '#' }}" class="btn btn-outline">
                        {{ $settings['secondary_button_text'] }}
                    </a>
                @endif
            </div>
        </div>

        <div>
            @if(!empty($settings['image']))
                <img src="{{ $settings['image'] }}"
                     alt="{{ $settings['image_alt'] ?? $settings['heading'] ?? '' }}"
                     class="rounded-xl shadow-lg w-full">
            @endif
        </div>
    </div>
</section>
