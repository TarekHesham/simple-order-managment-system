<?php

namespace App\Filament\Resources\Receives\Pages;

use App\Filament\Resources\Receives\ReceiveResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReceives extends ListRecords
{
    protected static string $resource = ReceiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
