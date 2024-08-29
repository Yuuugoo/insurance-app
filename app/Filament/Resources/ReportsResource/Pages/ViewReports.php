<?php

namespace App\Filament\Resources\ReportsResource\Pages;

use Exception;
use App\Enums\Terms;
use App\Models\Report;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Dompdf\FrameDecorator\Text;
use Filament\Actions\EditAction;
use Filament\Infolists\Infolist;
use App\Livewire\AuditTrailWidget;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Field;
use Illuminate\Support\Facades\Auth;
use App\Filament\Pages\AuditTrailPage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Infolists\Components\Split;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use App\Filament\Resources\ReportsResource;
use Filament\Forms\Components\MorphToSelect;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Actions\Action as InfolistAction;
use App\Filament\Resources\ReportsResource\Widgets\ReportsStatsOverview;

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
                ->disabled(fn (Report $record) => $record->canEdit() && Auth::user()->hasRole(['cashier','agent']))
                ->label('Edit this Report'),
            // Action::make('activities')
            //     ->label('Activity Log')
            //     ->color('gray')
            //     ->action(function () {
            //         return redirect()->route('filament.admin.resources.reports.activities', $this->record);
            //     }),
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
                            TextEntry::make('arpr_date')->label('AR/PR Date:')->inlineLabel()->date('m-d-Y'),
                            TextEntry::make('inception_date')->label('Inception Date:')->date('m-d-Y')->inlineLabel(),
                            TextEntry::make('salesPerson.name')->label('Sales Person:')->icon('heroicon-o-user')->inlineLabel(),   
                            TextEntry::make('costCenter.name')->label('Cost Center:')->icon('heroicon-o-map-pin')->inlineLabel(),
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
                                TextEntry::make('types.name')
                                ->inlineLabel()
                                ->label('Insurance Type'),
                            ]),
                        Section::make()
                            ->schema([
                                TextEntry::make('providers.name')->inlineLabel()->label('Insurance Provider'),
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
                        TextEntry::make('terms')
                            ->label('Terms'),
                        TextEntry::make('payments.name')
                            ->label('Payment Mode'),
                        TextEntry::make('gross_premium')
                            ->label('Gross Premium'),

                            TextEntry::make('totalpayment')
                            ->label('Total Payment')
                            ->visible(fn ($record) => $record->terms === Terms::TWO || $record->terms === Terms::THREE || $record->terms === Terms::SIX)
                            ->state(function (Model $record) {
                                $total = 0;
                                if ($record->terms === Terms::TWO || $record->terms === Terms::THREE || $record->terms === Terms::SIX) {
                                    $total += $record->{'1st_payment'} ?? 0;
                                    $total += $record->{'2nd_payment'} ?? 0;
                                }
                                if ($record->terms === Terms::THREE || $record->terms === Terms::SIX) {
                                    $total += $record->{'3rd_payment'} ?? 0;
                                }
                                if ($record->terms === Terms::SIX) {
                                    $total += $record->{'4th_payment'} ?? 0;
                                    $total += $record->{'5th_payment'} ?? 0;
                                    $total += $record->{'6th_payment'} ?? 0;
                                }
                                return $total;
                            }),
                        
                        TextEntry::make('1st_payment')
                            ->label('1st Payment')
                            ->visible(fn ($record) => $record->terms === Terms::TWO || $record->terms === Terms::THREE || $record->terms === Terms::SIX),
                        TextEntry::make('1st_payment_date')
                            ->label('1st Payment Date')
                            ->visible(fn ($record) => $record->terms === Terms::TWO || $record->terms === Terms::THREE || $record->terms === Terms::SIX),
                        TextEntry::make('2nd_payment')
                            ->label('2nd Payment')
                            ->visible(fn ($record) => $record->terms === Terms::TWO || $record->terms === Terms::THREE || $record->terms === Terms::SIX),
                        TextEntry::make('2nd_payment_date')
                            ->label('2nd Payment Date')
                            ->visible(fn ($record) => $record->terms === Terms::TWO || $record->terms === Terms::THREE || $record->terms === Terms::SIX),            
                        TextEntry::make('3rd_payment')
                            ->label('3rd Payment')
                            ->visible(fn ($record) => $record->terms === Terms::THREE || $record->terms === Terms::SIX),
                        TextEntry::make('3rd_payment_date')
                            ->label('3rd Payment Date')
                            ->visible(fn ($record) => $record->terms === Terms::THREE || $record->terms === Terms::SIX),
                        TextEntry::make('4th_payment')
                            ->label('4th Payment')
                            ->visible(fn ($record) => $record->terms === Terms::SIX),
                        TextEntry::make('4th_payment_date')
                            ->label('4th Payment Date')
                            ->visible(fn ($record) => $record->terms === Terms::SIX),
                        TextEntry::make('5th_payment')
                            ->label('5th Payment')
                            ->visible(fn ($record) => $record->terms === Terms::SIX),
                        TextEntry::make('5th_payment_date')
                            ->label('5th Payment Date')
                            ->visible(fn ($record) => $record->terms === Terms::SIX),
                        TextEntry::make('6th_payment')
                            ->label('6th Payment')
                            ->visible(fn ($record) => $record->terms === Terms::SIX),
                        TextEntry::make('6th_payment_date')
                            ->label('6th Payment Date')
                            ->visible(fn ($record) => $record->terms === Terms::SIX),
                        

                        TextEntry::make('total_payment')
                        ->label('Total Payment') 
                        ->visible(fn ($record) => $record->terms !== Terms::TWO && $record->terms !== Terms::THREE && $record->terms !== Terms::SIX),
                        TextEntry::make('payment_balance')->label('Outstanding Balance'),
                        TextEntry::make('policy_status')->label('Policy Status')->badge(),
                        TextEntry::make('payment_status')->label('Payment Status to Provider')->badge(),
                        TextEntry::make('payment_status_aap')->label('Payment Status to AAP')->badge(),
                        

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
                                ->prose(),
                            TextEntry::make('acct_remarks')
                                ->label('Accounting Remarks')
                                ->markdown()
                                ->prose()

                    ])->grow(false) ->hidden(fn ($record) => $record->acct_remarks === null && $record->cashier_remarks == null),
                    Section::make('HISTORY')
                        ->extraAttributes(['class' => 'activity-log-section'])
                        ->schema([
                            TextEntry::make('audit_trail')
                                ->label('')
                                ->extraAttributes(['class' => 'activity-log-content'])
                                ->html()
                                ->getStateUsing(function () {
                                    return view('filament-activity-log::pages.list-activities', [
                                        'activities' => $this->getActivityLogData(),
                                    ]);
                                })
                    ])->collapsed(),
            ]);
    }

    public function getActivities(): Collection
    {
        return $this->record->activities->sortByDesc('created_at');
    }

    public function getActivityLogData(): array
    {
        return $this->record->activities->sortByDesc('created_at')->toArray();
    }



}
