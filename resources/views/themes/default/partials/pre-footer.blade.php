{{--
    Pre-Footer Contact Section
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

    // Ekstrak src="..." dari full <iframe> yang dipaste admin,
    // lalu validasi hanya izinkan URL Google Maps — cegah XSS/injeksi
    $safeMapsUrl = null;
    if ($mapsRaw) {
        // Coba ekstrak src dari dalam tag <iframe src="...">
        preg_match('/\bsrc=["\']([^"\']+)["\']/', $mapsRaw, $srcMatch);
        $extracted = $srcMatch[1] ?? trim($mapsRaw); // fallback: mungkin input langsung URL

        // Whitelist: hanya domain Google Maps
        $allowedPattern = '/^https:\/\/(www\.)?google\.(com|co\.[a-z]{2}|[a-z]{2})\/maps\//i';
        $safeMapsUrl = preg_match($allowedPattern, $extracted) ? $extracted : null;
    }

    $hasInfo = $email || $phone || $location || $safeMapsUrl;
    $hasForm = $contactForm && $contactForm->fields->isNotEmpty();
@endphp

<section class="bg-slate-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section Title --}}
        @if($title)
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 tracking-tight">
                {{ $title }}
            </h2>
        @endif

        <div class="grid grid-cols-1 {{ $hasForm ? 'lg:grid-cols-2' : 'max-w-2xl mx-auto' }} gap-12 items-start">

            {{-- Kolom Kiri: Informasi Kontak --}}
            @if($hasInfo)
                <div class="space-y-6">

                    @if($email)
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-amber-500/20 rounded-xl flex items-center justify-center mt-0.5">
                                <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-slate-400 text-xs uppercase tracking-widest mb-1">Email</p>
                                <a href="mailto:{{ $email }}" class="text-white hover:text-amber-400 transition-colors font-medium">
                                    {{ $email }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($phone)
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-amber-500/20 rounded-xl flex items-center justify-center mt-0.5">
                                <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-slate-400 text-xs uppercase tracking-widest mb-1">Telepon</p>
                                <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="text-white hover:text-amber-400 transition-colors font-medium">
                                    {{ $phone }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($location)
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-amber-500/20 rounded-xl flex items-center justify-center mt-0.5">
                                <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-slate-400 text-xs uppercase tracking-widest mb-1">Lokasi</p>
                                <p class="text-white font-medium leading-relaxed">{{ $location }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Google Maps — render iframe aman dari URL yang sudah divalidasi regex --}}
                    @if($safeMapsUrl)
                        <div class="mt-6 rounded-2xl overflow-hidden border border-slate-700 aspect-video">
                            <iframe
                                src="{{ $safeMapsUrl }}"
                                width="100%"
                                height="100%"
                                style="border:0;"
                                allowfullscreen
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                            ></iframe>
                        </div>
                    @endif

                </div>
            @endif

            {{-- Kolom Kanan: Form Kontak --}}
            @if($hasForm)
                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 backdrop-blur-sm relative">
                    {{-- Modal Notifikasi --}}
                    @if(session('form_success') || $errors->any())
                        <div id="contactFormModal-{{ $contactForm->slug }}" class="absolute inset-0 z-10 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm rounded-2xl">
                            <div class="bg-slate-800 border border-slate-700 rounded-2xl shadow-xl w-full max-w-sm p-6 transform transition-all text-center">
                                @if(session('form_success'))
                                    <div class="w-14 h-14 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-white mb-2">Berhasil!</h3>
                                    <p class="text-slate-300 mb-6 text-sm">{{ session('form_success') }}</p>
                                @endif
                                @if($errors->any())
                                    <div class="w-14 h-14 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-white mb-2">Gagal!</h3>
                                    <p class="text-slate-300 mb-6 text-sm">Silakan periksa kembali isian formulir Anda.</p>
                                @endif
                                <button onclick="document.getElementById('contactFormModal-{{ $contactForm->slug }}').style.display='none'" class="w-full bg-slate-700 hover:bg-slate-600 text-white font-medium py-2.5 rounded-xl transition-colors text-sm">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ url('/forms/' . $contactForm->slug) }}" class="space-y-5">
                        @csrf
                        @foreach($contactForm->fields as $field)
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">
                                    {{ $field->label }}
                                    @if($field->is_required ?? false)
                                        <span class="text-amber-400">*</span>
                                    @endif
                                </label>

                                @if($field->type === 'textarea')
                                    <textarea
                                        name="{{ $field->name }}"
                                        rows="4"
                                        placeholder="{{ $field->placeholder ?? '' }}"
                                        {{ ($field->is_required ?? false) ? 'required' : '' }}
                                        class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400/50 transition text-sm"
                                    >{{ old($field->name) }}</textarea>
                                @elseif($field->type === 'select')
                                    <select
                                        name="{{ $field->name }}"
                                        {{ ($field->is_required ?? false) ? 'required' : '' }}
                                        class="w-full bg-slate-800 border border-white/20 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-amber-400/50 transition text-sm"
                                    >
                                        <option value="">— Pilih —</option>
                                        @foreach((array)($field->options ?? []) as $opt)
                                            <option value="{{ $opt }}" {{ old($field->name) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input
                                        type="{{ $field->type ?? 'text' }}"
                                        name="{{ $field->name }}"
                                        value="{{ old($field->name) }}"
                                        placeholder="{{ $field->placeholder ?? '' }}"
                                        {{ ($field->is_required ?? false) ? 'required' : '' }}
                                        class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400/50 transition text-sm"
                                    >
                                @endif
                                
                                @error($field->name)
                                    <p class="text-sm text-red-400 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach

                        <button type="submit"
                            class="w-full bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold py-3 px-6 rounded-xl transition-all shadow-lg shadow-amber-500/25 mt-2">
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</section>
