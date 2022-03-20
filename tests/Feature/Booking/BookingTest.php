<?php

namespace Tests\Feature\Booking;

use App\Models\Booking\Booking;
use App\Models\Room\Room;
use App\Models\User\Role;
use App\Models\User\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class BookingTest extends TestCase
{
    /** @test */
    public function user_can_book_available_room()
    {
        $user = $this->loginAs(Role::PREMIUM_USER);

        $room = Room::factory()->for(User::factory(), 'owner')->create();

        $expectedBalance = $user->balance - Booking::BOOKING_FEE;

        $response = $this->postJson(route('booking.store'), [
            'room_id' => $room->getKey(),
            'booking_date' => date('Y-m-d'),
        ]);

        $response->assertJson([
            'type' => 'bookings',
            'id' => $response->decodeResponseJson()['id'],
            'attributes' => [
                'price' => $room->price,
                'total_paid' => $room->price + Booking::BOOKING_FEE,
                'booking_date' => date('Y-m-d'),
            ],
            'relationships' => []
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'balance' => $expectedBalance,
        ]);
    }

    /** @test */
    public function owner_should_not_book_his_room()
    {
        $user = $this->loginAs(Role::OWNER);

        $room = Room::factory()->for($user, 'owner')->create();

        $expectedBalance = $user->balance;

        $response = $this->postJson(route('booking.store'), [
            'room_id' => $room->getKey(),
            'booking_date' => date('Y-m-d'),
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'balance' => $expectedBalance,
        ]);
    }

    /** @test */
    public function user_should_have_enough_balance()
    {
        $user = $this->loginAs(Role::REGULAR_USER, [], false);

        $room = Room::factory()->for(User::factory(), 'owner')->create();

        $expectedBalance = $user->balance;

        $response = $this->postJson(route('booking.store'), [
            'room_id' => $room->getKey(),
            'booking_date' => date('Y-m-d'),
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'balance' => $expectedBalance,
        ]);
    }

    /** @test */
    public function user_should_only_book_available_room()
    {
        $user = $this->loginAs(Role::PREMIUM_USER);

        $room = Room::factory()->for(User::factory(), 'owner')->create(['unit' => 0]);

        $expectedBalance = $user->balance;

        $response = $this->postJson(route('booking.store'), [
            'room_id' => $room->getKey(),
            'booking_date' => date('Y-m-d'),
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'balance' => $expectedBalance,
        ]);
    }

    /** @test */
    public function invalid_input_should_return_errors()
    {
        $user = $this->loginAs(Role::REGULAR_USER);

        $room = Room::factory()->for(User::factory(), 'owner')->create();

        $expectedBalance = $user->balance;

        $this->postJson(route('booking.store'), [
            'room_id' => 354,
            'booking_date' => date('Y-m-d'),
        ])->assertInvalid('room_id');

        $this->postJson(route('booking.store'), [
            'room_id' => $room->getKey(),
            'booking_date' => date('d-m-Y'),
        ])->assertInvalid('booking_date');

        $this->postJson(route('booking.store'), [
            'room_id' => $room->getKey(),
            'booking_date' => 'Kemal',
        ])->assertInvalid('booking_date');

        $this->assertDatabaseHas('users', [
            'id' => $user->getKey(),
            'balance' => $expectedBalance,
        ]);
    }
}
