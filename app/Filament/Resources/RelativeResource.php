<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RelativeResource\Pages;
use App\Filament\Resources\RelativeResource\RelationManagers;
use App\Models\Relative;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RelativeResource extends Resource
{
    protected static ?string $model = Relative::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('father_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mother_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_father')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_mother'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('address'),
                Tables\Columns\TextColumn::make('job_father'),
                Tables\Columns\TextColumn::make('job_mother'),
                Tables\Columns\TextColumn::make('cin_father'),
                Tables\Columns\TextColumn::make('cin_mother'),
                Tables\Columns\TextColumn::make('notes'),

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
            'index' => Pages\ListRelatives::route('/'),
            'create' => Pages\CreateRelative::route('/create'),
            'edit' => Pages\EditRelative::route('/{record}/edit'),
        ];
    }
}
