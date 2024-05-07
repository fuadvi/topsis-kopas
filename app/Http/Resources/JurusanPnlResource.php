<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JurusanPnlResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'criteria' => $this->criteria->map(
                fn($item) => BobotCriteriaResource::make($item->criteria)
            ),
            'subject' => $this->subject->map(
                fn($item) => SubjectResource::make($item)
            )
        ];
    }
}
