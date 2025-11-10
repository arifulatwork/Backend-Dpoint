<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StudentIntakeSubmissionResource\Pages;
use App\Models\StudentIntakeSubmission;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Form;   // v2
use Filament\Resources\Resource;
use Filament\Resources\Table;  // v2
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class StudentIntakeSubmissionResource extends Resource
{
    protected static ?string $model = StudentIntakeSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';
    protected static ?string $navigationGroup = 'Student Services';
    protected static ?int $navigationSort = 10;
    protected static ?string $modelLabel = 'Student Intake Submission';
    protected static ?string $pluralModelLabel = 'Student Intake Submissions';

    /**
     * Expose the form schema for reuse (e.g., View page).
     */
    public static function formSchema(): array
    {
        return [
            Section::make('Personal')
                ->schema([
                    TextInput::make('full_name')
                        ->label('Full Name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    TextInput::make('contact_phone')
                        ->label('Contact Phone')
                        ->maxLength(50),
                    TextInput::make('nationality')
                        ->required()
                        ->maxLength(100),
                    TextInput::make('current_location')
                        ->label('Current Location')
                        ->required()
                        ->maxLength(150),
                ])->columns(2),

            Section::make('Visa & Residence')
                ->schema([
                    Select::make('visa_status')
                        ->label('Visa Status')
                        ->options([
                            'valid'          => '✅ Valid visa',
                            'expires_soon'   => '⏳ Expires soon',
                            'no_visa'        => '❌ No visa',
                        ])
                        ->required(),
                    DatePicker::make('visa_expiry_date')
                        ->label('Visa Expiry Date')
                        ->helperText('Optional — required only if you have a visa'),
                    Select::make('has_residence_card')
                        ->label('Residence Card')
                        ->options([
                            'yes'        => 'Yes',
                            'no'         => 'No',
                            'in_process' => 'In process',
                        ])
                        ->required(),
                ])->columns(3),

            Section::make('Student Profile & Compliance')
                ->schema([
                    Select::make('student_status')
                        ->label('Student Status')
                        ->options([
                            'current_student'    => 'Current student',
                            'finished_bachelor'  => 'Finished Bachelor',
                            'finished_master'    => 'Finished Master',
                            'working_professional'=> 'Working professional',
                            'other'              => 'Other',
                        ])
                        ->required(),
                    Select::make('has_accommodation')
                        ->label('Accommodation')
                        ->options([
                            'yes' => 'Yes',
                            'no'  => 'No',
                        ])
                        ->nullable(),
                    Select::make('has_health_insurance')
                        ->label('Health Insurance')
                        ->options([
                            'yes' => 'Yes',
                            'no'  => 'No',
                        ])
                        ->nullable(),
                    Select::make('has_empadronamiento')
                        ->label('Empadronamiento')
                        ->options([
                            'yes' => 'Yes',
                            'no'  => 'No',
                        ])
                        ->nullable(),
                ])->columns(2),

            Section::make('Services & Notes')
                ->schema([
                    TagsInput::make('services_needed')
                        ->label('Services Needed')
                        ->placeholder('Add a service…')
                        ->suggestions([
                            'Admission counseling',
                            'Visa support',
                            'Accommodation',
                            'Job search',
                            'Document review',
                            'Insurance setup',
                            'Empadronamiento help',
                        ])
                        ->helperText('Stored as JSON array.'),
                    Textarea::make('additional_info')
                        ->label('Additional Info')
                        ->rows(5)
                        ->maxLength(5000),
                    KeyValue::make('document_paths')
                        ->keyLabel('Label / File')
                        ->valueLabel('Path / URL')
                        ->helperText('Stored document paths (read-only)')
                        ->disableAddingRows()
                        ->disableEditingKeys()
                        ->disableEditingValues()
                        ->disableDeletingRows()
                        ->hiddenOn('create'),
                ])->columns(2),

            Section::make('Payment & Status')
                ->schema([
                    TextInput::make('amount_cents')
                        ->label('Amount (cents)')
                        ->numeric()
                        ->required()
                        ->prefix('€')
                        ->helperText('Amount is stored in cents (e.g., 9900 = €99.00)'),
                    TextInput::make('currency')
                        ->default('eur')
                        ->maxLength(8)
                        ->required(),
                    TextInput::make('stripe_payment_intent_id')
                        ->label('Stripe PI ID')
                        ->maxLength(255),
                    Select::make('status')
                        ->options([
                            'pending_payment' => 'Pending Payment',
                            'paid'            => 'Paid',
                            'refunded'        => 'Refunded',
                            'cancelled'       => 'Cancelled',
                        ])
                        ->required(),
                    DatePicker::make('submitted_at')
                        ->label('Submitted At'),
                ])->columns(2),
        ];
    }

    // v2 signature
    public static function form(Form $form): Form
    {
        return $form->schema(static::formSchema());
    }

    // v2 signature
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('nationality')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('current_location')
                    ->label('Location')
                    ->toggleable()
                    ->sortable(),
                BadgeColumn::make('visa_status')
                    ->label('Visa')
                    ->colors([
                        'success' => 'valid',
                        'warning' => 'expires_soon',
                        'danger'  => 'no_visa',
                    ])
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'valid'        => 'Valid',
                            'expires_soon' => 'Expires soon',
                            'no_visa'      => 'No visa',
                            default        => ucfirst((string)$state),
                        };
                    })
                    ->sortable(),
                TextColumn::make('visa_expiry_date')
                    ->label('Visa Expiry')
                    ->date()
                    ->toggleable(),
                BadgeColumn::make('has_residence_card')
                    ->label('Residence Card')
                    ->colors([
                        'success' => 'yes',
                        'warning' => 'in_process',
                        'danger'  => 'no',
                    ])
                    ->formatStateUsing(fn ($s) => match ($s) {
                        'yes' => 'Yes', 'no' => 'No', 'in_process' => 'In process', default => $s,
                    })
                    ->toggleable(),
                TextColumn::make('student_status')
                    ->label('Student Status')
                    ->formatStateUsing(function ($s) {
                        return match ($s) {
                            'current_student'     => 'Current student',
                            'finished_bachelor'   => 'Finished Bachelor',
                            'finished_master'     => 'Finished Master',
                            'working_professional'=> 'Working professional',
                            default               => ucfirst(str_replace('_',' ', (string)$s)),
                        };
                    })
                    ->toggleable(),
                BadgeColumn::make('has_accommodation')
                    ->label('Accommodation')
                    ->colors(['success' => 'yes', 'danger' => 'no'])
                    ->formatStateUsing(fn ($s) => $s ? strtoupper($s[0]).substr($s,1) : '-')
                    ->toggleable(isToggledHiddenByDefault: true),
                BadgeColumn::make('has_health_insurance')
                    ->label('Insurance')
                    ->colors(['success' => 'yes', 'danger' => 'no'])
                    ->formatStateUsing(fn ($s) => $s ? strtoupper($s[0]).substr($s,1) : '-')
                    ->toggleable(isToggledHiddenByDefault: true),
                BadgeColumn::make('has_empadronamiento')
                    ->label('Empadronamiento')
                    ->colors(['success' => 'yes', 'danger' => 'no'])
                    ->formatStateUsing(fn ($s) => $s ? strtoupper($s[0]).substr($s,1) : '-')
                    ->toggleable(isToggledHiddenByDefault: true),
                TagsColumn::make('services_needed')
                    ->label('Services'),
                TextColumn::make('amount_cents')
                    ->label('Amount')
                    ->formatStateUsing(fn ($state, StudentIntakeSubmission $record) =>
                        number_format(($record->amount_cents ?? 0) / 100, 2) . ' ' . strtoupper($record->currency ?? 'EUR')
                    )
                    ->sortable(),
                BadgeColumn::make('status')
                    ->colors([
                        'warning'   => 'pending_payment',
                        'success'   => 'paid',
                        'danger'    => 'refunded',
                        'secondary' => 'cancelled',
                    ])
                    ->sortable(),
                TextColumn::make('submitted_at')
                    ->label('Submitted')
                    ->dateTime(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->since(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending_payment' => 'Pending Payment',
                    'paid'            => 'Paid',
                    'refunded'        => 'Refunded',
                    'cancelled'       => 'Cancelled',
                ]),
                SelectFilter::make('visa_status')->options([
                    'valid'        => 'Valid',
                    'expires_soon' => 'Expires soon',
                    'no_visa'      => 'No visa',
                ]),
                SelectFilter::make('student_status')->options([
                    'current_student'     => 'Current student',
                    'finished_bachelor'   => 'Finished Bachelor',
                    'finished_master'     => 'Finished Master',
                    'working_professional'=> 'Working professional',
                    'other'               => 'Other',
                ]),
                Filter::make('submitted_recent')
                    ->label('Submitted (last 30 days)')
                    ->query(fn (Builder $q) => $q->where('submitted_at', '>=', now()->subDays(30))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Action::make('markPaid')
                    ->label('Mark as Paid')
                    ->visible(fn (StudentIntakeSubmission $record) => $record->status !== 'paid')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (StudentIntakeSubmission $record) => $record->update(['status' => 'paid'])),

                Action::make('markPending')
                    ->label('Mark as Pending')
                    ->visible(fn (StudentIntakeSubmission $record) => $record->status !== 'pending_payment')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(fn (StudentIntakeSubmission $record) => $record->update(['status' => 'pending_payment'])),

                Action::make('markRefunded')
                    ->label('Refunded')
                    ->visible(fn (StudentIntakeSubmission $record) => $record->status !== 'refunded')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (StudentIntakeSubmission $record) => $record->update(['status' => 'refunded'])),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('bulkPaid')
                    ->label('Mark Selected as Paid')
                    ->color('success')
                    ->action(fn ($records) => $records->each->update(['status' => 'paid'])),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListStudentIntakeSubmissions::route('/'),
            'create' => Pages\CreateStudentIntakeSubmission::route('/create'),
            'view'   => Pages\ViewStudentIntakeSubmission::route('/{record}'),
            'edit'   => Pages\EditStudentIntakeSubmission::route('/{record}/edit'),
        ];
    }
}
