<?php

namespace Whatsloan\Repositories\TaskStages;

use Illuminate\Database\Eloquent\Model;
use Whatsloan\Services\AccessControl\ControlsAccess;
use Whatsloan\Services\Audits\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Whatsloan\Repositories\Tasks\Task;

class TaskStage extends Model
{
    use Auditable,SoftDeletes;

    /**
     * Get the Task stages that owns the Tasks.
     */
     protected $fillable = ['label', 'key','uuid'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

}
