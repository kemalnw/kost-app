<?php

namespace App\Models\Room\Traits\Relationship;

use App\Models\User\User;

trait RoomRelationship
{
    /**
     * Owner
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
