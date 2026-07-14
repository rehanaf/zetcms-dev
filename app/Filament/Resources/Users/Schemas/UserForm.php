<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('User')
                ->tabs([
                    Tabs\Tab::make('Akun & Role')
                        ->icon('heroicon-o-user')
                        ->schema([
                            Grid::make(3)->schema([

                                // ── Kolom utama (2/3) ──────────────────
                                Group::make([
                                    Section::make('Informasi Akun')
                                        ->icon('heroicon-o-user-circle')
                                        ->schema([
                                            TextInput::make('name')
                                                ->label('Nama Lengkap')
                                                ->required(),

                                            TextInput::make('email')
                                                ->label('Email')
                                                ->email()
                                                ->required()
                                                ->unique('users', 'email', ignoreRecord: true),

                                            TextInput::make('password')
                                                ->label('Password')
                                                ->password()
                                                ->revealable()
                                                ->required(fn (string $operation) => $operation === 'create')
                                                ->dehydrated(fn ($state) => filled($state))
                                                ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                                                ->helperText('Kosongkan jika tidak ingin mengubah password'),
                                        ]),

                                    Section::make('Bio')
                                        ->icon('heroicon-o-document-text')
                                        ->schema([
                                            Textarea::make('bio')
                                                ->label('Tentang User')
                                                ->rows(4),
                                        ]),
                                ])->columnSpan(2),

                                // ── Kolom sidebar (1/3) ─────────────────
                                Group::make([
                                    Section::make('Role & Status')
                                        ->icon('heroicon-o-shield-check')
                                        ->schema([
                                            Select::make('role_id')
                                                ->label('Role')
                                                ->relationship('role', 'name')
                                                ->preload()
                                                ->required(),

                                            Toggle::make('is_active')
                                                ->label('Akun Aktif')
                                                ->default(true)
                                                ->helperText('Nonaktifkan untuk memblokir login user'),
                                        ]),

                                    Section::make('Avatar')
                                        ->icon('heroicon-o-photo')
                                        ->schema([
                                            FileUpload::make('avatar')
                                                ->label('Foto Profil')
                                                ->image()
                                                ->imagePreviewHeight('100')
                                                ->disk('public')
                                                ->directory('avatars')
                                                ->avatar(),
                                        ]),
                                ])->columnSpan(1),

                            ]),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }
}
