<?php

namespace App\Models\User\Traits\Method;

use App\Models\User\Role;
use Database\Factories\UserFactory;

trait UserMethod
{
    /**
     * Determine if the user has role with the given role name
     *
     * @param string $roleName
     * @return boolean
     */
    public function hasRole($roleName)
    {
        return $this->whereHas('role', function($q) use($roleName) {
            return $q->whereName($roleName);
        })
        ->exists();
    }

    /**
     * Claim the user credit based on role
     *
     * @return void
     */
    public function claimCredit()
    {
        switch ($this->role_id) {
            case Role::REGULAR_USER:
                $this->increment('balance', 20);
                break;

            case Role::PREMIUM_USER:
                $this->increment('balance', 40);
                break;
        }
    }
}
