<?php

namespace App\Filament\Resources\InsuranceProviderSimpleResource\Pages;

use App\Filament\Resources\InsuranceProviderSimpleResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRecords;

class ManageInsuranceProviderSimples extends ManageRecords
{
    protected static string $resource = InsuranceProviderSimpleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->color('aap-blue')
                ->label('Add New Insurance Provider'),
        ];  
    }
}
