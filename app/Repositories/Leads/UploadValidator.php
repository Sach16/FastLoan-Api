<?php

namespace Whatsloan\Repositories\Leads;

use Whatsloan\Repositories\Sources\Source;
use Whatsloan\Repositories\Types\Type;

class UploadValidator
{

    /**
     * Validation rules for projects bulk
     * upload
     *
     * @return array
     */
    public static function rules($row)
    {
        $referral = Source::where('key', 'REFERRAL')->first()->id;
        $homeLoan = Type::where('key', 'HL')->first()->id;

        $rules = [
            "first_name"               => "required|min:3|alpha_spaces",
            "last_name"                => "required|alpha_spaces",
            "city_id"                  => "required|numeric|exists:cities,id",
            "type_id"                  => "required|numeric|exists:types,id",
            "amount"                   => "required|numeric|min:0",
            "net_income"               => "required|numeric|min:0",
            "existing_loan_emi"        => "required|numeric|min:0",
            "source_id"                => "required|numeric|exists:sources,id",
            "phone_number"             => "required|digits:10|unique:users,phone",
            'email_id'                 => 'required|email',
            'company_name'             => 'required|alpha_spaces',
            'assigned_to'              => 'required|numeric|exists:users,id',
            'date_of_birth'            => 'required|date|date_format:d-m-Y|older_than',
            'permanent_account_number' => 'required',
            'education'                => 'required',
            'property_verified'        => 'required|in:YES,NO',
        ];

        if ($row['source_id'] == $referral) {
            $rules['referral_id'] = "required|numeric|exists:users,id";
        }

        if ($row['property_verified'] == 'YES') {
            $rules['project_id'] = "required|numeric|exists:projects,id";
        }

        return $rules;
    }

}
