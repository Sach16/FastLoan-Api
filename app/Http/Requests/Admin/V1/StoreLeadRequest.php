<?php

namespace Whatsloan\Http\Requests\Admin\V1;

use Whatsloan\Http\Requests\Request;
use Whatsloan\Repositories\Sources\Source;
use Whatsloan\Repositories\Types\Type;

class StoreLeadRequest extends Request
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
            "settings[company].required"    => "The company name required",
            "settings[net_income].required" => "The net income should ne a valid number",
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            "source_id"             => "required|exists:sources,id",
            "first_name"            => "required|max:255|alpha_spaces",
            "last_name"             => "required|max:255|alpha_spaces",
            "phone"                 => "required|digits:10|unique:users,phone",
            "email"                 => "required|email|max:255",
            "existing_loan_emi"     => "required",
            //    "settings[net_income]"      => "required",
            //    "settings[company]"         => "required",
            "amount"                => "required|integer|min:1",
            "type_id"               => "required|exists:types,id",
            "assigned_to"           => "required|exists:users,id",
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

        $data = request()->all();

        if (isset($data['type_id']) && $data['type_id'] != -1 && Type::find($data['type_id'])->key != null && Type::find($data['type_id'])->key == 'HL' && isset($data['property_verified']) && $data['property_verified'] == 1) {
            $rules["builder_id"] = "required|exists:builders,id";
            $rules["project_id"] = "required|exists:projects,id";
        }

        if (isset($data['source_id']) && $data['source_id'] != -1 && Source::find($data['source_id']) != null && Source::find($data['source_id'])->key == 'REFERRAL') {
            $rules["referral_id"] = "required|exists:users,id";
        }

        return $rules;
    }

}
