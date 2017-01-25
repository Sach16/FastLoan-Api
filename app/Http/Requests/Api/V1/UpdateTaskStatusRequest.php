<?php

namespace Whatsloan\Http\Requests\Api\V1;

use Whatsloan\Http\Requests\Api\V1\Request;

class UpdateTaskStatusRequest extends Request
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
            "task_status_uuid"     => "required|exists:task_statuses,uuid",
            "remarks"              => "required|min:3",
            "document"             => "mimes:pdf,jpeg|max:2048", 
        ];
    }
       
}
