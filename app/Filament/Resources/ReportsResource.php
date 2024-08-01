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
use Illuminate\Support\Str;
use App\Enums\InsuranceProd;
use App\Enums\InsuranceType;
use App\Enums\PaymentStatus;
use App\Rules\NamewithSpace;
use Doctrine\DBAL\Types\Type;
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
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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
use Filament\Forms\Components\Repeater;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Tables\Actions\DeleteAction as ActionsDeleteAction;
use Illuminate\Validation\Rule;

class ReportsResource extends Resource
{
    protected static ?string $model = Report::class;
    protected static ?string $navigationGroup = 'REPORTS';
    protected static ?string $recordTitleAttribute = 'arpr_num';
    protected static ?string $navigationIcon = 'heroicon-o-folder';

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
                                        ->label('Insurance Provider')
                                        ->inlineLabel()
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->required()
                                        ->reactive()
                                        ->live()
                                        ->native(false)
                                        ->options(InsuranceProd::class),
                                    TextInput::make('arpr_num')
                                        ->disabled(fn () => Auth::user()->hasRole('acct-staff'))
                                        ->label('AR/PR No.')
                                        ->inlineLabel()
                                        ->visible(fn (Get $get) => !empty($get('insurance_prod')))
                                        ->required(fn (Get $get) => !empty($get('insurance_prod')))
                                        ->helperText('Enter AR/PR No.')
                                        ->rules([
                                            'required',
                                            function (Get $get) {
            
                                                $rule = Rule::unique('reports', 'arpr_num');
                                                if (strtolower($get('insurance_prod')) !== 'others') {
                                                    $rule->where('insurance_prod', $get('insurance_prod'));
                                                    return $rule;

                                                }
                                                elseif(strtolower($get('insurance_prod')) !== 'others'){
                                                    $rule->where('others_insurance_prod', $get('others_insurance_prod'));
                                                    return $rule;
                                                }
                                                
                                                

                                            },
                                        ]),
                                    TextInput::make('others_insurance_prod')
                                        ->helperText('Enter Other Insurance Provider')
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->label('Insurance Provider (OTHERS)')
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
                                        ->required()
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
                                        ->required()
                                        ->label('Inception/Effectivity')
                                        ->displayFormat('m-d-Y' )
                                        ->native(false),
                                    TextInput::make('assured')
                                        ->rules([new NamewithSpace()])
                                        ->required()
                                        ->readOnly(Auth::user()->hasRole('acct-staff'))
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->label('Assured')
                                        ->inlineLabel(),
                                    TextInput::make('policy_num')
                                        ->readOnly(Auth::user()->hasRole('acct-staff'))
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->required()
                                        ->label('Policy Number')
                                        ->inlineLabel(),
                                ])->columns(2),
                            Section::make()
                                ->schema([
                                    Select::make('insurance_type')
                                        ->label('Select Insurance Type')
                                        ->inlineLabel()
                                        ->required()
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
                                        ->label('Insurance Type (OTHERS)')
                                        ->helperText('Enter Other Insurance Type')
                                        ->inlineLabel()
                                        ->required()
                                        ->visible(fn (Get $get) => strtolower($get('insurance_type')) === 'others')
                                        ->required(fn (Get $get) => strtolower($get('insurance_type')) === 'others'),
                                    Select::make('application')
                                        ->native(false)
                                        ->label('Select Mode of Application')
                                        ->inlineLabel()
                                        ->required()            
                                        ->disabled(Auth::user()->hasRole('acct-staff'))       
                                        ->options(ModeApplication::class)
                                        ->live(),
                                    TextInput::make('others_application')
                                        ->label('Mode of Application (OTHERS)')
                                        ->helperText('Enter Other Insurance Type')
                                        ->required()
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
                                ->required() 
                                ->readOnly(Auth::user()->hasRole('acct-staff'))
                                ->disabled(Auth::user()->hasRole('acct-staff'))
                                ->label('Plate Number / CS Number')
                                ->inlineLabel(),
                            TextInput::make('car_details')
                                ->required()      
                                ->readOnly(Auth::user()->hasRole('acct-staff'))
                                ->disabled(Auth::user()->hasRole('acct-staff'))
                                ->label('Car Details')
                                ->inlineLabel(),
                            Select::make('policy_status')
                                ->required()    
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
                                        ->required()
                                        ->label('Select Terms')
                                        ->inlineLabel()
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->options(Terms::class)
                                        ->reactive()
                                        ->afterStateUpdated(function (callable $set, $get) {
                                            if ($get('terms') === 'straight') {
                                                $set('total_payment', $get('gross_premium'));
                                            }
                                        }),
                                    TextInput::make('gross_premium')
                                        ->label('Enter Gross Premium')
                                        ->inlineLabel()
                                        ->numeric()
                                        ->required()
                                        ->reactive()
                                        ->disabled(Auth::user()->hasRole('acct-staff')),
                                        // ->afterStateUpdated(function (callable $set, $get) {
                                        //     if ($get('terms') === 'straight') {
                                        //         $set('total_payment', $get('gross_premium'));
                                        //     }
                                        // }), Commented Out since Accounting Staff will edit this field
                                    TextInput::make('total_payment')
                                        ->label('Total Payment')
                                        ->inlineLabel()
                                        ->numeric()
                                        ->disabled(Auth::user()->hasRole('cashier'))
                                        ->reactive()
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
                                        ->required()
                                        ->live()
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->label('Select Payment Mode')
                                        ->inlineLabel()
                                        ->options(Payment::class),
                                    FileUpload::make('policy_file')
                                        ->acceptedFileTypes(['image/jpeg','image/png','application/pdf'])
                                        ->helperText('Supported File Types: .jpeg, .png, .pdf')
                                        ->label('Upload Policy File')
                                        ->inlineLabel()
                                        ->hidden(Auth::user()->hasAnyRole(['acct-staff', 'acct-manager']))
                                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file) {
                                            $extension = $file->getClientOriginalExtension();
                                            $uuid = (string) Str::uuid();
                                            return "depo_{$uuid}.{$extension}";
                                        })
                                        ->directory(function () {
                                            $userId = auth()->id(); 
                                            return "uploads/users/{$userId}/policies";
                                        }),
                                    Select::make('payment_status')
                                        ->required()
                                        ->inlineLabel()
                                        ->hidden(Auth::user()->hasRole('cashier'))
                                        ->live()
                                        ->label('Select Payment Status')
                                        ->native(false)
                                        ->options(collect(PaymentStatus::cases())
                                        ->reject(fn ($status) => strtolower($status->value) === 'pending')
                                            ->pluck('name', 'value')
                                            ->toArray()),
                            ])->columns(2),
                            Section::make()
                                ->schema([
                                    
                                    // DatePicker::make('remit_date')
                                    //     ->hidden(Auth::user()->hasAnyRole(['cashier', 'acct-manager']))
                                    //     ->label('Remittance Date')
                                    //     ->native(false)
                                    //     ->displayFormat('m-d-Y'),
                                    // DatePicker::make('remit_date_partial')
                                    //     ->hidden(Auth::user()->hasAnyRole(['cashier', 'acct-manager']))
                                    //     ->label('Final Remittance Date')
                                    //     ->native(false)
                                    //     ->displayFormat('m-d-Y')
                                    //     ->visible(function (callable $get) {
                                    //         $paymentStatus = $get('payment_status');
                                    //         return strtolower($paymentStatus) === 'partial';
                                    //     }),
                                    // FileUpload::make('depo_slip')       
                                    //     ->acceptedFileTypes(['image/jpeg','image/png','application/pdf'])
                                    //     ->helperText('Supported File Types: .jpeg, .png, .pdf')
                                    //     ->label('Upload Deposit Slip')
                                    //     ->required()
                                    //     ->openable()
                                    //     ->downloadable()
                                    //     ->hidden(Auth::user()->hasRole('cashier'))
                                    //     ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file) {
                                    //         $extension = $file->getClientOriginalExtension();
                                    //         $uuid = (string) Str::uuid();
                                    //         return "depo_{$uuid}.{$extension}";
                                    //     })
                                    //     ->afterStateUpdated(function ($state,$record) {
                                    //         if ($state && !$record->id) {
                                    //             $record->save(); 
                                    //         }
                                    //     })
                                    //     ->directory(function () {
                                    //         $userId = auth()->id(); 
                                    //         return "uploads/users/{$userId}/deposit_slips";
                                    //     }),
                                    // FileUpload::make('final_depo_slip')       
                                    //     ->required()
                                    //     ->helperText('Supported File Types: .jpeg, .png, .pdf')
                                    //     ->acceptedFileTypes(['image/jpeg','image/png','application/pdf'])
                                    //     ->label('Upload Another Deposit Slip')
                                    //     ->openable()
                                    //     ->downloadable()
                                    //     ->visible(function (callable $get) {
                                    //         $paymentStatus = $get('payment_status');
                                    //         return strtolower($paymentStatus) === 'partial';
                                    //     })
                                    //     ->hidden(Auth::user()->hasRole('cashier'))
                                    //     ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file) {
                                    //         $extension = $file->getClientOriginalExtension();
                                    //         $uuid = (string) Str::uuid();
                                    //         return "final-depo_{$uuid}.{$extension}";
                                    //     })
                                    //     ->afterStateUpdated(function ($state,$record) {
                                    //         if ($state && !$record->id) {
                                    //             $record->save(); 
                                    //         }
                                    //     })
                                    //     ->directory(function () {
                                    //         $userId = auth()->id(); 
                                    //         return "uploads/users/{$userId}/final-deposit_slips";
                                    //     }),
                                        Repeater::make('remit_deposit')
                                            ->label('')
                                            ->required()
                                            ->addActionLabel('Add Another Deposit Slip')
                                            ->minItems(1)
                                            ->schema([
                                                DatePicker::make('remit_date')
                                                    ->hidden(Auth::user()->hasAnyRole(['cashier', 'acct-manager']))
                                                    ->label('Remittance Date')
                                                    ->native(false)
                                                    ->displayFormat('m-d-Y'),
                                                FileUpload::make('depo_slip')       
                                                    ->acceptedFileTypes(['image/jpeg','image/png','application/pdf'])
                                                    ->helperText('Supported File Types: .jpeg, .png, .pdf')
                                                    ->label('Upload Deposit Slip')
                                                    ->required()
                                                    ->openable()
                                                    ->downloadable()
                                                    ->hidden(Auth::user()->hasRole('cashier'))
                                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file) {
                                                        $extension = $file->getClientOriginalExtension();
                                                        $uuid = (string) Str::uuid();
                                                        return "depo_{$uuid}.{$extension}";
                                                    })
                                                    ->afterStateUpdated(function ($state,$record) {
                                                        if ($state && !$record->id) {
                                                            $record->save(); 
                                                        }
                                                    })
                                                    ->directory(function () {
                                                        $userId = auth()->id(); 
                                                        return "uploads/users/{$userId}/deposit_slips";
                                                    }),
                                            ])->columns(2),
                                ])->columnSpanFull()->hidden(Auth::user()->hasAnyRole(['cashier', 'acct-manager'])),
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
        ->deferLoading()
        ->defaultSort('created_at', 'desc')
            ->columns([
                // TextColumn::make('created_at')
                //     ->searchable()
                //     ->dateTime('d-M-Y')
                //     ->label('Date Created')
                //     ->icon('heroicon-o-calendar-days'),
                TextColumn::make('arpr_date')
                    ->sortable()
                    ->searchable()
                    ->label('AR/PR Date'),
                TextColumn::make('inception_date')
                    ->label('Inception Date')
                    ->searchable()
                    ->date('m-d-Y')
                    ->visibleFrom('md'),
                // TextColumn::make('sale_person')
                //     ->label('Sales Person')
                //     ->icon('heroicon-o-user')
                //     ->visibleFrom('md'),    
                TextColumn::make('cost_center')
                    ->label('Cost Center')
                    ->searchable()
                    ->visibleFrom('md')
                    ->icon('heroicon-o-map-pin'),
                TextColumn::make('arpr_num')
                    ->label('AR/PR No.')
                    ->searchable(),
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
                    ->visibleFrom('md')
                    ->grow(false),
                TextColumn::make('car_details')
                    ->label('Car Details')
                    ->visibleFrom('md'),
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
                    ->visibleFrom('md')
                    ->badge(),
                TextColumn::make('cashier.name')
                    ->label('Submitted By')
                    ,
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
                TrashedFilter::make()
                    ->placeholder('All Records w/o Archived')
                    ->label('Archived')
                    ->trueLabel('All Records w/ Archived')
                    ->falseLabel('Archived Records'),
                Filter::make('arpr_date')
                    ->form([
                        DatePicker::make('from')
                            ->label('From Date')
                            ->placeholder('Select start date')
                            ->native(false)
                            ->displayFormat('d.m.Y')
                            ->format('m-d-Y'),
                        DatePicker::make('until')
                            ->label('To Date')
                            ->placeholder('Select end date')
                            ->native(false)
                            ->displayFormat('d.m.Y')
                            ->format('m-d-Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                function (Builder $query, $date) {
                                    return $query->whereRaw("STR_TO_DATE(arpr_date, '%m-%d-%Y') >= ?", [Carbon::parse($date)->format('Y-m-d')]);
                                }
                            )
                            ->when(
                                $data['until'],
                                function (Builder $query, $date) {
                                    return $query->whereRaw("STR_TO_DATE(arpr_date, '%m-%d-%Y') <= ?", [Carbon::parse($date)->format('Y-m-d')]);
                                }
                            );
                    })
            ])
            ->actions([
                ActionGroup::make([
                    ActionsDeleteAction::make()
                        ->label('Archive')
                        ->icon('heroicon-m-archive-box-arrow-down')
                        ->color('info')
                        ->requiresConfirmation()
                        ->modalHeading('Archive this Report?')
                        ->modalIcon('heroicon-m-archive-box-arrow-down')
                        ->successNotificationTitle('Report Archived Successfully'),
                    RestoreAction::make()
                        ->color('success'),
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
                    Tables\Actions\Action::make('activities')
                        ->label('View Recent Changes')
                        ->hidden(fn (Report $record) => Auth::user()->hasAnyRole(['acct-staff', 'cashier']))
                        ->url(fn ($record) => ReportsResource::getUrl('activities', ['record' => $record])) 
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
            'activities' => Pages\ReportsChanges::route('/{record}/activities'),
        ];
    }
}
