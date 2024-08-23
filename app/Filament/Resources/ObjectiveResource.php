<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ObjectiveResource\Pages;
use App\Filament\Resources\ObjectiveResource\RelationManagers;
use App\Models\Objective;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ObjectiveResource extends Resource
{
    protected static ?string $model = Objective::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Textarea::make('description')
                    ->maxLength(500)
                    ->required()
                    ->label('Objective'),
                Forms\Components\DatePicker::make('target_date')
                    ->required(),
//                Forms\Components\Select::make('progress')
//                    ->options([
//                        'not started yet' => 'not started yet',
//                        'in progress' => 'in progress',
//                        'finished' => 'finished',
//                    ]),
//                Forms\Components\Select::make('course_id')
//                    ->label('Course')
//                    ->relationship('course', 'name')
//                    ->createOptionForm([
//                        Forms\Components\TextInput::make('name'),
//                        Forms\Components\Select::make('level')
//                            ->options([
//                                'bébé' => 'bébé',
//                                '1-2 ans' => '1-2 ans',
//                                '2-3 ans' => '2-3 ans',
//                                '3 ans' => '3 ans',
//                                '4 ans' => '4 ans',
//                                '5 ans' => '5 ans',
//                            ]),
//                        Forms\Components\Select::make('staff_id')
//                            ->label('Staff')
//                            ->relationship('staff', 'name')
//                    ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label('Objective')
                    ->limit('30')
                    ->searchable(),
                Tables\Columns\TextColumn::make('target_date')
                    ->sortable(),
                Tables\Columns\TextColumn::make('progress')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('course.name')->label('Course')

            ])
            ->filters([

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
            'index' => Pages\ListObjectives::route('/'),
            'create' => Pages\CreateObjective::route('/create'),
            'edit' => Pages\EditObjective::route('/{record}/edit'),
        ];
    }
}
