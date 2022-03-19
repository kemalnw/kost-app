<?php

namespace App\Models\User\Traits\Method;

use App\Models\User\Role;

trait UserMethod
{
    /**
     * Determine if the user has role with the given role id
     *
     * @param int $roleId
     * @return boolean
     */
    public function hasRole($roleId)
    {
        return $this->role_id === $roleId;
    }

    /**
     * Claim user free credit based on role
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
