<?php

namespace App\Filament\Resources\FormSubmissions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FormSubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('form_id')
                    ->relationship('form', 'name')
                    ->required()
                    ->disabled(),
                Textarea::make('data')
                    ->label('Submitted Data (JSON)')
                    ->json()
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $state)
                    ->columnSpanFull()
                    ->disabled(),
                TextInput::make('ip_address')
                    ->disabled(),
                TextInput::make('user_agent')
                    ->disabled(),
                Toggle::make('is_read')
                    ->required(),
            ]);
    }
}
