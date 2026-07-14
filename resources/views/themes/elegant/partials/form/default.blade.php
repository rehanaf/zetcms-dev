{{--
    Form — Elegant Variant
    Fields: title, description, form_id
    Relasi ke Model: \App\Models\Form
--}}
<section class="py-5 bg-white" id="kontak">
    <div class="container py-4">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center max-w-lg mx-auto mb-5">
                @if(!empty($data['title']))
                    <h2 class="display-5 text-chocolate mt-1 font-serif">{{ $data['title'] }}</h2>
                @endif
                <div class="divider"></div>
                @if(!empty($data['description']))
                    <p class="text-muted mt-3 font-sans">{{ $data['description'] }}</p>
                @endif
            </div>
        @endif

        @if(!empty($data['form_id']))
            @php $form = \App\Models\Form::find($data['form_id']); @endphp
            @if($form)
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        {{-- Notifikasi --}}
                        @if(session('form_success') || $errors->any())
                            <div class="alert {{ session('form_success') ? 'alert-success border-success text-success bg-white' : 'alert-danger border-danger text-danger bg-white' }} rounded shadow-sm alert-dismissible fade show mb-4 font-sans" role="alert">
                                @if(session('form_success'))
                                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('form_success') }}
                                @endif
                                @if($errors->any())
                                    <i class="fa-solid fa-circle-xmark me-2"></i> Ada kesalahan pada form Anda. Silakan cek kembali.
                                @endif
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="card bg-cream border-0 rounded-4 shadow-lg p-4 p-md-5">
                            <form method="POST" action="{{ url('/forms/' . $form->slug) }}">
                                @csrf
                                <div class="row g-4">
                                    @foreach($form->fields as $field)
                                        <div class="{{ $field->type === 'textarea' ? 'col-12' : 'col-md-6' }}">
                                            <label class="form-label font-sans text-chocolate fw-semibold">
                                                {{ $field->label }}
                                                @if($field->is_required ?? false)
                                                    <span class="text-danger">*</span>
                                                @endif
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
                                                <div class="text-danger small mt-1 font-sans">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                    
                                    <div class="col-12 mt-4 text-center">
                                        <button type="submit" class="btn-elegant px-5 py-3 w-100">
                                            Kirim Pesan <i class="fa-solid fa-paper-plane ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <p class="text-white-50 text-center font-sans">Form tidak ditemukan.</p>
            @endif
        @endif
    </div>
</section>
