<?php

namespace Whatsloan\Http\Requests\Admin\V1;

use Whatsloan\Http\Requests\Request;

class StoreReferralRequest extends Request
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
            "first_name"               => "required|min:3|max:255|alpha_spaces",
            "last_name"                => "required|max:255|alpha_spaces",
            'designation_id'           => 'required|numeric|exists:designations,id',
            "email"                    => "required|email",
            "phone"                    => "required|digits:10",
            "settings.dob"             => "date|date_format:d-m-Y",
            "settings.pan"             => "alpha_num|min:6",
            "settings.skype"           => "min:3",
            "settings.company"         => "min:3",
            "settings.facetime"        => "min:3",
            // "settings.education"       => "min:3",
            "settings.net_income"      => "numeric",
            "settings.profession"      => "in:-1,Salaried,Doctor,Self Employed-Professionals,Self Employed-Others",
            "settings.cibil_score"     => "numeric",
            "settings.cibil_status"    => "in:-1,Settled A/C,Written off A/C,Overdue A/C,Good A/C",
            "settings.salary_bank"     => "min:3",
            "settings.contact_time"    => "min:3",
            "settings.marital_status"  => "in:-1,Married,Unmarried",
            "settings.resident_status" => "in:-1,Indian,NRI,PIO/OCI",
            'alphaStreet'              => 'required|min:3',
            'betaStreet'               => 'min:3',
            'city_id'                  => 'required|numeric|exists:cities,id',
            'state'                    => 'required|min:2|alpha_spaces',
            'country'                  => 'required|min:2|alpha_spaces',
            'zipcode'                  => 'required|digits:6',
        ];
    }
}
