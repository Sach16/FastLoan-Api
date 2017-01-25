<?php

namespace Whatsloan\Http\Requests\Admin\V1;

use Whatsloan\Http\Requests\Request;

class StoreUserRequest extends Request
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
            "first_name"              => "required|min:3|max:255|alpha_spaces",
            "last_name"               => "required|max:255|alpha_spaces",
            'designation_id'          => 'required|required|numeric|exists:designations,id',
            "email"                   => "required|email",
            "phone"                   => "required|digits:10|unique:users",
            "settings.dob"            => "date|date_format:d-m-Y|before:today",
            "settings.marital_status" => "in:-1,Married,Unmarried",
            "settings.gender"         => "in:-1,male,female,other",
            'alphaStreet'             => 'required|min:3',
            'betaStreet'              => 'min:3',
            'city_id'                 => 'required|numeric|exists:cities,id',
            'state'                   => 'required|min:2|alpha_spaces',
            'country'                 => 'required|min:2|alpha_spaces',
            'zipcode'                 => 'required|digits:6',
            'settings.DOJ'            => 'required|date|date_format:d-m-Y',
            'settings.exp_on_DOJ'     => 'required|numeric',
            // 'bank'                    => 'required|numeric',
            "profile_picture"         => "image|image_size:<=300|max:4096",
            "address_proof"           => "mimes:pdf,jpeg,png,bmp,gif,svg|max:10240",
            "id_proof"                => "mimes:pdf,jpeg,png,bmp,gif,svg|max:10240",
            "products_handled"        => "mimes:pdf,jpeg,png,bmp,gif|max:10240",
            "experience_with_banks"   => "mimes:pdf,jpeg,png,bmp,gif|max:10240",
        ];
    }
}
