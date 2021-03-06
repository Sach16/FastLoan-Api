<?php

namespace Whatsloan\Http\Requests\Admin\V1;

use Whatsloan\Http\Requests\Request;

class StoreTaskStatusRequest extends Request
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
            'label' => 'required|min:3|alpha_spaces|unique:task_statuses,label',
        ];
    }
}
