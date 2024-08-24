<?php

namespace App\Filament\Resources;

use view;
use Carbon\Carbon;
use App\Enums\mode;
use Filament\Forms;
use App\Enums\Terms;
use App\Models\User;
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
use App\Models\PaymentMode;
use App\Rules\PolicyNumber;
use Illuminate\Support\Str;
use App\Enums\InsuranceProd;
use App\Enums\InsuranceType;
use App\Enums\PaymentStatus;
use App\Rules\NamewithSpace;
use Doctrine\DBAL\Types\Type;
use App\Enums\ModeApplication;
use App\Imports\ReportsImport;
use Faker\Provider\ar_EG\Text;
use Filament\Facades\Filament;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use App\Models\InsuranceProvider;
use Filament\Actions\DeleteAction;
use Illuminate\Support\HtmlString;
use Filament\Tables\Filters\Filter;
use function Laravel\Prompts\table;
use Filament\Support\Enums\MaxWidth;
use function Laravel\Prompts\select;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use App\Enums\Payment as EnumsPayment;
use App\Exports\ReportsExport;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Filament\Exports\ReportExporter;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\FiltersLayout;
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
use App\Models\CostCenter as ModelsCostCenter;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use App\Filament\Resources\ReportsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\InsuranceType as ModelsInsuranceType;
use Filament\Tables\Actions\Action;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Tables\Actions\DeleteAction as ActionsDeleteAction;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;



