<?php

namespace Whatsloan\Repositories\Users;

class UploadValidator
{

    /**
     * Validation rules for projects bulk
     * upload
     *
     * @return array
     */
    public static function rules()
    {
        return [
            "*.first_name"               => "required|min:3|max:255|alpha_spaces",
            "*.last_name"                => "required|max:255|alpha_spaces",
            "*.email_id"                 => "required|email",
            "*.phone_number"             => "required|digits:10",
            "*.date_of_birth"            => "required|date|date_format:d-m-Y",
            "*.permanent_account_number" => "required",
            "*.company_name"             => "required|alpha_spaces",
            "*.education"                => "required|alpha_spaces",
            "*.net_income"               => "required|numeric|min:0",
            "*.profession_id"            => "required|in:Salaried,Doctor,Self Employed-Professionals,Self Employed-Others",
            "*.cibil_score"              => "required|numeric",
            "*.cibil_status_id"          => "required|in:Settled A/C,Written off A/C,Overdue A/C,Good A/C",
            "*.salary_bank_name"         => "required|alpha_spaces",
            "*.marital_status_id"        => "required|in:Married,Unmarried",
            "*.resident_status_id"       => "required|in:Indian,NRI,PIO/OCI",
            "*.city_id"                  => "required|numeric|exists:cities,id",
        ];
    }
}
