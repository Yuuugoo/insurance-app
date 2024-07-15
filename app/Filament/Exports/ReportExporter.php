<?php

namespace App\Filament\Exports;

use App\Models\Report;
use App\Enums\CostCenter;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\CellVerticalAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;

class ReportExporter extends Exporter
{
    protected static ?string $model = Report::class;

    public static function getColumns(): array
    {
        return [
           ExportColumn::make('reports_id'),
           ExportColumn::make('created_at'),
           ExportColumn::make('sale_person'),
           ExportColumn::make('cost_center')
           ->formatStateUsing(function (CostCenter $state) {
            return $state->name; // or (string) $state
    }),
           ExportColumn::make('arpr_num')
           ->label('ARPR Number'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your report export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

    
}
