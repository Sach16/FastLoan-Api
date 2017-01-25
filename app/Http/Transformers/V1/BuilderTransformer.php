<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Builders\Builder;

class BuilderTransformer extends TransformerAbstract
{

    
    
    
    /**
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * @var array
     */
    protected $availableIncludes = [
        'address',
    ];
    
    
    /**
     * @param $e
     * @return array
     */
    public function transform(Builder $builder)
    {
        return [
            'uuid' => $builder->uuid,
            'name' => $builder->name
        ];
    }
    
    
    
    /**
     * Include address
     * @param  Builder $builder
     * @return
     */
    public function includeAddress(Builder $builder)
    {
        return $this->collection($builder->addresses, new AddressTransformer);
    }
}
