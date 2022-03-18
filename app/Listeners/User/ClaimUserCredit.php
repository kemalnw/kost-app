<?php

namespace App\Listeners\User;

use App\Events\Auth\UserRegistered;

class ClaimUserCredit
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        $event->user->claimCredit();
    }
}
