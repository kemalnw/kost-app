<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User\User;
use Illuminate\Testing\Fluent\AssertableJson;

class LoginTest extends TestCase
{
    /** @test */
    public function user_can_login_to_app()
    {
        $user = User::factory()->create([
            'email' => 'hi@kemalnw.id',
        ]);

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk();

        $response->assertJsonStructure([
            'status', 'message', 'data' => ['token'],
        ]);

        $user = $this->withToken($response->decodeResponseJson()['data']['token'])
            ->get(route('account.current-user'));

        $user->assertOk();
    }

    /** @test */
    public function invalid_login_should_return_proper_message()
    {
        $this->postJson(route('auth.login'), [
            'email' => '',
            'password' => '',
        ])
        ->assertStatus(422)
        ->assertJson(function (AssertableJson $json) {
            $json->has('message')
                ->has('errors', 2)
                ->whereAllType([
                    'errors.email' => 'array',
                    'errors.password' => 'array',
                ]);
        });


        $this->postJson(route('auth.login'), [
            'email' => 'hi@kemalnw.id',
            'password' => '',
        ])
        ->assertStatus(422)
        ->assertJson(function (AssertableJson $json) {
            $json->has('message')
                ->has('errors', 1)
                ->whereAllType([
                    'errors.password' => 'array',
                ]);
        });

        $this->postJson(route('auth.login'), [
            'email' => '',
            'password' => 'password',
        ])
        ->assertStatus(422)
        ->assertJson(function (AssertableJson $json) {
            $json->has('message')
                ->has('errors', 1)
                ->whereAllType([
                    'errors.email' => 'array',
                ]);
        });

        $this->postJson(route('auth.login'), [
            'email' => 'hi@kemalnw.id',
            'password' => 'pass',
        ])
        ->assertStatus(422)
        ->assertJson(function (AssertableJson $json) {
            $json->has('message')
                ->has('errors', 1)
                ->whereAllType([
                    'errors.password' => 'array',
                ]);
        });

        $this->postJson(route('auth.login'), [
            'email' => 'hi@kemalnw.id',
            'password' => 'password',
        ])
        ->assertUnauthorized()
        ->assertJson(function (AssertableJson $json) {
            $json->has('message')
                ->has('errors', 1)
                ->whereAllType([
                    'errors.email' => 'array',
                ]);
        });
    }
}
