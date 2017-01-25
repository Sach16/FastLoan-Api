<?php

namespace Whatsloan\Http\Requests\Api\V1;

use Whatsloan\Http\Requests\Api\V1\Request;

class StoreProjectRequest extends Request
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
            "name"          => "required",
            //"street"  => "required",
            "builder_uuid"  => "required|exists:builders,uuid",
            "city_uuid"     => "required|exists:cities,uuid",
            "bank_uuid"     => "required|exists:banks,uuid",
            "assignee"      => "required|exists:users,uuid",
            "project_uuid"  => "exists:projects,uuid",
            "project_picture"      => "mimes:jpeg|max:2048", 
        ];
    }
}
