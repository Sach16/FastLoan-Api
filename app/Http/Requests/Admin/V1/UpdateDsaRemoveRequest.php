<?php

namespace Whatsloan\Http\Requests\Admin\V1;

use Whatsloan\Http\Requests\Request;

class UpdateDsaRemoveRequest extends Request
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
            'assigned_to'               => 'required|exists:users,id',
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
            "assigned_to.required"  => "The Assign customers to another DSA field is required..",
            "assigned_to.exists"    => "The selected Assign customers to another DSA is invalid.",
        ];
        return parent::messages();
    }
}
