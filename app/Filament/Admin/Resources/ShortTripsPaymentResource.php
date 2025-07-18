<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ShortTripsPaymentResource\Pages;
use App\Models\TripPayment;
use App\Models\TripBooking;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;

class ShortTripsPaymentResource extends Resource
{
    protected static ?string $model = TripPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-cash';
    protected static ?string $navigationGroup = 'Short Trips & Excursions';
    protected static ?string $navigationLabel = 'Short Trips Payments';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('booking_id')
                ->label('Booking')
                ->searchable()
                ->required()
                ->getSearchResultsUsing(function (string $search) {
                    return TripBooking::with('user')
                        ->whereHas('user', function ($query) use ($search) {
                            $query->where('first_name', 'like', "%{$search}%")
                                  ->orWhere('last_name', 'like', "%{$search}%");
                        })
                        ->get()
                        ->mapWithKeys(fn ($booking) => [
                            $booking->id => 'Booking #' . $booking->id . ' - ' .
                                $booking->user?->first_name . ' ' . $booking->user?->last_name
                        ]);
                })
                ->getOptionLabelUsing(fn ($value): ?string => optional(
                    TripBooking::with('user')->find($value)
                )?->user?->first_name . ' ' .
                    optional(TripBooking::with('user')->find($value))->user?->last_name),

            TextInput::make('stripe_payment_intent_id')
                ->label('Stripe Payment Intent ID')
                ->required()
                ->maxLength(255),

            TextInput::make('amount')
                ->numeric()
                ->label('Amount (USD)')
                ->required()
                ->prefix('$'),

            TextInput::make('currency')
                ->required()
                ->maxLength(10),

            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'succeeded' => 'Succeeded',
                    'failed' => 'Failed',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->sortable()->label('ID'),

            TextColumn::make('user_full_name')
                ->label('User')
                ->getStateUsing(fn ($record) =>
                    $record->booking?->user?->first_name . ' ' . $record->booking?->user?->last_name
                )
                ->searchable(),

            TextColumn::make('booking.id')->label('Booking ID'),

            TextColumn::make('stripe_payment_intent_id')->label('Stripe Intent ID'),

            TextColumn::make('amount')->money('usd')->label('Amount'),

            TextColumn::make('currency')->label('Currency'),

            BadgeColumn::make('status')
                ->enum([
                    'pending' => 'Pending',
                    'succeeded' => 'Succeeded',
                    'failed' => 'Failed',
                ])
                ->colors([
                    'warning' => 'pending',
                    'success' => 'succeeded',
                    'danger' => 'failed',
                ])
                ->label('Status'),

            TextColumn::make('created_at')->dateTime()->sortable(),
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
            'index' => Pages\ListShortTripsPayments::route('/'),
            'create' => Pages\CreateShortTripsPayment::route('/create'),
            'edit' => Pages\EditShortTripsPayment::route('/{record}/edit'),
        ];
    }
}
