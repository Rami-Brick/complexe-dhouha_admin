<?php

namespace App\Filament\Resources;

use App\Filament\Exports\StudentExporter;
use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                    ->relationship('relative', 'parent_name', function($query) {
                        return $query->select('id', \DB::raw('COALESCE(father_name, mother_name) as parent_name'))->orderBy('father_name', 'asc');
                    })
                    ->createOptionForm([
                        Forms\Components\TextInput::make('father_name')
                            ->string()
                            ->nullable()
                            ->requiredWithout('father_name,mother_name')
                            ->validationMessages([
                                'required_without' => 'Please provide at least one parent\'s name.',])
                        ,
                        Forms\Components\TextInput::make('mother_name')
                            ->string()
                            ->nullable(),
                        Forms\Components\TextInput::make('phone_father')
                            ->string()
                            ->length(8)
                            ->unique('relatives',column: 'phone_father')
                            ->nullable()
                            ->requiredWithout('phone_father,phone_mother')
                            ->validationMessages([
                                'required_without' => 'Please provide at least one parent\'s phone number.',]),
                        Forms\Components\TextInput::make('phone_mother')
                            ->string()
                            ->length(8)
                            ->unique('relatives',column: 'phone_mother'),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->unique('relatives',column: 'email'),
                        Forms\Components\TextInput::make('address')
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
                            ->unique('relatives',column: 'cin_father')
                            ->requiredWithout('cin_father,cin_mother')
                            ->validationMessages([
                                'required_without' => 'Please provide at least one parent\'s cin.',]),
                        Forms\Components\TextInput::make('cin_mother')
                            ->string()
                            ->length(8)
                            ->unique('relatives',column: 'cin_mother')
                            ->alphaNum(),


                        Forms\Components\TextInput::make('notes')
                            ->nullable(),
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
            ->striped()
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(url('/img/default.jpg')),

                Tables\Columns\TextColumn::make('first_name')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('birth_date')
                    ->sortable()
                    ->label('Age')
                    ->getStateUsing(fn ($record) => Carbon::parse($record->birth_date)->diff(Carbon::now())->format('%y year(s), %m month(s)')),
                Tables\Columns\TextColumn::make('course.name')->label('Course')
                ->sortable()
                ->searchable(),
                Tables\Columns\BadgeColumn::make('gender')
                    ->colors([
                        'blue' => 'boy',
                        'pink' => 'girl',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('relative.parent_name')->label('Relative'),
                Tables\Columns\TextColumn::make('payment_status')
                ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'boy' => 'boy',
                        'girl' => 'girl',
                    ]),
                Tables\Filters\SelectFilter::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'name')
                    ->options(function () {
                        return \App\Models\Course::all()->pluck('name', 'id');
                    }),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'Paid'=>'Paid',
                        'Overdue'=>'Overdue',
                        'Partial'=>'Partial'
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make('Excel')->exporter(StudentExporter::class)
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
