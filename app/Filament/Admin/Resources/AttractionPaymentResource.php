<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AttractionPaymentResource\Pages;
use App\Models\AttractionPayment;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class AttractionPaymentResource extends Resource
{
    protected static ?string $model = AttractionPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Destinations';
    protected static ?string $modelLabel = 'Attraction Payment';
    protected static ?string $pluralModelLabel = 'Attraction Payments';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('booking_id')
                    ->relationship('booking', 'id')
                    ->label('Booking')
                    ->required()
                    ->searchable(),

                TextInput::make('payment_intent_id')
                    ->required()
                    ->maxLength(255),

                TextInput::make('payment_method')
                    ->required()
                    ->maxLength(255),

                TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->prefix('€'),

                TextInput::make('currency')
                    ->required()
                    ->default('eur')
                    ->maxLength(255),

                Select::make('status')
                    ->options([
                        'succeeded' => 'Succeeded',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
                        'canceled' => 'Canceled',
                    ])
                    ->required(),

                DateTimePicker::make('created_at')
                    ->label('Created At')
                    ->disabled()
                    ->visibleOn('edit'),

                DateTimePicker::make('updated_at')
                    ->label('Updated At')
                    ->disabled()
                    ->visibleOn('edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->label('ID'),
                TextColumn::make('booking.id')->label('Booking ID')->sortable(),
                TextColumn::make('payment_intent_id')->label('Intent ID')->wrap(),
                TextColumn::make('payment_method')->label('Method'),
                TextColumn::make('amount')->label('Amount')->money('eur'),
                TextColumn::make('currency')->label('Currency'),
                BadgeColumn::make('status')->label('Status')->colors([
                    'success' => 'succeeded',
                    'warning' => 'pending',
                    'danger' => 'failed',
                    'gray' => 'canceled',
                ]),
                TextColumn::make('created_at')->label('Created At')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAttractionPayments::route('/'),
            'create' => Pages\CreateAttractionPayment::route('/create'),
            'edit' => Pages\EditAttractionPayment::route('/{record}/edit'),
        ];
    }
}
