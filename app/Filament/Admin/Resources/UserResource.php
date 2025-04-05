<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Resources\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Additional Details')
                    ->schema([
                        Forms\Components\TextInput::make('age')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(120),
                            
                        Forms\Components\TagsInput::make('interests')
                            ->nullable()
                            ->placeholder('Add interest')
                            ->helperText('Press enter to add an interest'),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Security')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->visibleOn('create')
                            ->helperText('Leave empty to generate random password')
                            ->dehydrated(fn ($state) => filled($state))
                            ->default(Str::random(10)),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->getStateUsing(fn ($record) => "{$record->first_name} {$record->last_name}")
                    ->searchable(['first_name', 'last_name']),
                    
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('age')
                    ->sortable()
                    ->alignCenter(),
                    
                Tables\Columns\TextColumn::make('interests')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) return '';
                        return is_array($state) ? implode(', ', $state) : $state;
                    })
                    ->limit(30)
                    ->tooltip(function ($record) {
                        if (empty($record->interests)) return '';
                        return is_array($record->interests) 
                            ? implode(', ', $record->interests) 
                            : $record->interests;
                    }),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_interests')
                    ->label('With interests')
                    ->query(fn ($query) => $query->whereNotNull('interests')),
                    
                Tables\Filters\SelectFilter::make('age_group')
                    ->options([
                        '1-17' => 'Under 18',
                        '18-25' => '18-25',
                        '26-40' => '26-40',
                        '41+' => '41+',
                    ])
                    ->query(function ($query, $data) {
                        if ($value = $data['value']) {
                            [$min, $max] = explode('-', $value);
                            if ($max === '+') {
                                $query->where('age', '>=', $min);
                            } else {
                                $query->whereBetween('age', [$min, $max]);
                            }
                        }
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}