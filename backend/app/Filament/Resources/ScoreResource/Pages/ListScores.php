<?php

namespace App\Filament\Resources\ScoreResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ScoreResource;
use App\Filament\Traits\HasDescendingOrder;

class ListScores extends ListRecords
{
    use HasDescendingOrder;

    protected static string $resource = ScoreResource::class;
}
