<?php

namespace App\Filament\Resources\RajaOngkirSettingResource\Pages;

use App\Filament\Resources\RajaOngkirSettingResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRajaOngkirSetting extends EditRecord
{
    protected static string $resource = RajaOngkirSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $isValid = $this->record->validateApiKey();

        if ($isValid) {
            Notification::make()
                ->title('Api Key is Valid')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Api Key is  inValid')
                ->danger()
                ->body($record->error_message ?? 'Unknown error')
                ->send();
        }
    }
}
