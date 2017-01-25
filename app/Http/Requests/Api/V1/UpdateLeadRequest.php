<?php

namespace Whatsloan\Http\Requests\Api\V1;

use Whatsloan\Http\Requests\Api\V1\Request;

class UpdateLeadRequest extends Request
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
            "source_uuid"        => "required|exists:sources,uuid",
            "referral_uuid"      => "exists:users,uuid",
            "name"               => "required|max:255",
            "phone"              => "required|min:8",
            "email"              => "required|email|max:255",
            "city_uuid"          => "required|exists:cities,uuid",
            "type_uuid"          => "required|exists:types,uuid",
            "loan_amount"        => "required",
            "net_salary"         => "required",   
            "existing_loan_emi"  => "required", 
            "company_name"       => "required|max:255", 
        ];
    }
}
