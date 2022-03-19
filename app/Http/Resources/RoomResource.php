<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'type' => 'rooms',
            'id' => $this->getKey(),
            'attributes' => [
                'name' => $this->name,
                'price' => $this->price,
                'location' => $this->location,
            ],
        ];
    }
}
