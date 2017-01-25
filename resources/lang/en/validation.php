<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
     */

    'accepted'             => 'The :attribute must be accepted.',
    'active_url'           => 'The :attribute is not a valid URL.',
    'after'                => 'The :attribute must be a date after :date.',
    'alpha'                => 'The :attribute may only contain letters.',
    'alpha_dash'           => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'The :attribute may only contain letters and numbers.',
    'array'                => 'The :attribute must be an array.',
    'before'               => 'The :attribute must be a date before :date.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => 'The :attribute confirmation does not match.',
    'date'                 => 'The :attribute is not a valid date.',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => 'The :attribute must be :digits digits.',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => 'The :attribute must be a valid email address.',
    'exists'               => 'The selected :attribute is invalid.',
    'filled'               => 'The :attribute field is required.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => 'The selected :attribute is invalid.',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => 'The :attribute must be an integer.',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'The :attribute must be a number.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'The :attribute field is required.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'The :attribute has already been taken.',
    'url'                  => 'The :attribute format is invalid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
     */

    'custom'               => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'zip'            => [
            'required' => 'The pincode field is required.',
            'digits'   => 'The pincode must be 6 digits.',
        ],
        'zipcode'        => [
            'required' => 'The pincode field is required.',
            'digits'   => 'The pincode must be 6 digits.',
        ],
        'state_id'       => [
            'required' => 'The state field is required.',
            'exists'   => 'The selected state is invalid',
        ],
        'city_id'        => [
            'required' => 'The city field is required.',
            'exists'   => 'The selected city is invalid',
        ],
        'alphaStreet'    => [
            'required' => 'The Street #1 field is required.',
            'min'      => 'The Street #1 must be at least :min.',
        ],
        'type_id'        => [
            'required' => 'The loan type field is required.',
            'exists'   => 'The selected loan type is invalid',
        ],
        'owner_id'       => [
            'required' => 'The owner name field is required.',
            'exists'   => 'The selected owner name is invalid',
        ],
        'builder_id'     => [
            'required' => 'The builder name field is required.',
            'exists'   => 'The selected builder name is invalid',
        ],
        'agent_id'       => [
            'required' => 'The DSA member field is required.',
            'exists'   => 'The selected DSA member is invalid',
        ],
        'source_id'      => [
            'required' => 'The source field is required.',
            'exists'   => 'The selected source is invalid',
        ],
        'task_status_id' => [
            'required' => 'The task status field is required.',
            'exists'   => 'The selected task status is invalid',
        ],
        'task_stage_id'  => [
            'required' => 'The task stage field is required.',
            'exists'   => 'The selected task stage is invalid',
        ],
        'from'           => [
            'required' => 'The from date field is required.',
            'exists'   => 'The selected from date is invalid',
        ],
        'to'             => [
            'required' => 'The to date field is required.',
            'exists'   => 'The selected to date is invalid',
        ],
        'status_id'      => [
            'required' => 'The status field is required.',
            'exists'   => 'The selected status is invalid',
        ],
        'loan_status_id' => [
            'required' => 'The loan status field is required.',
            'exists'   => 'The selected loan status is invalid',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
     */

    'attributes'           => [],
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Rules
    |--------------------------------------------------------------------------
    |
    | Custom rules created in app/validators.php
    |
     */
    "alpha_spaces"         => "The :attribute may only contain letters and spaces",
    "ifsc_code"            => "The :attribute may only contain valid IFSC code",
    "skype"                => "The :attribute may only contain valid Skype name",
    "older_than"           => "The date of birth may only contain minimum age to be 18 years old",

];
