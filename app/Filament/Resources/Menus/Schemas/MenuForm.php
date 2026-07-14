<?php

namespace App\Filament\Resources\Menus\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, callable $set) => 
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                TextInput::make('slug')
                    ->required()
                    ->unique('menus', 'slug', ignoreRecord: true),
                Section::make('Menu Items')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Menu Text')
                                            ->required(),
                                        Select::make('page_id')
                                            ->label('Link to Page')
                                            ->relationship('page', 'title')
                                            ->nullable()
                                            ->searchable(),
                                        Select::make('post_id')
                                            ->label('Link to Post')
                                            ->relationship('post', 'title')
                                            ->nullable()
                                            ->searchable(),
                                        TextInput::make('url')
                                            ->label('Custom URL')
                                            ->placeholder('https:// atau /path'),
                                        Select::make('target')
                                            ->options([
                                                '_self' => 'Same Window (_self)',
                                                '_blank' => 'New Window (_blank)',
                                            ])
                                            ->default('_self')
                                            ->required(),
                                        TextInput::make('order')
                                            ->numeric()
                                            ->default(0)
                                            ->required(),
                                    ])
                            ])
                            ->orderColumn('order')
                            ->collapsible()
                            ->collapsed()
                            ->cloneable()
                            ->columnSpanFull()
                    ])
                    ->columnSpanFull()
            ]);
    }
}
