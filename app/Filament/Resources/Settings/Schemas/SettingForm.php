<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->required()
                    ->unique('settings', 'key', ignoreRecord: true),
                Textarea::make('value')
                    ->columnSpanFull(),
                Select::make('type')
                    ->options([
                        'text' => 'Text',
                        'textarea' => 'Textarea',
                        'boolean' => 'Boolean Toggle',
                        'json' => 'JSON',
                        'image' => 'Image / File',
                    ])
                    ->required()
                    ->default('text'),
                Select::make('group')
                    ->options([
                        'general' => 'General Settings',
                        'seo' => 'SEO Default Settings',
                        'social' => 'Social Media Links',
                        'custom' => 'Custom Settings',
                    ])
                    ->required()
                    ->default('general'),
            ]);
    }
}
