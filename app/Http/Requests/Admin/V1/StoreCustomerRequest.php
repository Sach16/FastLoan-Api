<?php

namespace Whatsloan\Http\Requests\Admin\V1;

use Whatsloan\Http\Requests\Request;

class StoreCustomerRequest extends Request
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
            "first_name"                      => "required|min:3|max:255|alpha_spaces",
            "last_name"                       => "required|max:255|alpha_spaces",
            "email"                           => "required|email",
            "phone"                           => "unique:users|required|digits:10",
            'designation_id'                  => 'required|required|numeric|exists:designations,id',
            "settings.dob"                    => "date|date_format:d-m-Y",
            "settings.permanentAccountNumber" => "min:6|alpha_spaces",
            "settings.skype"                  => "skype",
            "settings.company"                => "min:3",
            "settings.facetime"               => "min:3",
            "settings.net_income"             => "numeric|min:1",
            "settings.profession"             => "in:Salaried,Doctor,Self Employed-Professionals,Self Employed-Others",
            "settings.cibil_score"            => "numeric|min:0",
            "settings.cibil_status"           => "in:Settled A/C,Written off A/C,Overdue A/C,Good A/C",
            "settings.salary_bank"            => "min:3|alpha_spaces",
            "settings.contact_time"           => "min:3",
            "settings.marital_status"         => "in:-1,Married,Unmarried",
            "settings.resident_status"        => "in:Indian,NRI,PIO/OCI",
            'alphaStreet'                     => 'required|min:3',
            'betaStreet'                      => 'min:3',
            "city_id"                         => "required|exists:cities,id",
            'state'                           => 'required|min:2|alpha_spaces',
            'country'                         => 'required|min:2|alpha_spaces',
            'zipcode'                         => 'required|digits:6',
            "address_proof"                   => "mimes:pdf,jpeg,png,bmp,gif,svg|max:10240",
            "id_proof"                        => "mimes:pdf,jpeg,png,bmp,gif,svg|max:10240",
            "products_handled"                => "mimes:pdf,jpeg,png,bmp,gif|max:10240",
            "experience_with_banks"           => "mimes:pdf,jpeg,png,bmp,gif|max:10240",
            "profile_picture"                 => "image|image_size:<=100|max:4096",
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
            "settings.dateOfBirth.date_format"    => "Date of birth should be in Y-m-d format",
            "settings.permanentAccountNumber.min" => "Permanent account number should be a minimum of 6 characters",
            "settings.skype.min"                  => "Skype should be a minimum of 3 characters",
            "settings.company.min"                => "Company should be a minimum of 3 characters",
            "settings.facetime.min"               => "Facetime should be a minimum of 3 characters",
            "settings.education.min"              => "Education should be a minimum of 3 characters",
            "settings.net_income.numeric"         => "Net Income should be numeric",
            "settings.cibil_score.numeric"        => "Cibil score should be numeric",
            "settings.salary_bank"                => "Salary bank should be a minimum of 3 characters",
            "settings.contact_time.date_format"   => "Contact time should be in hours-minutes-seconds",
        ];
        return parent::messages();
    }
}
