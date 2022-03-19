<?php

namespace App\Http\Controllers\API\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\DeleteRequest;
use App\Http\Requests\Room\ShowRequest;
use App\Http\Requests\Room\StoreRequest;
use App\Http\Requests\Room\UpdateRequest;
use App\Http\Resources\RoomResource;
use App\Models\Room\Room;
use App\Services\Room\RoomService;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Construct
     *
     * @param RoomService $service
     */
    public function __construct(
        protected RoomService $service
    )
    {}

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return RoomResource::collection(
            $this->service->rooms($request)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        RoomResource::withoutWrapping();

        return RoomResource::make(
            $this->service->store($request)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  ShowRequest  $request
     * @param  Room $room
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request, Room $room)
    {
        RoomResource::withoutWrapping();

        return RoomResource::make($room);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  Room $room
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Room $room)
    {
        RoomResource::withoutWrapping();

        return RoomResource::make(
            $this->service->updateById($request, $room->getKey())
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeleteRequest $request
     * @param  Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRequest $request, Room $room)
    {
        if ($this->service->removeById($room->getKey())) {
            return $this->generateResponse('Successfully deleted the Room.');
        }

        return $this->generateErrorResponse('Something went wrong.');
    }
}
