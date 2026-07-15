{{--
    CTA — Elegant Variant
    Fields: title, description, subtitle,
            button_text, button_url, bg_image_id
--}}
<section class="py-5 position-relative overflow-hidden bg-surface-elevated" style="border-top: 2px solid var(--color-accent); border-bottom: 2px solid var(--color-accent);">
    {{-- Background image overlay --}}
    @if(!empty($data['bg_image_id']))
        @php $bg = \App\Models\Media::find($data['bg_image_id']); @endphp
        @if($bg)
            <div class="position-absolute top-0 start-0 w-100 h-100"
                 style="background-image: url('{{ $bg->url }}'); background-size: cover; background-position: center; opacity: 0.15;"></div>
        @endif
    @endif

    <div class="container py-5 text-center position-relative" style="z-index: 2;">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                @if(!empty($data['subtitle']))
                    <p class="text-primary text-gradient fw-bold m-0 mb-3" style="letter-spacing: 3px; font-size: 0.9rem;">
                        {{ $data['subtitle'] }}
                    </p>
                @endif

                @if(!empty($data['title']))
                    <h2 class="display-4 font-serif text-white mb-4">
                        {{ $data['title'] }}
                    </h2>
                @endif

                @if(!empty($data['description']))
                    <p class="text-white-50 font-sans lead mb-5 px-md-4">
                        {{ $data['description'] }}
                    </p>
                @endif

                @if(!empty($data['button_text']))
                    <a href="{{ $data['button_url'] ?? '#' }}" class="btn-gradient px-5 py-3 fs-5 shadow-lg">
                        {{ $data['button_text'] }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
