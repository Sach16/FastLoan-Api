<?php

namespace Whatsloan\Http\Requests\Api\V1;

use Whatsloan\Http\Requests\Api\V1\Request;

class ProductFilterRequest extends Request
{



    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "type_uuid"   => "required|exists:types,uuid",
            "bank_uuid"      => "required|exists:banks,uuid",
            "city_uuid"      => "required|exists:cities,uuid",            
        ];
    }
}
