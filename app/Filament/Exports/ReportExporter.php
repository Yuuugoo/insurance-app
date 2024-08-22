<?php

namespace App\Filament\Exports;

use App\Models\Report;
use App\Enums\CostCenter;
use App\Enums\InsuranceProd;
use App\Enums\InsuranceType;
use App\Enums\ModeApplication;
use App\Enums\Payment;
use App\Enums\PolicyStatus;
use App\Enums\Terms;
use App\Models\CostCenter as ModelsCostCenter;
use App\Models\InsuranceProvider;
use App\Models\InsuranceType as ModelsInsuranceType;
use App\Models\PaymentMode;
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
            
            ExportColumn::make('salesPerson.name')
                ->label('SALES PERSON')
                ->extraHeaderAttributes(['style' => 'background-color: #FFA500;']), // Orange color

            ExportColumn::make('costCenter.name')
                ->label('COST CENTER'),
            ExportColumn::make('arpr_num')
                ->label('ARPR NUMBER'),
            ExportColumn::make('arpr_date')
                ->label('ARPR DATE'),
            ExportColumn::make('inception_date')
                ->label('INCEPTION DATE'),
            ExportColumn::make('assured')
                ->label('ASSURED'),
            ExportColumn::make('policy_num')
                ->label('POLICY NUMBER'),
            ExportColumn::make('providers.name')
                ->label('INSURANCE PROVIDER'),
            ExportColumn::make('types.name')
                ->label('INSURANCE TYPE'),
            ExportColumn::make('terms')
                ->label('TERMS')
                ->formatStateUsing(function (Terms $state) {
                    return $state->getLabel();
                }),
            ExportColumn::make('gross_premium')
                ->label('GROSS PREMIUM'),
            ExportColumn::make('payments.name')
                ->label('MODE OF PAYMENT'),
            ExportColumn::make('total_payment')
                ->label('TOTAL PAYMENT'),
            ExportColumn::make('plate_num')
                ->label('PLATE NO'),
            ExportColumn::make('car_details')
                ->label('CAR DETAILS'),
            ExportColumn::make('policy_status')
                ->label('POLICY STATUS')
                ->formatStateUsing(function (PolicyStatus $state) {
                    return $state->getLabel();
                }),
            ExportColumn::make('financing_bank')
                ->label('MORTAGAGEE OR FINANCING'),
            ExportColumn::make('application')
                ->label('MODE OF APPLICATION')
                ->formatStateUsing(function (ModeApplication $state) {
                    return $state->getLabel();
                }),

        ];
    }

    public function getXlsxCellStyle(): ?Style
    {
        return (new Style())
            ->setFontSize(9)
            ->setShouldWrapText()
            ->setFontName('Calibri')
            ->setCellAlignment(CellAlignment::CENTER)
            ->setCellVerticalAlignment(CellVerticalAlignment::CENTER);
    }

    public function getXlsxHeaderCellStyle(): ?Style
    {
        return (new Style())
            ->setFontBold()
            ->setShouldWrapText()
            ->setFontSize(9)
            ->setFontName('Arial')
            ->setFontColor(Color::rgb(0, 0, 0))
            ->setBackgroundColor(Color::rgb(184, 217, 173))
            ->setCellAlignment(CellAlignment::CENTER)
            ->setCellVerticalAlignment(CellVerticalAlignment::CENTER);
    }

    public function getFileName(Export $export): string
    {
        return "report-{$export->getKey()}";
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
