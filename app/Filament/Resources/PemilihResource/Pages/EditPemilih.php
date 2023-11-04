<?php

namespace App\Filament\Resources\PemilihResource\Pages;

use App\Filament\Resources\PemilihResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPemilih extends EditRecord
{
    protected static string $resource = PemilihResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
