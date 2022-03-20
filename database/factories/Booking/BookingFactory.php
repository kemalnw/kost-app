<?php

namespace Database\Factories\Booking;

use App\Models\Room\Room;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'room_id' => Room::factory(),
            'user_id' => User::factory(),
            'price' => function($attribute) {
                return Room::find($attribute['room_id'])->price;
            },
            'total_paid' => function($attribute) {
                return Room::find($attribute['room_id'])->price;
            },
            'booking_date' => $this->faker->date(),
        ];
    }
}
