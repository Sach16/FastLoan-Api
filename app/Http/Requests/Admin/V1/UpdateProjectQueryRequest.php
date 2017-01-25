<?php

namespace Whatsloan\Http\Requests\Admin\V1;

use Whatsloan\Http\Requests\Request;

class UpdateProjectQueryRequest extends Request
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
            "query"      => "required|min:3",
            "start_date" => "required|date",
            "end_date"   => "required|date",
            // "due_date"   => "required|date",
            "status"     => "required|in:APPROVED,REJECTED,SUBMITTED,PENDING",
        ];
    }
}
