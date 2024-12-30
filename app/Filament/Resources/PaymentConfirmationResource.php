<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentConfirmationResource\Pages;
use App\Filament\Resources\PaymentConfirmationResource\RelationManagers;
use App\Models\PaymentConfirmation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;


class PaymentConfirmationResource extends Resource
{
    protected static ?string $model = PaymentConfirmation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_number')
                    ->label('Order Number')
                    ->formatStateUsing(fn($record) => $record->order->order_number)
                    ->disabled()
                    ->dehydrated(false),

                Forms\Components\TextInput::make('phone')
                    ->label('Phone')
                    ->formatStateUsing(fn($record) => $record->order->phone)
                    ->disabled()
                    ->dehydrated(false),

                Forms\Components\TextInput::make('payment_method_id')
                    ->formatStateUsing(fn($record) => $record->paymentMethod->name)
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\TextInput::make('source_bank_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('source_account_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->image(),
                Forms\Components\DatePicker::make('transfer_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order.phone')
                    ->label('Phone')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paymentMethod.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('source_bank_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('source_account_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('transfer_date')
                    ->date()
                    ->sortable(),
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
                Action::make('approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(
                        fn(PaymentConfirmation $record): bool =>
                        !$record->is_approve && $record->order->payment_status == 'unpaid'
                    )
                    ->action(function (PaymentConfirmation $record): void {
                        $record->order->update([
                            'payment_status' => 'paid',
                            'status' => 'processing',
                        ]);

                        Notification::make()
                            ->title('Payment Approved Successfully')
                            ->success()
                            ->send();
                    }),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentConfirmations::route('/'),
            'create' => Pages\CreatePaymentConfirmation::route('/create'),
            'view' => Pages\ViewPaymentConfirmation::route('/{record}'),
            // 'edit' => Pages\EditPaymentConfirmation::route('/{record}/edit'),
        ];
    }
}
