<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RealEstatePropertyResource\Pages;
use App\Models\RealEstateProperty;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class RealEstatePropertyResource extends Resource
{
    protected static ?string $model = RealEstateProperty::class;

    protected static ?string $navigationIcon = 'heroicon-o-home'; // Safe icon
    protected static ?string $navigationGroup = 'Premium';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->required()
                ->maxLength(1000),

            Forms\Components\TextInput::make('location')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('price')
                ->required()
                ->numeric()
                ->prefix('€'),

            Forms\Components\Select::make('type')
                ->required()
                ->options([
                    'apartment' => 'Apartment',
                    'villa' => 'Villa',
                    'commercial' => 'Commercial',
                    'penthouse' => 'Penthouse',
                    'townhouse' => 'Townhouse',
                ])
                ->searchable(),

            Forms\Components\TextInput::make('bedrooms')
                ->numeric()
                ->minValue(0),

            Forms\Components\TextInput::make('bathrooms')
                ->numeric()
                ->minValue(0),

            Forms\Components\TextInput::make('area')
                ->label('Area (e.g., 120 m²)')
                ->maxLength(100),

            Forms\Components\FileUpload::make('image')
                ->directory('properties')
                ->image()
                ->imagePreviewHeight('200')
                ->maxSize(2048)
                ->required(),

            Forms\Components\TextInput::make('premium_discount')
                ->label('Premium Discount (e.g., "10% OFF")')
                ->maxLength(100),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('image')
                ->square()
                ->height(50),

            Tables\Columns\TextColumn::make('title')
                ->searchable()
                ->limit(30),

            Tables\Columns\TextColumn::make('type')
                ->sortable(),

            Tables\Columns\TextColumn::make('location')
                ->searchable()
                ->limit(30),

            Tables\Columns\TextColumn::make('price')
                ->label('Price')
                ->sortable()
                ->formatStateUsing(fn ($state) => '€' . number_format((float) $state, 2)),

            Tables\Columns\TextColumn::make('premium_discount')
                ->label('Premium Discount')
                ->sortable(),
        ])
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRealEstateProperties::route('/'),
            'create' => Pages\CreateRealEstateProperty::route('/create'),
            'edit' => Pages\EditRealEstateProperty::route('/{record}/edit'),
        ];
    }
}
