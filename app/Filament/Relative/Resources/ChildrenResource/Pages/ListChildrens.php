<?php

namespace App\Filament\Relative\Resources\ChildrenResource\Pages;

use App\Filament\Relative\Resources\ChildrenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChildrens extends ListRecords
{
    protected static string $resource = ChildrenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
