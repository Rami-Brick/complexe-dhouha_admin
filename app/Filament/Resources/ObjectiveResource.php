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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')                ,
                Forms\Components\DatePicker::make('target_date'),
                Forms\Components\TextInput::make('progress'),
                Forms\Components\Select::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('target_date'),
                Tables\Columns\TextColumn::make('progress'),
                Tables\Columns\TextColumn::make('course.name')->label('Course')

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
            'index' => Pages\ListObjectives::route('/'),
            'create' => Pages\CreateObjective::route('/create'),
            'edit' => Pages\EditObjective::route('/{record}/edit'),
        ];
    }
}
