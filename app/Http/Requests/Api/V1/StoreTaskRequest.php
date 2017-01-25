<?php

namespace Whatsloan\Http\Requests\Api\V1;

use Whatsloan\Http\Requests\Api\V1\Request;

class StoreTaskRequest extends Request
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
            "loan_uuid"         => "required|exists:loans,uuid",
            "member_uuid"        => "required|exists:users,uuid",
            "task_status_uuid"   => "required|exists:task_statuses,uuid",
            "task_stage_uuid"    => "required|exists:task_stages,uuid",
            "priority"           => "required",
            "description"        => "required|min:3",
            "from"               => "required|date",
            "to"                 => "required|date",                      
        ];
    }   
}
