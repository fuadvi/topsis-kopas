<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $minat = UserDetailResource::collection($this->result);
        return [
            "id" => $this->id,
            "nama" => $this->nama,
            "asal_sekolah" => $this->asal_sekolah,
            "jurusan" => $this->jurusan?->nama,
            "nis" => $this?->nis,
            "kelas" => $this?->class,
            "minat" => $minat->take(1),
//            "perhitungan" => $this->perhitungan
        ];
    }
}
