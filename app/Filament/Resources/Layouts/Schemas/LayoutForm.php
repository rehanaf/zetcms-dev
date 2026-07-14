<?php

namespace App\Filament\Resources\Layouts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class LayoutForm
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
                    ->unique('layouts', 'slug', ignoreRecord: true),
                TextInput::make('view_path')
                    ->required()
                    ->placeholder('layouts.app')
                    ->default('layouts.app'),
                Textarea::make('description')
                    ->columnSpanFull(),
                Toggle::make('is_default')
                    ->required()
                    ->default(false),
            ]);
    }
}
