<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray($request) : array
    {
        return parent::toArray($request);
    }
}
