<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InternshipCategoryResource\Pages;
use App\Filament\Admin\Resources\InternshipCategoryResource\RelationManagers;
use App\Models\InternshipCategory;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InternshipCategoryResource extends Resource
{
    protected static ?string $model = InternshipCategory::class;
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Internships';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('icon')->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('icon'),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInternshipCategories::route('/'),
            'create' => Pages\CreateInternshipCategory::route('/create'),
            'edit' => Pages\EditInternshipCategory::route('/{record}/edit'),
        ];
    }
}