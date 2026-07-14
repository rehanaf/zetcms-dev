# Schema Database Laravel CMS + SEO + Layout/Partial

## Struktur Tabel

**User & Akses**
- `roles` — Super Admin, Admin, Editor, Author
- `users` — punya `role_id`, avatar, bio

**Konten**
- `categories` — nested (self-referencing `parent_id`)
- `tags` + pivot `post_tag`
- `posts` — artikel/blog, status draft/published/scheduled
- `pages` — halaman statis (About, Contact, dll), terhubung ke `layout_id`

**Layout & Page Builder (partial/komponen)**
- `layouts` — daftar master layout Blade (`layouts.app`, `layouts.landing`, dst)
- `components` — partial reusable: header, footer, hero, sidebar, cta, dsb, punya `view_path` + `settings` (json)
- `page_components` — pivot yang menyusun komponen mana saja tampil di suatu `page`, di region mana (header/content/sidebar/footer), urutannya, dan override setting per halaman

Ini yang bikin CMS-nya fleksibel: satu halaman bisa disusun dari beberapa blok/partial secara dinamis dari database, tanpa perlu bikin file Blade baru tiap kali ada halaman baru.

**SEO**
- `seo_metas` — polymorphic (`morphs('seo_metable')`), bisa dipasang ke `posts`, `pages`, `categories`, atau model lain. Isinya lengkap: meta title/description/keywords, canonical URL, robots index/follow, Open Graph, Twitter Card, structured data (Schema.org JSON-LD), serta setting sitemap (priority, change frequency, include/exclude)
- `redirects` — 301/302, penting supaya SEO tidak rusak saat slug/URL berubah
- `settings` — key-value untuk pengaturan situs & default SEO global (site_name, meta default, dsb)

**Pendukung**
- `media` — file manager, ada `alt_text` (penting untuk SEO gambar)
- `menus` + `menu_items` — nested menu, bisa nunjuk ke `page`, `post`, atau URL manual
- `comments` — nested (reply), moderasi via status

## Cara Pakai Trait SEO

Setiap model yang butuh SEO tinggal pakai `App\Traits\HasSeo`:

```php
class Post extends Model
{
    use HasSeo; // otomatis dapat relasi morphOne ke SeoMeta + accessor meta_title/meta_description/og_image
}
```

Lalu di controller:

```php
$post = Post::with('seo', 'category', 'tags')->where('slug', $slug)->firstOrFail();
return view('post-show', ['model' => $post]);
```

Dan di Blade cukup:

```blade
@include('components.seo-meta', ['model' => $model])
```

## Struktur Folder Blade

```
resources/views/
├── layouts/
│   └── app.blade.php          # master layout, include header/footer + render page_components
├── partials/
│   ├── header.blade.php
│   ├── footer.blade.php
│   └── dynamic-component.blade.php   # jembatan render komponen dari DB
└── components/
    └── seo-meta.blade.php     # blok meta tag SEO lengkap
```

Alur render halaman dengan page builder:
1. Controller ambil `Page` beserta `components` (yang sudah include relasi `component`)
2. `layouts/app.blade.php` loop `$page->components` per region, lalu `@include('partials.dynamic-component')`
3. `dynamic-component.blade.php` resolve `view_path` milik komponen tsb (misal `partials.hero-banner`) dan render dengan `settings` gabungan (default + override)

## Urutan Migration

File migration sudah diberi prefix angka supaya urut sesuai foreign key (roles → users → layouts/components → categories/tags → pages/posts → pivot → media/menu → seo → redirect/comment/setting). Tinggal jalankan:

```bash
php artisan migrate
```

## Sistem Theme & Variant Komponen

CMS ini mendukung multi-theme, dan tiap type komponen (hero, pricing, dst) bisa punya beberapa variant tampilan.

**Struktur folder:**
```
resources/views/
├── themes/
│   └── default/                    # satu folder = satu theme
│       ├── layouts/app.blade.php
│       ├── partials/
│       │   ├── header.blade.php
│       │   ├── footer.blade.php
│       │   ├── hero/
│       │   │   ├── variant1.blade.php   # simpel
│       │   │   ├── variant2.blade.php   # dua kolom + gambar
│       │   │   └── variant3.blade.php   # fullscreen background
│       │   └── pricing/
│       │       └── variant1.blade.php
│       └── components/seo-meta.blade.php
└── partials/
    ├── dynamic-component.blade.php  # jembatan render, LINTAS theme (tidak ikut folder themes/)
    └── component-missing.blade.php  # fallback kalau variant tidak ditemukan
```

**Skema tambahan:**
- `themes` — daftar theme terpasang, `is_active` menandakan theme yang sedang dipakai
- `layouts.theme_id`, `components.theme_id` — tiap layout/komponen "milik" satu theme
- `components.type` + `components.variant` — menentukan file Blade mana yang dipakai (`partials/{type}/{variant}.blade.php`), lewat accessor `resolved_view_path` di model `Component`
- `components.thumbnail` — preview gambar untuk dropdown pemilihan variant di admin

