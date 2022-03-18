<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User\Role;
use Illuminate\Support\Arr;

class RegistrationTest extends TestCase
{
    /** @test */
    public function user_can_register_as_regular_user()
    {
        $user = [
            'name' => 'Kemal',
            'email' => 'hi@kemalnw.id',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'role' => Role::REGULAR_USER,
        ];

        $response = $this->postJson(route('auth.register'), $user);

        $response->assertCreated();

        $response->assertJson([
            'type' => 'users',
            'id' => $response->decodeResponseJson()['id'],
            'attributes' => [
                'name' => $user['name'],
                'email' => $user['email'],
            ],
        ]);

        $user = ['password' => bcrypt($user['password']), ...$user];
        $this->assertDatabaseHas('users', Arr::except([
            'balance' => 20,
            ...$user
        ],
        [
            'password_confirmation',
            'role',
        ]));
    }

    /** @test */
    public function user_can_register_as_owner()
    {
        $user = [
            'name' => 'Kemal',
            'email' => 'hi@kemalnw.id',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'role' => Role::OWNER,
        ];

        $response = $this->postJson(route('auth.register'), $user);

        $response->assertCreated();

        $response->assertJson([
            'type' => 'users',
            'id' => $response->decodeResponseJson()['id'],
            'attributes' => [
                'name' => $user['name'],
                'email' => $user['email'],
            ],
        ]);

        $user = ['password' => bcrypt($user['password']), ...$user];
        $this->assertDatabaseHas('users', Arr::except($user, [
            'password_confirmation',
            'role',
        ]));
    }

    /** @test */
    public function user_can_register_as_premium_user()
    {
        $user = [
            'name' => 'Kemal',
            'email' => 'hi@kemalnw.id',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'role' => Role::PREMIUM_USER,
        ];

        $response = $this->postJson(route('auth.register'), $user);

        $response->assertCreated();

        $response->assertJson([
            'type' => 'users',
            'id' => $response->decodeResponseJson()['id'],
            'attributes' => [
                'name' => $user['name'],
                'email' => $user['email'],
            ],
        ]);

        $user = ['password' => bcrypt($user['password']), ...$user];
        $this->assertDatabaseHas('users', Arr::except([
            'balance' => 40,
            ...$user
        ],
        [
            'password_confirmation',
            'role',
        ]));
    }
}
