<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InternshipLearningOutcomeResource\Pages;
use App\Filament\Admin\Resources\InternshipLearningOutcomeResource\RelationManagers;
use App\Models\InternshipLearningOutcome;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InternshipLearningOutcomeResource extends Resource
{
    protected static ?string $model = InternshipLearningOutcome::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationGroup = 'Internships';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('internship_id')
                ->relationship('internship','title')
                ->searchable()
                ->required(),
            Forms\Components\TextInput::make('outcome')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id'),
            Tables\Columns\TextColumn::make('internship.title')->label('Internship'),
            Tables\Columns\TextColumn::make('outcome')->wrap(),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInternshipLearningOutcomes::route('/'),
            'create' => Pages\CreateInternshipLearningOutcome::route('/create'),
            'edit' => Pages\EditInternshipLearningOutcome::route('/{record}/edit'),
        ];
    }
}