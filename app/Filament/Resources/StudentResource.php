<?php

namespace App\Filament\Resources;

use App\Filament\Exports\StudentExporter;
use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Relative;
use App\Models\Student;
use Carbon\Carbon;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->alpha()
                    ->maxLength(25)
                    ->requiredWithAll('last_name,birth_date,gender,payment_status'),

                Forms\Components\TextInput::make('last_name')
                    ->alpha()
                    ->maxLength(25)
                    ->requiredWithAll('first_name,birth_date,gender,payment_status'),

                Forms\Components\DatePicker::make('birth_date'),
                Forms\Components\Select::make('gender')
                ->required()
                ->options([
                    'boy'=>'boy',
                    'girl'=>'girl'
                    ]),
                

                Forms\Components\Select::make('course_id')
                    ->required()
                    ->label('Course')
                    ->relationship('course', 'name'),

                Forms\Components\Select::make('relative_id')
                    ->label('Relative')
                    ->relationship('relative')
                    ->searchable()
                    ->options(
                        Relative::limit(5)->whereDoesntHave('students')->get()
                        ->mapWithKeys(function ($relative) {
                            return [$relative->id => trim($relative->father_name . ', ' . $relative->mother_name)];
                        })
                    )
                    ->getSearchResultsUsing(function ($query, string|null$search) {
                        return Relative::query()
                            ->where(function ($query) use ($search) {
                                $query->where('father_name', 'like', '%' . $search . '%')
                                    ->orWhere('mother_name', 'like', '%' . $search . '%');
                            })->limit(5)
                            ->get()
                            ->mapWithKeys(function ($relative) {
                                return [$relative->id => trim($relative->father_name . ', ' . $relative->mother_name)];
                            });
                    })
                    ->getOptionLabelUsing(function ($value) {
                        $relative = Relative::findOrFail($value);
                        return trim($relative->father_name . ', ' . $relative->mother_name);
                    })
                    ->createOptionForm([
                        Forms\Components\TextInput::make('father_name')
                            ->nullable()
                            ->requiredWithout('mother_name')
                            ->alpha()
                            ->maxLength(25)
                            ->validationMessages([
                                'required_without' => 'Please provide at least one parent\'s name.'])
                            ->requiredWithAll('father_name,phone_father,email,cin_father,job_father'),

                        Forms\Components\TextInput::make('mother_name')
                            ->nullable()
                            ->alpha()
                            ->maxLength(25)
                            ->requiredWithout('father_name')
                            ->validationMessages([
                                'required_without' => 'Please provide at least one parent\'s name.'])
                            ->requiredWithAll('mother_name,phone_mother,email,cin_mother'),

                        Forms\Components\TextInput::make('phone_father')
                            ->length(8)
                            ->unique('relatives',column: 'phone_father',ignoreRecord: true)
                            ->nullable()
                            ->alphaNum()
                            ->requiredWithAll('father_name,email,cin_father')
                            ->requiredWithout('phone_mother')
                            ->validationMessages([
                                'required_without' => 'Please provide at least one parent\'s phone number.']),

                        Forms\Components\TextInput::make('phone_mother')
                            ->nullable()
                            ->length(8)
                            ->alphaNum()
                            ->unique('relatives',column:'phone_mother',ignoreRecord: true )
                            ->requiredWithout('phone_father'),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique('relatives',column: 'email',ignoreRecord: true),

                        Forms\Components\TextInput::make('address')
                            ->required()
                            ->maxLength(100)
                            ->string(),

                        Forms\Components\TextInput::make('job_father')
                            ->alpha()
                            ->maxLength(25)
                            ->requiredWithAll('father_name,phone_father,email,cin_father,job_father'),

                        Forms\Components\TextInput::make('job_mother')
                            ->maxLength(25)
                            ->alpha()
                            ->required('mother_name,phone_mother,email,cin_mother,job_mother'),

                        Forms\Components\TextInput::make('cin_father')
                            ->length(8)
                            ->alphaNum()
                            ->unique('relatives',column:'cin_father',ignoreRecord: true )
                            ->requiredWithout('cin_mother')
                            ->validationMessages([
                                'required_without' => 'Please provide at least one parent\'s cin.']),

                        Forms\Components\TextInput::make('cin_mother')
                            ->length(8)
                            ->unique('relatives',column: 'cin_mother',ignoreRecord: true)
                            ->alphaNum()
                            ->requiredWithout('cin_father')
                            ->validationMessages([
                                'required_without' => 'Please provide at least one parent\'s cin.']),

                        Forms\Components\TextInput::make('notes')
                            ->nullable()
                            ->maxLength(500),
                    ]),

                Forms\Components\Select::make('payment_status')
                    ->required()
                    ->options([
                        'Paid'=>'Paid',
                        'Overdue'=>'Overdue',
                        'Partial'=>'Partial',
                ]),
                Forms\Components\Textarea::make('comments')
                    ->maxLength(500)
                    ->nullable(),
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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creation Date')
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
            ->headerActions([
                Tables\Actions\ExportAction::make('Excel')
                    ->exporter(StudentExporter::class)
                    ->formats([
                        ExportFormat::Xlsx
                    ])
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                Tables\Actions\ExportBulkAction::make('Excel')->exporter(StudentExporter::class),
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
