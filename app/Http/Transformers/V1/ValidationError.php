<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class ValidationError extends TransformerAbstract
{

    /**
     * @param $errors
     * @return array
     * @author Sharon Simon <sharon@inkoniq.com>
     */
    public function transform($errors)
    {

        return [
            'status'   => 'error',
            'code'     => 422,
            'messages' => $errors
        ];
    }
}
