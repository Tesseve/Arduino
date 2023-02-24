<?php

namespace App\Filament\Resources\PlayerResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PlayerResource;
use App\Filament\Traits\HasDescendingOrder;

class ListPlayers extends ListRecords
{
    use HasDescendingOrder;

    protected static string $resource = PlayerResource::class;
}
