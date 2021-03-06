<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
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
            'type' => 'users',
            'id' => $this->getKey(),
            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,
                'balance' => $this->when(
                    Auth::check() && Auth::id() === $this->getKey(),
                    function() {
                        return ;
                    }
                ),
            ],
            'relationships' => [],
        ];
    }
}
