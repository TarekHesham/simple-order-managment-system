<?php

namespace App\Filament\Resources\Receives\Pages;

use App\Filament\Resources\Receives\ReceiveResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateReceive extends CreateRecord
{
    protected static string $resource = ReceiveResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        $data['total_amount'] = $data['quantity'] * $data['unit_price'];
        return $data;
    }
}
