<?php

namespace Tests\Feature\Room;

use Tests\TestCase;
use App\Models\Room\Room;
use App\Models\User\Role;
use App\Models\User\User;
use Illuminate\Http\Response;

class ShowRoomForUserTest extends TestCase
{
    /** @test */
    public function user_can_show_room_detail_via_api()
    {
        $this->loginAs(Role::PREMIUM_USER);
        $owner = User::factory()->withRole(Role::OWNER)->create();
        $room = Room::factory()->for($owner, 'owner')->create();

        $response = $this->get(
            route('rooms.show', ['room' => $room->getKey()]));

        $response->assertOk();

        $response->assertJson([
            'type' => 'rooms',
            'id' => $response->decodeResponseJson()['id'],
            'attributes' => [
                'name' => $room['name'],
                'price' => $room['price'],
                'location' => $room['location'],
                'unit' => $room['unit'],
            ],
            'relationships' => [
                'owner' => [
                    'type' => 'users',
                    'id' => $owner->getKey(),
                    'attributes' => [
                        'name' => $owner->name,
                        'email' => $owner->email,
                    ],
                    'relationships' => []
                ]
            ]
        ]);
    }

    /** @test */
    public function missing_room_should_return_proper_message()
    {
        $this->loginAs(Role::REGULAR_USER);
        $response = $this->get(
            route('rooms.show', ['room' => rand(1, 100)]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $response->assertJsonStructure([
            'status', 'message'
        ]);
    }
}
