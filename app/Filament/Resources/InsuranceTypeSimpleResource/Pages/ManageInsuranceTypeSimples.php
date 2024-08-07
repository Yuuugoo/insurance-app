<?php

namespace App\Filament\Resources\InsuranceTypeSimpleResource\Pages;

use App\Filament\Resources\InsuranceTypeSimpleResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRecords;

class ManageInsuranceTypeSimples extends ManageRecords
{
    protected static string $resource = InsuranceTypeSimpleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->color('aap-blue')
                ->label('Add New Insurance Type'),
            Action::make('back')
                ->label('Back')
                ->color('gray')
                ->url(function() {
                    return route('filament.admin.pages.c-m-s-page');
                })
        ];
    }
}
