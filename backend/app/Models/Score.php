<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Score extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['value', 'mode', 'player_id'];

    protected $with = ['player'];

    protected $searchableFields = ['*'];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
