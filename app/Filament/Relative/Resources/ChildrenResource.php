<?php

namespace App\Filament\Relative\Resources;

use AnourValar\EloquentSerialize\Tests\Models\Post;
use App\Filament\Relative\Resources\ChildrenResource\Pages;
use App\Models\Student;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;



class ChildrenResource extends Resource
{
    protected static ?string $model = Student::class;




    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('relative_id', auth()->id());
    }
  public static function infolist(Infolist $infolist): Infolist
  {

      return $infolist
          ->schema([
              Components\Section::make()->schema([
                  Components\ImageEntry::make('image')


                      ->circular()
                      ->height(250)
                      ->defaultImageUrl(url('/img/default.jpg')),
                  Components\TextEntry::make('first_name')
                      ->columnSpan(2)
                      ->markdown(),
                  Components\TextEntry::make('last_name')
                      ,
                  Components\TextEntry::make('birth_date')
                      ->icon('heroicon-m-user')
                      ->label('Age')
                      ->getStateUsing(fn ($record) => Carbon::parse($record->birth_date)->diff(Carbon::now())->format('%y year(s), %m month(s)')),
                  Components\TextEntry::make('gender')
                      ->badge()
                      ->color(fn (string $state): string => match ($state) {
                          'boy' => 'success',
                          'girl' => 'danger',
                      }),
                  Components\TextEntry::make('payment_status')
                      ->icon('heroicon-m-banknotes')
                      ->iconColor(fn (string $state): string => match ($state) {
                          'Paid' => 'success',
                          'Overdue' => 'danger',
                          'Partial' => 'primary',
                      })
                      ->color(fn (string $state): string => match ($state) {
                          'Paid' => 'success',
                          'Overdue' => 'danger',
                          'Partial' => 'primary',

                      }),
                  Components\TextEntry::make('event_participation')
                        ->icon('heroicon-m-calendar-days')
                        ->iconColor(fn (string $state): string => match ($state) {
                            'No Event Participation yet' => 'danger',
                        })
                        ->default('No Event Participation yet')
                        ->color(fn (string $state): string => match ($state) {
                          'No Event Participation yet' => 'danger',

                      }),
                  Components\TextEntry::make('leave_with'),
                  Components\TextEntry::make('comments'),

              ])
          ]);

  }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')

                    ->label('')
                    ->circular()
                    ->defaultImageUrl(url('/img/default.jpg')),
                TextColumn::make('first_name'),
                TextColumn::make('last_name'),
                Tables\Columns\TextColumn::make('birth_date')
                    ->sortable()
                    ->label('Age')
                    ->getStateUsing(fn ($record) => Carbon::parse($record->birth_date)->diff(Carbon::now())->format('%y year(s), %m month(s)')),
                Tables\Columns\BadgeColumn::make('gender')
                    ->colors([
                        'blue' => 'boy',
                        'pink' => 'girl',
                    ])
                    ->sortable(),
            ])
            ->actions([
                ViewAction::make()
                    ->form([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
            ])
            ]);



    }
    public static function getAction(): void
    {
        Action::make('edit')
            ->label('Edit post')
            ->url(fn (Post $record): string => route('posts.edit', $record))
            ->icon('heroicon-s-pencil');
        ViewAction::make()
            ->form([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                // ...
            ]);
    }

    public static function getRelations(): array
    {
        return [


        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChildrens::route('/'),
            'view' => Pages\ViewChildren::route('/{record}/view'),
        ];
    }
}
