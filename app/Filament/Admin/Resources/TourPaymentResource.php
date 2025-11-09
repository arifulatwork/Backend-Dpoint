<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TourPaymentResource\Pages;
use App\Models\TourPayment;
use App\Models\TourBooking;
use Filament\Resources\Resource;
use Filament\Resources\Form;   // ✅ Resources\Form
use Filament\Resources\Table;  // ✅ Resources\Table
use Filament\Tables;

// Form components
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\KeyValue;

// Table columns
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class TourPaymentResource extends Resource
{
    protected static ?string $model = TourPayment::class;

    protected static ?string $navigationIcon  = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Tours';
    protected static ?string $navigationLabel = 'Tour Payments';
    protected static ?int    $navigationSort  = 40;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('booking_id')
                ->label('Booking')
                ->options(function () {
                    // Show latest bookings with their tour titles for convenience
                    return TourBooking::query()
                        ->latest()
                        ->with('tour')
                        ->get()
                        ->mapWithKeys(function ($b) {
                            $label = sprintf('#%d — %s', $b->id, $b->tour->title ?? '—');
                            return [$b->id => $label];
                        });
                })
                ->searchable()
                ->required(),

            Select::make('provider')
                ->options([
                    'stripe' => 'Stripe',
                    // add more providers if needed
                ])
                ->required()
                ->default('stripe'),

            TextInput::make('provider_payment_id')
                ->label('Provider Payment ID')
                ->required()
                ->maxLength(255),

            TextInput::make('amount')
                ->numeric()
                ->minValue(0)
                ->required(),

            TextInput::make('currency')
                ->maxLength(10)
                ->default('EUR')
                ->required(),

            Select::make('status')
                ->options([
                    'succeeded'         => 'Succeeded',
                    'processing'        => 'Processing',
                    'requires_payment'  => 'Requires Payment',
                    'failed'            => 'Failed',
                    'canceled'          => 'Canceled',
                ])
                ->required()
                ->default('processing'),

            Textarea::make('note')
                ->rows(3),

            KeyValue::make('meta')
                ->keyLabel('Key')
                ->valueLabel('Value')
                ->columnSpan('full')
                ->helperText('Optional metadata (stored as JSON).'),
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

                TextColumn::make('booking.id')
                    ->label('Booking')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('booking.tour.title')
                    ->label('Tour')
                    ->limit(40)
                    ->searchable()
                    ->toggleable(),

                BadgeColumn::make('provider')
                    ->label('Provider')
                    ->colors(['primary'])
                    ->sortable(),

                TextColumn::make('provider_payment_id')
                    ->label('Provider ID')
                    ->limit(16)
                    ->tooltip(fn ($record) => $record->provider_payment_id)
                    ->toggleable(),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->formatStateUsing(fn ($state, $record) => $state !== null
                        ? strtoupper($record->currency ?? 'EUR') . ' ' . number_format((float)$state, 2)
                        : '—'
                    )
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'succeeded',
                        'warning' => ['processing', 'requires_payment'],
                        'danger'  => ['failed', 'canceled'],
                    ])
                    ->sortable(),

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
            // e.g. add a relation manager to view the related booking here
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTourPayments::route('/'),
            'create' => Pages\CreateTourPayment::route('/create'),
            'edit'   => Pages\EditTourPayment::route('/{record}/edit'),
            // Add a View page if you like:
            // 'view'   => Pages\ViewTourPayment::route('/{record}'),
        ];
    }
}
