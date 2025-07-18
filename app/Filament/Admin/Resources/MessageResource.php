<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MessageResource\Pages;
use App\Models\Message;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-alt-2';
    protected static ?string $navigationGroup = 'Social';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('sender_id')
                ->label('Sender')
                ->relationship('sender', 'email')
                ->searchable()
                ->required(),

            Select::make('receiver_id')
                ->label('Receiver')
                ->relationship('receiver', 'email')
                ->searchable()
                ->required(),

            Textarea::make('content')
                ->required()
                ->rows(4)
                ->label('Message Content'),

            Toggle::make('read')
                ->label('Read')
                ->inline(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('sender.email')
                ->label('Sender')
                ->searchable()
                ->sortable(),

            TextColumn::make('receiver.email')
                ->label('Receiver')
                ->searchable()
                ->sortable(),

            TextColumn::make('content')
                ->label('Content')
                ->limit(50)
                ->wrap(),

            IconColumn::make('read')
                ->label('Read')
                ->boolean()
                ->sortable(),

            TextColumn::make('created_at')
                ->label('Sent At')
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
            'index' => Pages\ListMessages::route('/'),
            'create' => Pages\CreateMessage::route('/create'),
            'edit' => Pages\EditMessage::route('/{record}/edit'),
        ];
    }
}
