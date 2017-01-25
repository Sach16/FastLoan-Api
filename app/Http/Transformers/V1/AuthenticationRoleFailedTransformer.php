<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class AuthenticationRoleFailedTransformer extends TransformerAbstract
{

    /**
     * @param string $message
     * @return array
     */
    public function transform($message = '')
    {
        return [
            'status'  => false,
            'code'    => 401,
            'message' => $message,
        ];
    }
}
