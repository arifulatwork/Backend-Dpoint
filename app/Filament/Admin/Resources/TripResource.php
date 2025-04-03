<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TripResource\Pages;
use App\Filament\Admin\Resources\TripResource\RelationManagers;
use App\Models\Trip;
use App\Models\TripCategory;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Str;

class TripResource extends Resource
{
    protected static ?string $model = Trip::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe';

    protected static ?string $navigationGroup = 'Explore';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->options(TripCategory::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                            
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                            
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                            
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->maxLength(65535),
                            
                        Forms\Components\FileUpload::make('image_url')
                            ->label('Featured Image')
                            ->directory('trip-images')
                            ->image()
                            ->required(),
                            
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->required(),
                                    
                                Forms\Components\TextInput::make('original_price')
                                    ->numeric()
                                    ->required(),
                                    
                                Forms\Components\TextInput::make('discount_percentage')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100),
                            ]),
                            
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('duration_days')
                                    ->numeric()
                                    ->required(),
                                    
                                Forms\Components\TextInput::make('max_participants')
                                    ->numeric()
                                    ->minValue(1),
                            ]),
                    ]),
                    
                Forms\Components\Card::make()
                    ->schema([
                        Repeater::make('highlights')
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->required(),
                            ]),
                            
                        Repeater::make('learning_outcomes')
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->required(),
                            ]),
                            
                        Repeater::make('personal_development')
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->required(),
                            ]),
                            
                        Repeater::make('certifications')
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->required(),
                            ]),
                            
                        Repeater::make('environmental_impact')
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->required(),
                            ]),
                            
                        Repeater::make('community_benefits')
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->required(),
                            ]),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->square()
                    ->size(50),
                    
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('price')
                    ->money('EUR')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('duration_days')
                    ->suffix(' days'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
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
            // Add relation managers here if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrips::route('/'),
            'create' => Pages\CreateTrip::route('/create'),
            'edit' => Pages\EditTrip::route('/{record}/edit'),
        ];
    }
}