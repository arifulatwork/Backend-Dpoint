<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AccommodationAppointmentResource\Pages;
use App\Models\AccommodationAppointment;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class AccommodationAppointmentResource extends Resource
{
    protected static ?string $model = AccommodationAppointment::class;

    protected static ?string $navigationIcon = 'heroicon-s-calendar';
    protected static ?string $navigationGroup = 'Bookings';
    protected static ?string $navigationLabel = 'Accommodation Appointments';
    protected static ?string $modelLabel = 'Accommodation Appointment';
    protected static ?string $pluralModelLabel = 'Accommodation Appointments';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Appointment')
                    ->columns(2)
                    ->schema([
                        Forms\Components\BelongsToSelect::make('point_of_interest_id')
                            ->label('Property')
                            ->relationship('pointOfInterest', 'name')
                            ->searchable()
                            ->required(),

                        Forms\Components\BelongsToSelect::make('user_id')
                            ->label('User')
                            ->relationship('user', 'email')
                            ->searchable()
                            ->nullable(),

                        Forms\Components\DatePicker::make('appointment_date')
                            ->label('Check-in')
                            ->required(),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('Check-out')
                            ->required()
                            ->after('appointment_date'),

                        Forms\Components\TextInput::make('number_of_guests')
                            ->label('Guests')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'pending'   => 'Pending',
                                'confirmed' => 'Confirmed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('pending'),
                    ]),

                Forms\Components\Section::make('Details')
                    ->columns(1)
                    ->schema([
                        Forms\Components\Textarea::make('special_requests')
                            ->rows(3)
                            ->maxLength(5000)
                            ->label('Special Requests'),

                        Forms\Components\KeyValue::make('appointment_details')
                            ->label('Appointment Details')
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->reorderable()
                            ->nullable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pointOfInterest.name')
                    ->label('Property')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('User')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('appointment_date')
                    ->label('Check-in')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Check-out')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('number_of_guests')
                    ->label('Guests')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'primary' => 'pending',
                        'success' => 'confirmed',
                        'danger'  => 'cancelled',
                    ])
                    ->enum([
                        'pending'   => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('From'),
                        Forms\Components\DatePicker::make('until')->label('Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn ($q, $date) => $q->whereDate('appointment_date', '>=', $date))
                            ->when($data['until'] ?? null, fn ($q, $date) => $q->whereDate('end_date', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('appointment_date', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAccommodationAppointments::route('/'),
            'create' => Pages\CreateAccommodationAppointment::route('/create'),
            'edit'   => Pages\EditAccommodationAppointment::route('/{record}/edit'),
        ];
    }
}
