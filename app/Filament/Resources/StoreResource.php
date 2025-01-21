<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreResource\Pages;
use App\Filament\Resources\StoreResource\RelationManagers;
use App\Models\RajaOngkirSetting;
use App\Models\Store;
use App\Services\RajaOngkirService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Set;
use illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        $rajaOngkirService = new RajaOngkirService();
        $rajaOngkirSetting = RajaOngkirSetting::getActive();
        $isProVersion = $rajaOngkirSetting?->isPro() ?? false;

        if (!$rajaOngkirSetting?->is_valid) {
            Notification::make()
                ->title('Rajaongkir API is not valid')
                ->body('Please configure valid Rajaongkir settings before creating a store')
                ->danger()
                ->send();

            return $form->schema([]);
        }

        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Basic Information')
                            ->description('Basic information about the store')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('description')
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('whatsapp')
                                    ->prefix('62')
                                    ->helperText('Mohon masukan nomor tanpa angka 0 diawal. Contoh 812345678900')
                                    ->placeholder('812345678900')
                                    ->required()
                                    ->numeric()
                                    ->dehydrateStateUsing(fn($state) => '62' . ltrim($state, '62'))
                                    ->formatStateUsing(fn($state) => ltrim($state, '62'))
                                    ->validationAttribute('Nomor WhatsApp')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email_notification')
                                    ->email(),
                                Forms\Components\FileUpload::make('image')
                                    ->image()
                                    ->directory('stores'),
                                Forms\Components\FileUpload::make('banner')
                                    ->columnSpanFull()
                                    ->multiple()
                                    ->directory('stores/banner'),
                                Forms\Components\FileUpload::make('info_swiper')
                                    ->columnSpanFull()
                                    ->multiple()
                                    ->directory('stores/swiper'),
                                Forms\Components\FileUpload::make('advertise')
                                    ->image()
                                    ->directory('stores/advertise'),
                                Forms\Components\Toggle::make('payment_gateway')
                            ])
                    ]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Address')
                            ->description('Use Rajaongkir API')
                            ->schema([
                                Forms\Components\Select::make('province_id')
                                    ->label('Province')
                                    ->options(fn() => $rajaOngkirService->getProvinces())
                                    ->default(function ($record) {
                                        return $record?->province_id;
                                    })
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) use ($rajaOngkirService) {
                                        $set('regency_id', null);
                                        $set('regency_name', null);

                                        if ($state) {
                                            $provinces = $rajaOngkirService->getProvinces();
                                            $set('province_name', $provinces[$state] ?? '');
                                        }
                                    })
                                    ->required(),
                                Forms\Components\Select::make('regency_id')
                                    ->label('Regency')
                                    ->required()
                                    ->options(function (Get $get, $record) use ($rajaOngkirService) {
                                        $provinceId = $get('province_id') ?? $record?->province_id;
                                        if (!$provinceId) {
                                            return Collection::empty();
                                        }

                                        return $rajaOngkirService->getCities($provinceId);
                                    })
                                    ->default(function ($record) {
                                        return $record?->regency_id;
                                    })
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) use ($rajaOngkirService) {
                                        if ($state) {
                                            $cities = $rajaOngkirService->getCities($get('province_id'));
                                            $set('regency_name', $cities[$state] ?? '');
                                        }
                                    }),
                                Forms\Components\Select::make('subdistrict_id')
                                    ->label('District')
                                    ->options(function (Get $get, $record) use ($rajaOngkirService) {
                                        $cityId = $get('regency_id') ?? $record?->regency_id;
                                        if (!$cityId) {
                                            return Collection::empty();
                                        }

                                        return $rajaOngkirService->getSubdistricts($cityId)
                                            ->map(fn($item) => $item['name'])
                                            ->toArray();
                                    })
                                    ->default(function ($record) {
                                        return $record?->subdistrict_id;
                                    })
                                    ->live()
                                    ->required()
                                    ->disabled(fn(Get $get, $record) => !($get('regency_id') ?? $record?->regency_id))
                                    ->visible($isProVersion)
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) use ($rajaOngkirService) {
                                        if ($state && $get('regency_id')) {
                                            $subdistricts = $rajaOngkirService->getSubdistricts($get('regency_id'));
                                            $subdistrictData = $subdistricts[$state] ?? null;

                                            if ($subdistrictData) {
                                                $set('subdistrict_name', $subdistrictData['name']);
                                            }
                                        } else {
                                            $set('subdistrict_name', null);
                                        }
                                    }),

                                Forms\Components\TextInput::make('address')
                                    ->maxLength(255),
                                Forms\Components\Hidden::make('province_name'),
                                Forms\Components\Hidden::make('regency_name'),
                                Forms\Components\Hidden::make('subdistrict_name'),
                            ]),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('kartu member')
                                    ->schema([
                                        Forms\Components\FileUpload::make('card_front')
                                            ->image()
                                            ->directory('stores/card_front'),
                                        Forms\Components\FileUpload::make('card_back')
                                            ->image()
                                            ->directory('stores/card_back'),
                                    ])
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        $isProVersion = RajaOngkirSetting::getActive()?->isPro() ?? false;
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\ImageColumn::make('advertise')
                    ->circular(),
                Tables\Columns\ImageColumn::make('banner')
                    ->circular()
                    ->stacked(),
                Tables\Columns\ImageColumn::make('info_swiper')
                    ->circular()
                    ->stacked(),
                Tables\Columns\TextColumn::make('whatsapp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('province_name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('regency_name')
                    ->numeric()
                    ->sortable(),

                ...($isProVersion ? [
                    Tables\Columns\TextColumn::make('subdistrict_name')
                        ->searchable(),
                ] : []),

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
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return Store::count() < 1;
    }
}
