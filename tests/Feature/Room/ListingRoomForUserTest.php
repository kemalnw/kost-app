<?php

namespace Tests\Feature\Room;

use Tests\TestCase;
use App\Models\Room\Room;
use App\Models\User\User;

class ListingRoomForUserTest extends TestCase
{
    /** @test */
    public function user_can_retrieve_room_list()
    {
        Room::factory()->for(User::factory(), 'owner')->count(3)->create();

        $response = $this->get(route('rooms.index'));

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
        $this->assertEquals(count($content['data']), 3);
    }

    /** @test */
    public function user_can_search_room_list_by_name()
    {
        $rooms = Room::factory()->count(3)->create();

        $room = $rooms->first();

        $response = $this->get(route('rooms.index', ['search' => $room->name]))
            ->assertOk()
            ->decodeResponseJson();

        $this->assertEquals(
            $rooms->where('name', $room->name)->count(),
            count($response['data'])
        );
    }

    /** @test */
    public function user_can_search_room_list_by_location()
    {
        $rooms = Room::factory()->count(3)->create();

        $room = $rooms->first();

        $response = $this->get(route('rooms.index', ['search' => $room->location]))
            ->assertOk()
            ->decodeResponseJson();

        $this->assertEquals(
            $rooms->where('location', $room->location)->count(),
            count($response['data'])
        );
    }

    /** @test */
    public function user_can_search_room_list_by_price()
    {
        $rooms = Room::factory()->count(3)->create();

        $room = $rooms->first();

        $response = $this->get(route('rooms.index', ['search' => $room->price]))
            ->assertOk()
            ->decodeResponseJson();

        $this->assertEquals(
            $rooms->where('price', $room->price)->count(),
            count($response['data'])
        );
    }

    /** @test */
    public function ensure_room_list_sorted_by_price()
    {
        $rooms = Room::factory()->count(3)->create();

        $room = $rooms->sortBy('price')->first();

        $response = $this->get(route('rooms.index'))
            ->assertOk()
            ->decodeResponseJson();

        $this->assertEquals($room->id, $response['data'][0]['id']);
    }
}
