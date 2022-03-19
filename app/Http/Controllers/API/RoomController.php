<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
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
     * Retrieve all list rooms
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return RoomResource::collection(
            $this->service->rooms($request)
        );
    }
}
