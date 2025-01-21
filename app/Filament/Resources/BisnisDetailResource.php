<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BisnisDetailResource\Pages;
use App\Filament\Resources\BisnisDetailResource\RelationManagers;
use App\Models\BisnisDetail;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BisnisDetailResource extends Resource
{
    protected static ?string $model = BisnisDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Bisnis';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Detail Bisnis')
                    ->schema([
                        Forms\Components\TextInput::make('bisnis_name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('bisnis_description')
                            ->maxLength(255),
                    ]),

                Fieldset::make('content')
                    ->schema([
                        Forms\Components\FileUpload::make('bisnis_image')
                            ->image()
                            ->directory('bisnis'),
                    ]),

                Fieldset::make('Rules and Benefits')
                    ->schema([
                        Repeater::make('bisnisRules')
                            ->relationship('bisnisRules')
                            ->schema([
                                TextInput::make('rules')
                                    ->required()
                            ]),
                        Repeater::make('bisnisBenefits')
                            ->relationship('bisnisBenefits')
                            ->schema([
                                TextInput::make('benefits')
                                    ->required()
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bisnis_name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('bisnis_image'),
                Tables\Columns\TextColumn::make('bisnis_description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListBisnisDetails::route('/'),
            'create' => Pages\CreateBisnisDetail::route('/create'),
            'edit' => Pages\EditBisnisDetail::route('/{record}/edit'),
        ];
    }
}
