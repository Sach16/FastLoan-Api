<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class ResourceCreated extends TransformerAbstract
{

    /**
     * @param $resource
     * @return array
     */
    public function transform($resource)
    {
        return [
            'status'   => 'created',
            'code'     => 201,
            'message'  => 'Resource created with id '.$resource->id,
            'resource' => [
                'model' => $resource->getTable(),
                'id'    => $resource->id,
                'uuid'    => $resource->uuid,
            ],
        ];
    }
}
