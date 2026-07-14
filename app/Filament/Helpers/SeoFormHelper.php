<?php

namespace App\Filament\Helpers;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

class SeoFormHelper
{
    public static function make(): Section
    {
        return Section::make('SEO & Metadata')
            ->relationship('seo')
            ->description('Kelola meta tags, Open Graph, Twitter Card, dan konfigurasi sitemap untuk optimasi SEO.')
            ->collapsible()
            ->collapsed()
            ->schema([
                Tabs::make('SEO Details')
                    ->tabs([
                        Tabs\Tab::make('Meta Tags')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('Meta Title')
                                            ->placeholder('Judul SEO (fallback ke judul konten)')
                                            ->maxLength(60),
                                        TextInput::make('focus_keyword')
                                            ->label('Focus Keyword')
                                            ->placeholder('Kata kunci utama'),
                                        TextInput::make('meta_keywords')
                                            ->label('Meta Keywords')
                                            ->placeholder('kata-kunci, kata-kunci-lain')
                                            ->columnSpanFull(),
                                        Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->placeholder('Deskripsi singkat konten (maksimal 160-320 karakter)')
                                            ->maxLength(320)
                                            ->columnSpanFull(),
                                        TextInput::make('canonical_url')
                                            ->label('Canonical URL')
                                            ->url()
                                            ->placeholder('https://example.com/url-utama')
                                            ->columnSpanFull(),
                                        TextInput::make('locale')
                                            ->label('Locale')
                                            ->placeholder('id')
                                            ->maxLength(10)
                                            ->default('id'),
                                        TextInput::make('hreflang_group')
                                            ->label('Hreflang Group UUID')
                                            ->placeholder('Masukkan UUID jika terjemahan dari konten lain')
                                            ->uuid(),
                                    ]),
                            ]),
                        Tabs\Tab::make('Media Sosial (Open Graph & Twitter)')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Section::make('Open Graph (Facebook / LinkedIn)')
                                            ->compact()
                                            ->schema([
                                                TextInput::make('og_title')
                                                    ->label('OG Title'),
                                                TextInput::make('og_description')
                                                    ->label('OG Description'),
                                                FileUpload::make('og_image')
                                                    ->label('OG Image')
                                                    ->image(),
                                                Select::make('og_type')
                                                    ->label('OG Type')
                                                    ->options([
                                                        'website' => 'Website',
                                                        'article' => 'Article',
                                                        'profile' => 'Profile',
                                                        'book' => 'Book',
                                                    ])
                                                    ->default('website'),
                                            ])
                                            ->columnSpan(1),
                                        Section::make('Twitter Card')
                                            ->compact()
                                            ->schema([
                                                Select::make('twitter_card')
                                                    ->label('Twitter Card Type')
                                                    ->options([
                                                        'summary' => 'Summary',
                                                        'summary_large_image' => 'Summary Large Image',
                                                        'app' => 'App',
                                                        'player' => 'Player',
                                                    ])
                                                    ->default('summary_large_image'),
                                                TextInput::make('twitter_title')
                                                    ->label('Twitter Title'),
                                                TextInput::make('twitter_description')
                                                    ->label('Twitter Description'),
                                                FileUpload::make('twitter_image')
                                                    ->label('Twitter Image')
                                                    ->image(),
                                            ])
                                            ->columnSpan(1),
                                    ]),
                            ]),
                        Tabs\Tab::make('Index & Sitemap')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Section::make('Robots')
                                            ->compact()
                                            ->schema([
                                                Toggle::make('robots_index')
                                                    ->label('Allow Search Engines to Index (robots_index)')
                                                    ->default(true),
                                                Toggle::make('robots_follow')
                                                    ->label('Allow Search Engines to Follow Links (robots_follow)')
                                                    ->default(true),
                                            ])
                                            ->columnSpan(1),
                                        Section::make('Sitemap')
                                            ->compact()
                                            ->schema([
                                                Toggle::make('sitemap_include')
                                                    ->label('Include in XML Sitemap')
                                                    ->default(true),
                                                TextInput::make('sitemap_priority')
                                                    ->label('Sitemap Priority')
                                                    ->numeric()
                                                    ->default(0.5)
                                                    ->minValue(0.0)
                                                    ->maxValue(1.0)
                                                    ->step(0.1),
                                                Select::make('sitemap_change_freq')
                                                    ->label('Change Frequency')
                                                    ->options([
                                                        'always' => 'Always',
                                                        'hourly' => 'Hourly',
                                                        'daily' => 'Daily',
                                                        'weekly' => 'Weekly',
                                                        'monthly' => 'Monthly',
                                                        'yearly' => 'Yearly',
                                                        'never' => 'Never',
                                                    ])
                                                    ->default('weekly'),
                                            ])
                                            ->columnSpan(1),
                                    ]),
                            ]),
                        Tabs\Tab::make('Schema Markup (JSON-LD)')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Textarea::make('schema_markup')
                                    ->label('Structured Data (JSON-LD)')
                                    ->helperText('Masukkan format JSON-LD valid (tanpa tag <script>). Contoh: {"@context": "https://schema.org", "@type": "Article"}')
                                    ->json()
                                    ->dehydrateStateUsing(fn ($state) => is_string($state) ? json_decode($state, true) : $state)
                                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $state)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }
}
