<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CostCenterSimpleResource\Pages;
use App\Filament\Resources\CostCenterSimpleResource\RelationManagers;
use App\Models\CostCenter;
use App\Models\CostCenterSimple;
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

class CostCenterSimpleResource extends Resource
{
    protected static ?string $model = CostCenter::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationGroup = 'REPORTS';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    TextInput::make('name')
                        ->label('Name')
                        ->rule(['required'])
                        ->unique(ignoreRecord:True)
                        ->inlineLabel()
                        ->live()
                        ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, Forms\Components\TextInput $component) {
                            $livewire->validateOnly($component->getStatePath());
                        }),

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
                    ->label('Edit'),
            ])
            ->bulkActions([
                
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCostCenterSimples::route('/'),
        ];
    }
}
