<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InternshipSkillResource\Pages;
use App\Filament\Admin\Resources\InternshipSkillResource\RelationManagers;
use App\Models\InternshipSkill;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InternshipSkillResource extends Resource
{
    protected static ?string $model = InternshipSkill::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Internships';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->unique(ignoreRecord: true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id'),
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInternshipSkills::route('/'),
            'create' => Pages\CreateInternshipSkill::route('/create'),
            'edit' => Pages\EditInternshipSkill::route('/{record}/edit'),
        ];
    }
}
