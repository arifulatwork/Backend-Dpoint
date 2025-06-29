<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LocalTouchResource\Pages;
use App\Filament\Admin\Resources\LocalTouchResource\RelationManagers;
use App\Models\Experience;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class LocalTouchResource extends Resource
{
    protected static ?string $model = Experience::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationLabel = 'Local Touches';

    protected static ?string $modelLabel = 'Local Touch';

    protected static ?string $pluralModelLabel = 'Local Touches';

    protected static ?string $navigationGroup = 'Local Touch';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options([
                                'food' => 'Food',
                                'music' => 'Music',
                                'craft' => 'Craft',
                            ])
                            ->required(),
                            
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->maxLength(65535),
                            
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->prefix('€'),
                            
                        Forms\Components\TextInput::make('rating')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(5)
                            ->step(0.1),
                            
                        Forms\Components\TextInput::make('reviews')
                            ->numeric()
                            ->minValue(0),
                            
                        Forms\Components\TextInput::make('location')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('duration')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('max_participants')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                            
                        Forms\Components\FileUpload::make('image')
                            ->directory('local-touch-images')
                            ->image()
                            ->required(),
                            
                        Forms\Components\TextInput::make('city')
                            ->required()
                            ->maxLength(255),
                            
                        // Highlights Repeater
                        Forms\Components\Repeater::make('highlights')
    ->schema([
        Forms\Components\TextInput::make('value')
            ->label('Highlight')
            ->required(),
    ])
    ->label('Highlights')
    ->default([]),

                            
                        // Why Choose Repeater
                        Forms\Components\Repeater::make('why_choose')
                            ->label('Why Choose This Experience')
                            ->schema([
                                Forms\Components\Select::make('icon')
                                    ->options([
                                        'Award' => 'Award',
                                        'Leaf' => 'Leaf',
                                        'Wine' => 'Wine',
                                        'Heart' => 'Heart',
                                        'Star' => 'Star',
                                        'Shield' => 'Shield',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('title')
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->required(),
                            ])
                            ->default([]),
                            
                        Forms\Components\Fieldset::make('Host Information')
                            ->schema([
                                Forms\Components\TextInput::make('host.name')
                                    ->required(),
                                Forms\Components\TextInput::make('host.rating')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(5)
                                    ->step(0.1),
                                Forms\Components\TextInput::make('host.reviews')
                                    ->numeric()
                                    ->minValue(0),
                                Forms\Components\FileUpload::make('host.image')
                                    ->directory('host-images')
                                    ->image(),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->square(),
                    
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('type')
                    ->enum([
                        'food' => 'Food',
                        'music' => 'Music',
                        'craft' => 'Craft',
                    ]),
                    
                Tables\Columns\TextColumn::make('city')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('price')
                    ->money('EUR')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('rating')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'food' => 'Food',
                        'music' => 'Music',
                        'craft' => 'Craft',
                    ]),
                    
                Tables\Filters\Filter::make('high_rating')
                    ->query(fn ($query) => $query->where('rating', '>=', 4.5))
                    ->label('High Rating (4.5+)'),
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
            // Add relation managers if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocalTouches::route('/'),
            'create' => Pages\CreateLocalTouch::route('/create'),
            'edit' => Pages\EditLocalTouch::route('/{record}/edit'),
        ];
    }
}