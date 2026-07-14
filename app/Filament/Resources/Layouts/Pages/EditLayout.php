<?php

namespace App\Filament\Resources\Layouts\Pages;

use App\Filament\Resources\Layouts\LayoutResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditLayout extends EditRecord
{
    protected static string $resource = LayoutResource::class;

    public static bool $formActionsAreSticky = true;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit_blade')
                ->label('Edit Blade Template')
                ->icon('heroicon-o-code-bracket')
                ->color('warning')
                ->modalHeading(fn ($record) => 'Edit Blade: ' . $record->name)
                ->form([
                    Textarea::make('blade_content')
                        ->label('Blade Code')
                        ->rows(20)
                        ->extraAttributes(['style' => "font-family: 'Geist Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 14px; line-height: 1.5;"])
                        ->required()
                ])
                ->fillForm(function ($record) {
                    $path = resource_path("views/themes/{$record->theme->slug}/" . str_replace('.', '/', $record->view_path) . '.blade.php');
                    $content = '';
                    if (file_exists($path)) {
                        $content = file_get_contents($path);
                    }
                    return [
                        'blade_content' => $content
                    ];
                })
                ->action(function (array $data, $record) {
                    $path = resource_path("views/themes/{$record->theme->slug}/" . str_replace('.', '/', $record->view_path) . '.blade.php');
                    
                    // Ensure the directory exists
                    $dir = dirname($path);
                    if (!is_dir($dir)) {
                        mkdir($dir, 0755, true);
                    }
                    
                    file_put_contents($path, $data['blade_content']);
                    
                    Notification::make()
                        ->title('File Blade berhasil diperbarui!')
                        ->success()
                        ->send();
                }),
            DeleteAction::make(),
        ];
    }
}
