<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->alpha()
                    ->maxLength(25),
                Forms\Components\Select::make('level')
                    ->required()
                    ->options([
                        'bébé' => 'bébé',
                        '1-2 ans' => '1-2 ans',
                        '2-3 ans' => '2-3 ans',
                        '3 ans' => '3 ans',
                        '4 ans' => '4 ans',
                        '5 ans' => '5 ans',
                    ]),
                Forms\Components\Select::make('staff_id')
                    ->required()
                    ->label('Staff')
                    ->relationship('staff', 'name')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('level')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('staff.name')->label('Staff')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
