<?php

namespace App\Filament\Resources\ReportsResource\Pages;

use App\Models\Report;
use Filament\Actions\Action;
use Dompdf\FrameDecorator\Text;
use Filament\Actions\EditAction;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Filament\Infolists\Components\Split;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use App\Filament\Resources\ReportsResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Actions\Action as InfolistAction;

class ViewReports extends ViewRecord
{
    protected static string $resource = ReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back')
                ->color('warning')
                ->action(function () {
            
                    return redirect('/reports');
                }),
            EditAction::make('edit')
                ->color(fn (Report $record) => $record->canEdit() ? 'gray' : 'warning')
                ->disabled(fn (Report $record) => $record->canEdit())
                ->label('Edit this Report'),
            Action::make('pdf')
                ->label('Export to PDF')
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn (Report $record) => route('pdfview', $record))
                ->openUrlInNewTab(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Split::make([
                    Section::make('General Details')
                        ->schema([
                            // General Application
                            TextEntry::make('arpr_num')->label('AR/PR No.')->color('primary'),
                            TextEntry::make('arpr_date')->label('AR/PR Date')->date('m-d-Y')->color('primary'),
                            TextEntry::make('inception_date')->label('Inception Date')->date('m-d-Y')->color('primary'),
                            TextEntry::make('sale_person')->label('Sales Person')->icon('heroicon-o-user')->color('primary'),   
                            TextEntry::make('cost_center')->label('Cost Center')->icon('heroicon-o-map-pin')->color('primary'),
                            TextEntry::make('policy_num')->label('Policy Number')->color('primary'),
                        ])->columnSpan('full')->columns(2),
                        
                        // Dates
                        Section::make([
                            TextEntry::make('created_at')
                                ->color('primary')
                                ->date('m-d-Y')
                                ->label('Date Created')
                                ->icon('heroicon-o-calendar-days'),
                            TextEntry::make('updated_at')
                                ->date('m-d-Y')
                                ->color('primary')
                                ->label('Date Updated')
                                ->icon('heroicon-o-calendar-days'),
                            TextEntry::make('cashier.name')->label('Submitted By')->icon('heroicon-o-user')->color('primary'),
                        ])->grow(false),
                        
                ])->from('md')->columnSpan('full'),

                Split::make([
                    Section::make('Insurance Details')
                        ->schema([
                        // Insurance Details
                        TextEntry::make('insurance_prod')->label('Insurance Provider')->color('primary'),
                        TextEntry::make('insurance_type')->label('Insurance Type')->color('primary'),
                        TextEntry::make('assured')->label('Assured')->color('primary'),
                        TextEntry::make('application')->label('Mode of Application')->color('primary'),
                    ]),
                    Section::make('Payment Details')
                        ->schema([
                        // Payment Details
                        TextEntry::make('payment_mode')->label('Payment Mode')->color('primary'),
                        TextEntry::make('gross_premium')->label('Gross Premium')->color('primary'),
                        TextEntry::make('total_payment')->label('Total Payment')->color('primary'),
                        TextEntry::make('payment_balance')->label('Payment Balance')->color('primary'),
                        TextEntry::make('policy_status')->label('Policy Status')->badge(),
                        TextEntry::make('payment_status')->label('Payment Status')->badge(),
                        

                    ])->columns(2),
                ])->from('md')->columnSpan('full'),
                Split::make([
                    Section::make('Vehicle Details')
                            ->schema([
                            // Vehicle Details
                            TextEntry::make('plate_num')->label('Vehicle Plate No.')->color('primary'),
                            TextEntry::make('car_details')->label('Car Details')->color('primary'),
                            TextEntry::make('financing_bank')->label('Mortgagee/Financing Bank')->color('primary'),
                        ]),
                    Section::make('Uploaded Files')
                        ->schema([
                            // Uploaded Files
                            TextEntry::make('policy_file')
                                ->label('Policy File')
                                ->color('primary')
                                ->icon('heroicon-o-paper-clip')
                                ->suffixActions([
                                    InfolistAction::make('View')
                                        ->icon('heroicon-m-eye')
                                        ->url(fn (Report $record) => route('pdfview', $record))
                                        ->openUrlInNewTab(),
                                    InfolistAction::make('Download')
                                        ->icon('heroicon-m-arrow-down-tray')
                                        ->url(fn (Report $record) => route('pdfdownload', $record))
                                        ->openUrlInNewTab(false),
                                ]),
                            TextEntry::make('depo_slip')
                                ->label('Deposit Slip')
                                ->color('primary')
                                ->icon('heroicon-o-paper-clip')
                                ->suffixActions([
                                    InfolistAction::make('View')
                                        ->icon('heroicon-m-eye')
                                        ->url(fn (Report $record) => route('pdfview', $record))
                                        ->openUrlInNewTab(),
                                    InfolistAction::make('Download')
                                        ->icon('heroicon-m-arrow-down-tray')
                                        ->url(fn (Report $record) => route('pdfdownload', $record))
                                        ->openUrlInNewTab(false),
                            ]),

                        ]),
                ])->columnSpan('full')  ,
                        // Remarks
                    Section::make('REMARKS')
                        ->schema([
                            TextEntry::make('cashier_remarks')->label('Cashier Remarks')->markdown()->prose(),
                            TextEntry::make('acct_remarks')->label('Accounting Remarks')->markdown()->prose(),

                    ])->grow(false),

            ]);
    }

    

}
