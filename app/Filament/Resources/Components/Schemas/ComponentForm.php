<?php

namespace App\Filament\Resources\Components\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ComponentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('theme_id')
                    ->relationship('theme', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, callable $set) => 
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                TextInput::make('slug')
                    ->required()
                    ->unique('components', 'slug', ignoreRecord: true),
                Select::make('type')
                    ->options([
                        'header' => 'Header',
                        'footer' => 'Footer',
                        'sidebar' => 'Sidebar',
                        'hero' => 'Hero',
                        'pricing' => 'Pricing',
                        'testimonial' => 'Testimonial',
                        'section' => 'Section',
                        'widget' => 'Widget',
                        'cta' => 'CTA',
                        'custom' => 'Custom',
                    ])
                    ->required()
                    ->default('section'),
                TextInput::make('variant')
                    ->required()
                    ->default('variant1')
                    ->placeholder('variant1'),
                TextInput::make('view_path')
                    ->label('Custom View Path')
                    ->helperText('Kosongkan untuk otomatis menggunakan pola: partials/{type}/{variant}.blade.php')
                    ->placeholder('partials.hero.variant1 (opsional)'),
                FileUpload::make('thumbnail')
                    ->image()
                    ->directory('components'),
                Textarea::make('content')
                    ->columnSpanFull()
                    ->placeholder('Konten default / markup untuk komponen'),
                Textarea::make('settings')
                    ->label('Settings Schema (JSON)')
                    ->helperText('Tentukan skema input variabel untuk komponen dalam format JSON array. Contoh: [{"name": "heading", "label": "Judul Utama", "type": "text", "default": "Selamat Datang"}, {"name": "content", "label": "Isi Konten", "type": "textarea", "default": "Teks penjelasan"}, {"name": "show_button", "label": "Tampilkan Tombol", "type": "boolean", "default": true}]')
                    ->json()
                    ->dehydrateStateUsing(fn ($state) => is_string($state) ? json_decode($state, true) : $state)
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $state)
                    ->columnSpanFull()
                    ->extraAttributes(['style' => "font-family: 'Geist Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 13px; line-height: 1.5;"]),
                Toggle::make('is_active')
                    ->required()
                    ->default(true),
            ]);
    }
}
