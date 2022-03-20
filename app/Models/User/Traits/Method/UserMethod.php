<?php

namespace App\Models\User\Traits\Method;

use App\Models\User\Role;
use App\Models\User\User;

trait UserMethod
{
    /**
     * Determine if the user has role with the given role id
     *
     * @param int $roleId
     * @return bool
     */
    public function hasRole(int $roleId)
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
                $this->increment('balance', User::FREE_CREDIT_FOR_REGULER_USER);
                break;

            case Role::PREMIUM_USER:
                $this->increment('balance', User::FREE_CREDIT_FOR_PREMIUM_USER);
                break;
        }
    }

    /**
     * Determine if the user is owner of the room
     *
     * @param int $roomId
     * @return bool
     */
    public function hasRoom(int $roomId)
    {
        return $this->whereHas('rooms', function($room) use($roomId) {
            return $room->where('id', $roomId)
                ->where('user_id', $this->getKey());
        })
        ->exists();
    }

    /**
     * Determine if the user has enough balance
     *
     * @param int $amount
     * @return bool
     */
    public function hasEnoughBalance(int $amount)
    {
        return $this->balance >= $amount;
    }
}
