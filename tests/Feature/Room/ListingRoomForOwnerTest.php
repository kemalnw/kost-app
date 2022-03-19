<?php

namespace Tests\Feature\Room;

use Tests\TestCase;
use App\Models\Room\Room;
use App\Models\User\Role;
use App\Models\User\User;
use Illuminate\Http\Response;

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
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function owner_can_search_room_list_by_name()
    {
        $user = $this->loginAs(Role::OWNER);

        $rooms = Room::factory()->for($user, 'owner')->count(3)->create();

        $room = $rooms->first();

        $response = $this->get(route('owner.rooms.index', ['search' => $room->name]))
            ->assertOk()
            ->decodeResponseJson();

        $this->assertEquals(
            $rooms->where('name', $room->name)->count(),
            count($response['data'])
        );
    }

    /** @test */
    public function owner_can_search_room_list_by_location()
    {
        $user = $this->loginAs(Role::OWNER);

        $rooms = Room::factory()->for($user, 'owner')->count(3)->create();

        $room = $rooms->first();

        $response = $this->get(route('owner.rooms.index', ['search' => $room->location]))
            ->assertOk()
            ->decodeResponseJson();

        $this->assertEquals(
            $rooms->where('location', $room->location)->count(),
            count($response['data'])
        );
    }

    /** @test */
    public function owner_can_search_room_list_by_price()
    {
        $user = $this->loginAs(Role::OWNER);

        $rooms = Room::factory()->for($user, 'owner')->count(3)->create();

        $room = $rooms->first();

        $response = $this->get(route('owner.rooms.index', ['search' => $room->price]))
            ->assertOk()
            ->decodeResponseJson();

        $this->assertEquals(
            $rooms->where('price', $room->price)->count(),
            count($response['data'])
        );
    }
}
