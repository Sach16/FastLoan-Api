<?php

namespace Whatsloan\Http\Requests\Api\V1;

use Whatsloan\Http\Requests\Api\V1\Request;

class StoreLoanRequest extends Request
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
            "type_uuid" => "required|exists:types,uuid",
            "user_uuid" => "required|exists:users,uuid",
            "agent_uuid" => "required|exists:users,uuid",
            "bank_uuid" => "required|exists:banks,uuid",
            "loan_status_uuid" => "required|exists:loan_statuses,uuid",
            "amount" => "required|integer",
            "eligible_amount" => "required|integer",
            "approved_amount" => "required|integer",
            "project_uuid" => "required|exists:projects,uuid"
        ];
    }

}
