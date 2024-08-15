<?php

namespace App\Filament\Resources\CostCenterSimpleResource\Pages;

use App\Filament\Resources\CostCenterSimpleResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRecords;

class ManageCostCenterSimples extends ManageRecords
{
    protected static string $resource = CostCenterSimpleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->color('aap-blue')
                ->label('Add New Cost Center'),
        ];
    }
}
