<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SpecialDiscountResource\Pages;
use App\Models\SpecialDiscount;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class SpecialDiscountResource extends Resource
{
    protected static ?string $model = SpecialDiscount::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Premium';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Discount Title')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->rows(3),

                TextInput::make('location')
                    ->required()
                    ->maxLength(255),

                TextInput::make('discount')
                    ->label('Discount Amount (e.g. 25% OFF)')
                    ->required()
                    ->maxLength(50),

                Select::make('category')
                    ->options([
                        'restaurant' => 'Restaurant',
                        'museum' => 'Museum',
                        'shop' => 'Shop',
                        'attraction' => 'Attraction',
                        'realestate' => 'Real Estate',
                    ])
                    ->label('Discount Category')
                    ->required(),

                DatePicker::make('valid_until')
                    ->label('Valid Until')
                    ->required(),

                FileUpload::make('image')
                    ->label('Image')
                    ->image()
                    ->directory('discounts')
                    ->imagePreviewHeight('100'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('category')->sortable(),
                TextColumn::make('discount'),
                TextColumn::make('location'),
                TextColumn::make('valid_until')->label('Valid Until')->date(),
                ImageColumn::make('image')->label('Image')->circular()->height(40),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpecialDiscounts::route('/'),
            'create' => Pages\CreateSpecialDiscount::route('/create'),
            'edit' => Pages\EditSpecialDiscount::route('/{record}/edit'),
        ];
    }
}
