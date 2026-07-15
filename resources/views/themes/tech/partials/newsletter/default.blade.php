{{--
    Newsletter — Tech Variant
    Fields: title, description, button_text, placeholder
--}}
<section class="py-5 bg-primary text-white">
    <div class="container py-5 text-center">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                @if(!empty($data['title']))
                    <h2 class="display-6 font-serif text-primary text-gradient mb-3">{{ $data['title'] }}</h2>
                @endif
                
                @if(!empty($data['description']))
                    <p class="text-white-50 font-sans mb-4">{{ $data['description'] }}</p>
                @endif

                <form action="{{ route('newsletter.subscribe') }}" method="POST">
                    @csrf
                    <div class="input-group mb-3 shadow-lg">
                        <input type="email" name="email" class="form-control py-3 px-4" 
                               placeholder="{{ $data['placeholder'] ?? 'Email Anda...' }}" 
                               style="background-color: rgba(255,255,255,0.1); border-color: rgba(212,175,55,0.3); color: white;" required>
                        <button class="btn btn-gradient py-3 px-4" type="submit">
                            {{ $data['button_text'] ?? 'Langganan' }} <i class="fa-solid fa-paper-plane ms-2"></i>
                        </button>
                    </div>
                </form>
                <small class="text-white-50 font-sans mt-3 d-block">* Kami menghargai privasi Anda dan tidak akan mengirimkan spam.</small>
            </div>
        </div>
    </div>
</section>
