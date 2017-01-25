<?php

namespace Whatsloan\Http\Requests\Admin\V1;

use Whatsloan\Http\Requests\Request;

class UpdateLeadRequest extends Request
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
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'settings[loan_amount].required'  => 'The loan amount required',
            "settings[company_name].required" => "The company name required",
            "settings[net_income].required"   => "The net salary requires",
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            "source_id"             => "required|exists:sources,id",
            "first_name"            => "required|max:255|alpha_spaces",
            "last_name"             => "max:255|alpha_spaces",
            // "phone"                     => "required|isPhoneUpdatable|min:8",
            "phone"                 => "required|digits:10",
            "email"                 => "required|email|max:255",
            "existing_loan_emi"     => "required",
            "assigned_to"           => "exists:users,id",
            'alphaStreet'           => 'required|min:3',
            'betaStreet'            => 'min:3',
            'city_id'               => 'required|numeric|exists:cities,id',
            'state'                 => 'required|min:2|alpha_spaces',
            'country'               => 'required|min:2|alpha_spaces',
            'zipcode'               => 'required|digits:6',
            "address_proof"         => "mimes:pdf,jpeg,png,bmp,gif,svg|max:10240",
            "id_proof"              => "mimes:pdf,jpeg,png,bmp,gif,svg|max:10240",
            "products_handled"      => "mimes:pdf,jpeg,png,bmp,gif|max:10240",
            "experience_with_banks" => "mimes:pdf,jpeg,png,bmp,gif|max:10240",
            "profile_picture"       => "image|image_size:<=300|max:4096",
            "settings.dob"          => "date|date_format:d-m-Y|before:today|older_than",
        ];
    }

}
