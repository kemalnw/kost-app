<?php

namespace Tests\Feature\Room;

use Tests\TestCase;
use App\Models\Room\Room;
use App\Models\User\Role;
use App\Models\User\User;
use Illuminate\Http\Response;

class ShowRoomForOwnerTest extends TestCase
{
    /** @test */
    public function owner_can_show_room_detail_via_api()
    {
        $user = $this->loginAs(Role::OWNER);
        $room = Room::factory()->for($user, 'owner')->create();

        $response = $this->get(
            route('owner.rooms.show', ['room' => $room->getKey()]));

        $response->assertOk();

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
    }

    /** @test */
    public function room_should_only_be_shown_by_the_owner()
    {
        $this->loginAs(Role::OWNER);

        $room = Room::factory()->for(User::factory(), 'owner')->create();

        $response = $this->get(
            route('owner.rooms.show', ['room' => $room->getKey()]));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function only_owner_can_show_room()
    {
        $user = $this->loginAs(Role::REGULAR_USER);
        $room = Room::factory()->for($user, 'owner')->create();

        $response = $this->get(
            route('owner.rooms.show', ['room' => $room->getKey()]));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
