<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class ProfessionTransformer extends TransformerAbstract
{

    /**
     * @param $resource
     * @return array
     */
    public function transform($resource)
    {

        return [
            'uuid' => $loan->uuid,
            'name' => $loan->name,
        ];
    }
}
