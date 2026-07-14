<?php

namespace App\Filament\Resources\Forms\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
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
                RichEditor::make('success_message')
                    ->placeholder('Pesan yang ditampilkan setelah formulir dikirim')
                    ->toolbarButtons([
                        'blockquote', 'bold', 'bulletList', 'codeBlock', 'h2', 'h3', 'italic', 'link', 'orderedList', 'redo', 'strike', 'underline', 'undo',
                    ])
                    ->columnSpanFull(),
                TextInput::make('notification_email')
                    ->email()
                    ->placeholder('admin@example.com'),
                Section::make('Webhook Integration')
                    ->description('Kirim data formulir ke layanan eksternal secara otomatis.')
                    ->schema([
                        TextInput::make('settings.webhook_url')
                            ->label('Webhook URL')
                            ->url()
                            ->placeholder('https://api.example.com/endpoint'),
                        Select::make('settings.webhook_method')
                            ->label('HTTP Method')
                            ->options([
                                'POST' => 'POST',
                                'GET' => 'GET',
                                'PUT' => 'PUT',
                            ])
                            ->default('POST'),
                        RichEditor::make('settings.webhook_text_template')
                            ->label('Text Template')
                            ->helperText('Gunakan {{nama_field}} untuk memasukkan data form. Hasilnya akan dikirim sebagai field "text" dan "content" (format HTML).')
                            ->toolbarButtons([
                                'blockquote', 'bold', 'bulletList', 'codeBlock', 'h2', 'h3', 'italic', 'link', 'orderedList', 'redo', 'strike', 'underline', 'undo',
                            ]),
                        KeyValue::make('settings.webhook_headers')
                            ->label('Custom Headers')
                            ->keyLabel('Header Name')
                            ->valueLabel('Header Value')
                            ->addActionLabel('Add Header'),
                        KeyValue::make('settings.webhook_payload')
                            ->label('Custom Payload Mapping')
                            ->keyLabel('JSON Key')
                            ->valueLabel('JSON Value')
                            ->helperText('Kosongkan untuk mengirim semua data secara default. Gunakan {{nama_field}} untuk mapping variabel form.')
                            ->addActionLabel('Add Payload Field'),
                    ])
                    ->collapsed(),
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
