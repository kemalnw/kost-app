<?php

namespace Tests\Feature\Command;

use App\Models\User\Role;
use App\Models\User\User;
use Tests\TestCase;

class RechargeUserCreditTest extends TestCase
{
    /** @test */
    public function command_should_work_properly()
    {
        User::factory()->withRole(Role::REGULAR_USER)->create();
        User::factory()->withRole(Role::PREMIUM_USER)->create();

        $this->artisan('credit:recharge')->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'role_id' => Role::REGULAR_USER,
            'balance' => User::FREE_CREDIT_FOR_REGULER_USER,
        ]);

        $this->assertDatabaseHas('users', [
            'role_id' => Role::PREMIUM_USER,
            'balance' => User::FREE_CREDIT_FOR_PREMIUM_USER,
        ]);
    }

    /** @test */
    public function owner_should_have_no_credit()
    {
        User::factory()->withRole(Role::OWNER)->create();

        $this->artisan('credit:recharge')->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'role_id' => Role::OWNER,
            'balance' => 0,
        ]);
    }
}
