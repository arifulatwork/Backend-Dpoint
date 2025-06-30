<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PremiumSubscriptionResource\Pages;
use App\Models\Subscription;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class PremiumSubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationLabel = 'Premium Subscriptions';
    protected static ?string $modelLabel = 'Premium Subscription';
    protected static ?string $pluralModelLabel = 'Premium Subscriptions';
    protected static ?string $navigationGroup = 'Premium';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('premium_tier_id')
                    ->relationship('premiumTier', 'name')
                    ->required(),

                Forms\Components\Select::make('payment_id')
                    ->relationship('payment', 'transaction_id')
                    ->label('Payment Transaction')
                    ->required(),

                Forms\Components\TextInput::make('gateway_subscription_id')
                    ->label('Gateway Subscription ID')
                    ->required()
                    ->disabled(),

                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'cancelled' => 'Cancelled',
                        'paused' => 'Paused',
                        'expired' => 'Expired',
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('started_at')->required(),
                Forms\Components\DatePicker::make('expires_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->searchable(),
                Tables\Columns\TextColumn::make('premiumTier.name')->label('Tier'),
                Tables\Columns\TextColumn::make('payment.transaction_id')->label('Payment')->limit(20),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'paused',
                        'danger' => fn ($state) => in_array($state, ['cancelled', 'expired']),
                    ]),
                Tables\Columns\TextColumn::make('started_at')->date(),
                Tables\Columns\TextColumn::make('expires_at')->date(),
            ])
            ->defaultSort('started_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPremiumSubscriptions::route('/'),
            'create' => Pages\CreatePremiumSubscription::route('/create'),
            'edit' => Pages\EditPremiumSubscription::route('/{record}/edit'),
        ];
    }
}
