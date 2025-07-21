<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\NotificationResource\Pages;
use App\Models\Notification;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationLabel = 'Notifications';
    protected static ?string $pluralModelLabel = 'Notifications';
    protected static ?string $modelLabel = 'Notification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('User')
                    ->options(User::all()->pluck('first_name', 'id')) // Show first name only
                    ->searchable()
                    ->required(),

                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),

                Textarea::make('message')
                    ->label('Message')
                    ->required()
                    ->rows(4),

                Select::make('type')
                    ->label('Type')
                    ->options([
                        'info' => 'Info',
                        'success' => 'Success',
                        'warning' => 'Warning',
                        'error' => 'Error',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.first_name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('title')
                    ->label('Title')
                    ->limit(50)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->html()
                    ->getStateUsing(function ($record) {
                        $color = match ($record->type) {
                            'info' => '#3B82F6',     // blue-500
                            'success' => '#10B981',  // green-500
                            'warning' => '#F59E0B',  // yellow-500
                            'error' => '#EF4444',    // red-500
                            default => '#6B7280',    // gray-500
                        };

                        return "<span style='color:white;background-color:{$color};padding:2px 8px;border-radius:6px;font-size:0.75rem;'>"
                            . ucfirst($record->type) .
                            "</span>";
                    }),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
            'edit' => Pages\EditNotification::route('/{record}/edit'),
        ];
    }
}
