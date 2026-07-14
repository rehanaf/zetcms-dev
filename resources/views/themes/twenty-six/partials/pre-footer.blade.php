@php
    $isActive = \App\Models\Setting::get('contact_is_active', '0');
    if (!$isActive || $isActive === '0') return;

    $title = \App\Models\Setting::get('contact_title');
    $email = \App\Models\Setting::get('contact_email');
    $phone = \App\Models\Setting::get('contact_phone');
    $location = \App\Models\Setting::get('contact_location');
    $mapsRaw = \App\Models\Setting::get('contact_maps_embed');
    $formId = \App\Models\Setting::get('contact_form_id');
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

<section class="py-5 bg-dark text-white">
    <div class="container">
        @if($title)
            <h2 class="text-center mb-5 fw-bold">{{ $title }}</h2>
        @endif

        <div class="row g-5">
            @if($hasInfo)
                <div class="{{ $hasForm ? 'col-lg-6' : 'col-lg-8 mx-auto' }}">
                    <div class="d-flex flex-column gap-4">
                        @if($email)
                            <div class="d-flex align-items-start gap-3">
                                <div class="bg-warning text-dark p-2 rounded">Email</div>
                                <div>
                                    <p class="mb-0 text-muted small text-uppercase">Email</p>
                                    <a href="mailto:{{ $email }}" class="text-white text-decoration-none">{{ $email }}</a>
                                </div>
                            </div>
                        @endif

                        @if($phone)
                            <div class="d-flex align-items-start gap-3">
                                <div class="bg-warning text-dark p-2 rounded">Telp</div>
                                <div>
                                    <p class="mb-0 text-muted small text-uppercase">Telepon</p>
                                    <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="text-white text-decoration-none">{{ $phone }}</a>
                                </div>
                            </div>
                        @endif

                        @if($location)
                            <div class="d-flex align-items-start gap-3">
                                <div class="bg-warning text-dark p-2 rounded">Lokasi</div>
                                <div>
                                    <p class="mb-0 text-muted small text-uppercase">Lokasi</p>
                                    <p class="mb-0">{{ $location }}</p>
                                </div>
                            </div>
                        @endif

                        @if($safeMapsUrl)
                            <div class="ratio ratio-16x9 mt-3 rounded overflow-hidden">
                                <iframe src="{{ $safeMapsUrl }}" allowfullscreen loading="lazy"></iframe>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($hasForm)
                <div class="col-lg-6">
                    <div class="bg-light text-dark p-4 rounded shadow-sm">
                        <form method="POST" action="{{ url('/forms/' . $contactForm->slug) }}">
                            @csrf
                            @foreach($contactForm->fields as $field)
                                <div class="mb-3">
                                    <label class="form-label">{{ $field->label }} @if($field->is_required) * @endif</label>
                                    
                                    @if($field->type === 'textarea')
                                        <textarea name="{{ $field->name }}" class="form-control" rows="3" {{ $field->is_required ? 'required' : '' }}>{{ old($field->name) }}</textarea>
                                    @elseif($field->type === 'select')
                                        <select name="{{ $field->name }}" class="form-select" {{ $field->is_required ? 'required' : '' }}>
                                            <option value="">-- Pilih --</option>
                                            @foreach((array)$field->options as $opt)
                                                <option value="{{ $opt }}" {{ old($field->name) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="{{ $field->type ?? 'text' }}" name="{{ $field->name }}" class="form-control" value="{{ old($field->name) }}" {{ $field->is_required ? 'required' : '' }}>
                                    @endif
                                </div>
                            @endforeach
                            <button type="submit" class="btn btn-warning w-100 fw-bold">Kirim Pesan</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>