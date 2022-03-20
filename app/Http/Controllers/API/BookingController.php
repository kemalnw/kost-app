<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreRequest;
use App\Http\Resources\BookingResource;
use App\Services\Booking\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Construct
     *
     * @param BookingService $service
     */
    public function __construct(
        protected BookingService $service
    )
    {}

    /**
     * Booking a room for the user
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        BookingResource::withoutWrapping();

        return BookingResource::make(
            $this->service->order($request)
        );
    }
}
