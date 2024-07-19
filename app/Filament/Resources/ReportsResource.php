<?php

namespace App\Filament\Resources;

use view;
use Carbon\Carbon;
use App\Enums\mode;
use Filament\Forms;
use App\Enums\Terms;
use Filament\Tables;
use App\Rules\ARPRNO;
use App\Enums\payment;
use App\Models\Report;
use App\Models\Reports;
use Carbon\Traits\Date;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Enums\CostCenter;
use App\Enums\ModePayment;
use Filament\Tables\Table;
use App\Enums\PolicyStatus;
use App\Rules\PolicyNumber;
use App\Enums\InsuranceProd;
use App\Enums\InsuranceType;
use App\Enums\PaymentStatus;
use App\Rules\NamewithSpace;
use App\Enums\ModeApplication;
use Faker\Provider\ar_EG\Text;

use Filament\Facades\Filament;
use Barryvdh\DomPDF\Facade\Pdf;

use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\Filter;
use function Laravel\Prompts\table;
use function Laravel\Prompts\select;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use App\Enums\Payment as EnumsPayment;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Exports\ReportExporter;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\FiltersLayout;
use App\Filament\Exports\ProductExporter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;

use Filament\Forms\Components\RichEditor;

use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\Action as ActionsAction;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use App\Filament\Resources\ReportsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ReportsResource\RelationManagers;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Actions\DeleteAction as ActionsDeleteAction;

class ReportsResource extends Resource
{
    protected static ?string $model = Report::class;
    protected static ?string $navigationGroup = 'REPORTS';
    protected static ?string $recordTitleAttribute = 'arpr_num';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';



