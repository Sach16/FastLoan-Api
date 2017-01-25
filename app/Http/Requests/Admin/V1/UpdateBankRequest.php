<?php

namespace Whatsloan\Http\Requests\Admin\V1;

use Whatsloan\Http\Requests\Request;

class UpdateBankRequest extends Request
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
            'branch'      => 'required|min:3|alpha_spaces',
            'ifsc_code'   => 'min:3|ifsc_code',
            'alphaStreet' => 'required|min:3',
            'betaStreet'  => 'min:3',
            'city'        => 'required|integer|exists:cities,id',
            'state'       => 'required|min:3|alpha_spaces',
            'country'     => 'required|min:3|alpha_spaces',
            'zip'         => 'required|digits:6',
            'bank_picture'=> 'image|image_size:<=100|max:4096',
        ];
    }
}
