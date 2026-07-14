{{--
    Fallback kalau file variant komponen tidak ditemukan di theme aktif maupun theme default.
    Sengaja dibuat "senyap" di production, tapi kasih tanda jelas di local/staging.
--}}
@if(app()->environment(['local', 'staging']))
    <div style="padding:1rem;border:2px dashed #f59e0b;background:#fffbeb;color:#92400e;">
        ⚠️ Komponen tidak ditemukan. Cek kolom <code>type</code>/<code>variant</code> atau pastikan file Blade-nya ada di theme aktif.
    </div>
@endif
