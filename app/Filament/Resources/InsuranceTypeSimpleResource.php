<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InsuranceTypeSimpleResource\Pages;
use App\Filament\Resources\InsuranceTypeSimpleResource\RelationManagers;
use App\Models\InsuranceType;
use App\Models\InsuranceTypeSimple;
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

class InsuranceTypeSimpleResource extends Resource
{
    protected static ?string $model = InsuranceType::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Insurance Type Name')
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
            ->contentGrid([
                'md' => 5,
                'xl' => 10,
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                
            ])
            ->bulkActions([
                
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageInsuranceTypeSimples::route('/'),
        ];
    }
}
