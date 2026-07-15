<?php

namespace App\Filament\Resources\Themes\Tables;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ThemesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                ImageColumn::make('screenshot'),
                TextColumn::make('version')
                    ->searchable(),
                TextColumn::make('author')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (\App\Models\Theme $record) {
                        // Jangan biarkan tema default dihapus
                        if ($record->slug === 'default') {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Cannot delete default theme')
                                ->send();
                            return false;
                        }
                        
                        $themeDir = resource_path("views/themes/{$record->slug}");
                        if (\Illuminate\Support\Facades\File::exists($themeDir)) {
                            \Illuminate\Support\Facades\File::deleteDirectory($themeDir);
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
