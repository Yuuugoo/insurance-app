<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentModeSimpleResource\Pages;
use App\Filament\Resources\PaymentModeSimpleResource\RelationManagers;
use App\Models\PaymentMode;
use App\Models\PaymentModeSimple;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentModeSimpleResource extends Resource
{
    protected static ?string $model = PaymentMode::class;
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return 'Mode of Payment'; // Custom singular label
    }

    // Override to change the plural name displayed in the dashboard
    public static function getPluralModelLabel(): string
    {
        return 'Mode of Payments'; // Custom plural label
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                        ->label('Payment Mode Name')
                        ->rule(['required'])
                        ->unique(ignoreRecord:True)
                        ->live()
                        ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, Forms\Components\TextInput $component) {
                            $livewire->validateOnly($component->getStatePath());
                        })
                        ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    TextColumn::make('name')
                        ->label('Name'),
                ])
            ])
            ->defaultPaginationPageOption(25)
            ->contentGrid([
                'md' => 4,
                'xl' => 5,
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->color('aap-blue'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePaymentModeSimples::route('/'),
        ];
    }
}
