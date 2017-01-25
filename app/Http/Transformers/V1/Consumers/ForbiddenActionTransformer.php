<?php

namespace Whatsloan\Http\Transformers\V1\Consumers;

use League\Fractal\TransformerAbstract;

class ForbiddenActionTransformer extends TransformerAbstract
{

    /**
     * @return array
     */
    public function transform()
    {
        return [
            'status' => false,
            'message' => 'Action is not allowed',
            'code'  => 403
        ];
    }
}