    public static function form(Form $form): Form
    {
        return $form
          
            ->schema([

                Wizard::make([
                    Wizard\Step::make('Report Details')
                        ->schema([
                            Section::make()
                                ->schema([
                                    Select::make('insurance_prod')
                                        ->label('Select Insurance Provider')
                                        ->inlineLabel()
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->filled()
                                        ->live()
                                        ->native(false)
                                        ->options(InsuranceProd::class),
                                    TextInput::make('arpr_num')
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->filled()
                                        ->unique(ignoreRecord: true)
                                        ->rules([new ARPRNO()])
                                        ->label('AR/PR No.')
                                        ->inlineLabel()
                                        ->visible(fn (Get $get) => !empty($get('insurance_prod')))
                                        ->required(fn (Get $get) => !empty($get('insurance_prod'))),
                                    TextInput::make('others_insurance_prod')
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->label('Enter Insurance Provider (Others)')
                                        ->visible(fn (Get $get) => strtolower($get('insurance_prod')) === 'others')
                                        ->required(fn (Get $get) => strtolower($get('insurance_prod')) === 'others')
                                        ->dehydrateStateUsing(function (Get $get, $state) {
                                            return strtolower($get('insurance_prod')) === 'others' ? $state : null;
                                        })
                                        ->dehydrated(fn (Get $get) => strtolower($get('insurance_prod')) === 'others'),
                                ])->columns(2),
                            Section::make()
                                ->schema([
                                    TextInput::make('sale_person')
                                        ->rules([new NamewithSpace()])
                                        ->readOnly(Auth::user()->hasRole('acct-staff'))
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->label('Sales Person')
                                        ->inlineLabel(),
                                    Select::make('cost_center') 
                                        ->label('Cost Center')
                                        ->inlineLabel()
                                        ->native(false)
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->filled()
                                        ->options(CostCenter::class),
                                    TextInput::make('arpr_date')
                                        ->default(now()->format('m-d-Y'))
                                        ->label('AR/PR Date')
                                        ->inlineLabel()
                                        ->readOnly()
                                        ->formatStateUsing(function ($state) {
                                            if (!$state) return now()->format('m-d-Y');
                                            return $state instanceof Carbon ? $state->format('m-d-Y') : $state;
                                        })
                                        ->dehydrateStateUsing(function ($state) {
                                            if (!$state) return now()->format('m-d-Y');
                                            try {
                                                return Carbon::createFromFormat('m-d-Y', $state)->format('m-d-Y');
                                            } catch (\Exception $e) {
                                                return Date::parse($state)->format('m-d-Y');
                                            }
                                        })
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->extraAttributes(['readonly' => true, 'style' => 'pointer-events: none;'])
                                        ->rules(['date_format:m-d-Y']),
                                    DatePicker::make('inception_date')
                                        ->label('Inception Date')
                                        ->inlineLabel()
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->default(now())
                                        ->label('Inception/Effectivity')
                                        ->displayFormat('m-d-Y' )
                                        ->native(false),
                                    TextInput::make('assured')
                                        ->rules([new NamewithSpace()])
                                        ->filled()
                                        ->readOnly(Auth::user()->hasRole('acct-staff'))
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->label('Assured')
                                        ->inlineLabel(),
                                    TextInput::make('policy_num')
                                        ->readOnly(Auth::user()->hasRole('acct-staff'))
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->filled()
                                        ->label('Policy Number')
                                        ->inlineLabel(),
                                ])->columns(2),
                            Section::make()
                                ->schema([
                                    Select::make('insurance_type')
                                        ->label('Select Insurance Type')
                                        ->inlineLabel()
                                        ->filled()
                                        ->native(false)
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->options(InsuranceType::class)
                                        ->live()
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            if (strtolower($get('insurance_type')) !== 'others') {
                                                $set('others_insurance_type', null);
                                            }
                                        }),
                                    TextInput::make('others_insurance_type')
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->label('Enter Other Insurance Type')
                                        ->inlineLabel()
                                        ->visible(fn (Get $get) => strtolower($get('insurance_type')) === 'others')
                                        ->required(fn (Get $get) => strtolower($get('insurance_type')) === 'others'),
                                    Select::make('application')
                                        ->native(false)
                                        ->label('Select Mode of Application')
                                        ->inlineLabel()
                                        ->filled()              
                                        ->disabled(Auth::user()->hasRole('acct-staff'))       
                                        ->options(ModeApplication::class)
                                        ->live(),
                                    TextInput::make('others_appplication')
                                        ->label('Enter Other Mode of Application')
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->inlineLabel()
                                        ->visible(function (callable $get) {
                                            $paymentStatus = $get('application');
                                            return strtolower($paymentStatus) === 'others';
                                        })
                            ])->columns(2),                        
                        ])
                            ->description('View Report Details')
                            ->columns(['md' => 2, 'xl' => 3]),
                    Wizard\Step::make('Vehicle Details')
                        ->schema([                     
                            TextInput::make('plate_num')
                                ->filled()       
                                ->readOnly(Auth::user()->hasRole('acct-staff'))
                                ->disabled(Auth::user()->hasRole('acct-staff'))
                                ->label('Plate Number / CS Number')
                                ->inlineLabel(),
                            TextInput::make('car_details')
                                ->filled()       
                                ->readOnly(Auth::user()->hasRole('acct-staff'))
                                ->disabled(Auth::user()->hasRole('acct-staff'))
                                ->label('Car Details')
                                ->inlineLabel(),
                            Select::make('policy_status')
                                ->filled()     
                                ->disabled(Auth::user()->hasRole('acct-staff'))
                                ->label('Select Policy Status')
                                ->inlineLabel()
                                ->options(PolicyStatus::class),
                            TextInput::make('financing_bank')
                                ->readOnly(Auth::user()->hasRole('acct-staff'))
                                ->disabled(Auth::user()->hasRole('acct-staff'))
                                ->label('Mortgagee/Financing Bank')
                                ->inlineLabel(),
                        ])
                        ->description('View Vehicle Details')
                        ->columns(['md' => 2, 'xl' => 2]),
                    Wizard\Step::make('Payment Details')
                        ->schema([
                            Section::make()
                                ->schema([
                                    Select::make('terms')
                                        ->filled()
                                        ->label('Select Terms')
                                        ->inlineLabel()
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->options(Terms::class),
                                    TextInput::make('gross_premium')
                                        ->gte('total_payment')
                                        ->label('Enter Gross Premium')
                                        ->inlineLabel()
                                        ->numeric()
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->required()
                                        ->reactive(),
                                    TextInput::make('total_payment')
                                        ->label('Enter Total Payment')
                                        ->inlineLabel()
                                        ->numeric()
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->required()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function ($state, callable $set, $get) {
                                            $balance = intval($get('gross_premium')) - intval($state);
                                            $set('payment_balance', $balance); 
                                        }),
                                    TextInput::make('payment_balance')
                                        ->label('Total Payment Balance')
                                        ->inlineLabel()
                                        ->readOnly()
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->live(debounce: 500)
                                        ->visible(fn (Get $get) => !empty($get('total_payment') && ($get('gross_premium'))))
                                        ->formatStateUsing(fn ($state) => number_format($state, 2, '.', ''))
                                        ->dehydrateStateUsing(fn ($state) => str_replace(',', '', $state)),
                                    Select::make('payment_mode')
                                        ->filled()
                                        ->live()
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->label('Select Payment Mode')
                                        ->inlineLabel()
                                        ->options(Payment::class),
                                    FileUpload::make('policy_file')
                                        ->label('Upload Policy File')
                                        ->inlineLabel()       
                                        ->openable()
                                        ->visible(fn (Get $get) => !empty($get('payment_mode')))
                                        ->downloadable()
                                        ->hidden(fn () => ! Auth::user()->hasRole('cashier')),
                                ])->columns(2),
                            Section::make()
                                ->schema([
                                    Select::make('payment_status')
                                        ->required()
                                        ->hidden(Auth::user()->hasRole('cashier'))
                                        ->live()
                                        ->label('Payment Status')
                                        ->native(false)
                                        ->options(collect(PaymentStatus::cases())
                                        ->reject(fn ($status) => strtolower($status->name) === 'pending')
                                            ->pluck('name', 'value')
                                            ->toArray()),
                                    DatePicker::make('remit_date')
                                        ->hidden(Auth::user()->hasAnyRole(['cashier', 'acct-manager']))
                                        ->label('Remittance Date')
                                        ->native(false)
                                        ->displayFormat('m-d-Y'),
                                    DatePicker::make('remit_date_partial')
                                        ->hidden(Auth::user()->hasAnyRole(['cashier', 'acct-manager']))
                                        ->label('Final Remittance Date')
                                        ->native(false)
                                        ->displayFormat('m-d-Y')
                                        ->visible(function (callable $get) {
                                            $paymentStatus = $get('payment_status');
                                            return strtolower($paymentStatus) === 'partial';
                                        }),
                                    FileUpload::make('depo_slip')       
                                        ->filled()
                                        ->label('Deposit Slip')
                                        ->required()
                                        ->openable()
                                        ->downloadable()
                                        ->hidden(Auth::user()->hasRole('cashier')),
                                    FileUpload::make('final_depo_slip')       
                                        ->filled()
                                        ->label('Final Deposit Slip')
                                        ->required()
                                        ->openable()
                                        ->downloadable()
                                        ->visible(function (callable $get) {
                                            $paymentStatus = $get('payment_status');
                                            return strtolower($paymentStatus) === 'partial';
                                        })
                                        ->hidden(Auth::user()->hasRole('cashier')),
                                ])->columns(2),
                            Section::make()
                                ->schema([
                                    Toggle::make('add_remarks')
                                        ->live()
                                        ->default(0)
                                        ->label('Add Remarks'),
                                    MarkdownEditor::make('cashier_remarks')
                                        ->label('')
                                        ->visible(fn (Get $get) => !empty($get('add_remarks')))
                                        ->hidden(Auth::user()->hasRole('acct-staff'))
                                        ->disableToolbarButtons([
                                            'blockquote',
                                            'strike',
                                            'attachFiles',
                                            'codeBlock',
                                            'link',
                                            'table',
                                            'undo',
                                            'redo',
                                        ]),
                                    MarkdownEditor::make('acct_remarks')
                                        ->label('')
                                        ->visible(fn (Get $get) => !empty($get('add_remarks')))
                                        ->hidden(Auth::user()->hasRole('cashier'))
                                        ->disableToolbarButtons([
                                            'blockquote',
                                            'strike',
                                            'attachFiles',
                                            'codeBlock',
                                            'link',
                                            'table',
                                            'undo',
                                            'redo',
                                        ]),
                                ]),
                                
                        ])->columns(2),
                ])->columnSpanFull()->skippable(),                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->defaultSort('created_at', 'desc')
            ->columns([
                // TextColumn::make('created_at')
                //     ->searchable()
                //     ->dateTime('d-M-Y')
                //     ->label('Date Created')
                //     ->icon('heroicon-o-calendar-days'),
                TextColumn::make('arpr_date')
                    ->sortable()
                    ->label('AR/PR Date'),
                TextColumn::make('inception_date')
                    ->label('Inception Date')
                    ->date('m-d-Y')
                    ->visibleFrom('md'),
                // TextColumn::make('sale_person')
                //     ->label('Sales Person')
                //     ->icon('heroicon-o-user')
                //     ->visibleFrom('md'),    
                TextColumn::make('cost_center')
                    ->label('Cost Center')
                    ->icon('heroicon-o-map-pin'),
                TextColumn::make('arpr_num')
                    ->label('AR/PR No.')
                    ->searchable()
                    ->visibleFrom('md'),
                TextColumn::make('insurance_prod')
                    ->label('Insurance Provider')
                    ->grow(false)
                    ->visibleFrom('md'),
                TextColumn::make('insurance_type')
                    ->label('Insurance Type')
                    ->icon('heroicon-o-calendar-days')
                    ->visibleFrom('md'),
                TextColumn::make('assured')
                    ->label('Assured')
                    ->visibleFrom('md'),
                TextColumn::make('policy_num')
                    ->label('Policy Number')
                    ->visibleFrom('md'),
                TextColumn::make('application')
                    ->label('Mode of Application')
                    ->visibleFrom('md'),
                TextColumn::make('plate_num')
                    ->label('Vehicle Plate No.')
                    ->searchable()
                    ->grow(false),
                TextColumn::make('car_details')
                    ->label('Car Details'),
                // TextColumn::make('payment_mode')
                //     ->label('Payment Mode')
                //     ->sortable(),
                // TextColumn::make('gross_premium')->label('Gross Premium'),
                // TextColumn::make('total_payment')->label('Total Payment'),
                // TextColumn::make('payment_balance')->label('Payment Balance'),
                TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->badge(),
                TextColumn::make('policy_status')
                    ->searchable()
                    ->label('Policy Status')
                    ->sortable()
                    ->badge(),
                TextColumn::make('cashier.name')
                    ->label('Submitted By'),
                // To Fix this error: Column staff.email not found in the table
                // TextColumn::make('staff.email')
                //     ->label('Approved By'),
                // Remarks Has been moved to View Page
                // TextColumn::make('cashier_remarks')
                //     ->label('Cashier Remarks')
                //     ->icon('heroicon-o-calendar-days')
                //     ->visibleFrom('md'),
                // TextColumn::make('acct_remarks')
                //     ->label('Accounting Remarks')
                //     ->icon('heroicon-o-calendar-days')
                //     ->visibleFrom('md'),
                
            ])
            ->openRecordUrlInNewTab()
            ->defaultSort('arpr_date', 'desc')
            ->defaultPaginationPageOption(5)
            ->filters([
                TrashedFilter::make(),
                Filter::make('new_policy')
                    ->label('NEW Policy Status')
                    ->query(fn (Builder $query): Builder => $query->where('policy_status', 'new')),
                Filter::make('renewal_policy')
                    ->label('RENEWAL Policy Status')
                    ->query(fn (Builder $query): Builder => $query->where('policy_status', 'renewal')),
                Filter::make('paid_payment')
                    ->label('PAID Payment')
                    ->query(fn (Builder $query): Builder => $query->where('payment_status', 'paid')),
                Filter::make('pending_payment')
                    ->label('PENDING Payment')
                    ->query(fn (Builder $query): Builder => $query->where('payment_status', 'pending')),
            ])
            
