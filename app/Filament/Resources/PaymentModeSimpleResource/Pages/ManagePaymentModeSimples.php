<?php

namespace App\Filament\Resources\PaymentModeSimpleResource\Pages;

use App\Filament\Resources\PaymentModeSimpleResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePaymentModeSimples extends ManageRecords
{
    protected static string $resource = PaymentModeSimpleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->color('aap-blue')
                ->label('Add New Mode of Payment'),
        ];
    }
}
