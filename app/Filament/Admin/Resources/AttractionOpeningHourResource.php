<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AttractionOpeningHourResource\Pages;
use App\Models\Attraction;
use App\Models\AttractionOpeningHour;

use Filament\Resources\Resource;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Forms;
use Filament\Tables;

class AttractionOpeningHourResource extends Resource
{
    protected static ?string $model = AttractionOpeningHour::class;

    protected static ?string $navigationIcon  = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Opening Hours';
    protected static ?string $navigationGroup = 'Destinations';
    
    protected static ?int    $navigationSort  = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('attraction_id')
                ->label('Attraction')
                ->relationship('attraction', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\Select::make('day_of_week')
                ->label('Day of Week')
                ->options([
                    0 => 'Sunday',
                    1 => 'Monday',
                    2 => 'Tuesday',
                    3 => 'Wednesday',
                    4 => 'Thursday',
                    5 => 'Friday',
                    6 => 'Saturday',
                ])
                ->required(),

            Forms\Components\Toggle::make('is_closed')
                ->label('Closed')
                ->reactive(),

            // ✅ Compatible time fields for older Filament builds
            Forms\Components\TextInput::make('open_time')
                ->label('Opens')
                ->type('time')
                ->step(60)
                ->visible(fn ($get) => ! $get('is_closed'))
                ->required(fn ($get) => ! $get('is_closed')),

            Forms\Components\TextInput::make('close_time')
                ->label('Closes')
                ->type('time')
                ->step(60)
                ->visible(fn ($get) => ! $get('is_closed'))
                ->required(fn ($get) => ! $get('is_closed')),

            Forms\Components\TextInput::make('timezone')
                ->label('Time Zone')
                ->default('Europe/Riga')
                ->maxLength(64),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('attraction.name')
                    ->label('Attraction')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('day_of_week')
                    ->label('Day')
                    ->formatStateUsing(fn ($state) => ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][$state] ?? $state)
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_closed')
                    ->label('Closed')
                    ->boolean(),

                Tables\Columns\TextColumn::make('open_time')->label('Opens'),
                Tables\Columns\TextColumn::make('close_time')->label('Closes'),
                Tables\Columns\TextColumn::make('timezone')->label('TZ'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // ✅ Filament v2-compatible SelectFilter
                Tables\Filters\SelectFilter::make('attraction_id')
                    ->label('Attraction')
                    ->options(fn () => Attraction::orderBy('name')->pluck('name', 'id')->toArray()),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data) {
                        if (!empty($data['is_closed'])) {
                            $data['open_time'] = null;
                            $data['close_time'] = null;
                        }
                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('day_of_week');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAttractionOpeningHours::route('/'),
            'create' => Pages\CreateAttractionOpeningHour::route('/create'),
            'edit'   => Pages\EditAttractionOpeningHour::route('/{record}/edit'),
        ];
    }
}
