<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class ResourceUpdated extends TransformerAbstract
{

    /**
     * @param $resource
     * @return array
     */
    public function transform($resource)
    {
        return [
            'status'   => 'updated',
            'code'     => 204,
            'message'  => 'Resource updated',
            'resource' => [
                'model' => $resource->getTable(),
                'id'    => $resource->id,
                'uuid'    => $resource->uuid,
            ],
        ];
    }
}
