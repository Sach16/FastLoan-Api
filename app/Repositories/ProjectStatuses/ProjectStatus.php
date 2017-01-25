<?php

namespace Whatsloan\Repositories\ProjectStatuses;

use Illuminate\Database\Eloquent\Model;

use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Services\Audits\Auditable;

class ProjectStatus extends Model
{

    use Auditable;
    
    /**
     * Get the post that owns the comment.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
