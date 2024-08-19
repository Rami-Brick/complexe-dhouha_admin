<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name'),
                Forms\Components\TextInput::make('last_name'),
                Forms\Components\DatePicker::make('birth_date'),
                Forms\Components\Select::make('gender')
                ->options([
                    'boy'=>'boy',
                    'girl'=>'girl'
                    ]),

                Forms\Components\Select::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'name'),

                Forms\Components\Select::make('relative_id')
                    ->label('Relative')
                    ->relationship('relative', 'father_name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('father_name'),
                        Forms\Components\TextInput::make('mother_name'),
                        Forms\Components\TextInput::make('phone_father'),
                        Forms\Components\TextInput::make('phone_mother'),
                        Forms\Components\TextInput::make('email'),
                        Forms\Components\TextInput::make('address'),
                        Forms\Components\TextInput::make('job_father'),
                        Forms\Components\TextInput::make('job_mother'),
                        Forms\Components\TextInput::make('cin_father'),
                        Forms\Components\TextInput::make('cin_mother'),
                        Forms\Components\TextInput::make('notes'),
                    ]),

                Forms\Components\Select::make('payment_status')
                ->options([
                    'Paid'=>'Paid',
                    'Overdue'=>'Overdue',
                    'Partial'=>'Partial'
                ]),
                Forms\Components\TextInput::make('comments'),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('students')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name'),
                Tables\Columns\TextColumn::make('last_name'),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Profile Picture')
                    ->circular()
                    ->defaultImageUrl(url('/img/default.jpg')),

                Tables\Columns\TextColumn::make('birth_date'),
                Tables\Columns\TextColumn::make('course.name')->label('Course'),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('relative.father_name')->label('Relative'),
                Tables\Columns\TextColumn::make('payment_status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
