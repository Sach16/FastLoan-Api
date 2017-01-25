<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\TaskStatuses\TaskStatus;


class TaskStatusTransformer extends TransformerAbstract
{
    /**
     * @param $resource
     * @return array
     */
    public function transform(TaskStatus $task_status)
    {
        return [
            'uuid' => $task_status->uuid,
            'key' => $task_status->key,
            'label' => $task_status->label,
        ];
    }
}
