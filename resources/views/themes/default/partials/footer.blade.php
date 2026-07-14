<footer class="bg-white border-t border-slate-200 py-6 mt-12 text-center text-xs text-slate-500">
    <div class="max-w-7xl mx-auto px-4">
        &copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name', config('app.name')) }}. Seluruh hak cipta dilindungi.
    </div>
</footer>
