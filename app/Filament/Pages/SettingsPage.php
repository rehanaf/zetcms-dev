<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Models\Form as FormModel;
use App\Models\Menu;
use App\Models\Page;
use Filament\Actions\Action as HeaderAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page as FilamentPage;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use BackedEnum;
use UnitEnum;

class SettingsPage extends FilamentPage
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $title = 'Settings';

    protected static ?int $navigationSort = 1;

    // ── Form state ──────────────────────────────────────
    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        $booleanKeys = ['maintenance_mode', 'contact_is_active'];

        foreach ($booleanKeys as $boolKey) {
            if (isset($settings[$boolKey])) {
                $settings[$boolKey] = (bool) $settings[$boolKey];
            } else {
                $settings[$boolKey] = false;
            }
        }

        $this->form->fill($settings);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make($this->getFormActions())
                        ->alignment('start')
                        ->fullWidth(false)
                        ->sticky($this->areFormActionsSticky()),
                ]),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        $pages = Page::pluck('title', 'id')->prepend('— Tidak ada —', '');
        $menus = Menu::pluck('name', 'id')->prepend('— Tidak ada —', '');
        $forms = FormModel::where('is_active', true)->pluck('name', 'id')->prepend('— Tidak ada —', '');

        return $schema
            ->statePath('data')
            ->components([
                Tabs::make('Settings Tabs')
                    ->tabs([

                        // ── TAB 1: GENERAL ──────────────────────────
                        Tab::make('General')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Section::make('Identitas Website')
                                    ->description('Informasi dasar website Anda')
                                    ->icon('heroicon-o-building-office')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('site_name')
                                            ->label('Nama Website')
                                            ->required(),
                                        TextInput::make('app_url')
                                            ->label('Site URL')
                                            ->url()
                                            ->helperText('Kosongkan untuk menggunakan bawaan sistem (.env)')
                                            ->placeholder('https://domainanda.com'),
                                        TextInput::make('site_tagline')
                                            ->label('Tagline'),
                                        TextInput::make('site_email')
                                            ->label('Email Kontak')
                                            ->email(),
                                        TextInput::make('site_phone')
                                            ->label('Nomor Telepon'),
                                        Textarea::make('site_address')
                                            ->label('Alamat')
                                            ->columnSpanFull()
                                            ->rows(2),
                                        TextInput::make('copyright_text')
                                            ->label('Teks Copyright')
                                            ->columnSpanFull(),
                                        TextInput::make('header_button_text')
                                            ->label('Header Button Text')
                                            ->placeholder('e.g. Booking')
                                            ->helperText('Teks tombol di header'),
                                        TextInput::make('header_button_url')
                                            ->label('Header Button URL')
                                            ->placeholder('e.g. #kontak or https://...')
                                            ->helperText('URL tujuan tombol di header'),
                                    ]),

                                Section::make('Logo & Favicon')
                                    ->description('Unggah logo dan favicon website')
                                    ->icon('heroicon-o-photo')
                                    ->columns(2)
                                    ->schema([
                                        FileUpload::make('site_logo')
                                            ->label('Logo Website')
                                            ->disk('public')
                                            ->directory('settings')
                                            ->image()
                                            ->imagePreviewHeight('80'),
                                        FileUpload::make('site_favicon')
                                            ->label('Favicon')
                                            ->disk('public')
                                            ->directory('settings')
                                            ->image()
                                            ->imagePreviewHeight('80'),
                                    ]),

                                Section::make('Halaman & Tampilan')
                                    ->description('Konfigurasi halaman dan menu website')
                                    ->icon('heroicon-o-document-duplicate')
                                    ->columns(2)
                                    ->schema([
                                        Select::make('homepage_page_id')
                                            ->label('Halaman Beranda (Homepage)')
                                            ->options($pages)
                                            ->searchable()
                                            ->columnSpanFull(),
                                        Toggle::make('maintenance_mode')
                                            ->label('Mode Maintenance')
                                            ->helperText('Aktifkan untuk menonaktifkan akses publik sementara')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // ── TAB 2: MENU LOCATIONS ───────────────────
                        Tab::make('Menu Locations')
                            ->icon('heroicon-o-bars-3')
                            ->schema([
                                Section::make('Lokasi Menu')
                                    ->description('Pilih menu yang tampil di setiap posisi website')
                                    ->icon('heroicon-o-link')
                                    ->columns(1)
                                    ->schema([
                                        Select::make('menu_header')
                                            ->label('Menu Header (Navigasi Atas)')
                                            ->options($menus)
                                            ->searchable()
                                            ->helperText('Menu yang tampil di bagian navigasi atas website'),
                                        Select::make('menu_footer')
                                            ->label('Menu Footer (Navigasi Bawah)')
                                            ->options($menus)
                                            ->searchable()
                                            ->helperText('Menu yang tampil di bagian footer website'),
                                        Select::make('menu_sidebar')
                                            ->label('Menu Sidebar')
                                            ->options($menus)
                                            ->searchable()
                                            ->helperText('Menu yang tampil di sidebar website'),
                                    ]),
                            ]),

                        // ── TAB 3: SEO ───────────────────────────────
                        Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Section::make('Meta Default')
                                    ->description('Nilai default SEO ketika halaman tidak memiliki meta sendiri')
                                    ->icon('heroicon-o-tag')
                                    ->columns(1)
                                    ->schema([
                                        TextInput::make('seo_meta_title')
                                            ->label('Meta Title Default'),
                                        Textarea::make('seo_meta_description')
                                            ->label('Meta Description Default')
                                            ->rows(3),
                                        TextInput::make('seo_meta_keywords')
                                            ->label('Meta Keywords')
                                            ->helperText('Pisahkan dengan koma'),
                                        FileUpload::make('seo_og_image')
                                            ->label('Open Graph Image Default')
                                            ->disk('public')
                                            ->directory('settings')
                                            ->image()
                                            ->helperText('Gambar yang tampil saat link dibagikan di media sosial (min. 1200×630px)'),
                                    ]),

                                Section::make('Tracking & Analitik')
                                    ->description('Kode tracking Google Analytics dan Tag Manager')
                                    ->icon('heroicon-o-chart-bar')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('seo_google_analytics_id')
                                            ->label('Google Analytics ID')
                                            ->placeholder('G-XXXXXXXXXX'),
                                        TextInput::make('seo_google_tag_manager_id')
                                            ->label('Google Tag Manager ID')
                                            ->placeholder('GTM-XXXXXXX'),
                                    ]),

                                Section::make('Robots.txt')
                                    ->icon('heroicon-o-shield-check')
                                    ->schema([
                                        Textarea::make('seo_robots_txt')
                                            ->label('Isi robots.txt')
                                            ->rows(5)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // ── TAB 4: SOCIAL MEDIA ──────────────────────
                        Tab::make('Social Media')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Section::make('Link Media Sosial')
                                    ->description('Masukkan URL lengkap akun media sosial Anda')
                                    ->icon('heroicon-o-heart')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('social_facebook')
                                            ->label('Facebook')
                                            ->url()
                                            ->placeholder('https://facebook.com/username'),
                                        TextInput::make('social_instagram')
                                            ->label('Instagram')
                                            ->url()
                                            ->placeholder('https://instagram.com/username'),
                                        TextInput::make('social_twitter')
                                            ->label('Twitter / X')
                                            ->url()
                                            ->placeholder('https://x.com/username'),
                                        TextInput::make('social_youtube')
                                            ->label('YouTube')
                                            ->url()
                                            ->placeholder('https://youtube.com/@channel'),
                                        TextInput::make('social_tiktok')
                                            ->label('TikTok')
                                            ->url()
                                            ->placeholder('https://tiktok.com/@username'),
                                        TextInput::make('social_linkedin')
                                            ->label('LinkedIn')
                                            ->url()
                                            ->placeholder('https://linkedin.com/in/username'),
                                        TextInput::make('social_whatsapp')
                                            ->label('WhatsApp')
                                            ->placeholder('+6281234567890')
                                            ->helperText('Format internasional tanpa tanda +, contoh: 6281234567890'),
                                    ]),
                            ]),

                        // ── TAB 5: CONTACT SECTION ───────────────────
                        Tab::make('Contact Section')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                Section::make('Bagian Informasi Kontak')
                                    ->description('Tampil di atas footer sebagai section kontak/informasi')
                                    ->icon('heroicon-o-phone')
                                    ->columns(2)
                                    ->schema([
                                        Toggle::make('contact_is_active')
                                            ->label('Aktifkan Section Ini')
                                            ->helperText('Jika dimatikan, section kontak tidak akan tampil di halaman publik')
                                            ->columnSpanFull(),
                                        TextInput::make('contact_title')
                                            ->label('Judul Section')
                                            ->placeholder('Hubungi Kami')
                                            ->columnSpanFull(),
                                        TextInput::make('contact_email')
                                            ->label('Email Kontak')
                                            ->email()
                                            ->placeholder('info@example.com'),
                                        TextInput::make('contact_phone')
                                            ->label('Nomor Telepon')
                                            ->placeholder('+62 812 3456 7890'),
                                        Textarea::make('contact_location')
                                            ->label('Alamat / Lokasi')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        Textarea::make('contact_maps_embed')
                                            ->label('Google Maps Embed')
                                            ->placeholder('<iframe src="https://www.google.com/maps/embed?pb=..." ...></iframe>')
                                            ->helperText('Paste kode <iframe> lengkap dari Google Maps → Share → Embed a map. Sistem akan mengekstrak src-nya secara otomatis.')
                                            ->rows(4)
                                            ->columnSpanFull(),
                                        Select::make('contact_form_id')
                                            ->label('Form Kontak')
                                            ->options($forms)
                                            ->searchable()
                                            ->helperText('Form yang akan ditampilkan di samping informasi kontak')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // ── TAB 6: CUSTOM ────────────────────────────
                        Tab::make('Custom Code')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Section::make('Script & Kode Kustom')
                                    ->description('Tambahkan script atau CSS kustom ke website')
                                    ->icon('heroicon-o-command-line')
                                    ->schema([
                                        Textarea::make('custom_header_scripts')
                                            ->label('Script di <head>')
                                            ->helperText('Akan disisipkan sebelum tag </head>')
                                            ->rows(5)
                                            ->columnSpanFull(),
                                        Textarea::make('custom_footer_scripts')
                                            ->label('Script di Footer')
                                            ->helperText('Akan disisipkan sebelum tag </body>')
                                            ->rows(5)
                                            ->columnSpanFull(),
                                        Textarea::make('custom_css')
                                            ->label('Custom CSS')
                                            ->helperText('CSS tambahan untuk halaman publik')
                                            ->rows(5)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                    ])
                    ->columnSpanFull(),
            ]);
    }

    public function save(): void
    {
        $keys = [
            'site_name', 'app_url', 'site_tagline', 'site_email', 'site_phone', 'site_address',
            'site_logo', 'site_favicon', 'homepage_page_id', 'maintenance_mode', 'copyright_text',
            'menu_header', 'menu_footer', 'menu_sidebar',
            'seo_meta_title', 'seo_meta_description', 'seo_meta_keywords', 'seo_og_image',
            'seo_google_analytics_id', 'seo_google_tag_manager_id', 'seo_robots_txt',
            'social_facebook', 'social_instagram', 'social_twitter', 'social_youtube',
            'social_tiktok', 'social_linkedin', 'social_whatsapp',
            'contact_is_active', 'contact_title', 'contact_email', 'contact_phone',
            'contact_location', 'contact_maps_embed', 'contact_form_id',
            'custom_header_scripts', 'custom_footer_scripts', 'custom_css',
            'header_button_text', 'header_button_url',
        ];

        $booleanKeys = ['maintenance_mode', 'contact_is_active'];

        $groups = [
            'site_name' => 'general', 'app_url' => 'general', 'site_tagline' => 'general', 'site_email' => 'general',
            'site_phone' => 'general', 'site_address' => 'general', 'site_logo' => 'general',
            'site_favicon' => 'general', 'homepage_page_id' => 'general',
            'maintenance_mode' => 'general', 'copyright_text' => 'general',
            'menu_header' => 'general', 'menu_footer' => 'general', 'menu_sidebar' => 'general',
            'seo_meta_title' => 'seo', 'seo_meta_description' => 'seo', 'seo_meta_keywords' => 'seo',
            'seo_og_image' => 'seo', 'seo_google_analytics_id' => 'seo',
            'seo_google_tag_manager_id' => 'seo', 'seo_robots_txt' => 'seo',
            'social_facebook' => 'social', 'social_instagram' => 'social', 'social_twitter' => 'social',
            'social_youtube' => 'social', 'social_tiktok' => 'social', 'social_linkedin' => 'social',
            'social_whatsapp' => 'social',
            'contact_is_active' => 'contact', 'contact_title' => 'contact', 'contact_email' => 'contact',
            'contact_phone' => 'contact', 'contact_location' => 'contact',
            'contact_maps_embed' => 'contact', 'contact_form_id' => 'contact',
            'custom_header_scripts' => 'custom', 'custom_footer_scripts' => 'custom', 'custom_css' => 'custom',
            'header_button_text' => 'general', 'header_button_url' => 'general',
        ];

        $state = $this->form->getState();

        foreach ($keys as $key) {
            $value = $state[$key] ?? null;

            if (in_array($key, $booleanKeys)) {
                $value = $value ? '1' : '0';
            }

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => $groups[$key] ?? 'general', 'type' => 'text']
            );
        }

        Notification::make()
            ->title('Settings berhasil disimpan!')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Settings')
                ->icon('heroicon-o-check-circle')
                ->action('save'),
        ];
    }

    public function areFormActionsSticky(): bool
    {
        return true;
    }
}
