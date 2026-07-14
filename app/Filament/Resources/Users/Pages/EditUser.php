<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public static bool $formActionsAreSticky = true;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->before(function (DeleteAction $action) {
                    // Cegah hapus diri sendiri
                    if ($this->record->id === auth()->id()) {
                        $action->cancel();
                        \Filament\Notifications\Notification::make()
                            ->title('Tidak dapat menghapus akun sendiri.')
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
