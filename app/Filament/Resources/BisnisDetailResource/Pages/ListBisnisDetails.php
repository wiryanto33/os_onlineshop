<?php

namespace App\Filament\Resources\BisnisDetailResource\Pages;

use App\Filament\Resources\BisnisDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBisnisDetails extends ListRecords
{
    protected static string $resource = BisnisDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
