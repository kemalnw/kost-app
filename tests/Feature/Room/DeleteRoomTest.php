<?php

namespace Tests\Feature\Room;

use Tests\TestCase;
use App\Models\Room\Room;
use App\Models\User\Role;
use App\Models\User\User;
use Illuminate\Http\Response;

class DeleteRoomTest extends TestCase
{
    /** @test */
    public function owner_can_delete_his_room()
    {
        $user = $this->loginAs(Role::OWNER);
        $room = Room::factory()->for($user, 'owner')->create();

        $response = $this->deleteJson(
            route('owner.rooms.destroy', ['room' => $room->getKey()]));

        $response->assertOk();

        $this->assertSoftDeleted('rooms', ['id' => $room->getKey()]);
    }

    /** @test */
    public function room_should_only_be_deleted_by_the_owner()
    {
        $this->loginAs(Role::OWNER);
        $room = Room::factory()->for(User::factory(), 'owner')->create();

        $response = $this->deleteJson(
            route('owner.rooms.destroy', ['room' => $room->getKey()]));

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertNotSoftDeleted('rooms', ['id' => $room->getKey()]);
    }

    /** @test */
    public function only_owner_can_delete_room()
    {
        $user = $this->loginAs(Role::PREMIUM_USER);
        $room = Room::factory()->for($user, 'owner')->create();

        $response = $this->deleteJson(
            route('owner.rooms.destroy', ['room' => $room->getKey()]));

        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertNotSoftDeleted('rooms', ['id' => $room->getKey()]);
    }

    /** @test */
    public function missing_room_should_return_proper_message()
    {
        $this->loginAs(Role::OWNER);
        $response = $this->deleteJson(
            route('owner.rooms.destroy', ['room' => rand(1, 100)]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $response->assertJsonStructure([
            'status', 'message'
        ]);
    }
}
