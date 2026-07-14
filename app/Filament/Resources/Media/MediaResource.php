<?php

namespace App\Filament\Resources\Media;

use App\Filament\Resources\Media\Pages\ManageMedia;
use App\Models\Media;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Support\Facades\Storage;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Tabs::make('Detail Media')
                    ->tabs([
                        \Filament\Schemas\Components\Tabs\Tab::make('File & Detail')
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
                        \Filament\Schemas\Components\Tabs\Tab::make('SEO & Deskripsi')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                TextInput::make('alt_text')
                                    ->label('Alt Text (SEO)')
                                    ->placeholder('Deskripsi gambar untuk pembaca layar & SEO'),
                                TextInput::make('caption')
                                    ->label('Caption / Keterangan'),
                            ]),
                        \Filament\Schemas\Components\Tabs\Tab::make('System Metadata')
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
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Preview Media')
                    ->schema([
                        ImageEntry::make('file_path')
                            ->label('')
                            ->disk('public')
                            ->columnSpanFull()
                            ->height(300),
                    ])
                    ->columnSpanFull(),
                Section::make('Informasi File')
                    ->schema([
                        TextEntry::make('file_name')
                            ->label('Nama File'),
                        TextEntry::make('collection')
                            ->label('Koleksi')
                            ->badge(),
                        TextEntry::make('mime_type')
                            ->label('Tipe MIME'),
                        TextEntry::make('size')
                            ->label('Ukuran')
                            ->formatStateUsing(fn ($state) => $state ? number_format($state / 1024, 2) . ' KB' : '-'),
                    ])
                    ->columns(2),
                Section::make('SEO & Deskripsi')
                    ->schema([
                        TextEntry::make('alt_text')
                            ->label('Alt Text'),
                        TextEntry::make('caption')
                            ->label('Caption'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('file_path')
                    ->label('Preview')
                    ->disk('public')
                    ->square()
                    ->size(50)
                    ->defaultImageUrl(fn ($record) => asset('images/placeholder-file.png')),
                TextColumn::make('file_name')
                    ->label('Nama File')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('collection')
                    ->label('Koleksi')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('mime_type')
                    ->label('Tipe')
                    ->sortable(),
                TextColumn::make('size')
                    ->label('Ukuran')
                    ->formatStateUsing(fn ($state) => number_format($state / 1024, 2) . ' KB')
                    ->sortable(),
                TextColumn::make('alt_text')
                    ->label('Alt Text')
                    ->limit(20)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Diunggah')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('collection')
                    ->options([
                        'default' => 'Default',
                        'pages' => 'Halaman (Pages)',
                        'posts' => 'Artikel (Posts)',
                        'banners' => 'Banners / Slider',
                        'logos' => 'Logos',
                    ]),
            ])
            ->actions([
                Action::make('copy_url')
                    ->label('Salin URL')
                    ->icon('heroicon-o-clipboard')
                    ->color('success')
                    ->action(function ($record) {
                        // action kosong — copy dilakukan di browser via Alpine
                    })
                    ->extraAttributes(fn ($record) => [
                        'x-data' => "{ url: '" . Storage::disk('public')->url($record->file_path) . "' }",
                        '@click.stop' => 'navigator.clipboard.writeText(url)',
                    ]),
                ViewAction::make()
                    ->modalHeading(fn ($record) => 'Detail: ' . $record->file_name),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMedia::route('/'),
        ];
    }
}
