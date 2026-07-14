<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Filament\Helpers\SeoFormHelper;
use App\Filament\Helpers\MediaSelectHelper;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    protected static array $variantCache = [];
    protected static ?string $activeThemeSlug = null;

    public static function getVariantOptions(string $type): array
    {
        if (isset(self::$variantCache[$type])) {
            return self::$variantCache[$type];
        }

        if (self::$activeThemeSlug === null) {
            self::$activeThemeSlug = 'default';
            try {
                self::$activeThemeSlug = \App\Models\Theme::where('is_active', true)->first()?->slug ?? 'default';
            } catch (\Exception $e) {
            }
        }
        
        $themeSlug = self::$activeThemeSlug;
        $options = [];
        
        $themePath = resource_path("views/themes/{$themeSlug}/partials/{$type}");
        $defaultPath = resource_path("views/themes/default/partials/{$type}");
        
        if (is_dir($themePath)) {
            $files = glob($themePath . '/*.blade.php');
            if ($files) {
                foreach ($files as $file) {
                    $name = basename($file, '.blade.php');
                    $options[$name] = ucfirst($name);
                }
            }
        }
        
        if (is_dir($defaultPath)) {
            $files = glob($defaultPath . '/*.blade.php');
            if ($files) {
                foreach ($files as $file) {
                    $name = basename($file, '.blade.php');
                    if (!isset($options[$name])) {
                        $options[$name] = ucfirst($name) . ' (Default Theme)';
                    }
                }
            }
        }
        
        self::$variantCache[$type] = $options ?: ['default' => 'Default'];
        return self::$variantCache[$type];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Page Editor')
                    ->tabs([
                        // TAB 1: CONTENT
                        Tabs\Tab::make('Halaman & Konten')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        Group::make([
                                            Section::make()
                                                ->schema([
                                                    TextInput::make('title')
                                                        ->required()
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(fn (string $operation, $state, callable $set) => 
                                                            $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                                                ]),
                                            Section::make('Page Builder (Predefined Components)')
                                                ->description('Susun komponen dinamis untuk halaman ini.')
                                                ->schema([
                                                    Builder::make('content')
                                                        ->label('Content Blocks')
                                                        ->blockPickerColumns([
                                                            'default' => 2,
                                                            'sm' => 2,
                                                            'md' => 2,
                                                            'lg' => 2,
                                                        ])
                                                        ->blockPickerWidth('2xl')
                                                        ->blocks([
                                                            // 1. HERO
                                                            Block::make('hero')
                                                                ->label('Hero')
                                                                ->icon('heroicon-o-presentation-chart-bar')
                                                                ->schema([
                                                                    Select::make('variant')
                                                                        ->options(fn () => self::getVariantOptions('hero'))
                                                                        ->default('default')
                                                                        ->required(),
                                                                    TextInput::make('title')->required(),
                                                                    TextInput::make('subtitle'),
                                                                    RichEditor::make('content')
                                                                        ->extraInputAttributes(['style' => 'min-height: 400px;']),
                                                                     MediaSelectHelper::make('image_id', 'Gambar'),
                                                                     MediaSelectHelper::make('bg_image_id', 'Gambar Latar (Background)'),
                                                                    Grid::make(2)->schema([
                                                                        TextInput::make('primary_btn_text'),
                                                                        TextInput::make('primary_btn_url'),
                                                                        TextInput::make('secondary_btn_text'),
                                                                        TextInput::make('secondary_btn_url'),
                                                                    ]),
                                                                ]),

                                                            // 2. FEATURE
                                                            Block::make('feature')
                                                                ->label('Feature')
                                                                ->icon('heroicon-o-list-bullet')
                                                                ->schema([
                                                                    Select::make('variant')
                                                                        ->options(fn () => self::getVariantOptions('feature'))
                                                                        ->default('default')
                                                                        ->required(),
                                                                    TextInput::make('title')->required(),
                                                                    TextInput::make('subtitle'),
                                                                    Repeater::make('features')
                                                                        ->schema([
                                                                            TextInput::make('title')->required(),
                                                                            Textarea::make('description'),
                                                                            TextInput::make('icon'),
                                                                            MediaSelectHelper::make('image_id', 'Gambar Fitur'),
                                                                            TextInput::make('url'),
                                                                        ])
                                                                        ->columns(2),
                                                                ]),

                                                            // 3. ABOUT
                                                            Block::make('about')
                                                                ->label('About')
                                                                ->icon('heroicon-o-information-circle')
                                                                ->schema([
                                                                    Select::make('variant')
                                                                        ->options(fn () => self::getVariantOptions('about'))
                                                                        ->default('default')
                                                                        ->required(),
                                                                    TextInput::make('title')->required(),
                                                                    TextInput::make('subtitle'),
                                                                    RichEditor::make('content')
                                                                        ->extraInputAttributes(['style' => 'min-height: 400px;']),
                                                                     MediaSelectHelper::make('image_id', 'Gambar'),
                                                                    TextInput::make('button_text'),
                                                                    TextInput::make('button_url'),
                                                                ]),

                                                            // 4. TESTIMONIAL
                                                            Block::make('testimonial')
                                                                ->label('Testimonial')
                                                                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                                                                ->schema([
                                                                    Select::make('variant')
                                                                        ->options(fn () => self::getVariantOptions('testimonial'))
                                                                        ->default('default')
                                                                        ->required(),
                                                                    TextInput::make('title'),
                                                                    Textarea::make('description')->rows(2)->label('Deskripsi'),
                                                                    TextInput::make('subtitle'),
                                                                    MediaSelectHelper::make('bg_image_id', 'Gambar Latar (Background)'),
                                                                    Select::make('source')
                                                                        ->options([
                                                                            'dynamic' => 'Ambil dari Database (Relasi)',
                                                                            'manual' => 'Input Manual (ACF Repeater)',
                                                                        ])
                                                                        ->default('dynamic')
                                                                        ->live()
                                                                        ->required(),
                                                                    Select::make('testimonial_ids')
                                                                        ->label('Pilih Testimonial')
                                                                        ->multiple()
                                                                        ->options(fn () => \App\Models\Testimonial::where('is_active', true)->pluck('name', 'id')->toArray())
                                                                        ->visible(fn (callable $get) => $get('source') === 'dynamic'),
                                                                    TextInput::make('limit')
                                                                        ->numeric()
                                                                        ->default(6)
                                                                        ->visible(fn (callable $get) => $get('source') === 'dynamic'),
                                                                    Repeater::make('manual_testimonials')
                                                                        ->schema([
                                                                            TextInput::make('name')->required(),
                                                                            TextInput::make('role'),
                                                                            TextInput::make('company'),
                                                                            Textarea::make('content')->required(),
                                                                             MediaSelectHelper::make('avatar_id', 'Avatar'),
                                                                            Select::make('rating')
                                                                                ->options([1=>1, 2=>2, 3=>3, 4=>4, 5=>5])
                                                                                ->default(5),
                                                                        ])
                                                                        ->visible(fn (callable $get) => $get('source') === 'manual')
                                                                        ->columns(2),
                                                                ]),

                                                            // 5. CTA
                                                            Block::make('cta')
                                                                ->label('Call to Action')
                                                                ->icon('heroicon-o-megaphone')
                                                                ->schema([
                                                                    Select::make('variant')
                                                                        ->options(fn () => self::getVariantOptions('cta'))
                                                                        ->default('default')
                                                                        ->required(),
                                                                    TextInput::make('title')->required(),
                                                                    Textarea::make('description')->rows(2)->label('Deskripsi'),
                                                                    TextInput::make('subtitle'),
                                                                    TextInput::make('button_text'),
                                                                    TextInput::make('button_url'),
                                                                     MediaSelectHelper::make('bg_image_id', 'Gambar Latar'),
                                                                ]),

                                                            // 6. PRICING
                                                             Block::make('pricing')
                                                                 ->label('Pricing (Dynamic)')
                                                                 ->icon('heroicon-o-currency-dollar')
                                                                 ->schema([
                                                                     Select::make('variant')
                                                                         ->options(fn () => self::getVariantOptions('pricing'))
                                                                         ->default('default')
                                                                         ->required(),
                                                                     TextInput::make('title'),
                                                                     Textarea::make('description')->rows(2)->label('Deskripsi'),
                                                                     Select::make('pricing_ids')
                                                                         ->label('Pilih Pricing Plan')
                                                                         ->multiple()
                                                                         ->options(fn () => \App\Models\Pricing::where('is_active', true)->orderBy('order')->pluck('name', 'id')->toArray())
                                                                         ->required(),
                                                                 ]),

                                                            // 7. FAQ
                                                            Block::make('faq')
                                                                ->label('FAQ')
                                                                ->icon('heroicon-o-question-mark-circle')
                                                                ->schema([
                                                                    Select::make('variant')
                                                                        ->options(fn () => self::getVariantOptions('faq'))
                                                                        ->default('default')
                                                                        ->required(),
                                                                    TextInput::make('title'),
                                                                    Textarea::make('description')->rows(2)->label('Deskripsi'),
                                                                    TextInput::make('subtitle'),
                                                                    Repeater::make('faqs')
                                                                        ->schema([
                                                                            TextInput::make('question')->required(),
                                                                            Textarea::make('answer')->required(),
                                                                        ])
                                                                        ->columns(1),
                                                                ]),

                                                            // 8. TEAM MEMBER
                                                            Block::make('teamMember')
                                                                ->label('Team Member')
                                                                ->icon('heroicon-o-users')
                                                                ->schema([
                                                                    Select::make('variant')
                                                                        ->options(fn () => self::getVariantOptions('teamMember'))
                                                                        ->default('default')
                                                                        ->required(),
                                                                    TextInput::make('title'),
                                                                    Textarea::make('description')->rows(2)->label('Deskripsi'),
                                                                    TextInput::make('subtitle'),
                                                                    Repeater::make('members')
                                                                        ->schema([
                                                                            TextInput::make('name')->required(),
                                                                            TextInput::make('role'),
                                                                            MediaSelectHelper::make('image_id', 'Gambar'),
                                                                            Textarea::make('bio'),
                                                                            TextInput::make('facebook_url'),
                                                                            TextInput::make('twitter_url'),
                                                                            TextInput::make('linkedin_url'),
                                                                            TextInput::make('instagram_url'),
                                                                        ])
                                                                        ->columns(2),
                                                                ]),

                                                            // 9. GALLERY
                                                            Block::make('gallery')
                                                                ->label('Gallery')
                                                                ->icon('heroicon-o-photo')
                                                                ->schema([
                                                                    Select::make('variant')
                                                                        ->options(fn () => self::getVariantOptions('gallery'))
                                                                        ->default('default')
                                                                        ->required(),
                                                                    TextInput::make('title'),
                                                                    Textarea::make('description')->rows(2)->label('Deskripsi'),
                                                                    TextInput::make('subtitle'),
                                                                    Repeater::make('images')
                                                                        ->schema([
                                                                             MediaSelectHelper::make('image_id', 'Gambar'),
                                                                            TextInput::make('caption'),
                                                                            TextInput::make('url'),
                                                                        ])
                                                                        ->columns(2),
                                                                ]),

                                                            // 10. STATS
                                                            Block::make('stats')
                                                                ->label('Stats')
                                                                ->icon('heroicon-o-chart-bar-square')
                                                                ->schema([
                                                                    Select::make('variant')
                                                                        ->options(fn () => self::getVariantOptions('stats'))
                                                                        ->default('default')
                                                                        ->required(),
                                                                    TextInput::make('title'),
                                                                    Textarea::make('description')->rows(2)->label('Deskripsi'),
                                                                    MediaSelectHelper::make('bg_image_id', 'Gambar Latar (Background)'),
                                                                    Repeater::make('stats')
                                                                        ->schema([
                                                                            TextInput::make('number')->required(),
                                                                            TextInput::make('label')->required(),
                                                                            TextInput::make('icon'),
                                                                        ])
                                                                        ->columns(3),
                                                                ]),

                                                            // 11. LOGO CLOUD
                                                            Block::make('logoCloud')
                                                                ->label('Logo Cloud')
                                                                ->icon('heroicon-o-square-2-stack')
                                                                ->schema([
                                                                    Select::make('variant')
                                                                        ->options(fn () => self::getVariantOptions('logoCloud'))
                                                                        ->default('default')
                                                                        ->required(),
                                                                    TextInput::make('title'),
                                                                    Textarea::make('description')->rows(2)->label('Deskripsi'),
                                                                    Repeater::make('logos')
                                                                        ->schema([
                                                                             MediaSelectHelper::make('image_id', 'Logo'),
                                                                            TextInput::make('name'),
                                                                            TextInput::make('url'),
                                                                        ])
                                                                        ->columns(3),
                                                                ]),

                                                            // 12. VIDEO
                                                            Block::make('video')
                                                                ->label('Video')
                                                                ->icon('heroicon-o-video-camera')
                                                                ->schema([
                                                                    Select::make('variant')
                                                                        ->options(fn () => self::getVariantOptions('video'))
                                                                        ->default('default')
                                                                        ->required(),
                                                                    TextInput::make('title'),
                                                                    Textarea::make('description')->rows(2)->label('Deskripsi'),
                                                                    TextInput::make('video_url')->required(),
                                                                     MediaSelectHelper::make('placeholder_image_id', 'Gambar Placeholder'),
                                                                ]),

                                                            // 13. NEWSLETTER
                                                            Block::make('newsletter')
                                                                ->label('Newsletter')
                                                                ->icon('heroicon-o-envelope-open')
                                                                ->schema([
                                                                    Select::make('variant')
                                                                        ->options(fn () => self::getVariantOptions('newsletter'))
                                                                        ->default('default')
                                                                        ->required(),
                                                                    TextInput::make('title'),
                                                                    TextInput::make('description'),
                                                                    TextInput::make('button_text')->default('Subscribe'),
                                                                    TextInput::make('placeholder')->default('Enter your email'),
                                                                ]),

                                                            // 14. FORM
                                                            Block::make('form')
                                                                ->label('Form')
                                                                ->icon('heroicon-o-document-check')
                                                                ->schema([
                                                                    Select::make('variant')
                                                                        ->options(fn () => self::getVariantOptions('form'))
                                                                        ->default('default')
                                                                        ->required(),
                                                                    TextInput::make('title'),
                                                                    Textarea::make('description')->rows(2)->label('Deskripsi'),
                                                                    Select::make('form_id')
                                                                        ->label('Pilih Form')
                                                                        ->options(fn () => \App\Models\Form::pluck('name', 'id')->toArray())
                                                                        ->required(),
                                                                ]),

                                                            // 15. MENU
                                                            Block::make('menu')
                                                                ->label('Menu')
                                                                ->icon('heroicon-o-bars-3')
                                                                ->schema([
                                                                    Select::make('variant')
                                                                        ->options(fn () => self::getVariantOptions('menu'))
                                                                        ->default('default')
                                                                        ->required(),
                                                                    Textarea::make('description')->rows(2)->label('Deskripsi'),
                                                                    Select::make('menu_id')
                                                                        ->label('Pilih Menu')
                                                                        ->options(fn () => \App\Models\Menu::pluck('name', 'id')->toArray())
                                                                        ->required(),
                                                                ]),

                                                            // 16. TEXT EDITOR
                                                             Block::make('texteditor')
                                                                 ->label('Text Editor')
                                                                 ->icon('heroicon-o-pencil-square')
                                                                 ->schema([
                                                                     Select::make('variant')
                                                                         ->options(fn () => self::getVariantOptions('texteditor'))
                                                                         ->default('default')
                                                                         ->required(),
                                                                     RichEditor::make('content')
                                                                         ->required()
                                                                         ->extraInputAttributes(['style' => 'min-height: 400px;']),
                                                                 ]),

                                                            // 17. POST
                                                            Block::make('post')
                                                                ->label('Post (Query & Filter)')
                                                                ->icon('heroicon-o-document-text')
                                                                ->schema([
                                                                    Select::make('variant')
                                                                        ->options(fn () => self::getVariantOptions('post'))
                                                                        ->default('default')
                                                                        ->required(),
                                                                    TextInput::make('title'),
                                                                    Textarea::make('description')->rows(2)->label('Deskripsi'),
                                                                    TextInput::make('subtitle'),
                                                                    Grid::make(2)->schema([
                                                                        Select::make('category_id')
                                                                            ->label('Kategori')
                                                                            ->options(fn () => \App\Models\Category::pluck('name', 'id')->toArray())
                                                                            ->nullable(),
                                                                        Select::make('tag_id')
                                                                            ->label('Tag')
                                                                            ->options(fn () => \App\Models\Tag::pluck('name', 'id')->toArray())
                                                                            ->nullable(),
                                                                    ]),
                                                                    TextInput::make('search')->label('Cari Kata Kunci'),
                                                                    Select::make('post_ids')
                                                                        ->label('Pilih Post Spesifik (Opsional)')
                                                                        ->multiple()
                                                                        ->searchable()
                                                                        ->getSearchResultsUsing(fn (string $search): array => \App\Models\Post::where('title', 'like', "%{$search}%")->limit(50)->pluck('title', 'id')->toArray())
                                                                        ->getOptionLabelsUsing(fn (array $values): array => \App\Models\Post::whereIn('id', $values)->pluck('title', 'id')->toArray()),
                                                                    TextInput::make('limit')
                                                                        ->label('Limit Jumlah Data')
                                                                        ->numeric()
                                                                        ->default(6),
                                                                ]),

                                                            // 18. TABLE
                                                            Block::make('table')
                                                                ->label('Table')
                                                                ->icon('heroicon-o-table-cells')
                                                                ->schema([
                                                                    Select::make('variant')
                                                                        ->options(fn () => self::getVariantOptions('table'))
                                                                        ->default('default')
                                                                        ->required(),
                                                                    TextInput::make('title'),
                                                                    Textarea::make('description')->rows(2)->label('Deskripsi'),
                                                                    Repeater::make('rows')
                                                                        ->schema([
                                                                            Repeater::make('cells')
                                                                                ->schema([
                                                                                    TextInput::make('value')
                                                                                        ->hiddenLabel()
                                                                                        ->placeholder('Cell Value'),
                                                                                ])
                                                                                ->simple(TextInput::make('value'))
                                                                        ])
                                                                ]),

                                                            // 19. CAROUSEL
                                                            Block::make('carousel')
                                                                ->label('Carousel')
                                                                ->icon('heroicon-o-arrow-path')
                                                                ->schema([
                                                                    Select::make('variant')
                                                                        ->options(fn () => self::getVariantOptions('carousel'))
                                                                        ->default('default')
                                                                        ->required(),
                                                                    TextInput::make('title'),
                                                                    Textarea::make('description')->rows(2)->label('Deskripsi'),
                                                                    Repeater::make('items')
                                                                        ->schema([
                                                                             MediaSelectHelper::make('image_id', 'Gambar Slide'),
                                                                            TextInput::make('title'),
                                                                            TextInput::make('subtitle'),
                                                                            Textarea::make('description')->rows(2)->label('Deskripsi'),
                                                                            TextInput::make('button_text'),
                                                                            TextInput::make('button_url'),
                                                                        ])
                                                                        ->columns(2),
                                                                ]),

                                                            // 20. PRICE TABLE
                                                            Block::make('price_table')
                                                                ->label('Price Table (Manual)')
                                                                ->icon('heroicon-o-currency-dollar')
                                                                ->schema([
                                                                    Select::make('variant')
                                                                        ->options(fn () => self::getVariantOptions('price_table'))
                                                                        ->default('default')
                                                                        ->required(),
                                                                    TextInput::make('title'),
                                                                    Textarea::make('description')->rows(2)->label('Deskripsi'),
                                                                    Repeater::make('categories')
                                                                        ->label('Kategori / Grup')
                                                                        ->schema([
                                                                            TextInput::make('title')->required()->label('Nama Kategori'),
                                                                            TextInput::make('icon')->label('Ikon / Simbol'),
                                                                            MediaSelectHelper::make('image_id', 'Gambar Kategori'),
                                                                            Repeater::make('items')
                                                                                ->label('Daftar Item / Harga')
                                                                                ->schema([
                                                                                    TextInput::make('name')->required()->label('Nama Item'),
                                                                                    TextInput::make('price')->required()->label('Harga'),
                                                                                    Textarea::make('description')->rows(2)->label('Deskripsi'),
                                                                                ])
                                                                                ->columns(1),
                                                                        ])
                                                                        ->columns(1),
                                                                ]),

                                                         ])
                                                         ->collapsible()
                                                         ->collapsed()
                                                         ->cloneable()
                                                         ->columnSpanFull()
                                                 ]),
                                          ])->columnSpan(2),
                                          Group::make([
                                              Section::make('Options')
                                                  ->schema([
                                                      MediaSelectHelper::make('featured_image_id', 'Featured Image', isRelation: true),
                                                      TextInput::make('slug')
                                                          ->required()
                                                          ->unique('pages', 'slug', ignoreRecord: true),
                                                  ]),
                                          ])->columnSpan(1),
                                      ])
                              ]),
                        
                        // TAB 2: STATUS & PUBLISHING
                        Tabs\Tab::make('Status & Penerbitan')
                            ->icon('heroicon-o-paper-airplane')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('user_id')
                                            ->relationship('user', 'name')
                                            ->default(fn () => auth()->id())
                                            ->required(),
                                        Select::make('status')
                                            ->options([
                                                'draft' => 'Draft',
                                                'pending_review' => 'Pending Review',
                                                'published' => 'Published',
                                                'scheduled' => 'Scheduled',
                                            ])
                                            ->default('published')
                                            ->required(),
                                        DateTimePicker::make('published_at'),
                                        DateTimePicker::make('expired_at'),
                                    ])
                            ]),

                        // TAB 3: SEO & METADATA
                        Tabs\Tab::make('SEO & Metadata')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                SeoFormHelper::make(),
                            ])
                    ])
                    ->columnSpanFull()
            ]);
    }
}
