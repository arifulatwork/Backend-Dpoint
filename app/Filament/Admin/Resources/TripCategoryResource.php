<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TripCategoryResource\Pages;
use App\Filament\Admin\Resources\TripCategoryResource\RelationManagers;
use App\Models\TripCategory;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Str;

class TripCategoryResource extends Resource
{
    protected static ?string $model = TripCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Short Trips & Excursions';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                            
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                            
                        Forms\Components\FileUpload::make('icon')
                            ->directory('trip-category-icons')
                            ->image(),
                            
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('icon')
                    ->square(),
                    
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('slug'),
                    
                Tables\Columns\TextColumn::make('trips_count')
                    ->counts('trips')
                    ->label('Trips Count'),
            ])
            ->filters([
                //
            ])
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
        return [
            RelationManagers\TripsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTripCategories::route('/'),
            'create' => Pages\CreateTripCategory::route('/create'),
            'edit' => Pages\EditTripCategory::route('/{record}/edit'),
        ];
    }
}