<?php

namespace Whatsloan\Http\Requests\Admin\V1;

use Whatsloan\Http\Requests\Request;

class UpdateBuilderRequest extends Request
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
            'alpha_street'=> 'required|min:3',
            'beta_street' => 'min:3',
            'city_id'     => 'required|integer|exists:cities,id',
            'state'       => 'required|min:3|alpha_spaces',
            'country'     => 'required|min:3|alpha_spaces',
            'zipcode'     => 'required|digits:6',
        ];
    }
}
