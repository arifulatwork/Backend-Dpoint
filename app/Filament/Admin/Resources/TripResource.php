<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TripResource\Pages;
use App\Models\Trip;
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
    protected static ?string $navigationGroup = 'Short Trips & Excursions';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Card::make()->schema([
                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
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

                Forms\Components\Grid::make(3)->schema([
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

                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('duration_days')
                        ->numeric()
                        ->required(),

                    Forms\Components\TextInput::make('max_participants')
                        ->numeric()
                        ->minValue(1)
                        ->nullable(),
                ]),
            ]),

            Forms\Components\Card::make()->schema([
                Repeater::make('highlights')
                    ->label('Daily Highlights')
                    ->schema([
                        Forms\Components\TextInput::make('day')
                            ->label('Day')
                            ->numeric()
                            ->required(),

                        Repeater::make('activities')
                            ->label('Activities')
                            ->schema([
                                Forms\Components\TextInput::make('time')
                                    ->label('Time')
                                    ->required(),

                                Forms\Components\TextInput::make('activity')
                                    ->label('Activity')
                                    ->required(),

                                Forms\Components\Textarea::make('description')
                                    ->label('Description')
                                    ->required(),
                            ])
                            ->default([]),
                    ])
                    ->default([])
                    ->columns(1),

                Repeater::make('learning_outcomes')
                    ->schema([
                        Forms\Components\TextInput::make('item')->required(),
                    ])
                    ->default([]),

                Repeater::make('personal_development')
                    ->schema([
                        Forms\Components\TextInput::make('item')->required(),
                    ])
                    ->default([]),

                Repeater::make('certifications')
                    ->schema([
                        Forms\Components\TextInput::make('item')->required(),
                    ])
                    ->default([]),

                Repeater::make('environmental_impact')
                    ->schema([
                        Forms\Components\TextInput::make('item')->required(),
                    ])
                    ->default([]),

                Repeater::make('community_benefits')
                    ->schema([
                        Forms\Components\TextInput::make('item')->required(),
                    ])
                    ->default([]),
            ])->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->disk('public')
                    ->square()
                    ->size(50)
                    ->defaultImageUrl('https://via.placeholder.com/50'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->money('EUR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration_days')
                    ->label('Duration')
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
            // Add relation managers if needed
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
