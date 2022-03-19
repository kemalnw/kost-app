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

    /**
     * Create new room for current user
     *
     * @param Request $request
     * @return \App\Models\Room\Room
     */
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

    /**
     * Retrieve all rooms that related to current user
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\CursorPaginator
     */
    public function rooms(Request $request)
    {
        return $this->repository
            ->forOwner($request->user())
            ->cursorPaginate($request->limit);
    }

    /**
     * Update the specified room based on the given ID
     *
     * @param Request $request
     * @param integer $id
     * @return \App\Models\Room\Room
     */
    public function updateById(Request $request, int $id)
    {
        DB::beginTransaction();
        try {
            $room = $this->repository->updateById($request->except('user_id'), $id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $room;
    }
}
