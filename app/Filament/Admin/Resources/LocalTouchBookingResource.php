<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LocalTouchBookingResource\Pages;
use App\Filament\Admin\Resources\LocalTouchBookingResource\RelationManagers;
use App\Models\LocalTouchBooking;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LocalTouchBookingResource extends Resource
{
    protected static ?string $model = LocalTouchBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Local Touch';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),

                Forms\Components\Select::make('experience_id')
                    ->relationship('experience', 'name')
                    ->required(),

                Forms\Components\DatePicker::make('date')
                    ->required(),

                Forms\Components\TimePicker::make('time')
                    ->required(),

                Forms\Components\TextInput::make('participants')
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                Forms\Components\Textarea::make('special_requests')
                    ->maxLength(1000),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('User'),
                Tables\Columns\TextColumn::make('experience.name')->label('Experience'),
                Tables\Columns\TextColumn::make('date'),
                Tables\Columns\TextColumn::make('time'),
                Tables\Columns\TextColumn::make('participants'),
                Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'pending' => 'warning',
                    'confirmed' => 'success',
                    'cancelled' => 'danger',
                ])
                ->label('Status'),
                        ])
            ->filters([
                // You can add filters here if needed
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
            // Add relation managers here if needed
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocalTouchBookings::route('/'),
            'create' => Pages\CreateLocalTouchBooking::route('/create'),
            'edit' => Pages\EditLocalTouchBooking::route('/{record}/edit'),
        ];
    }    
}