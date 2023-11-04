<?php

namespace App\Filament\Resources\PemilihResource\Pages;

use App\Filament\Resources\PemilihResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPemilihs extends ListRecords
{
    protected static string $resource = PemilihResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
