<?php

namespace Whatsloan\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Whatsloan\Http\Transformers\V1\ValidationError;
use Whatsloan\Services\Transformers\Transformable;

abstract class Request extends FormRequest
{

    use Transformable;

    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        return $this->transformItem($errors, ValidationError::class, 422);
    }
}
