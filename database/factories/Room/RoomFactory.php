<?php

namespace Database\Factories\Room;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->name(),
            'price' => $this->faker->numberBetween(1000, 1000000),
            'location' => $this->faker->address(),
            'number_rooms' => $this->faker->randomDigitNotZero(),
        ];
    }
}
