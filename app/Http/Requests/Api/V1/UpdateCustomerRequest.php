<?php

namespace Whatsloan\Http\Requests\Api\V1;

use Whatsloan\Http\Requests\Api\V1\Request;

class UpdateCustomerRequest extends Request
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
            "phone"             => "required|min:8",
            "email"             => "required|email",
            "city_uuid"         => "required|exists:cities,uuid",
            "resident_status"   => "required",
            "profession"        => "required",
            "dob"               => "required|date",
            "age"               => "required|numeric",            
            "marital_status"    => "required",
            "company"           => "required",
            "net_income"        => "required",
            "salary_bank"       => "required",            
        ];
    }
}
