<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class CustomerCountTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * @param $e
     * @return array
     */
    public function transform($results)
    {
        return $results;
    }

}
