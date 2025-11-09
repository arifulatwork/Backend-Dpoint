<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TourCategoryResource\Pages;
use App\Models\TourCategory;
use Filament\Resources\Resource;
use Filament\Resources\Form;   // ✅ use Resources\Form (not Forms\Form)
use Filament\Resources\Table;  // ✅ use Resources\Table (not Tables\Table)
use Filament\Tables;

// Form components
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;

// Table columns
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\BadgeColumn;

class TourCategoryResource extends Resource
{
    protected static ?string $model = TourCategory::class;

    protected static ?string $navigationIcon  = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Tours';
    protected static ?string $navigationLabel = 'Tour Categories';
    protected static ?int    $navigationSort  = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(12)->schema([
                TextInput::make('key')
                    ->label('Key')
                    ->helperText('Unique identifier, e.g. "montenegro", "balkan", "spain".')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50)
                    ->columnSpan(4),

                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(8),

                Textarea::make('description')
                    ->rows(4)
                    ->columnSpan(12),
            ]),

            Grid::make(12)->schema([
                FileUpload::make('image')
                    ->label('Category Image')
                    ->image()
                    ->directory('images')     // stored on 'public' disk
                    ->visibility('public')    // ensure it's publicly accessible
                    ->columnSpan(6)
                    ->helperText('Shown on the category card in TripSection.'),

                TagsInput::make('destinations')
                    ->placeholder('Add a destination')
                    ->columnSpan(6),
            ]),

            Grid::make(12)->schema([
                TextInput::make('duration_min')
                    ->label('Min days')
                    ->numeric()
                    ->minValue(0)
                    ->columnSpan(3),

                TextInput::make('duration_max')
                    ->label('Max days')
                    ->numeric()
                    ->minValue(0)
                    ->columnSpan(3),

                TextInput::make('price_min')
                    ->label('Min price (€)')
                    ->numeric()
                    ->minValue(0)
                    ->columnSpan(3),

                TextInput::make('price_max')
                    ->label('Max price (€)')
                    ->numeric()
                    ->minValue(0)
                    ->columnSpan(3),
            ]),

            Grid::make(12)->schema([
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->columnSpan(3),

                Toggle::make('is_active')
                    ->default(true)
                    ->columnSpan(3),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->square()
                    ->toggleable(),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                // Badge for the category key (Filament v2 uses BadgeColumn)
                BadgeColumn::make('key')
                    ->label('Key')
                    ->colors(['primary'])
                    ->sortable()
                    ->searchable(),

                TextColumn::make('duration_min')
                    ->label('Min days')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('duration_max')
                    ->label('Max days')
                    ->sortable()
                    ->toggleable(),

                // Currency rendering safe for v2
                TextColumn::make('price_min')
                    ->label('Min €')
                    ->formatStateUsing(fn ($state) => $state !== null ? '€' . number_format((float) $state, 2) : '—')
                    ->toggleable(),

                TextColumn::make('price_max')
                    ->label('Max €')
                    ->formatStateUsing(fn ($state) => $state !== null ? '€' . number_format((float) $state, 2) : '—')
                    ->toggleable(),

                ToggleColumn::make('is_active')
                    ->label('Active'),

                TextColumn::make('sort_order')
                    ->label('Sort')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('Y-m-d H:i'),
            ])
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),     // ✅ ensures Edit is visible
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTourCategories::route('/'),
            'create' => Pages\CreateTourCategory::route('/create'),
            'edit'   => Pages\EditTourCategory::route('/{record}/edit'),
        ];
    }
}
