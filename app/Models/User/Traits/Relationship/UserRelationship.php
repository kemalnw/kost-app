<?php

namespace App\Models\User\Traits\Relationship;

use App\Models\Room\Room;
use App\Models\User\Role;

trait UserRelationship
{
    /**
     * Role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Rooms
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
