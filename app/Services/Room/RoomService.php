<?php

namespace App\Services\Room;

use App\Repositories\RoomRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomService
{
    /**
     * Construct
     *
     * @param RoomRepository $repository
     */
    public function __construct(
        protected RoomRepository $repository
    )
    {}

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $room = $this->repository->create([
                'user_id' => $request->user()->getKey(),
                ...$request->all()
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $room;
    }
}
