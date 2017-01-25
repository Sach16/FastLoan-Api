<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\ProjectStatuses\ProjectStatus;


class ProjectStatusTransformer extends TransformerAbstract
{

    /**
     * @param $resource
     * @return array
     */
    public function transform(ProjectStatus $projectStatus)
    {

        return [
            'uuid' => $projectStatus->uuid,
            'status' => $projectStatus->status,
        ];
    }
}