            ->actions([
                ActionGroup::make([
                    ActionsDeleteAction::make(),
                    ForceDeleteAction::make(), 
                    RestoreAction::make(),
                    Tables\Actions\EditAction::make()
                        ->color(fn (Report $record) => $record->canEdit() ? 'gray' : 'warning')
                        ->disabled(fn (Report $record) => $record->canEdit()),
                    Tables\Actions\ViewAction::make()
                        ->color('info'),
                    Tables\Actions\Action::make('pdf') 
                        ->label('PDF')
                        ->color('success')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->url(fn (Report $record) => route('pdfview', $record))
                        ->openUrlInNewTab(),
                ])->color('success')
            ])

            ->headerActions([
                ExportAction::make()
                    ->exporter(ReportExporter::class)
                    ->label('Export All Records')
                    ->color('success')
                    ->columnMapping(false)
                    ->chunkSize(250)
                    ->formats([
                        ExportFormat::Xlsx,
                    ])
                ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    
                ]),
                ExportBulkAction::make()->exporter(ReportExporter::class)
                    ->label('Export Selected Records')
                    ->color('success')
                    ->columnMapping(false)
                    ->chunkSize(250)
                    ->formats([
                        ExportFormat::Xlsx,
                    ])
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidget(): array
    {
        return [
            
        ];
    }
    
    

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReports::route('/create'),
            'edit' => Pages\EditReports::route('/{record}/edit'),
            'view' => Pages\ViewReports::route('/{record}'),
            
        ];
    }
}
