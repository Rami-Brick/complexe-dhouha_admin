<?php

namespace App\Filament\Resources;

use AnourValar\EloquentSerialize\Tests\Models\Post;
use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                ->options([
                    'boy'=>'boy',
                    'girl'=>'girl'
                    ]),

                Forms\Components\Select::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'name'),

                Forms\Components\Select::make('relative_id')
                    ->label('Relative')
                    ->relationship('relative', 'father_name')
                    ->createOptionForm([


                        Forms\Components\TextInput::make('father_name')
                            ->nullable()
                            ->requiredWithout('mother_name')
                            ->alpha()
                            ->maxLength(25)
                            ->validationMessages([
                                'required_without' => 'Please provide at least one parent\'s name.'])
                            ->requiredWithAll('father_name,phone_father,email,cin_father'),

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
                            ->nullable()
                            ->maxLength(100)
                            ->string(),

                        Forms\Components\TextInput::make('job_father')
                            ->alpha()
                            ->maxLength(25)
                            ->nullable(),

                        Forms\Components\TextInput::make('job_mother')
                            ->maxLength(25)
                            ->alpha()
                            ->nullable(),

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
                ->options([
                    'Paid'=>'Paid',
                    'Overdue'=>'Overdue',
                    'Partial'=>'Partial'
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
                Tables\Columns\TextColumn::make('relative.father_name')->label('Relative'),
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
                Tables\Actions\EditAction::make('edit'),
                Tables\Actions\DeleteAction::make('delete')
                    ->action(fn (Post $record) => $record->delete())
                    ->requiresConfirmation()
                    ->modalHeading('Delete post')
                    ->modalDescription('Are you sure you\'d like to delete this post? This cannot be undone.')
                    ->modalSubmitActionLabel('Yes, delete it'),
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
