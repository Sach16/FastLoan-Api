<?php

namespace Whatsloan\Http\Requests\Admin\V1;

use Whatsloan\Http\Requests\Request;

class LoginRequest extends Request
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
            //'email'    => 'required|email|exists:users,email',
            'phone'    => 'required|exists:users,phone',
            'password' => 'required',
        ];
    }
}