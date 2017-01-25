<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\TaskStages\TaskStage;


class TaskStageTransformer extends TransformerAbstract
{
    /**
     * @param $resource
     * @return array
     */
    public function transform(TaskStage $task_stage)
    {
        return [
            'uuid' => $task_stage->uuid,
            'key' => $task_stage->key,
            'label' => $task_stage->label,
        ];
    }
}
