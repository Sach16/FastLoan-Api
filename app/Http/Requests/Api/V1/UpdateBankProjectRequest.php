<?php

namespace Whatsloan\Http\Requests\Api\V1;

use Whatsloan\Http\Requests\Api\V1\Request;

class UpdateBankProjectRequest extends Request
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
            "assignee" => "required|exists:users,uuid",
        ];
    }

}
