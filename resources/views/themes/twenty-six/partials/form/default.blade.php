{{--
    Form — Default Variant
    Fields: title, description, form_id
    Relasi ke Model: \App\Models\Form
--}}
<section class="form form--default py-20 bg-white">
    <div class="container mx-auto px-6 max-w-2xl">
        {{-- Header --}}
        @if(!empty($data['title']) || !empty($data['description']))
            <div class="text-center mb-10">
                @if(!empty($data['title']))
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-3">
                        {{ $data['title'] }}
                    </h2>
                @endif
                @if(!empty($data['description']))
                    <p class="text-slate-500 text-lg">{{ $data['description'] }}</p>
                @endif
            </div>
        @endif

        @if(!empty($data['form_id']))
            @php $form = \App\Models\Form::find($data['form_id']); @endphp
            @if($form)
                {{--
                    Render form dinamis berdasarkan model Form.
                    Sesuaikan method render dengan implementasi project Anda.
                    Contoh menggunakan Blade component atau helper:
                --}}
                {{-- Modal Notifikasi --}}
                @if(session('form_success') || $errors->any())
                    <div id="formModal-{{ $form->slug }}" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
                        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 transform transition-all text-center">
                            @if(session('form_success'))
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800 mb-2">Berhasil!</h3>
                                <p class="text-slate-600 mb-6">{{ session('form_success') }}</p>
                            @endif
                            @if($errors->any())
                                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800 mb-2">Gagal!</h3>
                                <p class="text-slate-600 mb-6">Silakan periksa kembali isian formulir Anda.</p>
                            @endif
                            <button onclick="document.getElementById('formModal-{{ $form->slug }}').style.display='none'" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold py-2.5 rounded-xl transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                @endif

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-8">
                    <form method="POST" action="{{ url('/forms/' . $form->slug) }}" class="space-y-6">
                        @csrf
                        @foreach($form->fields as $field)
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                                    {{ $field->label }}
                                    @if($field->is_required ?? false)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>

                                @if($field->type === 'textarea')
                                    <textarea name="{{ $field->name }}" rows="4" placeholder="{{ $field->placeholder ?? '' }}" {{ ($field->is_required ?? false) ? 'required' : '' }} class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition shadow-sm">{{ old($field->name) }}</textarea>
                                @elseif($field->type === 'select')
                                    <select name="{{ $field->name }}" {{ ($field->is_required ?? false) ? 'required' : '' }} class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition shadow-sm">
                                        <option value="">— Pilih —</option>
                                        @foreach((array)($field->options ?? []) as $opt)
                                            <option value="{{ $opt }}" {{ old($field->name) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="{{ $field->type ?? 'text' }}" name="{{ $field->name }}" value="{{ old($field->name) }}" placeholder="{{ $field->placeholder ?? '' }}" {{ ($field->is_required ?? false) ? 'required' : '' }} class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition shadow-sm">
                                @endif

                                @error($field->name)
                                    <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                        
                        <div class="pt-2">
                            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3.5 px-6 rounded-xl transition-all shadow-lg shadow-amber-500/30">
                                Kirim Pesan
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <p class="text-slate-400 text-center">Form tidak ditemukan.</p>
            @endif
        @endif
    </div>
</section>
