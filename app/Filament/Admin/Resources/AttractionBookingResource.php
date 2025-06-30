<?php


namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AttractionBookingResource\Pages;
use App\Models\AttractionBooking;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class AttractionBookingResource extends Resource
{
    protected static ?string $model = AttractionBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Destinations';
    protected static ?string $modelLabel = 'Attraction Booking';
    protected static ?string $pluralModelLabel = 'Attraction Bookings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User')
                    ->searchable()
                    ->required(),

                Select::make('attraction_id')
                    ->relationship('attraction', 'name')
                    ->label('Attraction')
                    ->searchable()
                    ->required(),

                TextInput::make('participants')
                    ->numeric()
                    ->minValue(1)
                    ->default(1)
                    ->required(),

                DatePicker::make('booking_date')
                    ->required(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),

                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('attraction.name')
                    ->label('Attraction')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('participants')->sortable(),

                TextColumn::make('booking_date')->date()->sortable(),

                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'cancelled',
                    ])
                    ->sortable(),

                TextColumn::make('created_at')->label('Created')->since(),
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
            'index' => Pages\ListAttractionBookings::route('/'),
            'create' => Pages\CreateAttractionBooking::route('/create'),
            'edit' => Pages\EditAttractionBooking::route('/{record}/edit'),
        ];
    }
}
