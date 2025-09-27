<?php

namespace App\Filament\Admin\Resources;

use App\Models\PointOfInterest;
use App\Filament\Admin\Resources\PointOfInterestResource\Pages;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;

class PointOfInterestResource extends Resource
{
    protected static ?string $model = PointOfInterest::class;

    protected static ?string $navigationIcon = 'heroicon-o-location-marker';
    protected static ?string $navigationLabel = 'Points of Interest';
    protected static ?string $pluralModelLabel = 'Points of Interest';
    protected static ?string $modelLabel = 'Point of Interest';
    protected static ?string $navigationGroup = 'Destinations';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Card::make()->schema([

                Select::make('destination_id')
                    ->label('Destination')
                    ->relationship('destination', 'city')
                    ->required(),

                TextInput::make('name')->required(),

                Select::make('type')
                    ->required()
                    ->label('Type')
                    ->options([
                        // ✅ Kept types
                        'accommodation' => 'Accommodation',
                        'restaurant'    => 'Restaurant',
                        'bar'           => 'Bar',
                        'attraction'    => 'Attraction',
                        'activity'      => 'Activity',
                        'event'         => 'Event',
                        'shuttle'       => 'Airport Shuttle',
                        'legal advice'  => 'Legal Advice',
                        'NIE/TIE'       => 'NIE/TIE',

                        // ❌ Old types (commented out)
                        // 'hotel'      => 'Hotel',
                        // 'park'       => 'Park',
                        // 'museum'     => 'Museum',
                        // 'flight'     => 'Flight',
                    ]),

                TextInput::make('latitude_input')
                    ->label('Latitude')
                    ->numeric()
                    ->afterStateUpdated(fn ($state, callable $set, $get) =>
                        $set('position', json_encode([$state, (float) $get('longitude_input')]))
                    ),

                TextInput::make('longitude_input')
                    ->label('Longitude')
                    ->numeric()
                    ->afterStateUpdated(fn ($state, callable $set, $get) =>
                        $set('position', json_encode([(float) $get('latitude_input'), $state]))
                    ),

                Hidden::make('position')
                    ->required()
                    ->rule('json'),

                Textarea::make('description')->rows(3),

                FileUpload::make('image')
                    ->label('Image')
                    ->image()
                    ->disk('public')
                    ->directory('points-of-interest'),

                TextInput::make('rating')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(5)
                    ->step(0.1)
                    ->nullable(),

                TextInput::make('price')->nullable(),

                TextInput::make('booking_url')
                    ->url()
                    ->label('Booking URL')
                    ->nullable(),

                TagsInput::make('amenities')
                    ->label('Amenities')
                    ->nullable(),

                // ❌ Removed for now because "flight" type is not used
                // TextInput::make('flight_details')
                //     ->label('Flight Details (JSON)')
                //     ->nullable()
                //     ->rule('json'),

                TextInput::make('shuttle_details')
                    ->label('Shuttle Details (JSON)')
                    ->nullable()
                    ->rule('json'),

                TextInput::make('latitude')
                    ->disabled()
                    ->dehydrated(false)
                    ->label('Computed Latitude'),

                TextInput::make('longitude')
                    ->disabled()
                    ->dehydrated(false)
                    ->label('Computed Longitude'),

            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('image')
                ->disk('public')
                ->square()
                ->label('Image'),

            TextColumn::make('name')
                ->sortable()
                ->searchable(),

            TextColumn::make('type')->label('Type')->sortable(),

            TextColumn::make('destination.city')
                ->label('Destination')
                ->sortable()
                ->searchable(),

            TextColumn::make('rating')->sortable(),

            TextColumn::make('latitude')->sortable(),
            TextColumn::make('longitude')->sortable(),

            TextColumn::make('created_at')->dateTime('d M Y'),
        ])
        ->filters([
            SelectFilter::make('type')->options([
                // ✅ Kept types
                'accommodation' => 'Accommodation',
                'restaurant'    => 'Restaurants',
                'bar'           => 'Bars',
                'attraction'    => 'Attractions',
                'activity'      => 'Activities',
                'event'         => 'Events',
                'shuttle'       => 'Airport Shuttle',
                'legal advice'  => 'Legal Advice',
                'NIE/TIE'       => 'NIE/TIE',

                // ❌ Old types (commented out)
                // 'hotel'   => 'Hotel',
                // 'park'    => 'Park',
                // 'museum'  => 'Museum',
                // 'flight'  => 'Flight',
            ]),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPointOfInterests::route('/'),
            'create' => Pages\CreatePointOfInterest::route('/create'),
            'edit'   => Pages\EditPointOfInterest::route('/{record}/edit'),
        ];
    }
}
