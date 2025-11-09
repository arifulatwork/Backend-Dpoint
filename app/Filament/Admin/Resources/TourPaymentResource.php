<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TourPaymentResource\Pages;
use App\Filament\Admin\Resources\TourPaymentResource\RelationManagers;
use App\Models\TourPayment;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TourPaymentResource extends Resource
{
    protected static ?string $model = TourPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTourPayments::route('/'),
            'create' => Pages\CreateTourPayment::route('/create'),
            'edit' => Pages\EditTourPayment::route('/{record}/edit'),
        ];
    }    
}
