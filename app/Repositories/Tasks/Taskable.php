<?php

namespace Whatsloan\Repositories\Tasks;

trait Taskable
{

    /**
     * Model has many addresses
     */
    public function tasks()
    {
        return $this->morphMany(Task::class, 'taskable');
    }
}
