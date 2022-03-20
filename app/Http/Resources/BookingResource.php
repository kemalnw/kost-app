<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'type' => 'bookings',
            'id' => $this->getKey(),
            'attributes' => [
                'price' => $this->price,
                'total_paid' => $this->total_paid,
                'booking_date' => $this->booking_date->format('Y-m-d'),
            ],
            'relationships' => [],
        ];
    }
}
