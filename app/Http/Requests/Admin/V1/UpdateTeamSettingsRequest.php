<?php

namespace Whatsloan\Http\Requests\Admin\V1;

use Whatsloan\Http\Requests\Request;

class UpdateTeamSettingsRequest extends Request
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
            'target'               => 'required|integer|min:0',
            'achieved'             => 'required|integer|min:0',
            'incentive_earned'     => 'required|regex:/^\d*(\.\d{2})?$/',
            'incentive_plan'       => 'required|regex:/^\d*(\.\d{2})?$/',
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
            "incentive_earned.regex"    => "Incentive Plan should be in 1.00 format",
            "incentive_plan.regex"    => "Incentive Earned should be in 1.00 format",
        ];
        return parent::messages();
    }
}
