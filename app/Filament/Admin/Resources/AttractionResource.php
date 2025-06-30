<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AttractionResource\Pages;
use App\Models\Attraction;
use App\Models\Destination;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;

class AttractionResource extends Resource
{
    protected static ?string $model = Attraction::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationLabel = 'Attractions';
    protected static ?string $pluralModelLabel = 'Attractions';
    protected static ?string $modelLabel = 'Attraction';
    protected static ?string $navigationGroup = 'Destinations';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Attraction Info')->schema([
                Select::make('destination_id')
                    ->label('Destination')
                    ->relationship('destination', 'city')
                    ->searchable()
                    ->required(),

                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('type')
                    ->required()
                    ->maxLength(255),

                TextInput::make('duration')
                    ->required(),

                TextInput::make('price')
                    ->numeric()
                    ->required(),

                TextInput::make('group_price')
                    ->numeric()
                    ->label('Group Price')
                    ->nullable(),

                TextInput::make('min_group_size')
                    ->numeric()
                    ->label('Min Group Size'),

                TextInput::make('max_group_size')
                    ->numeric()
                    ->label('Max Group Size'),

                FileUpload::make('image')
                    ->label('Image')
                    ->image()
                    ->directory('attractions')
                    ->disk('public')
                    ->visibility('public')
                    ->required(),

                TagsInput::make('highlights')
                    ->label('Highlights')
                    ->placeholder('Add highlight items'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->square()
                    ->height(60),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->sortable(),

                TextColumn::make('destination.city')
                    ->label('Destination')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('price')
                    ->money('eur', true),

                TextColumn::make('group_price')
                    ->money('eur', true)
                    ->label('Group Price'),

                TextColumn::make('duration'),

                TextColumn::make('created_at')
                    ->dateTime('d M Y'),
            ])
            ->filters([
                SelectFilter::make('destination_id')
                    ->label('Filter by Destination')
                    ->relationship('destination', 'city'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttractions::route('/'),
            'create' => Pages\CreateAttraction::route('/create'),
            'edit' => Pages\EditAttraction::route('/{record}/edit'),
        ];
    }
}
