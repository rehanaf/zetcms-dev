<?php

namespace App\Filament\Resources\Comments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('post_id')
                    ->relationship('post', 'title')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name'),
                Select::make('parent_id')
                    ->relationship('parent', 'author_name')
                    ->placeholder('None (Top-level comment)'),
                TextInput::make('author_name'),
                TextInput::make('author_email')
                    ->email(),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending Moderation',
                        'approved' => 'Approved',
                        'spam' => 'Spam',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }
}
