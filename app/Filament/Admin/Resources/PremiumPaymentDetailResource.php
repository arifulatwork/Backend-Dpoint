<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PremiumPaymentDetailResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;

class PremiumPaymentDetailResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Premium Payments';
    protected static ?string $modelLabel = 'Premium Payment';
    protected static ?string $pluralModelLabel = 'Premium Payments';
    protected static ?string $navigationGroup = 'Premium';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->disabled(),

            Forms\Components\TextInput::make('payment_gateway')->disabled(),
            Forms\Components\TextInput::make('transaction_id')->disabled(),
            Forms\Components\TextInput::make('amount')->disabled(),
            Forms\Components\TextInput::make('currency')->disabled(),

            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'succeeded' => 'Succeeded',
                    'failed' => 'Failed',
                    'refunded' => 'Refunded',
                ])
                ->disabled(),

            Forms\Components\KeyValue::make('metadata')->disabled(),

            Forms\Components\DateTimePicker::make('created_at')->disabled(),
            Forms\Components\DateTimePicker::make('updated_at')->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->searchable(),
                Tables\Columns\TextColumn::make('amount')->sortable(),
                Tables\Columns\TextColumn::make('currency'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'pending',
                        'success' => 'succeeded',
                        'danger' => 'failed',
                        'warning' => 'refunded',
                    ]),
                Tables\Columns\TextColumn::make('metadata.tier_id')->label('Tier ID'),
                Tables\Columns\TextColumn::make('transaction_id')->limit(20),
                Tables\Columns\TextColumn::make('created_at')->since(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('purpose', 'premium');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPremiumPaymentDetails::route('/'),
            'view' => Pages\ViewPremiumPaymentDetail::route('/{record}'),
        ];
    }
}
