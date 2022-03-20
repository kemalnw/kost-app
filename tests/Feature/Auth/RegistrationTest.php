<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User\Role;
use App\Models\User\User;
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
            'balance' => User::FREE_CREDIT_FOR_REGULER_USER,
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
            'balance' => User::FREE_CREDIT_FOR_PREMIUM_USER,
            ...$user
        ],
        [
            'password_confirmation',
            'role',
        ]));
    }

    /** @test */
    public function empty_input_data_should_return_errors()
    {
        $user = [
            'name' => 'Kemal',
            'email' => 'hi@kemalnw.id',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'role' => rand(1,3),
        ];

        $response = $this->postJson(
                route('auth.register'),
                Arr::except($user, ['email', 'password', 'role']));

        $response->assertInvalid(['email', 'password', 'role']);

        $response = $this->postJson(
            route('auth.register'),
            Arr::except($user, ['name', 'password', 'role']));

        $response->assertInvalid(['name', 'password', 'role']);

        $response = $this->postJson(
            route('auth.register'),
            Arr::except($user, ['name', 'email', 'role']));

        $response->assertInvalid(['name', 'email', 'role']);

        $response = $this->postJson(
            route('auth.register'),
            Arr::except($user, ['name', 'email', 'password']));

        $response->assertInvalid(['name', 'email', 'password']);
    }

    /** @test */
    public function invalid_input_data_should_return_errors()
    {
        $user = [
            'name' => 'Kemal',
            'email' => 'hi@kemalnw.id',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'role' => rand(1,3),
        ];

        $response = $this->postJson(
            route('auth.register'),
            Arr::set($user, 'email', 'kemalnw.id'));

        $response->assertInvalid('email');

        Arr::set($user, 'email', 'hi@kemalnw.id');
        $response = $this->postJson(
            route('auth.register'),
            Arr::set($user, 'password', '12345'));

        $response->assertInvalid(['password']);

        Arr::set($user, 'password', '1234578');
        $response = $this->postJson(
            route('auth.register'),
            Arr::except($user, 'password_confirmation'));

        $response->assertInvalid(['password']);

        $response = $this->postJson(
            route('auth.register'),
            Arr::set($user, 'role', '-1'));

        $response->assertInvalid(['role']);
    }
}
