<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Types\Type;

class TypeTransformer extends TransformerAbstract
{

    /**
     * @param Type $type
     * @return array
     */
    public function transform(Type $type)
    {
        return [
            'uuid'        => $type->uuid,
            'name'        => $type->name,
            'key'         => $type->key,
            'description' => $type->description,
            'created_at'  => $type->created_at,
            'updated_at'  => $type->updated_at,
        ];
    }
}