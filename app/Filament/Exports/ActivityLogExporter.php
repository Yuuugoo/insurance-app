<?php

namespace App\Filament\Exports;

use App\Models\ActivityLog;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Models\Contracts\HasName;
use Filament\Support\Contracts\HasLabel;
use Spatie\Activitylog\Models\Activity;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\CellVerticalAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;

class ActivityLogExporter extends Exporter
{
    protected static ?string $model = Activity::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('Log ID'),
            ExportColumn::make('causer_id')
                ->label('User ID'),
            ExportColumn::make('username')
                ->label('User Name')
                ->formatStateUsing(function ($record) {
                    $username = $record->causer->name ?? 'Unknown User';
                    return "{$username}";
                }),
            ExportColumn::make('event')
                ->label('Action')
                ->formatStateUsing(function ($record) {
                    $action = strtolower($record->event);
                    return "{$action}";
                }),
            ExportColumn::make('properties')
                ->label('Description')
                ->formatStateUsing(function ($record) {
                    $properties = $record->properties;
                
                    if (!isset($properties['attributes']) || !isset($properties['old'])) {
                        return 'Raw Data: ' . json_encode($properties);
                    }

                    $attributes = $properties['attributes'];
                    $old = $properties['old'];

                    $formatted = [];

                    foreach ($attributes as $column => $newValue) {
                        $oldValue = $old[$column] ?? null;

                        // Convert complex objects to strings
                        $newValue = $this->convertToString($newValue);
                        $oldValue = $this->convertToString($oldValue);

                        if ($newValue !== $oldValue) {
                            if ($oldValue === null) {
                                $formatted[] = "Added '{$column}': '{$newValue}'";
                            } else {
                                $formatted[] = "Changed '{$column}' from '{$oldValue}' to '{$newValue}'";
                            }
                        }
                    }

                    // Check for deleted fields
                    foreach ($old as $column => $oldValue) {
                        if (!array_key_exists($column, $attributes)) {
                            $oldValue = $this->convertToString($oldValue);
                            $formatted[] = "Removed '{$column}': '{$oldValue}'";
                        }
                    }

                    if (empty($formatted)) {
                        return 'No changes detected';
                    }
                
                    return implode("\n", $formatted);
                }),
            ExportColumn::make('subject_type')
                ->label('Record')
                ->formatStateUsing(function ($record) {
                    $arprNum = $record->subject->arpr_num ?? 'N/A';
                    return "'{$arprNum}'";
                }),
            ExportColumn::make('created_at')
                ->label('Updated At')
                ->formatStateUsing(function ($record) {
                    $updatedAt = $record->created_at->format('m/d/Y h:i A');
                    return "{$updatedAt}";
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
            ->setFontSize(9)
            ->setFontName('Arial')
            ->setFontColor(Color::rgb(0, 0, 0))
            ->setBackgroundColor(Color::rgb(184, 217, 173))
            ->setCellAlignment(CellAlignment::CENTER)
            ->setCellVerticalAlignment(CellVerticalAlignment::CENTER);
    }

    public function getFileName(Export $export): string
    {
        return "audit-trail-{$export->getKey()}";
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your audit trail export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
