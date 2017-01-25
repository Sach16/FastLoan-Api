<?php

namespace Whatsloan\Http\Requests\Api\V1;

use Whatsloan\Http\Requests\Api\V1\Request;

class StoreLsrQueryRequest extends Request
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
            "project_uuid"  => "exists:projects,uuid",
            "query"         => "required",
            "assigned_to"   => "required|exists:users,uuid",
            "start_date"    => "required",
            "end_date"      => "required",
        ];
    }
}
