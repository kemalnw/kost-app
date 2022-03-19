<?php

namespace Tests;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected $seed = true;

    protected function loginAs(int $roleId = 1, array $overrides = [])
    {
        $user = User::factory()->withRole($roleId)->create($overrides);
        Sanctum::actingAs($user);

        return $user;
    }
}
