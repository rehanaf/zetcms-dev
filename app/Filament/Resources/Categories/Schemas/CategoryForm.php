<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Filament\Helpers\SeoFormHelper;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('parent_id')
                    ->relationship('parent', 'name'),
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, callable $set) => 
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                TextInput::make('slug')
                    ->required()
                    ->unique('categories', 'slug', ignoreRecord: true),
                Textarea::make('description')
                    ->columnSpanFull(),
                \App\Filament\Helpers\MediaSelectHelper::make('image_id', 'Gambar Kategori', isRelation: true),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required()
                    ->default(true),
                SeoFormHelper::make(),
            ]);
    }
}
