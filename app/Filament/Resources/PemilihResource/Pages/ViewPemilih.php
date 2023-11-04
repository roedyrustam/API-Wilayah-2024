<?php

namespace App\Filament\Resources\PemilihResource\Pages;

use App\Filament\Resources\PemilihResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPemilih extends ViewRecord
{
    protected static string $resource = PemilihResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
