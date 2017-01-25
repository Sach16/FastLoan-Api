<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Sources\Source;

class SourceTransformer extends TransformerAbstract
{

    /**
     * @param Source $source
     * @return array
     */
    public function transform(Source $source)
    {
        return [
            'uuid'        => $source->uuid,
            'name'        => $source->name,
            'description' => $source->description,
            'created_at'  => $source->created_at,
            'updated_at'  => $source->updated_at,
        ];
    }
}