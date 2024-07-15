<?php

namespace App\Filament\Resources;

use view;
use App\Enums\mode;
use Filament\Forms;
use App\Enums\Terms;
use Filament\Tables;
use App\Enums\payment;
use App\Models\Report;
use App\Models\Reports;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Enums\CostCenter;
use App\Enums\ModePayment;
use Filament\Tables\Table;
use App\Enums\PolicyStatus;
use App\Enums\InsuranceProd;
use App\Enums\InsuranceType;
use App\Enums\ModeApplication;
use Faker\Provider\ar_EG\Text;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use function Laravel\Prompts\table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use App\Enums\Payment as EnumsPayment;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use Filament\Actions\Action as ActionsAction;
use App\Filament\Resources\ReportsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ReportsResource\RelationManagers;
use Barryvdh\DomPDF\Facade\Pdf;

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
                            TextInput::make('sale_person')
                                ->label('Sales Person'),
                            Select::make('cost_center')
                                ->label('Cost center')
                                ->options(CostCenter::class),
                            TextInput::make('arpr_num')
                                ->label('AR/PR No.'),
                            DatePicker::make('arpr_date')
                                ->label('AR/PR Date')
                                ->native(false),
                            DatePicker::make('inception_date')
                                ->label('Inception/Effectivity')
                                ->native(false),
                            TextInput::make('assured')
                                ->label('Assured'),
                            TextInput::make('policy_num')
                                ->label('Policy Number'),                        
                            Select::make('insurance_prod')
                                ->label('Insurance Provider')
                                ->options(InsuranceProd::class),
                            Select::make('insurance_type')
                                ->label('Type of Insurance')
                                ->options(InsuranceType::class),
                            Select::make('application')
                                ->label('Mode of Application')
                                ->options(ModeApplication::class),
                        ])
                            ->description('View Report Details')
                            ->columns(['md' => 2, 'xl' => 3]),
                    Wizard\Step::make('Vehicle Details')
                        ->schema([                     
                            TextInput::make('plate_num')
                                ->label('Plate Number'),
                            TextInput::make('car_details')
                                ->label('Car Details'),
                            Select::make('policy_status')
                                ->label('Policy Status')
                                ->options(PolicyStatus::class),
                            TextInput::make('financing_bank')
                                ->label('Mortagagee/Financing'),
                        ])
                        ->description('View Vehicle Details')
                        ->columns(['md' => 2, 'xl' => 2]),
                    Wizard\Step::make('Payment Details')
                        ->schema([
                            Select::make('terms')
                                ->label('Terms')
                                ->options(Terms::class),
                            TextInput::make('gross_premium')
                                ->numeric()
                                ->required(),
                            TextInput::make('total_payment') 
                                ->numeric()
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, callable $set, $get) {
                                    $balance = floatval($get('gross_premium')) - floatval($state);
                                    $set('payment_balance', number_format($balance, 2));
                                }),
                            TextInput::make('payment_balance')
                                ->numeric()
                                ->readOnly() 
                                ->live(debounce: 500),
                            Select::make('payment_mode')
                                ->label('Mode of Payment')
                                ->options(Payment::class),
                            FileUpload::make('depo_slip')
                                ->openable()
                                ->downloadable()
                                ->hidden(fn () => ! Auth::user()->hasAnyRole(['acct-staff', 'acct-manager']))
                        ]),
                ])
                ->columnSpanFull()                    

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->searchable()
                    ->dateTime()
                    ->label('Date Created')
                    ->icon('heroicon-o-calendar-days'),
                TextColumn::make('sale_person')
                    ->label('Sales Person')
                    ->icon('heroicon-o-user')
                    ->visibleFrom('md'),    
                TextColumn::make('cost_center')
                    ->label('Cost Center')
                    ->icon('heroicon-o-map-pin'),
                TextColumn::make('arpr_num')
                    ->label('AR/PR No.')
                    ->searchable()
                    ->visibleFrom('md'),
                TextColumn::make('arpr_date')
                    ->label('AR/PR Date')
                    ->visibleFrom('md'),
                TextColumn::make('insurance_prod')
                    ->label('Insurance Provider')
                    ->visibleFrom('md'),
                TextColumn::make('insurance_type')
                    ->label('Insurance Type')
                    ->icon('heroicon-o-calendar-days')
                    ->visibleFrom('md'),
                TextColumn::make('inception_date')
                    ->label('Inception Date')
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
                TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->badge(),
                TextColumn::make('payment_mode')
                    ->label('Payment Mode')
                    ->sortable(),
                TextColumn::make('gross_premium')->label('Gross Premium'),
                TextColumn::make('total_payment')->label('Total Payment'),
                TextColumn::make('payment_balance')->label('Payment Balance'),
                TextColumn::make('policy_status')
                    ->searchable()
                    ->label('Policy Status')
                    ->sortable()
                    ->badge(),
                TextColumn::make('user_reports.email')
                    ->label('Submitted By'),
                TextColumn::make('cashier_remarks')
                    ->label('Cashier Remarks')
                    ->icon('heroicon-o-calendar-days')
                    ->visibleFrom('md'),
                TextColumn::make('acct_remarks')
                    ->label('Accounting Remarks')
                    ->icon('heroicon-o-calendar-days')
                    ->visibleFrom('md'),
            ])->defaultSort('created_at', 'desc')
            ->filters([
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
                    Tables\Actions\EditAction::make()
                        ->color('success'),
                    Tables\Actions\ViewAction::make()
                        ->color('gray'),
                    Tables\Actions\Action::make('pdf') 
                        ->label('PDF')
                        ->color('success')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->url(fn (Report $record) => route('pdf', $record))
                        ->openUrlInNewTab(), 
                ])->color('success')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    
                ]),
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
