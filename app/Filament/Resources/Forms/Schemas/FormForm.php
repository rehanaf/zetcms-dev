<?php

namespace App\Filament\Resources\Forms\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class FormForm
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
                    ->unique('forms', 'slug', ignoreRecord: true),
                Textarea::make('success_message')
                    ->placeholder('Pesan yang ditampilkan setelah formulir dikirim')
                    ->columnSpanFull(),
                TextInput::make('notification_email')
                    ->email()
                    ->placeholder('admin@example.com'),
                Textarea::make('settings')
                    ->label('Form Settings (JSON)')
                    ->helperText('Pengaturan tambahan dalam format JSON.')
                    ->json()
                    ->dehydrateStateUsing(fn ($state) => is_string($state) ? json_decode($state, true) : $state)
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $state)
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required()
                    ->default(true),
                Section::make('Form Fields')
                    ->schema([
                        Repeater::make('fields')
                            ->relationship('fields')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('label')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (string $operation, $state, callable $set) => 
                                                $operation === 'create' ? $set('name', Str::slug($state, '_')) : null),
                                        TextInput::make('name')
                                            ->required(),
                                        Select::make('type')
                                            ->options([
                                                'text' => 'Text',
                                                'email' => 'Email',
                                                'textarea' => 'Textarea',
                                                'select' => 'Select Dropdown',
                                                'checkbox' => 'Checkbox',
                                                'radio' => 'Radio Buttons',
                                                'number' => 'Number',
                                                'date' => 'Date Picker',
                                                'file' => 'File Upload',
                                            ])
                                            ->default('text')
                                            ->required(),
                                        TextInput::make('placeholder'),
                                        Toggle::make('is_required')
                                            ->label('Required?')
                                            ->default(false),
                                        TextInput::make('order')
                                            ->numeric()
                                            ->default(0)
                                            ->required(),
                                    ]),
                                Textarea::make('options')
                                    ->label('Field Options (JSON)')
                                    ->helperText('Hanya untuk tipe select/radio/checkbox. Contoh: [{"label":"Pria","value":"L"},{"label":"Wanita","value":"P"}]')
                                    ->json()
                                    ->dehydrateStateUsing(fn ($state) => is_string($state) ? json_decode($state, true) : $state)
                                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $state)
                                    ->columnSpanFull()
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
