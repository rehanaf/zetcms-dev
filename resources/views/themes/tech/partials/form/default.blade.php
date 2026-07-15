{{--
    Form — Elegant Variant
    Fields: title, description, form_id
    Relasi ke Model: \App\Models\Form
--}}
<section class="py-5 bg-white" id="kontak">
    <div class="container py-4">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center max-w-lg mx-auto mb-5" data-aos="fade-up">
                @if(!empty($data['title']))
                    <h2 class="display-5 text-primary mt-1 font-serif">{{ $data['title'] }}</h2>
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


                        <div class="card bg-main border-0 rounded-4 shadow-lg p-4 p-md-5" data-aos="fade-up" data-aos-delay="200">
                            <form method="POST" action="{{ url('/forms/' . $form->slug) }}" class="ajax-form">
                                @csrf
                                <div class="row g-4">
                                    @foreach($form->fields as $field)
                                        <div class="{{ $field->type === 'textarea' ? 'col-12' : 'col-md-6' }}">
                                            <label class="form-label font-sans text-primary fw-semibold">
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
                                        <button type="submit" class="btn-gradient px-5 py-3 w-100">
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
