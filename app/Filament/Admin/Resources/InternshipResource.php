<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InternshipResource\Pages;
use App\Models\Internship;

use Filament\Resources\Resource;
use Filament\Resources\Form;   // v2 Form
use Filament\Resources\Table;  // v2 Table

use Filament\Forms;            // components live under Forms\Components in v2 too
use Filament\Tables;

class InternshipResource extends Resource
{
    protected static ?string $model = Internship::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Internships';
    protected static ?string $navigationLabel = 'Internships';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('category_id')
                ->label('Category')
                ->relationship('category', 'name')   // uses your belongsTo('category')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->required()
                ->rows(5),

            Forms\Components\TextInput::make('duration')
                ->maxLength(255),

            Forms\Components\TextInput::make('price')
                ->numeric()
                ->required()
                ->suffix('EUR'),

            Forms\Components\TextInput::make('original_price')
                ->numeric()
                ->nullable()
                ->suffix('EUR'),

            Forms\Components\TextInput::make('rating')
                ->numeric()
                ->step('0.1')
                ->minValue(0)
                ->maxValue(5)
                ->default(0),

            Forms\Components\TextInput::make('review_count')
                ->numeric()
                ->minValue(0)
                ->default(0),

            Forms\Components\TextInput::make('company')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('location')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('mode')
                ->options([
                    'remote' => 'Remote',
                    'on-site' => 'On-site',
                    'hybrid' => 'Hybrid',
                ])
                ->required(),

            Forms\Components\FileUpload::make('image')
                ->image()
                ->directory('internships'),

            Forms\Components\Toggle::make('featured'),

            Forms\Components\DatePicker::make('deadline')
                ->displayFormat('Y-m-d')  // v2-friendly
                ->nullable(),

            Forms\Components\TextInput::make('spots_left')
                ->numeric()
                ->minValue(0)
                ->nullable(),

            Forms\Components\Select::make('skills')
                ->label('Skills')
                ->multiple()
                ->relationship('skills', 'name')  // uses your belongsToMany('skills')
                ->searchable()
                ->preload(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('company')
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\BadgeColumn::make('mode') // BadgeColumn exists in v2
                    ->enum([
                        'remote' => 'Remote',
                        'on-site' => 'On-site',
                        'hybrid' => 'Hybrid',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->formatStateUsing(fn ($state) => is_null($state) ? '-' : ('$' . number_format((float) $state, 2)))
                    ->sortable(),

                Tables\Columns\IconColumn::make('featured')
                    ->boolean()
                    ->label('Featured'),

                Tables\Columns\TextColumn::make('deadline')
                    ->date('Y-m-d')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Category'),

                Tables\Filters\SelectFilter::make('mode')
                    ->options([
                        'remote' => 'Remote',
                        'on-site' => 'On-site',
                        'hybrid' => 'Hybrid',
                    ]),

                Tables\Filters\TernaryFilter::make('featured')
                    ->label('Featured'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListInternships::route('/'),
            'create' => Pages\CreateInternship::route('/create'),
            'edit'   => Pages\EditInternship::route('/{record}/edit'),
        ];
    }
}
