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
        $calculation = json_decode($this?->calculation?? '') ?? [];

        if ( $this?->position == 10 and !empty($calculation))
        {
            $calculation =  collect($calculation)->map(fn($perhitungan) => (object)[
                "jurusan" => $perhitungan->jurusan,
                "score" => $perhitungan->score / 3,
            ]);
        }

        return [
            "position" => $this?->position ?? '',
            "description" => $this?->description ?? '',
            "calculation" =>  $calculation ?? [],
            "metode" => $this?->metode ?? '',
        ];
    }
}


