<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required(fn($context) => $context === 'create') // Hanya diperlukan saat pembuatan
                            ->maxLength(255)
                            ->dehydrated(fn($state) => $state !== null),
                        Forms\Components\TextInput::make('point')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('is_admin')
                            ->required(),
                    ])
                    ->columns(2), // Mengatur jumlah kolom dalam grup ini
                Forms\Components\Fieldset::make('Dokumen')
                    ->schema([
                        Forms\Components\FileUpload::make('foto_profile')
                            ->directory('profiles')
                            ->image(),
                        Forms\Components\FileUpload::make('foto_ktp')
                            ->directory('ktp')
                            ->image(),
                        Forms\Components\TextInput::make('address')
                            ->maxLength(255)
                    ]),
                Forms\Components\Fieldset::make('Informasi Media Sosial')
                    ->schema([
                        Forms\Components\TextInput::make('facebook'),
                        Forms\Components\TextInput::make('instagram'),
                        Forms\Components\TextInput::make('tiktok'),
                    ])
                    ->columns(3), // Membagi field ini menjadi 3 kolom
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->numeric(),
                        Forms\Components\Select::make('role')
                            ->options([
                                'distributor' => 'Distributor',
                                'stockist' => 'Stockist',
                                'agent' => 'Agent',
                                'reseller' => 'Reseller'
                            ]),
                    ])
                    ->columns(2),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('point')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_admin')
                    ->boolean(),
                Tables\Columns\ImageColumn::make('foto_profile')
                    ->label('Profile Picture')
                    ->circular(),
                Tables\Columns\TextColumn::make('role')
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
