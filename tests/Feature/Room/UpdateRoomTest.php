<?php

namespace Tests\Feature\Room;

use Tests\TestCase;
use App\Models\Room\Room;
use App\Models\User\Role;
use App\Models\User\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class UpdateRoomTest extends TestCase
{
    /** @test */
    public function owner_can_update_his_room()
    {
        $user = $this->loginAs(Role::OWNER);

        $room = Room::factory()->for($user, 'owner')->create([
            'name' => 'Kost Indah'
        ]);

        $updatedRoom = [
            'name' => 'Kost Permata',
            'location' => 'Kota Surakarta',
            'price' => 500000,
            'unit' => 10,
        ];

        $response = $this->putJson(
            route('owner.rooms.update', ['room' => $room->getKey()]),
            $updatedRoom);

        $response->assertOk();

        $response->assertJson([
            'type' => 'rooms',
            'id' => $response->decodeResponseJson()['id'],
            'attributes' => [
                'name' => $updatedRoom['name'],
                'price' => $updatedRoom['price'],
                'location' => $updatedRoom['location'],
                'unit' => $updatedRoom['unit'],
            ],
            'relationships' => []
        ]);

        $this->assertDatabaseHas('rooms', [
            'user_id' => $user->getKey(),
            ...$updatedRoom
        ]);
    }

    /** @test */
    public function room_should_only_be_updated_by_the_owner()
    {
        $this->loginAs(Role::OWNER);

        $room = Room::factory()->for(User::factory(), 'owner')->create();

        $updatedRoom = [
            'name' => 'Kost Permata',
            'location' => 'Kota Surakarta',
            'price' => 500000,
            'unit' => 10,
        ];

        $response = $this->putJson(
            route('owner.rooms.update', ['room' => $room->getKey()]),
            $updatedRoom);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseHas(
            'rooms',
            $room->makeHidden(['updated_at', 'created_at'])->toArray()
        );
    }

    /** @test */
    public function only_owner_can_update_room()
    {
        $user = $this->loginAs(Role::REGULAR_USER);
        $room = Room::factory()->for($user, 'owner')->create();

        $updatedRoom = [
            'name' => 'Kost Permata',
            'location' => 'Kota Surakarta',
            'price' => 500000,
            'unit' => 10,
        ];

        $response = $this->putJson(
            route('owner.rooms.update', ['room' => $room->getKey()]),
            $updatedRoom);

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseHas(
            'rooms',
            $room->makeHidden(['updated_at', 'created_at'])->toArray()
        );
    }

    /** @test */
    public function ensure_the_required_fields_can_not_empty()
    {
        $user = $this->loginAs(Role::OWNER);

        $room = Room::factory()->for($user, 'owner')->create([
            'name' => 'Kost Indah'
        ]);

        $updatedRoom = [
            'name' => 'Kost Permata',
            'location' => 'Kota Surakarta',
            'price' => 500000,
            'unit' => 10,
        ];

        $this->putJson(
            route('owner.rooms.update', ['room' => $room->getKey()]),
            Arr::except($updatedRoom, [
                'location', 'price', 'unit'
            ])
        )
        ->assertInvalid(['location', 'price', 'unit']);

        $this->putJson(
            route('owner.rooms.update', ['room' => $room->getKey()]),
            Arr::except($updatedRoom, [
                'name', 'price', 'unit'
            ])
        )
        ->assertInvalid(['name', 'price', 'unit']);

        $this->putJson(
            route('owner.rooms.update', ['room' => $room->getKey()]),
            Arr::except($updatedRoom, [
                'name', 'location', 'unit'
            ])
        )
        ->assertInvalid(['name', 'location', 'unit']);

        $this->putJson(
            route('owner.rooms.update', ['room' => $room->getKey()]),
            Arr::except($updatedRoom, [
                'name', 'location', 'price'
            ])
        )
        ->assertInvalid(['name', 'location', 'price']);

        $this->assertDatabaseHas(
            'rooms',
            $room->makeHidden(['updated_at', 'created_at'])->toArray()
        );
    }

    /** @test */
    public function invalid_input_data_should_return_errors()
    {
        $user = $this->loginAs(Role::OWNER);

        $room = Room::factory()->for($user, 'owner')->create([
            'name' => 'Kost Indah'
        ]);

        $updatedRoom = [
            'name' => 'Kost Permata',
            'location' => 'Kota Surakarta',
            'price' => 500000,
            'unit' => 10,
        ];

        $this->putJson(
            route('owner.rooms.update', ['room' => $room->getKey()]),
            array_merge($updatedRoom, ['name' => 123])
        )
        ->assertInvalid('name');

        $this->putJson(
            route('owner.rooms.update', ['room' => $room->getKey()]),
            array_merge($updatedRoom, ['name' => Str::random(354)])
        )
        ->assertInvalid('name');

        $this->putJson(
            route('owner.rooms.update', ['room' => $room->getKey()]),
            array_merge($updatedRoom, ['location' => 123])
        )
        ->assertInvalid('location');

        $this->putJson(
            route('owner.rooms.update', ['room' => $room->getKey()]),
            array_merge($updatedRoom, ['location' => Str::random(354)])
        )
        ->assertInvalid('location');

        $this->putJson(
            route('owner.rooms.update', ['room' => $room->getKey()]),
            array_merge($updatedRoom, ['price' => 'abcd'])
        )
        ->assertInvalid('price');

        $this->putJson(
            route('owner.rooms.update', ['room' => $room->getKey()]),
            array_merge($updatedRoom, ['price' => -1])
        )
        ->assertInvalid('price');

        $this->putJson(
            route('owner.rooms.update', ['room' => $room->getKey()]),
            array_merge($updatedRoom, ['unit' => -1])
        )
        ->assertInvalid('unit');

        $this->assertDatabaseHas(
            'rooms',
            $room->makeHidden(['updated_at', 'created_at'])->toArray()
        );
    }

    /** @test */
    public function missing_room_should_return_proper_message()
    {
        $this->loginAs(Role::OWNER);
        $updatedRoom = [
            'name' => 'Kost Permata',
            'location' => 'Kota Surakarta',
            'price' => 500000,
            'unit' => 10,
        ];

        $response = $this->putJson(
            route('owner.rooms.update', ['room' => rand(1, 100)]),
            $updatedRoom);

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $response->assertJsonStructure([
            'status', 'message'
        ]);
    }
}
