{{--
    Pre-Footer Contact Section (Elegant)
    Dikontrol via Settings: contact_is_active, contact_title, contact_email,
    contact_phone, contact_location, contact_maps_embed, contact_form_id
--}}
@php
    $isActive    = \App\Models\Setting::get('contact_is_active', '0');
    if (!$isActive || $isActive === '0') return;

    $title       = \App\Models\Setting::get('contact_title');
    $email       = \App\Models\Setting::get('contact_email');
    $phone       = \App\Models\Setting::get('contact_phone');
    $location    = \App\Models\Setting::get('contact_location');
    $mapsRaw     = \App\Models\Setting::get('contact_maps_embed');
    $formId      = \App\Models\Setting::get('contact_form_id');
    $contactForm = $formId ? \App\Models\Form::with('fields')->find($formId) : null;

    $safeMapsUrl = null;
    if ($mapsRaw) {
        preg_match('/\bsrc=["\']([^"\']+)["\']/', $mapsRaw, $srcMatch);
        $extracted = $srcMatch[1] ?? trim($mapsRaw);
        $allowedPattern = '/^https:\/\/(www\.)?google\.(com|co\.[a-z]{2}|[a-z]{2})\/maps\//i';
        $safeMapsUrl = preg_match($allowedPattern, $extracted) ? $extracted : null;
    }

    $hasInfo = $email || $phone || $location || $safeMapsUrl;
    $hasForm = $contactForm && $contactForm->fields->isNotEmpty();
@endphp

<section id="kontak" class="py-5 bg-cream">
    <div class="container py-4">
        @if($title)
            <div class="text-center max-w-lg mx-auto mb-5">
                <p class="text-accent fw-bold m-0" style="letter-spacing: 2px;">Reservasi & Konsultasi</p>
                <h2 class="display-5 text-chocolate mt-1">{{ $title }}</h2>
                <div class="divider"></div>
            </div>
        @endif

        <div class="row g-5">
            {{-- Form Column --}}
            @if($hasForm)
                <div class="col-lg-6">
                    <div class="bg-white p-4 p-md-5 rounded shadow-sm border border-light position-relative">
                        <h3 class="font-serif text-chocolate mb-4"><i class="fa-solid fa-envelope-open-text text-accent me-2"></i>Kirim Permintaan Reservasi</h3>
                        
                        @if(session('form_success') || $errors->any())
                            <div class="alert alert-{{ session('form_success') ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
                                {{ session('form_success') ?? 'Silakan periksa kembali isian formulir Anda.' }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ url('/forms/' . $contactForm->slug) }}" class="row g-3">
                            @csrf
                            @foreach($contactForm->fields as $field)
                                <div class="col-12">
                                    <label class="form-label text-muted font-sans small fw-bold">
                                        {{ $field->label }} @if($field->is_required ?? false) <span class="text-accent">*</span> @endif
                                    </label>

                                    @if($field->type === 'textarea')
                                        <textarea name="{{ $field->name }}" rows="4" placeholder="{{ $field->placeholder ?? '' }}" {{ ($field->is_required ?? false) ? 'required' : '' }} class="form-control">{{ old($field->name) }}</textarea>
                                    @elseif($field->type === 'select')
                                        <select name="{{ $field->name }}" {{ ($field->is_required ?? false) ? 'required' : '' }} class="form-select">
                                            <option value="">— Pilih —</option>
                                            @foreach((array)($field->options ?? []) as $opt)
                                                <option value="{{ $opt }}" {{ old($field->name) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="{{ $field->type ?? 'text' }}" name="{{ $field->name }}" value="{{ old($field->name) }}" placeholder="{{ $field->placeholder ?? '' }}" {{ ($field->is_required ?? false) ? 'required' : '' }} class="form-control">
                                    @endif
                                    
                                    @error($field->name)
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn-elegant w-100 py-3"><i class="fa-solid fa-paper-plane me-2"></i>Kirim Pesan</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Info & Map Column --}}
            @if($hasInfo)
                <div class="col-lg-{{ $hasForm ? '6' : '12' }} d-flex flex-column justify-content-between">
                    <div class="bg-chocolate-dark text-light p-4 rounded shadow-sm mb-4 border-start border-3 border-accent">
                        <h4 class="font-serif text-accent mb-3">Informasi Kontak & Jam Operasional</h4>
                        <div class="row g-3">
                            @if($location)
                                <div class="col-sm-12 d-flex align-items-center">
                                    <i class="fa-solid fa-location-dot text-accent fs-5 me-3"></i>
                                    <span class="font-sans text-white-50">{{ $location }}</span>
                                </div>
                            @endif
                            @if($phone)
                                <div class="col-sm-12 d-flex align-items-center">
                                    <i class="fa-solid fa-phone text-accent fs-5 me-3"></i>
                                    <span class="font-sans text-white-50">{{ $phone }}</span>
                                </div>
                            @endif
                            @if($email)
                                <div class="col-sm-12 d-flex align-items-center">
                                    <i class="fa-solid fa-envelope text-accent fs-5 me-3"></i>
                                    <span class="font-sans text-white-50">{{ $email }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($safeMapsUrl)
                        <div class="map-container flex-grow-1" style="min-height: 380px;">
                            <iframe src="{{ $safeMapsUrl }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" style="width: 100%; height: 100%; border:0;"></iframe>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
