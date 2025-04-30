<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PremiumTierResource\Pages;
use App\Models\PremiumTier;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;

class PremiumTierResource extends Resource
{
    protected static ?string $model = PremiumTier::class;

    protected static ?string $navigationGroup = 'Premium';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Plan Name')
                ->required(),

            TextInput::make('price')
                ->numeric()
                ->step(0.01)
                ->required(),

            TextInput::make('period')
                ->label('Billing Period (e.g., month, year)')
                ->required(),

            Select::make('type')
                ->options([
                    'individual' => 'Individual',
                    'business' => 'Business',
                ])
                ->required(),

            Toggle::make('is_popular')
                ->label('Is this plan popular?'),

            Repeater::make('features')
                ->relationship('features') // assumes hasMany relation in model
                ->schema([
                    TextInput::make('feature')
                        ->label('Feature Description')
                        ->required(),
                ])
                ->columns(1)
                ->label('Included Features')
                ->defaultItems(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->sortable()->searchable(),

            BadgeColumn::make('type')
                ->enum([
                    'individual' => 'Individual',
                    'business' => 'Business',
                ])
                ->colors([
                    'individual' => 'success',
                    'business' => 'info',
                ]),

            TextColumn::make('price')->money('EUR')->sortable(),

            TextColumn::make('period')->sortable(),

            IconColumn::make('is_popular')->boolean(),
        ])
        ->filters([])
        ->actions([
            EditAction::make(),
        ])
        ->bulkActions([
            DeleteBulkAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            // You can add RelationManagers if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPremiumTiers::route('/'),
            'create' => Pages\CreatePremiumTier::route('/create'),
            'edit' => Pages\EditPremiumTier::route('/{record}/edit'),
        ];
    }
}
