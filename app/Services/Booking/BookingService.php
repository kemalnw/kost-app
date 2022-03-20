<?php

namespace App\Services\Booking;

use Illuminate\Http\Request;
use App\Models\Booking\Booking;
use Illuminate\Support\Facades\DB;
use App\Repositories\RoomRepository;
use App\Repositories\BookingRepository;
use App\Exceptions\InsufficientFundsException;
use App\Exceptions\RoomNotAvailableException;

class BookingService
{
    /**
     * Construct
     *
     * @param BookingRepository $repository
     */
    public function __construct(
        protected BookingRepository $repository,
        protected RoomRepository $roomRepository
    )
    {}

    /**
     * Book a room for current active user
     *
     * @param Request $request
     * @return \App\Models\Booking\Booking
     */
    public function order(Request $request)
    {
        $room = $this->roomRepository->findById($request->room_id);
        $user = $request->user();
        $fee = Booking::BOOKING_FEE;

        if (!$user->hasEnoughBalance($fee)) {
            throw new InsufficientFundsException();
        }

        if ($room->unit <= 0) {
            throw new RoomNotAvailableException();
        }

        DB::beginTransaction();
        try {
            $booking = $this->repository->create([
                'user_id' => $user->getKey(),
                'price' => $room->price,
                'total_paid' => $room->price + $fee,
                ... $request->all()
            ]);

            $room->decrement('unit', 1);

            $user->decrement('balance', $fee);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $booking;
    }
}
