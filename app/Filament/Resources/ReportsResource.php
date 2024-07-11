<?php

namespace App\Filament\Resources;

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
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use function Laravel\Prompts\table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use App\Enums\Payment as EnumsPayment;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;

use Filament\Forms\Components\Wizard\Step;
use App\Filament\Resources\ReportsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ReportsResource\RelationManagers;

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
                                ->label('AR/PR Date'),
                            DatePicker::make('inception_date')
                                ->label('Inception/Effectivity'),
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
                                ->label('Gross Premium'),

                            Select::make('payment_mode')
                                ->label('Mode of Payment')
                                ->options(Payment::class),
                            TextInput::make('total_payment')
                                ->label('Total Payment Amount'),

                            FileUpload::make('depo_slip')
                                ->openable()
                                ->downloadable()
                                ->hidden(fn () => ! Auth::user()->hasAnyRole(['acct-staff', 'acct-manager']))

                            
                            

                            
                        ]),
                ])->columnSpanFull()
                    

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->dateTime('d-M-Y')
                    ->label('Date Created'),
                TextColumn::make('sale_person')
                    ->label('Sales Person'),
                TextColumn::make('cost_center')
                    ->label('Cost Center'),
                TextColumn::make('arpr_num')
                    ->label('AR/PR No.'),
                TextColumn::make('arpr_date')
                    ->label('AR/PR Date'),
                TextColumn::make('plate_num')
                    ->label('Vehicle Plate No.'),
                TextColumn::make('policy_status')
                    ->label('Policy Status'),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
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
