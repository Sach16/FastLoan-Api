<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class HomeTeamTransformer extends TransformerAbstract
{

    /**
     * @param Source $source
     * @return array
     */
    public function transform($data)
    {
        return $data;
    }
}
