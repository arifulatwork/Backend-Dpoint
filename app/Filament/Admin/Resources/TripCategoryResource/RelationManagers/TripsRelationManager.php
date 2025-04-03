<?php

namespace App\Filament\Admin\Resources\TripCategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Str;
use App\Models\Trip;

class TripsRelationManager extends RelationManager
{
    protected static string $relationship = 'trips';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('price')
                    ->numeric()
                    ->required(),
                
                FileUpload::make('image_url')
                    ->label('Featured Image')
                    ->directory('trip-images')
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->square()
                    ->size(40),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('price')
                    ->money('USD'),
                
                Tables\Columns\TextColumn::make('duration_days')
                    ->suffix(' days'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}