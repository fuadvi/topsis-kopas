<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerhitunganRecource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array
    {
        return [
            "position" => $this?->position ?? '',
            "description" => $this?->description ?? '',
            "calculation" =>  json_decode($this?->calculation?? '') ?? [],
            "metode" => $this?->metode ?? '',
        ];
    }
}
