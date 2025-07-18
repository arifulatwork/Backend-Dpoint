<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ConnectionResource\Pages;
use App\Models\Connection;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class ConnectionResource extends Resource
{
    protected static ?string $model = Connection::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Social';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('requester_id')
                ->label('Requester')
                ->relationship('requester', 'email')
                ->searchable()
                ->required(),

            Select::make('receiver_id')
                ->label('Receiver')
                ->relationship('receiver', 'email')
                ->searchable()
                ->required(),

            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'accepted' => 'Accepted',
                    'rejected' => 'Rejected',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('requester.email')
                ->label('Requester')
                ->searchable()
                ->sortable(),

            TextColumn::make('receiver.email')
                ->label('Receiver')
                ->searchable()
                ->sortable(),

            TextColumn::make('status')
                ->sortable(),

            TextColumn::make('created_at')
                ->label('Requested At')
                ->dateTime()
                ->sortable(),
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
            'index' => Pages\ListConnections::route('/'),
            'create' => Pages\CreateConnection::route('/create'),
            'edit' => Pages\EditConnection::route('/{record}/edit'),
        ];
    }
}
