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



class RelativeResource extends Resource
{
    protected static ?string $model = Relative::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('father_name')
                    ->nullable()
                    ->requiredWithout('mother_name')
                    ->validationMessages([
                        'required_without' => 'Please provide at least one parent\'s name.'])
                    ->requiredWithAll('father_name,phone_father,email,cin_father'),

                Forms\Components\TextInput::make('mother_name')
                    ->nullable()
                    ->requiredWithout('father_name')
                    ->validationMessages([
                        'required_without' => 'Please provide at least one parent\'s name.'])
                    ->requiredWithAll('mother_name,phone_mother,email,cin_mother'),

                Forms\Components\TextInput::make('phone_father')
                    ->length(8)
                    ->unique('relatives',column: 'phone_father',ignoreRecord: true)
                    ->nullable()
                    ->requiredWithout('phone_mother')
                    ->validationMessages([
                        'required_without' => 'Please provide at least one parent\'s phone number.']),

                Forms\Components\TextInput::make('phone_mother')
                    ->nullable()
                    ->length(8)

                    ->unique('relatives',column:'phone_mother',ignoreRecord: true )

                    ->requiredWithout('phone_father'),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->unique('relatives',column: 'email',ignoreRecord: true),

                Forms\Components\TextInput::make('address')
                    ->nullable()
                    ->string(),

                Forms\Components\TextInput::make('job_father')
                    ->string()
                    ->nullable(),

                Forms\Components\TextInput::make('job_mother')
                    ->string()
                    ->nullable(),

                Forms\Components\TextInput::make('cin_father')
                    ->string()
                    ->length(8)
                    ->alphaNum()
                    ->unique('relatives',column:'cin_father',ignoreRecord: true )
                    ->requiredWithout('cin_mother')
                    ->validationMessages([
                        'required_without' => 'Please provide at least one parent\'s cin.']),

                Forms\Components\TextInput::make('cin_mother')
                    ->string()
                    ->length(8)
                    ->unique('relatives',column: 'cin_mother',ignoreRecord: true)
                    ->alphaNum()
                    ->requiredWithout('cin_father')
                    ->validationMessages([
                        'required_without' => 'Please provide at least one parent\'s cin.']),

                Forms\Components\TextInput::make('notes')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('father_name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mother_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_father')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_mother')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('job_father')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('job_mother')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cin_father')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cin_mother')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('notes'),

            ])
            ->filters([

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
