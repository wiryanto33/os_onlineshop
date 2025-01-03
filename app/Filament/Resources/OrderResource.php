<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Services\OrderStatusService;
use Carbon\Carbon;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Umum')
                            ->schema([
                                Forms\Components\TextInput::make('order_number')
                                    ->label('No. Pesanan')
                                    ->disabled(),
                                Forms\Components\TextInput::make('created_at')
                                    ->label('Tanggal Pesan')
                                    ->disabled()
                                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('d M Y H:i')),
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->disabled(),
                            ]),
                        Forms\Components\Section::make('Penerima')
                            ->schema([
                                Forms\Components\TextInput::make('recipient_name')
                                    ->label('Nama Penerima')
                                    ->disabled(),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->disabled(),
                            ]),

                        Forms\Components\Section::make('Detail Harga')
                            ->schema([
                                Forms\Components\TextInput::make('subtotal')
                                    ->disabled()
                                    ->numeric()
                                    ->default(0),
                                Forms\Components\TextInput::make('shipping_cost')
                                    ->disabled(),
                                Forms\Components\TextInput::make('total_amount')
                                    ->disabled()
                                    ->numeric()
                                    ->default(0),
                            ]),
                        Forms\Components\Section::make('Status Order')
                            ->schema([
                                Forms\Components\TextInput::make('snap_token')
                                    ->label('Snap Token midtrans')
                                    ->disabled(),
                                Forms\Components\Select::make('payment_status')
                                    ->label('Status Pembayaran')
                                    ->options([
                                        OrderStatusService::PAYMENT_UNPAID => OrderStatusService::getPaymentStatusLabel(OrderStatusService::PAYMENT_UNPAID),
                                        OrderStatusService::PAYMENT_PAID => OrderStatusService::getPaymentStatusLabel(OrderStatusService::PAYMENT_PAID),
                                    ])
                                    ->required()
                                    ->live()
                                    ->disabled(
                                        fn($record) =>
                                        $record->snap_token != null
                                    ),
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        OrderStatusService::STATUS_PENDING => OrderStatusService::getStatusLabel(OrderStatusService::STATUS_PENDING),
                                        OrderStatusService::STATUS_PROCESSING => OrderStatusService::getStatusLabel(OrderStatusService::STATUS_PROCESSING),
                                        OrderStatusService::STATUS_SHIPPED => OrderStatusService::getStatusLabel(OrderStatusService::STATUS_SHIPPED),
                                        OrderStatusService::STATUS_COMPLETED => OrderStatusService::getStatusLabel(OrderStatusService::STATUS_COMPLETED),
                                        OrderStatusService::STATUS_CANCELLED => OrderStatusService::getStatusLabel(OrderStatusService::STATUS_CANCELLED),
                                    ])
                                    ->required()
                                    ->live()
                                    ->disabled(fn($record, Forms\Get $get) => $get('payment_status') === OrderStatusService::PAYMENT_UNPAID),

                                Forms\Components\TextInput::make('shipping_number')
                                    ->label('Nomor Resi')
                                    ->visible(
                                        fn(Forms\Get $get): bool =>
                                        $get('payment_status') === OrderStatusService::PAYMENT_PAID &&
                                            ($get('status') === OrderStatusService::STATUS_SHIPPED ||
                                                $get('status') === OrderStatusService::STATUS_COMPLETED ||
                                                $get('status') === OrderStatusService::STATUS_CANCELLED
                                            )
                                    )
                                    ->required(
                                        fn(Forms\Get $get): bool =>
                                        $get('payment_status') === OrderStatusService::PAYMENT_PAID &&
                                            ($get('status') === OrderStatusService::STATUS_SHIPPED ||
                                                $get('status') === OrderStatusService::STATUS_COMPLETED ||
                                                $get('status') === OrderStatusService::STATUS_CANCELLED
                                            )
                                    )
                            ]),
                    ]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Pengiriman')
                            ->schema([
                                Forms\Components\Textarea::make('address_detail')
                                    ->disabled(),
                                Forms\Components\TextInput::make('shipping_code')
                                    ->disabled(),
                                Forms\Components\TextInput::make('shipping_service')
                                    ->disabled(),
                                Forms\Components\TextInput::make('shipping_description')
                                    ->disabled(),

                                Forms\Components\TextInput::make('shipping_etd')
                                    ->disabled(),

                                Forms\Components\TextInput::make('province')
                                    ->disabled(),
                                Forms\Components\TextInput::make('city')
                                    ->disabled(),
                                Forms\Components\TextInput::make('subdistrict')
                                    ->disabled(),

                                Forms\Components\Textarea::make('noted')
                                    ->disabled(),
                            ])
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable('desc'),
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('recipient_name')
                    ->label('Penerima')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('IDR')
                    ->label('Total')
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        OrderStatusService::PAYMENT_UNPAID => 'danger',
                        OrderStatusService::PAYMENT_PAID => 'success',
                    })
                    ->formatStateUsing(fn($state) => OrderStatusService::getPaymentStatusLabel($state)),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        OrderStatusService::STATUS_PENDING => 'warning',
                        OrderStatusService::STATUS_PROCESSING => 'info',
                        OrderStatusService::STATUS_SHIPPED => 'primary',
                        OrderStatusService::STATUS_COMPLETED => 'success',
                        OrderStatusService::STATUS_CANCELLED => 'danger',
                    })
                    ->formatStateUsing(fn($state) => OrderStatusService::getStatusLabel($state)),

                Tables\Columns\TextColumn::make('snap_token')
                    ->label("Payment Gatway")
                    ->searchable(),

                Tables\Columns\TextColumn::make('shipping_code')
                    ->label("Ekspedisi")
                    ->searchable(),


                Tables\Columns\TextColumn::make('shipping_number')
                    ->searchable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
