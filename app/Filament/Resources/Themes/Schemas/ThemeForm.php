<?php

namespace App\Filament\Resources\Themes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ThemeForm
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
                    ->unique('themes', 'slug', ignoreRecord: true),
                Textarea::make('description')
                    ->columnSpanFull(),
                \App\Filament\Helpers\MediaSelectHelper::make('screenshot_id', 'Screenshot', isRelation: true),
                TextInput::make('version')
                    ->required()
                    ->default('1.0.0'),
                TextInput::make('author'),
                Toggle::make('is_active')
                    ->required()
                    ->default(false),
            ]);
    }
}
