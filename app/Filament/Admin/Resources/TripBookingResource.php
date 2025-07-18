<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TripBookingResource\Pages;
use App\Models\TripBooking;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;

class TripBookingResource extends Resource
{
    protected static ?string $model = TripBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Short Trips & Excursions';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('trip_id')
                ->relationship('trip', 'title')
                ->label('Trip')
                ->required()
                ->searchable(),

            Select::make('user_id')
                ->label('User')
                ->searchable()
                ->required()
                ->getSearchResultsUsing(function (string $search) {
                    return User::where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->limit(20)
                        ->get()
                        ->mapWithKeys(fn ($user) => [
                            $user->id => $user->first_name . ' ' . $user->last_name
                        ]);
                })
                ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->first_name . ' ' . User::find($value)?->last_name),

            TextInput::make('participants')
                ->required()
                ->numeric()
                ->minValue(1),

            DatePicker::make('booking_date')
                ->required(),

            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'cancelled' => 'Cancelled',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),

                TextColumn::make('trip.title')->label('Trip'),

                TextColumn::make('user_full_name')
                    ->label('User')
                    ->getStateUsing(fn ($record) =>
                        $record->user?->first_name . ' ' . $record->user?->last_name
                    ),

                TextColumn::make('participants')->label('Participants'),

                TextColumn::make('booking_date')->date()->label('Booking Date'),

                BadgeColumn::make('status')
                    ->enum([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ])
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'cancelled',
                    ]),

                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListTripBookings::route('/'),
            'create' => Pages\CreateTripBooking::route('/create'),
            'edit' => Pages\EditTripBooking::route('/{record}/edit'),
        ];
    }
}
