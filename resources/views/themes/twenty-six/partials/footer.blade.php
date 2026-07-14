<footer class="bg-light border-top mt-auto pt-5 pb-3">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                @php
                    $siteName = \App\Models\Setting::get('site_name', config('app.name'));
                    $siteLogo = \App\Models\Setting::get('site_logo');
                @endphp
                
                <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 text-decoration-none text-dark mb-3">
                    @if($siteLogo)
                        <img src="{{ Storage::disk('public')->url($siteLogo) }}" alt="{{ $siteName }}" style="height: 32px;">
                    @endif
                    <span class="fw-bold fs-4">{{ $siteName }}</span>
                </a>
                
                <p class="text-muted small">
                    {{ \App\Models\Setting::get('site_tagline') }}
                </p>

                <ul class="list-unstyled text-muted small">
                    <li class="mb-2"><i class="bi bi-geo-alt-fill me-2"></i>{{ \App\Models\Setting::get('site_address') }}</li>
                    <li class="mb-2"><i class="bi bi-envelope-fill me-2"></i>{{ \App\Models\Setting::get('site_email') }}</li>
                    <li class="mb-2"><i class="bi bi-telephone-fill me-2"></i>{{ \App\Models\Setting::get('site_phone') }}</li>
                </ul>
            </div>

            @foreach($footerMenu->items ?? [] as $item)
                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="fw-bold mb-3">{{ $item->title }}</h6>
                    <ul class="list-unstyled">
                        @foreach($item->children as $child)
                            <li class="mb-2">
                                <a href="{{ $child->resolved_url }}" target="{{ $child->target }}" class="text-decoration-none text-muted small hover-text-primary">
                                    {{ $child->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <hr class="my-4">

        <div class="text-center text-muted small">
            &copy; {{ date('Y') }} {{ \App\Models\Setting::get('copyright_text', $siteName . '. All rights reserved.') }}
        </div>
    </div>
</footer>