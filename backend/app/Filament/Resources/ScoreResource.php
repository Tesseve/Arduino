<?php

namespace App\Filament\Resources;

use App\Models\Score;
use Filament\{Tables, Forms};
use Filament\Resources\{Form, Table, Resource};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Filters\DateRangeFilter;
use App\Filament\Resources\ScoreResource\Pages;

class ScoreResource extends Resource
{
    protected static ?string $model = Score::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $recordTitleAttribute = 'mode';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()->schema([
                Grid::make(['default' => 0])->schema([
                    TextInput::make('value')
                        ->rules(['max:255'])
                        ->required()
                        ->placeholder('Value')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                    TextInput::make('mode')
                        ->rules(['max:255', 'string'])
                        ->required()
                        ->placeholder('Mode')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                    Select::make('player_id')
                        ->rules(['exists:players,id'])
                        ->required()
                        ->relationship('player', 'name')
                        ->searchable()
                        ->placeholder('Player')
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('60s')
            ->columns([
                Tables\Columns\TextColumn::make('value')
                    ->toggleable()
                    ->searchable(true, null, true)
                    ->limit(50),
                Tables\Columns\TextColumn::make('mode')
                    ->toggleable()
                    ->searchable(true, null, true)
                    ->limit(50),
                Tables\Columns\TextColumn::make('player.name')
                    ->toggleable()
                    ->limit(50),
            ])
            ->filters([
                DateRangeFilter::make('created_at'),

                SelectFilter::make('player_id')
                    ->relationship('player', 'name')
                    ->indicator('Player')
                    ->multiple()
                    ->label('Player'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScores::route('/'),
            'create' => Pages\CreateScore::route('/create'),
            'view' => Pages\ViewScore::route('/{record}'),
            'edit' => Pages\EditScore::route('/{record}/edit'),
        ];
    }
}
