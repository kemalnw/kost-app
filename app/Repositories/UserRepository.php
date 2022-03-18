<?php

namespace App\Repositories;

use App\Concern\Repository;
use App\Models\User\User;

class UserRepository extends Repository
{
    /**
     * Construct
     *
     * @param User|null $user
     */
    public function __construct(User $user = null)
    {
        $this->model = $user ?? app(User::class);
    }
}
