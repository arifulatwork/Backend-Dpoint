<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\GuideResource\Pages;
use App\Models\Guide;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class GuideResource extends Resource
{
    protected static ?string $model = Guide::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Guides';
    protected static ?string $pluralModelLabel = 'Guides';
    protected static ?string $modelLabel = 'Guide';
    protected static ?string $navigationGroup = 'Destinations';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('attraction_id')
                ->relationship('attraction', 'name')
                ->searchable()
                ->required()
                ->label('Attraction'),

            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\FileUpload::make('avatar')
                ->label('Avatar')
                ->directory('guides/avatars')
                ->image()                 // limit to images
                ->disk('public')          // ensure this matches your filesystem config
                ->visibility('public')    // make the file publicly accessible
                ->maxSize(2048)           // 2 MB (adjust if needed)
                ->preserveFilenames()     // optional
                ->nullable(),

            Forms\Components\TextInput::make('rating')
                ->numeric()
                ->step(0.1)
                ->minValue(0)
                ->maxValue(5)
                ->default(0),

            Forms\Components\TextInput::make('reviews')
                ->numeric()
                ->minValue(0)
                ->default(0),

            Forms\Components\Textarea::make('experience')
                ->rows(3)
                ->nullable(),

            Forms\Components\TagsInput::make('languages')
                ->placeholder('Add languages (e.g. English, Spanish, French)'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->label('Photo'),

                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('attraction.name')
                    ->label('Attraction')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->sortable(),

                Tables\Columns\TextColumn::make('reviews')
                    ->label('Reviews')
                    ->sortable(),

                Tables\Columns\TagsColumn::make('languages')
                    ->label('Languages'),
            ])
            ->filters([
                Tables\Filters\Filter::make('high_rating')
                    ->label('Rating ≥ 4.5')
                    ->query(fn ($query) => $query->where('rating', '>=', 4.5)),

                Tables\Filters\SelectFilter::make('attraction_id')
                    ->label('Attraction')
                    ->relationship('attraction', 'name'),
            ])
            ->actions([
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
            // Add relation managers later if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGuides::route('/'),
            'create' => Pages\CreateGuide::route('/create'),
            'edit'   => Pages\EditGuide::route('/{record}/edit'),
        ];
    }
}
