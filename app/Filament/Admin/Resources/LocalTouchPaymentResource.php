<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LocalTouchPaymentResource\Pages;
use App\Models\LocalTouchPayment;
use App\Models\LocalTouchBooking;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;

class LocalTouchPaymentResource extends Resource
{
    protected static ?string $model = LocalTouchPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Local Touch';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('booking_id')
                ->label('Booking')
                ->searchable()
                ->required()
                ->getSearchResultsUsing(function (string $search) {
                    return LocalTouchBooking::with('user')
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
                ->getOptionLabelUsing(fn ($value): ?string => 
                    optional(LocalTouchBooking::with('user')->find($value))->user?->first_name 
                    . ' ' .
                    optional(LocalTouchBooking::with('user')->find($value))->user?->last_name
                ),

            TextInput::make('stripe_payment_intent_id')
                ->required(),

            TextInput::make('stripe_payment_method')
                ->required(),

            TextInput::make('amount')
                ->prefix('€')
                ->numeric()
                ->required(),

            Select::make('status')
                ->options([
                    'succeeded' => 'Succeeded',
                    'pending' => 'Pending',
                    'failed' => 'Failed',
                ])
                ->required(),

            Textarea::make('payment_details')
                ->json()
                ->maxLength(65535),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_full_name')
                    ->label('User')
                    ->getStateUsing(fn ($record) =>
                        $record->booking?->user?->first_name . ' ' . $record->booking?->user?->last_name
                    )
                    ->searchable(),

                TextColumn::make('booking.experience.name')->label('Experience'),

                TextColumn::make('amount')->money('EUR'),

                BadgeColumn::make('status')
                    ->colors([
                        'succeeded' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                    ])
                    ->label('Status'),

                TextColumn::make('stripe_payment_intent_id')
                    ->label('Payment ID')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'succeeded' => 'Succeeded',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
                    ]),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocalTouchPayments::route('/'),
            'create' => Pages\CreateLocalTouchPayment::route('/create'),
            'edit' => Pages\EditLocalTouchPayment::route('/{record}/edit'),
        ];
    }
}
