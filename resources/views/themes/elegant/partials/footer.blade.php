<footer class="bg-chocolate-dark text-light pt-5 pb-3">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-lg-6 col-md-6">
                <h3 class="font-serif text-accent mb-3">{{ config('app.name', 'Dapoer Cendana') }}</h3>
                <p class="text-white-50 font-sans small">Destinasi pilihan utama untuk perhelatan terindah, rapat profesional, kegiatan religius, dan cita rasa kuliner legendaris Indonesia.</p>
            </div>
            <div class="col-lg-6 col-md-6 text-md-end">
                <h5 class="font-serif text-light mb-3">Tautan Cepat</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white-50 text-decoration-none">Beranda</a></li>
                    <li><a href="#" class="text-white-50 text-decoration-none">Tentang Kami</a></li>
                </ul>
            </div>
        </div>
        <div class="border-top border-secondary pt-3 text-center">
            <p class="small text-white-50 m-0">&copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name', config('app.name')) }}. Seluruh hak cipta dilindungi.</p>
        </div>
    </div>
</footer>
