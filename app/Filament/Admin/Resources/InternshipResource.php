<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InternshipResource\Pages;
use App\Filament\Admin\Resources\InternshipResource\RelationManagers;
use App\Models\Internship;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InternshipResource extends Resource
{
    protected static ?string $model = Internship::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Internships';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('category_id')
                ->label('Category')
                ->options(InternshipCategory::all()->pluck('name','id'))
                ->searchable()
                ->required(),
            Forms\Components\TextInput::make('title')->required()->maxLength(255),
            Forms\Components\Textarea::make('description')->required(),
            Forms\Components\TextInput::make('duration'),
            Forms\Components\TextInput::make('price')->numeric()->required(),
            Forms\Components\TextInput::make('original_price')->numeric()->nullable(),
            Forms\Components\TextInput::make('rating')->numeric()->step(0.1)->default(0),
            Forms\Components\TextInput::make('review_count')->numeric()->default(0),
            Forms\Components\TextInput::make('company')->required(),
            Forms\Components\TextInput::make('location')->required(),
            Forms\Components\Select::make('mode')->options([
                'remote' => 'Remote',
                'on-site' => 'On-site',
                'hybrid' => 'Hybrid',
            ])->required(),
            Forms\Components\FileUpload::make('image')->image()->directory('internships'),
            Forms\Components\Toggle::make('featured'),
            Forms\Components\DatePicker::make('deadline')->nullable(),
            Forms\Components\TextInput::make('spots_left')->numeric()->nullable(),

            Forms\Components\Select::make('skills')
                ->multiple()
                ->relationship('skills','name')
                ->preload()
                ->searchable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('company')->sortable(),
            Tables\Columns\TextColumn::make('category.name')->label('Category'),
            Tables\Columns\TextColumn::make('mode')->sortable(),
            Tables\Columns\TextColumn::make('price')->money('usd'),
            Tables\Columns\IconColumn::make('featured')->boolean(),
            Tables\Columns\TextColumn::make('deadline')->date(),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInternships::route('/'),
            'create' => Pages\CreateInternship::route('/create'),
            'edit' => Pages\EditInternship::route('/{record}/edit'),
        ];
    }
}