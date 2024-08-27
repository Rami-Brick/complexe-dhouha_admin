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
                Forms\Components\Select::make('student_id')
                    ->label('Student')
                    ->required()
                    ->relationship('student', 'first_name'),
                Forms\Components\DatePicker::make('due_date')
                ->required(),

                Forms\Components\Select::make('products')
                    ->multiple()
                    ->options(function () {
                        return Configs::pluck('name', 'name')->toArray();
                    })
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $totalFee = collect($state)->sum(function ($product) {
                            $fee = Configs::where('name', $product)->value('value');
                            return $fee ?: 0;
                        });
                        $set('amount', $totalFee);
                        $set('paid_amount', $totalFee);
                        $set('status', 'Paid');
                    }),

                Forms\Components\TextInput::make('amount')
                    ->disabled()
                    ->dehydrated(true)
                    ,

                Forms\Components\TextInput::make('paid_amount')
                    ->numeric()
                    ->extraInputAttributes(['step' => '10'])
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $amount = $get('amount');
                        if ($state >= $amount) {
                            $status = 'Paid';
                        } else {
                            $status = 'Partial';
                        }
                        $set('status', $status);
                    }),

                Forms\Components\TextInput::make('status')
                    ->disabled()
                    ->dehydrated(true)
                ,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.first_name')->Label('Student')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->sortable(),
                Tables\Columns\TextColumn::make('products')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_amount')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
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
            ->headerActions([
                Action::make('Edit Fees')
                    ->label('Edit Billing Fees')
                    ->icon('heroicon-o-pencil')
                    ->action(function (array $data) {
                        $fees = collect($data['fees'])->pluck('amount', 'product')->toArray();
                        $configPath = config_path('billing.php');
                        File::put($configPath, "<?php\n\nreturn [\n    'fees' => " . var_export($fees, true) . ",\n];\n");
                    })
                    ->form([
                        Forms\Components\Repeater::make('fees')
                            ->schema([
                                Forms\Components\TextInput::make('product')
                                    ->label('Product Name')
                                    ->required(),
                                Forms\Components\TextInput::make('amount')
                                    ->label('Fee Amount')
                                    ->numeric()
                                    ->required(),
                            ])
                            ->defaultItems(count(config('billing.fees')))
                            ->default(function () {
                                $fees = config('billing.fees', []);
                                return collect($fees)->map(function ($amount, $product) {
                                    return [
                                        'product' => $product,
                                        'amount' => $amount,
                                    ];
                                })->values()->toArray();
                            })
                            ->minItems(1)
                            ->columns(2),
                    ])
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
            'create' => Pages\CreateBill::route('/create'),
            'edit' => Pages\EditBill::route('/{record}/edit'),
        ];
    }
}
