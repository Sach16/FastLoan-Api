<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class QueryExceptionTransformer extends TransformerAbstract
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
            'message' => "Db exception",
        ];
    }
}
