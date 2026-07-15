<?php

namespace App\Filament\Resources\Themes\Pages;

use App\Filament\Resources\Themes\ThemeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListThemes extends ListRecords
{
    protected static string $resource = ThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('upload_theme')
                ->label('Upload Theme (.zip)')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\FileUpload::make('theme_zip')
                        ->label('Theme Zip File')
                        ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed', 'application/octet-stream'])
                        ->required()
                        ->storeFiles(false),
                ])
                ->action(function (array $data) {
                    $file = $data['theme_zip'];
                    $zip = new \ZipArchive;
                    if ($zip->open($file->getRealPath()) === TRUE) {
                        $themeSlug = null;
                        for($i = 0; $i < $zip->numFiles; $i++) {
                            $stat = $zip->statIndex($i);
                            $parts = explode('/', $stat['name']);
                            if (!empty($parts[0])) {
                                $themeSlug = $parts[0];
                                break;
                            }
                        }
                        
                        if (!$themeSlug) {
                            \Filament\Notifications\Notification::make()->danger()->title('Invalid zip structure.')->send();
                            return;
                        }
                        
                        $extractPath = resource_path('views/themes');
                        $zip->extractTo($extractPath);
                        $zip->close();
                        
                        $jsonPath = $extractPath . '/' . $themeSlug . '/theme.json';
                        if (file_exists($jsonPath)) {
                            $themeData = json_decode(file_get_contents($jsonPath), true);
                            \App\Models\Theme::updateOrCreate(
                                ['slug' => $themeSlug],
                                [
                                    'name' => $themeData['name'] ?? \Illuminate\Support\Str::headline($themeSlug),
                                    'version' => $themeData['version'] ?? '1.0.0',
                                    'author' => $themeData['author'] ?? null,
                                    'description' => $themeData['description'] ?? null,
                                ]
                            );
                        } else {
                            \App\Models\Theme::updateOrCreate(
                                ['slug' => $themeSlug],
                                ['name' => \Illuminate\Support\Str::headline($themeSlug)]
                            );
                        }
                        
                        \Filament\Notifications\Notification::make()->success()->title('Theme uploaded successfully!')->send();
                    } else {
                        \Filament\Notifications\Notification::make()->danger()->title('Failed to open zip file.')->send();
                    }
                }),
            CreateAction::make(),
        ];
    }
}
