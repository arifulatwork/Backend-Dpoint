<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MontenegroTourBookingResource\Pages;
use App\Models\MontenegroTourBooking;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Illuminate\Database\Eloquent\Builder;

class MontenegroTourBookingResource extends Resource
{
    protected static ?string $model = MontenegroTourBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Trips Management';
    protected static ?string $navigationLabel = 'Montenegro Trip Bookings';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('user_id')
                ->relationship('user', 'name')
                ->label('User')
                ->searchable()
                ->required(),

            Select::make('montenegro_tour_id')
                ->relationship('montenegroTour', 'title')
                ->label('Montenegro Tour')
                ->searchable()
                ->required(),

            TextInput::make('stripe_payment_intent_id')
                ->label('Stripe Payment Intent ID')
                ->unique(ignoreRecord: true)
                ->required(),

            Toggle::make('paid')
                ->label('Payment Status'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('User')->searchable()->sortable(),
                TextColumn::make('montenegroTour.title')->label('Montenegro Tour')->searchable()->sortable(),
                TextColumn::make('stripe_payment_intent_id')->label('Payment Intent')->copyable(),
                BooleanColumn::make('paid')->label('Paid')->sortable(),
                TextColumn::make('created_at')->label('Booked At')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('paid')->label('Payment Status'),
            ])
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
            'index' => Pages\ListMontenegroTourBookings::route('/'),
            'create' => Pages\CreateMontenegroTourBooking::route('/create'),
            'edit' => Pages\EditMontenegroTourBooking::route('/{record}/edit'),
        ];
    }
}
