<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BillResource\Pages;
use App\Filament\Resources\BillResource\RelationManagers;
use App\Models\Bill;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BillResource extends Resource
{
    protected static ?string $model = Bill::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->label('Student')
                    ->relationship('student', 'first_name'),
                Forms\Components\DatePicker::make('due_date'),
                Forms\Components\TextInput::make('products'),
                Forms\Components\TextInput::make('amount'),
                Forms\Components\TextInput::make('paid_amount'),
                Forms\Components\Select::make('status')
                    ->options([
                        'Paid'=>'Paid',
                        'Overdue'=>'Overdue',
                        'Partial'=>'Partial'
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.first_name')->Label('Student'),
                Tables\Columns\TextColumn::make('due_date'),
                Tables\Columns\TextColumn::make('products'),
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\TextColumn::make('paid_amount'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('reference')
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
            'index' => Pages\ListBills::route('/'),
            'create' => Pages\CreateBill::route('/create'),
            'edit' => Pages\EditBill::route('/{record}/edit'),
        ];
    }
}
