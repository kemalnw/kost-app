<?php

namespace Tests\Feature\Room;

use Tests\TestCase;
use App\Models\User\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class AddRoomTest extends TestCase
{
    /** @test */
    public function owner_can_add_new_room()
    {
        $user = $this->loginAs(Role::OWNER);

        $room = [
            'name' => 'Kost Permata',
            'location' => 'Kota Surakarta',
            'price' => 500000,
            'number_rooms' => 10,
        ];

        $response = $this->postJson(route('owner.rooms.store'), $room);

        $response->assertCreated();

        $response->assertJson([
            'type' => 'rooms',
            'id' => $response->decodeResponseJson()['id'],
            'attributes' => [
                'name' => $room['name'],
                'price' => $room['price'],
                'location' => $room['location'],
            ],
            'relationships' => []
        ]);

        $this->assertDatabaseHas('rooms', [
            'user_id' => $user->getKey(),
            ...$room
        ]);
    }

    /** @test */
    public function only_owner_can_add_room()
    {
        $user = $this->loginAs(Role::REGULAR_USER);

        $room = [
            'name' => 'Kost Permata',
            'location' => 'Kota Surakarta',
            'price' => 500000,
            'number_rooms' => 10,
        ];

        $response = $this->postJson(route('owner.rooms.store'), $room);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseMissing('rooms', [
            'user_id' => $user->getKey()
        ]);
    }

    /** @test */
    public function ensure_the_required_fields_can_not_empty()
    {
        $user = $this->loginAs(Role::OWNER);

        $room = [
            'name' => 'Kost Permata',
            'location' => 'Kota Surakarta',
            'price' => 500000,
            'number_rooms' => 10,
        ];

        $this->postJson(
            route('owner.rooms.store'),
            Arr::except($room, [
                'location', 'price', 'number_rooms'
            ])
        )
        ->assertInvalid(['location', 'price', 'number_rooms']);

        $this->postJson(
            route('owner.rooms.store'),
            Arr::except($room, [
                'name', 'price', 'number_rooms'
            ])
        )
        ->assertInvalid(['name', 'price', 'number_rooms']);

        $this->postJson(
            route('owner.rooms.store'),
            Arr::except($room, [
                'name', 'location', 'number_rooms'
            ])
        )
        ->assertInvalid(['name', 'location', 'number_rooms']);

        $this->postJson(
            route('owner.rooms.store'),
            Arr::except($room, [
                'name', 'location', 'price'
            ])
        )
        ->assertInvalid(['name', 'location', 'price']);

        $this->assertDatabaseMissing('rooms', [
            'user_id' => $user->getKey()
        ]);
    }

    /** @test */
    public function invalid_input_data_should_return_errors()
    {
        $user = $this->loginAs(Role::OWNER);

        $room = [
            'name' => 'Kost Permata',
            'location' => 'Kota Surakarta',
            'price' => 500000,
            'number_rooms' => 10,
        ];

        $this->postJson(
            route('owner.rooms.store'),
            array_merge($room, ['name' => 123])
        )
        ->assertInvalid('name');

        $this->postJson(
            route('owner.rooms.store'),
            array_merge($room, ['name' => Str::random(354)])
        )
        ->assertInvalid('name');

        $this->postJson(
            route('owner.rooms.store'),
            array_merge($room, ['location' => 123])
        )
        ->assertInvalid('location');

        $this->postJson(
            route('owner.rooms.store'),
            array_merge($room, ['location' => Str::random(354)])
        )
        ->assertInvalid('location');

        $this->postJson(
            route('owner.rooms.store'),
            array_merge($room, ['price' => 'abcd'])
        )
        ->assertInvalid('price');

        $this->postJson(
            route('owner.rooms.store'),
            array_merge($room, ['price' => -1])
        )
        ->assertInvalid('price');

        $this->postJson(
            route('owner.rooms.store'),
            array_merge($room, ['number_rooms' => -1])
        )
        ->assertInvalid('number_rooms');

        $this->assertDatabaseMissing('rooms', [
            'user_id' => $user->getKey()
        ]);
    }
}
