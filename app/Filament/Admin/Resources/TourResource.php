<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TourResource\Pages;
use App\Models\Tour;
use App\Models\TourCategory;
use Filament\Resources\Resource;
use Filament\Resources\Form;   // ✅ Resources\Form
use Filament\Resources\Table;  // ✅ Resources\Table
use Filament\Tables;

// Form components
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;

// Table columns
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\BadgeColumn;

class TourResource extends Resource
{
    protected static ?string $model = Tour::class;

    protected static ?string $navigationIcon  = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'Tours';
    protected static ?string $navigationLabel = 'Tours';
    protected static ?int    $navigationSort  = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(12)->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(8),

                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->columnSpan(4),

                Select::make('category')
                    ->label('Category (key)')
                    ->options(fn () => TourCategory::orderBy('sort_order')->pluck('title', 'key'))
                    ->searchable()
                    ->required()
                    ->columnSpan(4)
                    ->helperText('Maps to TourCategory.key'),

                TextInput::make('currency')
                    ->default('EUR')
                    ->maxLength(10)
                    ->columnSpan(2),

                Toggle::make('is_active')
                    ->default(true)
                    ->columnSpan(2),

                Textarea::make('description')
                    ->rows(4)
                    ->columnSpan(12),
            ]),

            Grid::make(12)->schema([
                TextInput::make('duration_days')
                    ->label('Duration (days)')
                    ->numeric()
                    ->minValue(0)
                    ->required()
                    ->columnSpan(3),

                TextInput::make('base_price')
                    ->label('Base price (€)')
                    ->numeric()
                    ->minValue(0)
                    ->required()
                    ->columnSpan(3),

                TagsInput::make('destinations')
                    ->placeholder('Add destination')
                    ->columnSpan(6),
            ]),

            Grid::make(12)->schema([
                FileUpload::make('image_url')
                    ->label('Main Image')
                    ->image()
                    ->directory('tours')       // public disk; /storage/tours/...
                    ->visibility('public')
                    ->columnSpan(6)
                    ->helperText('Run "php artisan storage:link" to serve from /storage/tours/...'),

                Group::make()->schema([
                    TextInput::make('group_size.min')
                        ->numeric()
                        ->minValue(1)
                        ->label('Group min'),
                    TextInput::make('group_size.max')
                        ->numeric()
                        ->minValue(1)
                        ->label('Group max'),
                ])->columns(2)->columnSpan(6),
            ]),

            Repeater::make('itinerary')
                ->label('Itinerary (per day)')
                ->schema([
                    TextInput::make('day')
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->columnSpan(2),
                    TextInput::make('title')
                        ->required()
                        ->columnSpan(10),
                    Textarea::make('description')
                        ->rows(3)
                        ->columnSpan(12),
                    TagsInput::make('meals')
                        ->placeholder('Add meal (e.g. Breakfast)')
                        ->columnSpan(6),
                    TextInput::make('accommodation')
                        ->columnSpan(6),
                ])
                ->collapsible()
                ->itemLabel(fn ($state) => isset($state['day']) ? 'Day ' . $state['day'] : 'Day')
                ->minItems(1)
                ->columnSpan('full'),

            Grid::make(12)->schema([
                TagsInput::make('included')
                    ->placeholder('Add included item')
                    ->columnSpan(6),
                TagsInput::make('not_included')
                    ->placeholder('Add excluded item')
                    ->columnSpan(6),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_url')
                    ->label('Image')
                    ->square()
                    ->toggleable(),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('category')
                    ->label('Category')
                    ->colors(['primary'])
                    ->sortable()
                    ->searchable(),

                TextColumn::make('duration_days')
                    ->label('Days')
                    ->sortable(),

                TextColumn::make('base_price')
                    ->label('Price')
                    ->formatStateUsing(fn ($state, $record) => $state !== null
                        ? ($record->currency ?? 'EUR') . ' ' . number_format((float) $state, 2)
                        : '—'
                    ),

                ToggleColumn::make('is_active')
                    ->label('Active'),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('Y-m-d H:i'),
            ])
            ->defaultSort('updated_at', 'desc')
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
            // You can add a RelationManager to show bookings for a tour, e.g.:
            // TourBookingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTours::route('/'),
            'create' => Pages\CreateTour::route('/create'),
            'edit'   => Pages\EditTour::route('/{record}/edit'),
            'view'   => Pages\ViewTour::route('/{record}'),
        ];
    }
}
