<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DestinationResource\Pages;
use App\Models\Destination;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;

class DestinationResource extends Resource
{
    protected static ?string $model = Destination::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationLabel = 'Destinations';
    protected static ?string $pluralModelLabel = 'Destinations';
    protected static ?string $modelLabel = 'Destination';
    protected static ?string $navigationGroup = 'Destinations';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Card::make()->schema([
                TextInput::make('country')
                    ->required()
                    ->maxLength(100),

                TextInput::make('city')
                    ->required()
                    ->maxLength(100),

                FileUpload::make('image')
                    ->label('Destination Image')
                    ->image()
                    ->directory('destinations')
                    ->disk('public')
                    ->visibility('public')
                    ->required(),

                TextInput::make('coordinates')
                    ->label('Coordinates JSON')
                    ->required()
                    ->rule('json')
                    ->columnSpanFull(),

                Select::make('visit_type')
                    ->required()
                    ->options([
                        'individual' => 'Individual',
                        'group' => 'Group',
                        'company' => 'Company',
                    ]),

                TagsInput::make('highlights')
                    ->label('Highlights')
                    ->placeholder('Add highlights'),

                TagsInput::make('cuisine')
                    ->label('Cuisines')
                    ->placeholder('Add cuisines'),

                Textarea::make('description')
                    ->rows(5)
                    ->columnSpanFull(),

                TextInput::make('max_price')
                    ->numeric()
                    ->suffix('€')
                    ->label('Max Price'),

                TextInput::make('latitude')
                    ->disabled()
                    ->dehydrated(false)
                    ->label('Latitude'),

                TextInput::make('longitude')
                    ->disabled()
                    ->dehydrated(false)
                    ->label('Longitude'),
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

                TextColumn::make('country')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('city')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('visit_type')
                    ->colors([
                        'primary' => 'individual',
                        'success' => 'group',
                        'warning' => 'company',
                    ]),

                TextColumn::make('max_price')
                    ->money('eur', true)
                    ->sortable(),

                TextColumn::make('latitude')
                    ->sortable(),

                TextColumn::make('longitude')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('visit_type')
                    ->options([
                        'individual' => 'Individual',
                        'group' => 'Group',
                        'company' => 'Company',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // Add RelationManagers for attractions or pointsOfInterest if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDestinations::route('/'),
            'create' => Pages\CreateDestination::route('/create'),
            'edit' => Pages\EditDestination::route('/{record}/edit'),
        ];
    }
}
