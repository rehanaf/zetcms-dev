<?php

namespace App\Filament\Resources\Redirects\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RedirectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('old_url')
                    ->placeholder('/url-lama')
                    ->required(),
                TextInput::make('new_url')
                    ->placeholder('/url-baru atau https://domain.com/url-baru')
                    ->required(),
                Select::make('type')
                    ->options([
                        301 => '301 - Permanent Redirect',
                        302 => '302 - Temporary Redirect',
                    ])
                    ->default(301)
                    ->required(),
                TextInput::make('hits')
                    ->numeric()
                    ->default(0)
                    ->disabled()
                    ->dehydrated(false),
                Toggle::make('is_active')
                    ->required()
                    ->default(true),
            ]);
    }
}
