<?php

namespace Whatsloan\Http\Requests\Admin\V1;

use Whatsloan\Http\Requests\Request;

class StoreBulkCustomersRequest extends Request
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
            'upload' => 'required|max:2048'
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
            "upload.mimetypes"    => "Upload file should be in xlsx format",
        ];
        return parent::messages();
    }
}
