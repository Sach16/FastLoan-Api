<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class CountTransformer extends TransformerAbstract
{

    /**
     * @param Source $source
     * @return array
     */
    public function transform($data)
    {
        return [
            'name'  => $data['name'],
            'count' => $data['count'],
        ];
    }
}
