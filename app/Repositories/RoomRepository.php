<?php

namespace App\Repositories;

use App\Concern\Repository;
use App\Http\QueryFilters\RoomFilter;
use App\Models\Room\Room;
use App\Models\User\User;

class RoomRepository extends Repository
{
    /**
     * Construct
     *
     * @param Room|null $room
     */
    public function __construct(Room $room = null)
    {
        $this->model = $room ?? app(Room::class);
    }

    /**
     * Filtering the room based on the given user
     *
     * @param User $user
     * @return $this
     */
    public function forOwner(User $user)
    {
        $this->builder = $this->getQuery()
            ->where('user_id', $user->getKey());

        return $this;
    }

    /**
     * Filtering the room using the request filter
     *
     * @return $this
     */
    public function withRoomFilter()
    {
        $this->builder = $this->getQuery()->filter(RoomFilter::class);

        return $this;
    }

    /**
     * Ordering the room by the price field
     *
     * @return $this
     */
    public function orderByPrice()
    {
        if (!request()->filled('sort_attribute')) {
            $this->builder = $this->getQuery()->orderBy('price');
        }

        return $this;
    }
}
