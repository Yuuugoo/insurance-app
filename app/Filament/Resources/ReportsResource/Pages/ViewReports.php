<?php

namespace App\Filament\Resources\ReportsResource\Pages;

use App\Models\Report;
use Filament\Forms\Get;
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
                ->color('gray')
                ->action(function () {
                    return redirect('/reports');
                }),
            EditAction::make('edit')
                ->color(fn (Report $record) => $record->canEdit() ? 'gray' : 'aap-blue')
                ->disabled(fn (Report $record) => $record->canEdit())
                ->label('Edit this Report'),
            Action::make('pdf')
                ->label('Export to PDF')
                ->color('aap-blue')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn (Report $record) => route('pdfdownload', $record))
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
                            TextEntry::make('arpr_num')->label('AR/PR No.:')->inlineLabel(),
                            TextEntry::make('arpr_date')->label('AR/PR Date:')->inlineLabel(),
                            TextEntry::make('inception_date')->label('Inception Date:')->date('m-d-Y')->inlineLabel(),
                            TextEntry::make('sale_person')->label('Sales Person:')->icon('heroicon-o-user')->inlineLabel(),   
                            TextEntry::make('cost_center')->label('Cost Center:')->icon('heroicon-o-map-pin')->inlineLabel(),
                            TextEntry::make('policy_num')->label('Policy Number:')->inlineLabel(),
                        ])->columnSpan('full')->columns(2),
                        
                        // Dates
                        Section::make([
                            TextEntry::make('created_at')
                                ->date('m-d-Y')
                                ->label('Date Created')
                                ->icon('heroicon-o-calendar-days'),
                            TextEntry::make('updated_at')
                                ->date('m-d-Y')
                                ->label('Date Updated')
                                ->icon('heroicon-o-calendar-days'),
                            TextEntry::make('cashier.name')->label('Submitted By')->icon('heroicon-o-user'),
                        ])->grow(false),
                        
                ])->from('md')->columnSpan('full'),

                Split::make([
                    Section::make('Insurance Details')
                        ->schema([
                        // Insurance Details
                        Section::make()
                            ->schema([
                                TextEntry::make('insurance_type')
                                ->inlineLabel()
                                ->label('Insurance Type'),
                            ]),
                        Section::make()
                            ->schema([
                                TextEntry::make('insurance_prod')->inlineLabel()->label('Insurance Provider'),
                            ]),
                        Section::make()
                            ->schema([
                                TextEntry::make('application')->inlineLabel()->label('Mode of Application'),
                            ]),
                        Section::make()
                            ->schema([
                                TextEntry::make('assured')->inlineLabel()->label('Assured'),
                            ]),
                        
                    ])->columns(2),
                    Section::make('Payment Details')
                        ->schema([
                        // Payment Details
                        TextEntry::make('payment_mode')->label('Payment Mode'),
                        TextEntry::make('gross_premium')->label('Gross Premium'),
                        TextEntry::make('total_payment')->label('Total Payment'),
                        TextEntry::make('payment_balance')->label('Payment Balance'),
                        TextEntry::make('policy_status')->label('Policy Status')->badge(),
                        TextEntry::make('payment_status')->label('Payment Status')->badge(),
                        

                    ])->columns(2),
                ])->from('md')->columnSpan('full'),
                Split::make([
                    Section::make('Vehicle Details')
                            ->schema([
                            // Vehicle Details
                            TextEntry::make('plate_num')->label('Vehicle Plate No.:')->inlineLabel(),
                            TextEntry::make('car_details')->label('Car Details:')->inlineLabel(),
                            TextEntry::make('financing_bank')->label('Mortgagee/Financing:')->inlineLabel(),
                        ]),
                    Section::make('Uploaded Files')
                        ->schema([
                            // Uploaded Files
                            TextEntry::make('policy_file')
                            ->label('Policy File')
                                ->label('Policy File')
                                ->color('primary')
                                ->formatStateUsing(fn ($state) => basename($state))
                                ->icon('heroicon-o-paper-clip')
                                ->suffixActions([
                                    InfolistAction::make('View')
                                        ->icon('heroicon-m-eye')
                                        ->url(fn (Report $record) => asset('storage/' . $record->policy_file))                        
                                        //->url(fn (Report $record) => route('pdfview', $record))
                                        ->openUrlInNewTab(),
                                    InfolistAction::make('Download')
                                        ->icon('heroicon-m-arrow-down-tray')
                                        ->url(fn (Report $record) => asset('storage/' . $record->policy_file), shouldOpenInNewTab: false)
                                        //->url(fn (Report $record) => route('pdfdownload', $record))
                                        ->extraAttributes(['download' => ''])
                                ]),
                            TextEntry::make('remit_deposit')
                                ->label('')
                                ->html()
                                ->getStateUsing(function ($record) {
                                    $depo_slip = $record->remit_deposit ?? [];
                                    foreach ($depo_slip as &$item) {
                                        $item['depo_slip_filename'] = basename($item['depo_slip']);
                                    }
                                    $remit_depo = $depo_slip;
                                    return view('filament.pages.remit-deposit', ['remit_depo' => $remit_depo])->render();
                                })
                        ]),
                ])->columnSpan('full')  ,
                        // Remarks
                    Section::make('REMARKS')
                        ->schema([
                            TextEntry::make('cashier_remarks')
                                ->label('Cashier Remarks')
                                ->markdown()
                                ->hidden(fn ($record) => $record->cashier_remarks === null)
                                ->prose(),
                            TextEntry::make('acct_remarks')
                                ->label('Accounting Remarks')
                                ->markdown()
                                ->prose()
                                ->hidden(fn ($record) => $record->acct_remarks === null),

                    ])->grow(false),

            ]);
    }

    

}
