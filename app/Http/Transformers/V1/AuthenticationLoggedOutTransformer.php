<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class AuthenticationLoggedOutTransformer extends TransformerAbstract
{

    /**
     * @return array
     */
    public function transform()
    {
        return [
            'status'  => true,
            'message' => 'Logged out',
            'code'    => 200,
        ];
    }
}