**Alur render:**
1. `ThemeService::active()` ambil theme yang `is_active = true` (di-cache)
2. `dynamic-component.blade.php` panggil `ThemeService::componentView($component)` untuk resolve path lengkap, dengan fallback otomatis ke theme `default` kalau variant belum ada di theme aktif
3. Kalau tetap tidak ketemu, jatuh ke `partials.component-missing` (tampil sebagai warning di local/staging, senyap di production)

Lihat `database/seeders/ThemeDemoSeeder.php` untuk contoh lengkap mendaftarkan theme, layout, dan 4 komponen (3 variant hero + 1 pricing).

## Fitur Tambahan

Skema sekarang punya 30 migration. Selain CMS inti + SEO + theme/variant, ditambahkan:

| Fitur | Tabel | Catatan |
|---|---|---|
| Multi-bahasa | `translations` | Polymorphic per-field. Trait `HasTranslations` (`$post->translate('title', 'en')`). `seo_metas` dapat kolom `locale` + `hreflang_group` untuk hreflang tags |
| Revision history | `revisions` | Trait `HasRevisions` (`$post->saveRevision()`, `$post->restoreRevision($rev)`) |
| Activity log | `activity_logs` | Audit "siapa ubah apa kapan", polymorphic ke subject apapun |
| Auto-unpublish | kolom `expired_at` di `posts`/`pages` | Scope `Post::published()` otomatis exclude yang sudah expired |
| Search log | `search_logs` | Catat query pencarian pengunjung untuk temukan celah konten |
| Trafik harian | `post_views` | Agregat per hari (`post_id` + `viewed_date` unik), untuk grafik trend, terpisah dari counter total di `posts.views` |
| Related/Featured | `post_related`, kolom `is_featured` | Kurasi manual artikel terkait + highlight di homepage |
| Approval workflow | `approvals`, status `pending_review` | Editor submit → Admin approve/reject sebelum published |
| Form builder | `forms`, `form_fields`, `form_submissions` | Bikin form custom (kontak, newsletter) tanpa kode baru |
| Notifikasi in-app | `notifications` | Skema bawaan Laravel, dipakai untuk notif komentar baru / hasil approval |
| Sitemap statis | `sitemap_static_entries` | Untuk URL yang bukan dari model Eloquent tapi perlu masuk sitemap.xml |

Semua tabel/model/trait ini sudah ada di project, tinggal `php artisan migrate`.

## Observer & Notifikasi Otomatis

Supaya fitur activity log, revision, approval, dan form notification "hidup" tanpa perlu dipanggil manual di tiap controller, sudah dibuatkan Observer yang didaftarkan di `app/Providers/AppServiceProvider.php`:

| Observer | Dipasang di | Yang terjadi otomatis |
|---|---|---|
| `ContentObserver` (abstract, dipakai `PostObserver` & `PageObserver`) | `Post`, `Page` | Tiap `created`/`updated`/`deleted`/`restored` otomatis catat ke `activity_logs` dan simpan snapshot ke `revisions`. Perubahan status jadi `published` dicatat sebagai activity `published` tersendiri |
| `ApprovalObserver` | `Approval` | Saat Editor submit → notifikasi database ke semua user role Admin. Saat Admin approve/reject → notifikasi ke submitter + **otomatis update status Post/Page** (`approved` → `published`, `rejected` → `draft`) |
| `FormSubmissionObserver` | `FormSubmission` | Saat ada submission baru → notifikasi ke semua Admin (database, dan email kalau `forms.notification_email` diisi) |

Contoh pakai di controller — tidak perlu tulis logic log/notifikasi manual:

```php
// Cukup ini, activity log + revision otomatis tercatat oleh observer
$post = Post::create($validated);

// Editor submit untuk review — notifikasi ke Admin otomatis terkirim
Approval::create([
    'approvable_type' => Post::class,
    'approvable_id'   => $post->id,
    'submitted_by'    => auth()->id(),
]);
$post->update(['status' => 'pending_review']);

// Admin approve — status Post otomatis jadi published + submitter dapat notifikasi
$approval->update(['reviewed_by' => auth()->id(), 'status' => 'approved']);
```

Lihat notifikasi in-app milik user:

```php
auth()->user()->unreadNotifications; // dari tabel notifications
```

## Rekomendasi Package Pendukung (opsional)
- `spatie/laravel-sitemap` — generate XML sitemap otomatis dari `seo_metas` yang `sitemap_include = true`
- `spatie/laravel-medialibrary` — alternatif lebih lengkap untuk tabel `media`
- `spatie/laravel-sluggable` — auto-generate slug dari title
# zetcms-dev
