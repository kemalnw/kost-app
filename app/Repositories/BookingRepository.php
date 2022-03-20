<?php

namespace App\Repositories;

use App\Concern\Repository;
use App\Models\Booking\Booking;

class BookingRepository extends Repository
{
    /**
     * Construct
     *
     * @param Booking|null $booking
     */
    public function __construct(Booking $booking = null)
    {
        $this->model = $booking ?? app(Booking::class);
    }
}
