<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Tasks\Task;

class TaskStatusCountTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = [
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [
    ];

    /**
     * @param $e
     * @return array
     */
    public function transform($taskStatusCount)
    {

        $items = [];

        foreach ($taskStatusCount as $tsc) {
            $item = [];
            $item['count'] = $tsc['count'];

            $item['status'] = [
                'uuid' => $tsc['status']->uuid,
                'key' => $tsc['status']->key,
                'label' => $tsc['status']->label
            ];
            $items[] = $item;
        }

        return $items;
    }

}
