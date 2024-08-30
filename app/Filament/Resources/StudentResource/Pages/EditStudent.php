<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\Configs;
use App\Models\Course;
use App\Models\Product;
use App\Repositories\StudentRepository;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
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
                    ->label(__('course.course'))
                    ->placeholder(__('course.no-course'))
                    ->relationship('course', 'name')
                    ->default( $this->record->course_id ),
                Checkbox::make('generate')
                    ->label('Generate registration bill'),

                CheckboxList::make('options')
                    ->options(Product::list(Product::TYPE_OPTION))
                    ->default($this->record->options()->pluck('products.id')->toArray()),
                DatePicker::make('start')
                    ->label('Starting Date')
                    ->default($this->record->start_date),


            ])
            ->action(function (array $data) {
                $studentRepository = new StudentRepository($this->record);
                if(!$data['course_id']) {
                    $studentRepository->unAssignCourse();
                    return;
                }

                $course = Course::query()->findOrFail($data['course_id']);
                $optionIds = $data['options'];
                $courseStartDate = $data['start'];
                $studentRepository->assignCourse($course, $courseStartDate, $optionIds);

                $generateBill = $data['generate'];
                if ($generateBill) {
                    $studentRepository->generateRegistrationBill();
                }
            });

    }
}
