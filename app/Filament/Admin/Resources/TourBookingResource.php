<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TourBookingResource\Pages;
use App\Models\TourBooking;
use App\Models\Tour;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Resources\Form;   // ✅ Resources\Form
use Filament\Resources\Table;  // ✅ Resources\Table
use Filament\Tables;

// Form components
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

// Table columns
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class TourBookingResource extends Resource
{
    protected static ?string $model = TourBooking::class;

    protected static ?string $navigationIcon  = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Tours';
    protected static ?string $navigationLabel = 'Tour Bookings';
    protected static ?int    $navigationSort  = 30;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('user_id')
                ->label('User')
                ->options(fn () => User::query()->orderBy('email')->pluck('email', 'id'))
                ->searchable()
                ->required(),

            Select::make('tour_id')
                ->label('Tour')
                ->options(fn () => Tour::query()->orderBy('title')->pluck('title', 'id'))
                ->searchable()
                ->required(),

            TextInput::make('stripe_payment_intent_id')
                ->label('Stripe Payment Intent ID')
                ->required()
                ->maxLength(255),

            Toggle::make('paid')
                ->label('Paid')
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('user.email')
                    ->label('User')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('tour.title')
                    ->label('Tour')
                    ->searchable()
                    ->limit(40)
                    ->toggleable(),

                TextColumn::make('stripe_payment_intent_id')
                    ->label('Payment Intent')
                    ->limit(16)
                    ->tooltip(fn ($record) => $record->stripe_payment_intent_id)
                    ->toggleable(),

                ToggleColumn::make('paid')
                    ->label('Paid'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
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
        return [
            // e.g. PaymentsRelationManager::class (if you add one later)
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTourBookings::route('/'),
            'create' => Pages\CreateTourBooking::route('/create'),
            'edit'   => Pages\EditTourBooking::route('/{record}/edit'),
            // If you also want a view page, add:
            // 'view'   => Pages\ViewTourBooking::route('/{record}'),
        ];
    }
}
