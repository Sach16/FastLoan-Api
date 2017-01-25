<?php

namespace Whatsloan\Http\Requests\Admin\V1;

use Whatsloan\Http\Requests\Request;

class UpdateBankDocumentRequest extends Request
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
            'name'        => 'required|min:3|alpha_spaces',
            'description' => 'required|min:3|alpha_spaces',
            'document'    => 'mimes:pdf,jpeg,png,bmp,gif|max:10240',
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
            "name.required"    => "The Document name field is required",
            "name.alpha_spaces"    => "The Document name may only contain letters and spaces",
            "name.min"    => "The Document name  must be at least 3 characters",
        ];
        return parent::messages();
    }
}
