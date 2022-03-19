<?php

namespace App\Repositories;

use App\Concern\Repository;
use App\Models\Room\Room;

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
}
