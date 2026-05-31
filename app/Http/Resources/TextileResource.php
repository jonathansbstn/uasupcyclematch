<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TextileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'weight'      => (float) $this->weight,
            'address'     => $this->address,
            'latitude'    => (float) $this->latitude,
            'longitude'   => (float) $this->longitude,
            'image'       => $this->image ? asset('storage/' . $this->image) : null,
            'status'      => $this->status,
            'fabric_type' => $this->fabric_type,
            'owner'       => $this->whenLoaded('owner', function () {
                return [
                    'id'        => $this->owner->id,
                    'name'      => $this->owner->name,
                    'whatsapp'  => $this->owner->whatsapp,
                    'address'   => $this->address,
                ];
            }),
        ];
    }
}
