<?php

namespace Whatsloan\Http\Requests\Admin\V1;

use Whatsloan\Http\Requests\Request;

class StoreStateRequest extends Request
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
            'name'        => 'required|min:3|alpha_spaces',
            'description' => 'min:3',
        ];
    }

    /**
     * Override validation messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            "name.required"    => "The state name field is required",
        ];
        return parent::messages();
    }
}
