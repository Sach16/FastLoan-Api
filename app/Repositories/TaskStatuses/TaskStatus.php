<?php

namespace Whatsloan\Repositories\TaskStatuses;

use Illuminate\Database\Eloquent\Model;

use Whatsloan\Repositories\Tasks\Task;
use Whatsloan\Services\Audits\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskStatus extends Model
{

    use Auditable,SoftDeletes;

    /**
     * Fillable fields on the model
     *
     * @var array
     */
    protected $fillable = ['label', 'key','uuid'];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'created_at', 'updated_at',];


    /**
     * Get the Task status that owns the Tasks.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
