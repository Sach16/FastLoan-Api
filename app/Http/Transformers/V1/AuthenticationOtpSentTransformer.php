<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class AuthenticationOtpSentTransformer extends TransformerAbstract
{

    /**
     * @param $response
     * @return array
     */
    public function transform($response)
    {
        return [
            'status'  => $response->getReasonPhrase(),
            'code'    => $response->getStatusCode(),
            //'message' => $response->getBody(),
            'message' => "",
        ];
    }
}
