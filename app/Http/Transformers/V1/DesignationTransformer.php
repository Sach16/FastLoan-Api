<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Designations\Designation;


class DesignationTransformer extends TransformerAbstract
{

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
     * @param User $user
     * @return array
     */
    public function transform(Designation $designation)
    {
        
        return [
            'uuid'        => $designation->uuid,
            'name'        => $designation->name,
            'description' => $designation->description,
            'created_at'  => $designation->created_at,
            'updated_at'  => $designation->updated_at,
        ];
    }   
   
}
