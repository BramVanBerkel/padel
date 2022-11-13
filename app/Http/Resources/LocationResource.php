<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Location */
class LocationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'uuid' => $this->uuid,
        ];
    }
}
