<?php

namespace Whatsloan\Http\Transformers\V1\Consumers;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Products\Product;

class ProductTransformer extends TransformerAbstract {

    /**
     * @var array
     */
    protected $defaultIncludes = [        
        
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [
        
    ];

    /**
     * @param $e
     * @return array
     */
    public function transform(Product $product) {
        return [
            'uuid' => $product->uuid,            
        ];
    }  

}
