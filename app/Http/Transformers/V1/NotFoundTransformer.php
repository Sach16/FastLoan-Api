<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class NotFoundTransformer extends TransformerAbstract
{

    /**
     * @param $e
     * @return array
     */
    public function transform($e)
    {
        return [
            'status'  => 'error',
            'code'    => 404,
            'message' => 'Oops you found a dead link...',
        ];
    }
}
