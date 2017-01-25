<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class DocumentUploaded extends TransformerAbstract
{

    /**
     * @param $resource
     * @return array
     */
    public function transform($resource)
    {
        return [
            'status'   => 'uploaded',
            'code'     => 201,
            'message'  => 'Document Uploaded Successfully',
            'resource' => [
                'model' => $resource->getTable(),
                'id'    => $resource->id,
                'uuid'    => $resource->uuid,
            ],
        ];
    }
}
