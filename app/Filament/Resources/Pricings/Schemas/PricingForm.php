<?php

namespace App\Filament\Resources\Pricings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class PricingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('price')
                    ->required(),
                TextInput::make('billing_period')
                    ->default('monthly'),
                TextInput::make('icon')
                    ->placeholder('heroicon-o-check'),
                \App\Filament\Helpers\MediaSelectHelper::make('image_id', 'Gambar', isRelation: true),
                Textarea::make('description')
                    ->columnSpanFull(),
                Repeater::make('features')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->placeholder('Nama Fitur')
                            ->hiddenLabel(),
                        Toggle::make('is_included')
                            ->hiddenLabel()
                            ->default(true),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                TextInput::make('button_text')
                    ->default('Get Started'),
                TextInput::make('button_url')
                    ->default('#'),
                TextInput::make('order')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_featured')
                    ->label('Unggulan')
                    ->default(false),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }
}
