<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BalkanTripResource\Pages;
use App\Models\BalkanTrip;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class BalkanTripResource extends Resource
{
    protected static ?string $model = BalkanTrip::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'Trips Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('duration')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix('$')
                    ->required(),

                Forms\Components\FileUpload::make('image_url')
                    ->label('Trip Image')
                    ->directory('balkan-trips')
                    ->image()
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\TagsInput::make('destinations')
                    ->required(),

                Forms\Components\Section::make('Group Size')
                    ->schema([
                        Forms\Components\TextInput::make('group_size.min')
                            ->label('Min Participants')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('group_size.max')
                            ->label('Max Participants')
                            ->numeric()
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Repeater::make('itinerary')
                    ->schema([
                        Forms\Components\TextInput::make('day')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('title')
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->required(),

                        Forms\Components\TagsInput::make('meals')
                            ->required(),

                        Forms\Components\TextInput::make('accommodation')
                            ->required(),
                    ])
                    ->columnSpanFull(),

                Forms\Components\TagsInput::make('included')
                    ->required(),

                Forms\Components\TagsInput::make('not_included')
                    ->required(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->disk('public')
                    ->square(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('duration')
                    ->suffix(' days'),

                Tables\Columns\TextColumn::make('price')
                    ->money('USD', true),

                Tables\Columns\TextColumn::make('group_size.min')
                    ->label('Min Participants'),

                Tables\Columns\TextColumn::make('group_size.max')
                    ->label('Max Participants'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBalkanTrips::route('/'),
            'create' => Pages\CreateBalkanTrip::route('/create'),
            'edit' => Pages\EditBalkanTrip::route('/{record}/edit'),
        ];
    }
}
