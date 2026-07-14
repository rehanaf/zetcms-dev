<?php

namespace App\Filament\Resources\Pricings\Pages;

use App\Filament\Resources\Pricings\PricingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPricing extends EditRecord
{
    protected static string $resource = PricingResource::class;

    public static bool $formActionsAreSticky = true;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
