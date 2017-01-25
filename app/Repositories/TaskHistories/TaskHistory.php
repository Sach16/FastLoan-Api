<?php

namespace Whatsloan\Repositories\TaskHistories;

use Illuminate\Database\Eloquent\Model;
use Whatsloan\Services\Audits\Auditable;

class TaskHistory extends Model
{
    use Auditable;

    /**
     * Fillable fields on the model
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'modified_by',
        'task_id',
        'taskable_id',
        'taskable_type',
        'task_status_id',
        'task_stage_id',
        'priority',
        'description',
        'remarks',
        'from',
        'to',
        
    ];
    
    /**
     * Get the Task status that owns the Tasks.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