class ReportsResource extends Resource
{
    protected static ?string $model = Report::class;
    protected static ?string $navigationLabel = 'Reports';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected function getPaymentStatusSortOrder($status)
    {
        $order = [
            'pending' => 1,
            'partial' => 2,
            'paid' => 3,
        ];
        return $order[strtolower($status)] ?? 4; // Default to 4 for unknown statuses
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Report Details')
                        ->schema([
                            Section::make()
                                ->schema([
                                    Select::make('report_insurance_prod_id')
                                        ->label('Insurance Provider')
                                        ->inlineLabel()
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->required()
                                        ->reactive()
                                        ->live()
                                        ->native(false)
                                        ->options(InsuranceProvider::all()->pluck('name','insurance_provider_id')),
                                    TextInput::make('arpr_num')
                                        ->disabled(fn () => Auth::user()->hasRole('acct-staff'))
                                        ->label('AR/PR No.')
                                        ->inlineLabel()
                                        ->visible(fn (Get $get) => !empty($get('report_insurance_prod_id')))
                                        ->required(fn (Get $get) => !empty($get('report_insurance_prod_ids')))
                                        ->helperText('Enter AR/PR No.')
                                        ->live()
                                        ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, Forms\Components\TextInput $component) {
                                            $livewire->validateOnly($component->getStatePath());
                                        })
                                        ->rules([
                                            'required',
                                            function (Get $get) {
                                                $rule = Rule::unique('reports', 'arpr_num');
                                                $currentRecordId = $get('reports_id');

                                                $rule->where('report_insurance_prod_id', $get('report_insurance_prod_id'))
                                                ->ignore($currentRecordId, 'reports_id');
    
                                                return $rule;
                                            },
                                        ]),
                                ])->columns(2),
                            Section::make()
                                ->schema([
                                    
                                    Select::make('sales_person_id')
                                        ->options(function () {
                                            $query = User::whereHas('roles', function ($query) {
                                                $query->where('name', 'agent');
                                            });
                                            // Commented it So that sales person dropdown can select all agents.
                                            // if (Auth::user()->branch_id !== null) {
                                            //     $query->whereHas('costCenter', function ($subQuery) {
                                            //         $subQuery->where('cost_center_id', Auth::user()->branch_id);
                                            //     });
                                            // }
                                    
                                            return $query->pluck('name', 'id');
                                        })
                                        ->required()
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->label('Sales Person')
                                        ->inlineLabel(),
                                    Select::make('report_cost_center_id') 
                                        ->label('Cost Center')
                                        ->inlineLabel()
                                        ->native(false)
                                        ->disabled(Auth::user()->hasRole(['cashier', 'acct-staff']))
                                        ->required()
                                        ->options(function () {
                                            $query = ModelsCostCenter::query();
                                            
                                            if (Auth::user()->branch_id !== null) {
                                                $query->where('cost_center_id', Auth::user()->branch_id);
                                            }
                                            
                                            return $query->pluck('name', 'cost_center_id');
                                        })
                                        ->dehydrated(function (Get $get) {
                                            $query = ModelsCostCenter::query();
                                            
                                            if (Auth::user()->branch_id !== null) {
                                                $query->where('cost_center_id', Auth::user()->branch_id);
                                            }
                                            
                                            $options = $query->pluck('name', 'cost_center_id')->toArray();
                                            
                                            return array_key_exists($get('report_cost_center_id'), $options);
                                        })
                                        ->afterStateHydrated(function (Set $set) {
                                            $query = ModelsCostCenter::query();
                                            
                                            if (Auth::user()->branch_id !== null) {
                                                $query->where('cost_center_id', Auth::user()->branch_id);
                                            }
                                            
                                            $costCenter = $query->first();
                                            
                                            if ($costCenter) {
                                                $set('report_cost_center_id', $costCenter->cost_center_id);
                                            }
                                        }),
                                    DatePicker::make('arpr_date')
                                        ->label('AR/PR Date')
                                        ->inlineLabel()
                                        ->disabled(function ($get, $record) {
                                            if ($record && $get('arpr_date')) {
                                                return !Auth::user()->hasRole('acct-staff');
                                            }
                                            return Auth::user()->hasRole('acct-staff');
                                        })
                                        ->displayFormat('m-d-Y')
                                        ->native(false)
                                        ->live()
                                        ->afterStateUpdated(function (callable $set, $state, $get, $record) {
                                            if ($record && $state !== $record->arpr_date) {
                                                $set('show_description', true);
                                            } else {
                                                $set('show_description', false);
                                            }
                                        }),
                                    Textarea::make('arpr_date_remarks')
                                        ->label('AR/PR Date Remarks')
                                        ->hidden(fn (Get $get) => !$get('show_description') || Auth::user()->hasAnyRole(['cashier', 'acct-manager']))
                                        ->required(fn (Get $get) => $get('show_description')),
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
                                        ->live()
                                        ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, Forms\Components\TextInput $component) {
                                            $livewire->validateOnly($component->getStatePath());
                                            })
                                        ->inlineLabel(),
                                    TextInput::make('policy_num')
                                        ->readOnly(Auth::user()->hasRole('acct-staff'))
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->live()
                                        ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, Forms\Components\TextInput $component) {
                                            $livewire->validateOnly($component->getStatePath());
                                            })
                                        ->label('Policy Number')
                                        ->inlineLabel(),
                                ])->columns(2),
                            Section::make()
                                ->schema([
                                    Select::make('report_insurance_type_id')
                                        ->label('Select Insurance Type')
                                        ->inlineLabel()
                                        ->required()
                                        ->native(false)
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->options(ModelsInsuranceType::all()->pluck('name','insurance_type_id'))
                                        ->live(),
                                    Select::make('application')
                                        ->native(false)
                                        ->label('Select Mode of Application')
                                        ->inlineLabel()
                                        ->required()            
                                        ->disabled(Auth::user()->hasRole('acct-staff'))       
                                        ->options(ModeApplication::class)
                                        ->live(),
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
                                        ->live()
                                        ->afterStateUpdated(function (callable $set, $get) {
                                            if ($get('terms') === Terms::STRAIGHT->value) {
                                                $set('total_payment', $get('gross_premium'));
                                            }
                                        }),
                                    TextInput::make('gross_premium')
                                        ->label('Enter Gross Premium')
                                        ->inlineLabel()
                                        ->numeric()
                                        ->required()
                                        ->live(onBlur: true)
                                        ->disabled(Auth::user()->hasRole('acct-staff')),
                                ])->columns(2),
                            Section::make()
                                ->label('Payment Terms')
                                ->hidden(fn (Get $get) => $get('terms') == Terms::STRAIGHT->value)
                                ->afterStateUpdated(function ($state, $record) {
                                    if ($record && $record->exists) {
                                        $paymentFields = ['1stpayment', '2ndpayment', '3rdpayment', '4thpayment', '5thpayment', '6thpayment'];
                                        $terms = $state['terms'];
                                        $firstPaymentDate = $state['1stpayment_date'] ?? null;
                            
                                        $paymentCount = match ($terms) {
                                            Terms::TWO->value => 2,
                                            Terms::THREE->value => 3,
                                            Terms::SIX->value => 6,
                                            default => 1,
                                        };
                            
                                        for ($index = 0; $index < $paymentCount; $index++) {
                                            $paymentOrder = $index + 1;
                                            $dateField = $paymentFields[$index] . '_date';
                                            $paymentDate = $state[$dateField] ?? null;
                            
                                            if ($paymentOrder > 1 && $firstPaymentDate && !$paymentDate) {
                                                $paymentDate = (new Carbon($firstPaymentDate))->addMonths($index)->format('Y-m-d');
                                            }
                            
                                            $record->updateOrCreatePaymentTerm($paymentOrder, $state[$paymentFields[$index]] ?? null, $paymentDate);
                                        }
                                    }
                                })
                                ->schema([

                                    TextInput::make('1stpayment')
                                        ->label('Enter 1st Payment')
                                        ->numeric()
                                        ->visible(fn (Get $get) => $get('terms') !== Terms::STRAIGHT->value)
                                        ->afterStateHydrated(function (TextInput $component, $record) {
                                            if ($record) {
                                                $firstPayment = $record->paymentTerms()->where('payment_order', 1)->first();
                                                if ($firstPayment) {
                                                    $component->state($firstPayment->terms_payment);
                                                }
                                            }
                                        }),

                                    DatePicker::make('1stpayment_date')
                                        ->label('1st Payment Date')
                                        ->displayFormat('m-d-Y')
                                        ->native(false)
                                        ->required()
                                        ->visible(fn (Get $get) => $get('terms') !== Terms::STRAIGHT->value)
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                            if ($state) {
                                                $terms = $get('terms');
                                                $paymentCount = match ($terms) {
                                                    Terms::TWO->value => 2,
                                                    Terms::THREE->value => 3,
                                                    Terms::SIX->value => 6,
                                                    default => 1,
                                                };
                                                
                                                $firstDate = new Carbon($state);
                                                for ($i = 2; $i <= $paymentCount; $i++) {
                                                    $set("{$i}thpayment_date", $firstDate->copy()->addMonths($i - 1)->format('Y-m-d'));
                                                }
                                            }
                                        })
                                        ->afterStateHydrated(function (DatePicker $component, $record) {
                                            if ($record) {
                                                $firstPayment = $record->paymentTerms()->where('payment_order', 1)->first();
                                                if ($firstPayment) {
                                                    $component->state($firstPayment->payment_date);
                                                }
                                            }
                                        }),
                                    
                                    Checkbox::make('1stPaid')
                                        ->label('Is Paid')
                                        ->visible(fn (Get $get) => $get('terms') !== Terms::STRAIGHT->value)
                                        ->extraAttributes(['class' => 'is-paid-checkbox']),
                                       
                                        
                                    
                                    DatePicker::make('2ndpayment_date')
                                        ->label('2nd Payment Date')
                                        ->displayFormat('m-d-Y')
                                        ->native(false)
                                        ->visible(fn (Get $get) => in_array($get('terms'), [Terms::TWO->value, Terms::THREE->value, Terms::SIX->value]))
                                        ->disabled(true)
                                        ->afterStateHydrated(function (DatePicker $component, $record) {
                                            if ($record) {
                                                $secondPayment = $record->paymentTerms()->where('payment_order', 2)->first();
                                                if ($secondPayment) {
                                                    $component->state($secondPayment->payment_date);
                                                }
                                            }
                                        }),
                                    TextInput::make('2ndpayment')
                                        ->label('Enter 2nd Payment')
                                        ->numeric()
                                        ->visible(fn (Get $get) => in_array($get('terms'), [Terms::TWO->value, Terms::THREE->value, Terms::SIX->value]))
                                        ->afterStateHydrated(function (TextInput $component, $record) {
                                            if ($record) {
                                                $secondPayment = $record->paymentTerms()->where('payment_order', 2)->first();
                                                if ($secondPayment) {
                                                    $component->state($secondPayment->terms_payment);
                                                }
                                            }
                                        }),

                                    Checkbox::make('2ndPaid')
                                        ->label('Is Paid')
                                        ->visible(fn (Get $get) => in_array($get('terms'), [Terms::TWO->value, Terms::THREE->value, Terms::SIX->value])),
                                    
                                    DatePicker::make('3rdpayment_date')
                                        ->label('3rd Payment Date')
                                        ->displayFormat('m-d-Y')
                                        ->native(false)
                                        ->visible(fn (Get $get) => in_array($get('terms'), [Terms::THREE->value, Terms::SIX->value]))
                                        ->disabled(true)
                                        ->afterStateHydrated(function (DatePicker $component, $record) {
                                            if ($record) {
                                                $thirdPayment = $record->paymentTerms()->where('payment_order', 3)->first();
                                                if ($thirdPayment) {
                                                    $component->state($thirdPayment->payment_date);
                                                }
                                            }
                                        }),
                                    TextInput::make('3rdpayment')
                                        ->label('Enter 3rd Payment')
                                        ->numeric()
                                        ->visible(fn (Get $get) => in_array($get('terms'), [Terms::THREE->value, Terms::SIX->value]))
                                        ->afterStateHydrated(function (TextInput $component, $record) {
                                            if ($record) {
                                                $thirdPayment = $record->paymentTerms()->where('payment_order', 3)->first();
                                                if ($thirdPayment) {
                                                    $component->state($thirdPayment->terms_payment);
                                                }
                                            }
                                        }),

                                    Checkbox::make('3rdPaid')
                                        ->label('Is Paid')
                                        ->visible(fn (Get $get) => in_array($get('terms'), [Terms::THREE->value, Terms::SIX->value])), 
                                    
                                    DatePicker::make('4thpayment_date')
                                        ->label('4th Payment Date')
                                        ->displayFormat('m-d-Y')
                                        ->native(false)
                                        ->visible(fn (Get $get) => $get('terms') === Terms::SIX->value)
                                        ->disabled(true)
                                        ->afterStateHydrated(function (DatePicker $component, $record) {
                                            if ($record) {
                                                $fourthPayment = $record->paymentTerms()->where('payment_order', 4)->first();
                                                if ($fourthPayment) {
                                                    $component->state($fourthPayment->payment_date);
                                                }
                                            }
                                        }),
                                    TextInput::make('4thpayment')
                                        ->label('Enter 4th Payment')
                                        ->numeric()
                                        ->visible(fn (Get $get) => $get('terms') === Terms::SIX->value)
                                        ->afterStateHydrated(function (TextInput $component, $record) {
                                            if ($record) {
                                                $fourthPayment = $record->paymentTerms()->where('payment_order', 4)->first();
                                                if ($fourthPayment) {
                                                    $component->state($fourthPayment->terms_payment);
                                                }
                                            }
                                        }),

                                    Checkbox::make('4thPaid')
                                        ->label('Is Paid')
                                        ->visible(fn (Get $get) => $get('terms') === Terms::SIX->value),
                                    
                                    DatePicker::make('5thpayment_date')
                                        ->label('5th Payment Date')
                                        ->displayFormat('m-d-Y')
                                        ->native(false)
                                        ->visible(fn (Get $get) => $get('terms') === Terms::SIX->value)
                                        ->disabled(true)
                                        ->afterStateHydrated(function (DatePicker $component, $record) {
                                            if ($record) {
                                                $fifthPayment = $record->paymentTerms()->where('payment_order', 5)->first();
                                                if ($fifthPayment) {
                                                    $component->state($fifthPayment->payment_date);
                                                }
                                            }
                                        }),
                                    TextInput::make('5thpayment')
                                        ->label('Enter 5th Payment')
                                        ->numeric()
                                        ->visible(fn (Get $get) => $get('terms') === Terms::SIX->value)
                                        ->afterStateHydrated(function (TextInput $component, $record) {
                                            if ($record) {
                                                $fifthPayment = $record->paymentTerms()->where('payment_order', 5)->first();
                                                if ($fifthPayment) {
                                                    $component->state($fifthPayment->terms_payment);
                                                }
                                            }
                                        }),

                                    Checkbox::make('5thPaid')
                                        ->label('Is Paid')
                                        ->visible(fn (Get $get) => $get('terms') === Terms::SIX->value),
                                    
                                    DatePicker::make('6thpayment_date')
                                        ->label('6th Payment Date')
                                        ->displayFormat('m-d-Y')
                                        ->native(false)
                                        ->visible(fn (Get $get) => $get('terms') === Terms::SIX->value)
                                        ->disabled(true)
                                        ->afterStateHydrated(function (DatePicker $component, $record) {
                                            if ($record) {
                                                $sixthPayment = $record->paymentTerms()->where('payment_order', 6)->first();
                                                if ($sixthPayment) {
                                                    $component->state($sixthPayment->payment_date);
                                                }
                                            }
                                        }),
                                    TextInput::make('6thpayment')
                                        ->label('Enter 6th Payment')
                                        ->numeric()
                                        ->visible(fn (Get $get) => $get('terms') === Terms::SIX->value)
                                        ->afterStateHydrated(function (TextInput $component, $record) {
                                            if ($record) {
                                                $sixthPayment = $record->paymentTerms()->where('payment_order', 6)->first();
                                                if ($sixthPayment) {
                                                    $component->state($sixthPayment->terms_payment);
                                                }
                                            }
                                        }),

                                    Checkbox::make('6thPaid')
                                        ->label('Is Paid')
                                        ->visible(fn (Get $get) => $get('terms') === Terms::SIX->value),
                                ])->columns(3),
                            Section::make()
                                // Pa-hide ng section na to pre if hindi STRAIGHT ang terms
                                ->description('Straight Payment')
                                ->schema([
                                    TextInput::make('total_payment')
                                        ->label('Total Payment')
                                        ->inlineLabel()
                                        ->numeric()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function ($state, callable $set, $get) {
                                            $grossPremium = floatval($get('gross_premium'));
                                            $totalPayment = floatval($state);
                                            $balance = $grossPremium - $totalPayment;
                                            $set('payment_balance', $balance);
                                        }),
                                    TextInput::make('payment_balance')
                                        ->label('Outstanding Balance')
                                        ->inlineLabel()
                                        ->readOnly()
                                        ->live(debounce: 500)
                                        ->visible(fn (Get $get) => !empty($get('total_payment')) && !empty($get('gross_premium')))
                                        ->formatStateUsing(fn ($state) => number_format($state, 2, '.', ''))
                                        ->dehydrateStateUsing(fn ($state) => str_replace(',', '', $state)),
                                    Select::make('report_payment_mode_id')
                                        ->required()
                                        ->live()
                                        ->disabled(Auth::user()->hasRole('acct-staff'))
                                        ->label('Select Payment Mode')
                                        ->inlineLabel()
                                        ->options(PaymentMode::all()->pluck('name','payment_id')),
                                    FileUpload::make('policy_file')
                                        ->acceptedFileTypes(['image/jpeg','image/png','application/pdf'])
                                        ->helperText('Supported File Types: .jpeg, .png, .pdf')
                                        ->label('Upload Policy File')
                                        ->inlineLabel()
                                        ->hidden(Auth::user()->hasAnyRole(['acct-staff', 'acct-manager']))
                                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file) {
                                            $extension = $file->getClientOriginalExtension();
                                            $uuid = (string) Str::uuid();
                                            return "policy_{$uuid}.{$extension}";
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
                                        ->label('Select Payment Status to Provider')
                                        ->native(false)
                                        ->options(collect(PaymentStatus::cases())
                                        ->reject(fn ($status) => strtolower($status->value) === 'pending')
                                            ->pluck('name', 'value')
                                            ->toArray()),
                                    Select::make('payment_status_aap')
                                        ->required()
                                        ->inlineLabel()
                                        ->hidden(Auth::user()->hasRole('cashier'))
                                        ->live()
                                        ->label('Select Payment Status to AAP')
                                        ->native(false)
                                        ->options(collect(PaymentStatus::cases())
                                        ->reject(fn ($status) => strtolower($status->value) === 'pending')
                                            ->pluck('name', 'value')
                                            ->toArray()),
                            ])->columns(2)  ->hidden(fn (Get $get) => $get(('terms') !== Terms::STRAIGHT->value) || $get(('terms') !== null)),
                            Section::make()
                                ->schema([
                                    Repeater::make('remit_deposit')
                                        ->label('')
                                        ->required()
                                        ->addActionLabel('Add Deposit Slip')
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
                ])->columnSpanFull()->skippable(false),                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('arpr_date')
                    ->sortable()
                    ->searchable()
                    ->date('m-d-Y')
                    ->label('AR/PR Date'),
                TextColumn::make('costCenter.name')
                    ->label('Cost Center')
                    ->searchable()
                    ->visibleFrom('md')
                    ->icon('heroicon-o-map-pin'),
                TextColumn::make('arpr_num')
                    ->label('AR/PR No.')
                    ->searchable(),
                TextColumn::make('providers.name')
                    ->label('Insurance Provider')
                    ->label(fn () => new HtmlString('Insurance<br>Provider'))
                    ->visibleFrom('md'),
                TextColumn::make('types.name')
                    ->label('Insurance Type')
                    ->visibleFrom('md'),
                TextColumn::make('payment_status_aap')
                    ->label(fn () => new HtmlString('Payment Status<br>to AAP'))
                    ->badge(),  
                TextColumn::make('payment_status')
                    ->label(fn () => new HtmlString('Payment Status<br>to Provider'))
                    ->badge(),
                TextColumn::make('cashier.name')
                    ->label('Submitted By'),
            ])
            ->openRecordUrlInNewTab()
            ->defaultSort(function ($query) {
                return $query->orderByRaw("
                    CASE 
                        WHEN payment_status_aap = 'pending' OR payment_status = 'pending' THEN 1
                        WHEN payment_status_aap = 'partial' OR payment_status = 'partial' THEN 2
                        WHEN payment_status_aap = 'paid' AND payment_status = 'paid' THEN 3
                        ELSE 4
                    END

                   
                ")->orderBy('arpr_date', 'desc');
            })
            ->defaultPaginationPageOption(5)
            ->filters([
                Filter::make('arpr_date')
                    ->form([
                        DatePicker::make('from')
                            ->label('From')
                            ->placeholder('Select start date')
                            ->native(false)
                            ->displayFormat('d.m.Y')
                            ->format('m-d-Y'),
                        DatePicker::make('until')
                            ->label('To')
                            ->placeholder('Select end date')
                            ->native(false)
                            ->displayFormat('d.m.Y')
                            ->format('m-d-Y'),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->hidden(fn () => Auth::user()->hasAnyRole(['cashier', 'agent']))
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                function (Builder $query, $date) {
                                    return $query->whereRaw("STR_TO_DATE(arpr_date, '%Y-%m-%d-') >= ?", [Carbon::parse($date)->format('Y-m-d')]);
                                }
                            )
                            ->when(
                                $data['until'],
                                function (Builder $query, $date) {
                                    return $query->whereRaw("STR_TO_DATE(arpr_date, '%Y-%m-%d-') <= ?", [Carbon::parse($date)->format('Y-m-d')]);
                                }
                            );
                    }),
                Filter::make('insurance_prod_type')
                    ->form([
                        Select::make('report_insurance_prod_id')
                            ->label('Insurance Provider')
                            ->placeholder('Select Insurance Provider')
                            ->options(InsuranceProvider::all()->pluck('name','insurance_provider_id'))
                            ->native(false)
                            ->reactive()
                            ->searchable()
                            ->multiple(),
                        Select::make('report_insurance_type_id')
                            ->label('Insurance Type')
                            ->placeholder('Select Insurance Type')
                            ->options(ModelsInsuranceType::all()->pluck('name','insurance_type_id'))
                            ->native(false)
                            ->reactive()
                            ->searchable()
                            ->multiple(),
                        Select::make('report_cost_center_id')
                            ->label('Cost Center')
                            ->placeholder('Select Cost Center')
                            ->options(ModelsCostCenter::all()->pluck('name','cost_center_id'))
                            ->hidden(fn () => Auth::user()->hasAnyRole(['cashier', 'agent']))
                            ->native(false)
                            ->reactive()
                            ->searchable()
                            ->multiple(),

                    ])->columnSpanFull()->columns(3)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['report_insurance_prod_id'],
                                fn (Builder $query, array $insuranceTypes) => $query->whereIn('report_insurance_prod_id', $insuranceTypes)
                            )
                            ->when(
                                $data['report_insurance_type_id'],
                                fn (Builder $query, array $insuranceTypes) => $query->whereIn('report_insurance_type_id', $insuranceTypes)
                            )
                            ->when(
                                $data['report_cost_center_id'],
                                fn (Builder $query, array $costCenters) => $query->whereIn('report_cost_center_id', $costCenters)
                            );
                            
                    }),
                TrashedFilter::make()
                    ->placeholder('All Records w/o Archived')
                    ->label('Archived')
                    ->hidden(fn () => Auth::user()->hasAnyRole(['cashier', 'agent']))
                    ->trueLabel('All Records w/ Archived')
                    ->falseLabel('Archived Records'),
            ],layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
            ->filtersFormWidth('FiveExtraLarge')
            ->actions([
                ActionGroup::make([
                    ActionsDeleteAction::make()
                        ->label('Archive')
                        ->icon('heroicon-m-archive-box-arrow-down')
                        ->color('aap-blue')
                        ->requiresConfirmation()
                        ->modalHeading('Archive this Report?')
                        ->modalIcon('heroicon-m-archive-box-arrow-down')
                        ->successNotificationTitle('Report Archived Successfully'),
                    RestoreAction::make()
                        ->color('success'),
                    Tables\Actions\EditAction::make()
                        ->color(fn (Report $record) => $record->canEdit() ? 'gray' : 'aap-blue')
                        ->disabled(fn (Report $record) => $record->canEdit()),
                    Tables\Actions\ViewAction::make()
                        ->color('aap-blue'),
                    // Tables\Actions\Action::make('pdf') 
                    //     ->label('PDF')
                    //     ->color('aap-blue')
                    //     ->icon('heroicon-o-arrow-down-tray')
                    //     ->url(fn (Report $record) => route('pdfview', $record))
                    //     ->openUrlInNewTab(),
                    Tables\Actions\Action::make('activities')
                        ->label('View Recent Changes')
                        ->icon('heroicon-s-bookmark')
                        ->color('aap-blue')
                        ->hidden(fn () => Auth::user()->hasAnyRole(['acct-staff', 'cashier', 'agent']))
                        ->url(fn ($record) => ReportsResource::getUrl('activities', ['record' => $record])) 
                ])->color('aap-blue')
            ])

            ->headerActions([

                 Action::make('ExportReports')
                    ->label('Export Report')
                    ->hidden(fn () => Auth::user()->hasRole(['agent', 'cashier']))
                    ->color('aap-blue')
                    ->action(function () {
                        try {
                            return Excel::download(new ReportsExport, 'reports.xlsx');
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Export Failed')
                                ->body('Error: ' . $e->getMessage())
                                ->send();
                        }
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Reports Exported')
                            ->body('Reports data exported successfully.')
                    ),
                // ExportAction::make()
                //     ->exporter(ReportExporter::class)
                //     ->hidden(fn () => Auth::user()->hasRole(['agent', 'cashier']))
                //     ->label('Export All Records')
                //     ->color('aap-blue')
                //     ->columnMapping(false)
                //     ->chunkSize(250)
                //     ->formats([
                //         ExportFormat::Xlsx,
                //     ]),
                Action::make('importReports')
                    ->label('Import Report')
                    ->hidden(fn () => Auth::user()->hasRole(['agent', 'cashier']))
                    ->color('aap-blue')
                    ->form([
                        FileUpload::make('attachment')
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $file = public_path("storage/" . $data['attachment']);

                        try {
                            Excel::import(new ReportsImport, $file);
                
                            Notification::make()
                                ->success()
                                ->title('Reports Imported')
                                ->body('Reports data imported successfully.')
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Import Failed')
                                ->body('Error: ' . $e->getMessage())
                                ->send();
                        }
                
                       
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    
                ]),
                ExportBulkAction::make()->exporter(ReportExporter::class)
                    ->label('Export Selected Records')
                    ->color('success')
                    ->hidden(fn () => Auth::user()->hasRole(['agent', 'cashier']))
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
            ])
            ->when(Auth::user()->branch_id !== null, function (Builder $query) {
                $query->whereHas('costCenter', function (Builder $subQuery) {
                    $subQuery->where('cost_center_id', Auth::user()->branch_id);
                });
            });
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
