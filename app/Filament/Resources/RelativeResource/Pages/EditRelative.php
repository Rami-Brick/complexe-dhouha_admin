<?php

namespace App\Filament\Resources\RelativeResource\Pages;

use App\Filament\Resources\RelativeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRelative extends EditRecord
{
    protected static string $resource = RelativeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
