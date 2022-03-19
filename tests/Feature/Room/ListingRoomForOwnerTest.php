<?php

namespace Tests\Feature\Room;

use App\Models\Room\Room;
use App\Models\User\Role;
use App\Models\User\User;
use Tests\TestCase;

class ListingRoomForOwnerTest extends TestCase
{
    /** @test */
    public function owner_can_retrieve_his_room()
    {
        $user = $this->loginAs(Role::OWNER);

        Room::factory()->for($user, 'owner')->count(5)->create();

        $response = $this->get(route('owner.rooms.index'));

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                [
                    'type', 'id',
                    'attributes' => ['name', 'price', 'location'],
                    'relationships',
                ],
            ],
            'links' => [
                'first', 'last', 'prev', 'next',
            ],
            'meta' => [
                'path', 'per_page', 'next_cursor', 'prev_cursor',
            ],
        ]);

        $content = $response->decodeResponseJson();
        $this->assertEquals(count($content['data']), 5);
    }

    /** @test */
    public function owner_should_only_able_to_retrieve_his_own_room()
    {
        $this->loginAs(Role::OWNER);

        Room::factory()->for(User::factory(), 'owner')->count(3)->create();

        $response = $this->get(route('owner.rooms.index'));

        $response->assertOk();

        $content = $response->decodeResponseJson();
        $this->assertEquals(count($content['data']), 0);
    }

    /** @test */
    public function only_owner_can_get_rooms_list_through_listing_api()
    {
        $this->loginAs(Role::PREMIUM_USER);

        $response = $this->get(route('owner.rooms.index'));
        $response->assertStatus(403);
    }
}
