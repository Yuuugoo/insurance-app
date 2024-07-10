<?php

namespace App\Filament\Resources\ReportsResource\Pages;

use App\Filament\Resources\ReportsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReports extends CreateRecord
{
    protected static string $resource = ReportsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
