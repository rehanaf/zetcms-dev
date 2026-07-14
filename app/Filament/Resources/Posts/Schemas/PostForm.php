<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Helpers\SeoFormHelper;
use App\Filament\Helpers\MediaSelectHelper;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Post Editor')
                    ->tabs([
                        // TAB 1: ARTICLE CONTENT
                        Tabs\Tab::make('Artikel & Konten')
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
                                                     RichEditor::make('content')
                                                         ->required()
                                                         ->extraInputAttributes(['style' => 'min-height: 400px;'])
                                                         ->columnSpanFull(),
                                                 ]),
                                         ])->columnSpan(2),
                                         Group::make([
                                             Section::make('Options')
                                                 ->schema([
                                                     MediaSelectHelper::make('featured_image_id', 'Featured Image', isRelation: true),
                                                     TextInput::make('slug')
                                                         ->required()
                                                         ->unique('posts', 'slug', ignoreRecord: true),
                                                     Textarea::make('excerpt')
                                                         ->rows(3)
                                                         ->label('Excerpt / Ringkasan'),
                                                     Select::make('category_id')
                                                         ->relationship('category', 'name')
                                                         ->searchable()
                                                         ->createOptionForm([
                                                             TextInput::make('name')
                                                                 ->required()
                                                                 ->live(onBlur: true)
                                                                 ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                                             TextInput::make('slug')->required()->unique('categories', 'slug'),
                                                         ]),
                                                     Select::make('tags')
                                                         ->relationship('tags', 'name')
                                                         ->multiple()
                                                         ->preload()
                                                         ->searchable()
                                                         ->createOptionForm([
                                                             TextInput::make('name')
                                                                 ->required()
                                                                 ->live(onBlur: true)
                                                                 ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                                             TextInput::make('slug')->required()->unique('tags', 'slug'),
                                                         ]),
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
                                        Toggle::make('is_featured')
                                            ->label('Featured Post')
                                            ->default(false),
                                        TextInput::make('views')
                                            ->numeric()
                                            ->default(0)
                                            ->disabled()
                                            ->dehydrated(false),
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
