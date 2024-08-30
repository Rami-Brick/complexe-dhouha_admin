<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\Configs;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;
    protected static string $view='vendor.filament.resources.students.edit-students';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public function assignCourse()
    {
        return Action::make('assignCourse')
            ->modal()
            ->form([
                Select::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'name'),
                CheckboxList::make('products')
                    ->options(function () {
                        return Configs::query()
                            ->where('name', '!=', 'Inscription')
                            ->where('name', '!=', 'Scholarship')
                            ->pluck('name', 'name')
                            ->toArray();
                    }),
            ])
            ->action(function (array $data) {
                Notification::make()
                    ->success()
                    ->title($data['products'])
                    ->send();
            });

    }
}
