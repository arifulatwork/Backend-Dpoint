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

    // Use a v2-compatible icon name
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';
    protected static ?string $navigationGroup = 'Student Services';
    protected static ?int $navigationSort = 10;
    protected static ?string $modelLabel = 'Student Intake Submission';
    protected static ?string $pluralModelLabel = 'Student Intake Submissions';

    /**
     * Expose the form schema as a plain array so other pages (View) can reuse it.
     */
    public static function formSchema(): array
    {
        return [
            Section::make('Personal')
                ->schema([
                    TextInput::make('full_name')->label('Full Name')->required()->maxLength(255),
                    TextInput::make('email')->email()->required()->maxLength(255),
                    TextInput::make('contact_phone')->label('Contact Phone')->maxLength(50),
                    TextInput::make('nationality')->required()->maxLength(100),
                    TextInput::make('target_country')->label('Target Country')->maxLength(100),
                    TextInput::make('current_situation')->label('Current Situation')->maxLength(100),
                    DatePicker::make('visa_expiry_date')->label('Visa Expiry Date'),
                    Select::make('has_residence_card')
                        ->label('Has Residence Card')
                        ->options([
                            'yes' => 'Yes',
                            'no' => 'No',
                            'unknown' => 'Unknown',
                        ])
                        ->nullable(),
                ])->columns(2),

            Section::make('Service & Profile')
                ->schema([
                    TagsInput::make('services_needed')->label('Services Needed')->placeholder('Add a service…')->suggestions([
                        'Admission counseling',
                        'Visa support',
                        'Accommodation',
                        'Job search',
                        'Document review',
                    ]),
                    Textarea::make('professional_info')->label('Professional Info')->rows(4),
                    TextInput::make('future_plans')->label('Future Plans')->maxLength(100),
                    KeyValue::make('document_paths')
                        ->keyLabel('Label / File')
                        ->valueLabel('Path / URL')
                        ->helperText('Stored document paths (read-only)')
                        ->disableAddingRows()
                        ->disableEditingKeys()
                        ->disableEditingValues()
                        ->disableDeletingRows()
                        ->hiddenOn('create'), // ← v2-safe: show only on edit/view
                ])->columns(2),

            Section::make('Payment & Status')
                ->schema([
                    TextInput::make('amount_cents')
                        ->label('Amount (cents)')
                        ->numeric()
                        ->prefix('€')
                        ->helperText('Amount is stored in cents'),
                    TextInput::make('currency')->default('eur')->maxLength(8),
                    TextInput::make('stripe_payment_intent_id')->label('Stripe PI ID')->maxLength(255),
                    Select::make('status')
                        ->options([
                            'pending_payment' => 'Pending Payment',
                            'paid'            => 'Paid',
                            'refunded'        => 'Refunded',
                            'cancelled'       => 'Cancelled',
                        ])
                        ->required(),
                    DatePicker::make('submitted_at')->label('Submitted At'),
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
                TextColumn::make('full_name')->searchable()->sortable()->weight('bold')->label('Name'),
                TextColumn::make('email')->searchable()->copyable(),
                TextColumn::make('nationality')->toggleable()->sortable(),
                TextColumn::make('target_country')->label('Target')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('visa_expiry_date')->date()->label('Visa Expiry')->toggleable(),
                TagsColumn::make('services_needed')->label('Services'),
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
                    ])->sortable(),
                TextColumn::make('submitted_at')->dateTime()->label('Submitted'),
                TextColumn::make('created_at')->since()->label('Created'),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending_payment' => 'Pending Payment',
                    'paid'            => 'Paid',
                    'refunded'        => 'Refunded',
                    'cancelled'       => 'Cancelled',
                ]),
                Filter::make('submitted_recent')
                    ->label('Submitted (last 30 days)')
                    ->query(fn (Builder $q) => $q->where('submitted_at', '>=', now()->subDays(30))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), // remove if your v2 build lacks it
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
