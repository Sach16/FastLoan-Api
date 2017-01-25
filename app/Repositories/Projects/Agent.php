<?php

namespace Whatsloan\Repositories\Projects;

use Whatsloan\Repositories\Users\User;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Agent extends Pivot
{

    /**
     * Bank Project belongs to an Agent
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}