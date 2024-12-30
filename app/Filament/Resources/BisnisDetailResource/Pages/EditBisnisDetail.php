<?php

namespace App\Filament\Resources\BisnisDetailResource\Pages;

use App\Filament\Resources\BisnisDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBisnisDetail extends EditRecord
{
    protected static string $resource = BisnisDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
