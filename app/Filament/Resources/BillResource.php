<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BillResource\Pages;
use App\Forms\Components\Stepper;
use App\Models\Configs;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use App\Models\Bill;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class BillResource extends Resource
{
    protected static ?string $model = Bill::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
//                 Forms\Components\Select::make('student_id')
//                     ->label('Student')
//                     ->required()
//                     ->relationship('student', 'first_name')
//                     ->afterStateUpdated(function ($state, callable $set) {
//                                     $student = \App\Models\Student::find($state);
//                                     if ($student) {
//                                         $set('student_name', $student->first_name . ' ' . $student->last_name);
//                                     } else {
//                                         $set('student_name', null);
//                                     }
//                                 }),

                Forms\Components\TextInput::make('student_name')
                    ->disabled('true')
                    ->label('Student'),

                Forms\Components\DatePicker::make('issue_date')
                    ->label('Issue Date')
                    ->default(now()->format('Y-m-d')),


                Forms\Components\Placeholder::make('products')
                    ->disabled('true')
                    ->label('Products')
                    ->content(function (Bill|null $bill) {
                        return $bill ? implode(', ', collect($bill->products)->pluck('name', 'name')->toArray()) : '';
                    }),


                Forms\Components\TextInput::make('amount')
                    ->label('Total Amount')
                    ->disabled()
                    ->dehydrated(true),

                Forms\Components\TextInput::make('paid_amount')
                    ->numeric()
                    ->extraInputAttributes(['step' => '5'])
                    ->default(null)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $amount = $get('amount');
                        if ($state === null || $state < $amount) {
                            $status = 'Partial';
                        } elseif ($state >= $amount) {
                            $status = 'Paid';
                        }
                        $set('status', $status);
                    }),

                Forms\Components\TextInput::make('status')
                    ->disabled()
                    ->dehydrated(true),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->query(Bill::query()->orderBy('reference', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('student_name')->Label('Student')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('issue_date')
                    ->date('F Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_amount')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                  ->colors([
                            'green' => 'Paid',
                            'red' => 'not paid',
                            'orange' => 'Partial',
                                ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Paid'=>'Paid',
                        'Overdue'=>'Overdue',
                        'Partial'=>'Partial'
                    ]),
            ])
            ->actions([

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
//             'create' => Pages\CreateBill::route('/create'),
            'edit' => Pages\EditBill::route('/{record}/edit'),
        ];
    }
}
