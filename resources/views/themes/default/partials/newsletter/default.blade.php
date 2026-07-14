{{--
    Newsletter — Default Variant
    Fields: title, description, button_text, placeholder
--}}
<section class="newsletter newsletter--default py-20 bg-slate-50">
    <div class="container mx-auto px-6 max-w-2xl text-center">
        @if(!empty($data['title']))
            <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-3">
                {{ $data['title'] }}
            </h2>
        @endif

        @if(!empty($data['description']))
            <p class="text-slate-500 text-lg mb-8">{{ $data['description'] }}</p>
        @endif

        <form action="{{ route('newsletter.subscribe') }}" method="POST"
              class="flex flex-col sm:flex-row gap-3 justify-center">
            @csrf
            <input type="email" name="email"
                   placeholder="{{ $data['placeholder'] ?? 'Masukkan email Anda' }}"
                   required
                   class="flex-1 px-5 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-400 text-slate-800">
            <button type="submit"
                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl transition whitespace-nowrap">
                {{ $data['button_text'] ?? 'Subscribe' }}
            </button>
        </form>

        <p class="text-slate-400 text-xs mt-4">Kami tidak akan mengirim spam. Berhenti berlangganan kapan saja.</p>
    </div>
</section>
