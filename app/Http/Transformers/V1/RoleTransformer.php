<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class User1Transformer extends TransformerAbstract
{


     /**
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * @var array
     */
    protected $availableIncludes = [
    ];


    /**
     * User role trasformer
     * @param  Role $role
     * @return array
     */
    public function tranform(Role $role)
    {
        return [

            'role' => $role->tole
        ];
    }
}
