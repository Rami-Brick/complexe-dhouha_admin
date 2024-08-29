<?php

namespace App\Filament\Relative\Resources\ChildrenResource\Pages;

use App\Filament\Relative\Resources\ChildrenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ViewRecord;

class ViewChildren extends ViewRecord
{
    protected static string $resource = ChildrenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
            ->label('Edit Children'),
        ];
    }
}
