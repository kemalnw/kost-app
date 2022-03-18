<?php

namespace App\Models\User\Traits\Relationship;

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
}
