<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LocalTouchPaymentResource\Pages;
use App\Filament\Admin\Resources\LocalTouchPaymentResource\RelationManagers;
use App\Models\LocalTouchPayment;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LocalTouchPaymentResource extends Resource
{
    protected static ?string $model = LocalTouchPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Local Touch';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('booking_id')
                    ->relationship('booking', 'id') // You can make it more readable using custom labels
                    ->required(),

                Forms\Components\TextInput::make('stripe_payment_intent_id')
                    ->required(),

                Forms\Components\TextInput::make('stripe_payment_method')
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->prefix('€')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'succeeded' => 'Succeeded',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('payment_details')
                    ->json()
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking.user.name')->label('User'),
                Tables\Columns\TextColumn::make('booking.experience.name')->label('Experience'),
                Tables\Columns\TextColumn::make('amount')->money('EUR'),
                Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'succeeded' => 'success',
                    'pending' => 'warning',
                    'failed' => 'danger',
                ])
                ->label('Status'),
                Tables\Columns\TextColumn::make('stripe_payment_intent_id')->label('Payment ID')->toggleable(),
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
        return [
            //
        ];
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