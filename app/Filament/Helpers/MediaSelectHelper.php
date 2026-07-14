<?php

namespace App\Filament\Helpers;

use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Schemas\Components\Tabs;
use Illuminate\Support\HtmlString;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Models\Media;

class MediaSelectHelper
{
    public static function make(string $name, string $label = 'Pilih Media', bool $isRelation = false): Group
    {
        return Group::make([
            // 1. Reactive Preview Box (Adapts to Dark/Light mode)
            Placeholder::make($name . '_preview')
                ->label($label)
                ->content(function ($get) use ($name) {
                    $id = $get($name);
                    if ($id && $media = Media::find($id)) {
                        return new HtmlString("
                            <div class='flex flex-col gap-2 mb-1'>
                                <img src='" . asset('storage/' . $media->file_path) . "' class='max-h-36 w-auto object-contain rounded-lg border border-gray-200 dark:border-gray-700 p-1 bg-white dark:bg-gray-800' />
                                <div class='text-xs text-gray-600 dark:text-gray-400 font-medium'>
                                    {$media->file_name} (" . number_format($media->size / 1024, 1) . " KB)
                                </div>
                            </div>
                        ");
                    }
                    return new HtmlString("
                        <div class='h-24 flex items-center justify-center bg-gray-50 dark:bg-gray-900/50 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg text-gray-400 dark:text-gray-500 text-sm mb-1'>
                            Belum ada media terpilih
                        </div>
                    ");
                }),

            // 2. Hidden Input storing media ID
            Hidden::make($name)
                ->live(),

            // 3. Standalone Actions buttons
            Actions::make([
                // Button 1: Browse Media Modal
                Action::make('browse_media')
                    ->label('Pilih dari Galeri')
                    ->icon('heroicon-o-photo')
                    ->color('primary')
                    ->form([
                        TextInput::make('search_query')
                            ->label('Cari Nama File / Alt Text')
                            ->live(debounce: 300)
                            ->placeholder('Ketik nama file...'),

                        CheckboxList::make('media_id')
                            ->label('Pilih salah satu gambar:')
                            ->allowHtml()
                            ->options(function ($get) {
                                $search = $get('search_query');
                                $query = Media::query();
                                if ($search) {
                                    $query->where('file_name', 'like', "%{$search}%")
                                          ->orWhere('alt_text', 'like', "%{$search}%");
                                }
                                return $query->orderBy('created_at', 'desc')->get()->mapWithKeys(fn ($m) => [
                                    $m->id => "
                                        <div class='flex flex-col items-center gap-2 p-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-200 w-full box-border'>
                                            <img src='" . asset('storage/' . $m->file_path) . "' class='w-24 h-24 object-cover rounded-md border border-gray-200 dark:border-gray-700' />
                                            <span class='text-[11px] text-center max-w-full truncate font-medium text-gray-700 dark:text-gray-300'>{$m->file_name}</span>
                                        </div>
                                    "
                                ])->toArray();
                            })
                            ->columns(4)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (is_array($state) && count($state) > 1) {
                                    $set('media_id', [end($state)]);
                                }
                            }),
                    ])
                    ->action(function (array $data, callable $set) use ($name) {
                        $selectedId = is_array($data['media_id']) ? (end($data['media_id']) ?: null) : null;
                        $set($name, $selectedId);
                    }),

                // Button 2: Upload New Media Modal
                Action::make('upload_new_media')
                    ->label('Unggah Baru')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('success')
                    ->form([
                        Tabs::make('Unggah Media')
                            ->tabs([
                                Tabs\Tab::make('File & Detail')
                                    ->icon('heroicon-o-document-arrow-up')
                                    ->schema([
                                        FileUpload::make('file_path')
                                            ->label('File Media')
                                            ->required()
                                            ->disk('public')
                                            ->directory('media')
                                            ->live()
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                if ($state instanceof TemporaryUploadedFile) {
                                                    $set('file_name', $state->getClientOriginalName());
                                                    $set('mime_type', $state->getMimeType());
                                                    $set('size', $state->getSize());
                                                }
                                            }),
                                        TextInput::make('file_name')
                                            ->label('Nama File')
                                            ->required(),
                                        Select::make('collection')
                                            ->options([
                                                'default' => 'Default',
                                                'pages' => 'Halaman (Pages)',
                                                'posts' => 'Artikel (Posts)',
                                                'banners' => 'Banners / Slider',
                                                'logos' => 'Logos',
                                            ])
                                            ->default('default')
                                            ->required(),
                                    ]),
                                Tabs\Tab::make('SEO & Deskripsi')
                                    ->icon('heroicon-o-globe-alt')
                                    ->schema([
                                        TextInput::make('alt_text')
                                            ->label('Alt Text (SEO)')
                                            ->placeholder('Deskripsi gambar untuk SEO'),
                                        TextInput::make('caption')
                                            ->label('Caption / Keterangan'),
                                    ]),
                                Tabs\Tab::make('System Metadata')
                                    ->icon('heroicon-o-information-circle')
                                    ->schema([
                                        TextInput::make('mime_type')
                                            ->label('Tipe Mime')
                                            ->disabled()
                                            ->dehydrated(),
                                        TextInput::make('size')
                                            ->label('Ukuran (Bytes)')
                                            ->numeric()
                                            ->disabled()
                                            ->dehydrated(),
                                    ]),
                            ])
                            ->columnSpanFull()
                    ])
                    ->action(function (array $data, callable $set) use ($name) {
                        // Flatten fields from tabs
                        $mediaData = [];
                        foreach ($data as $key => $value) {
                            if (is_array($value)) {
                                $mediaData = array_merge($mediaData, $value);
                            } else {
                                $mediaData[$key] = $value;
                            }
                        }
                        $media = Media::create($mediaData);
                        $set($name, $media->id);
                    }),

                // Button 3: Clear Pilihan
                Action::make('clear_media_selection')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn ($get) => $get($name) !== null)
                    ->action(function (callable $set) use ($name) {
                        $set($name, null);
                    })
            ]),
        ]);
    }
}
