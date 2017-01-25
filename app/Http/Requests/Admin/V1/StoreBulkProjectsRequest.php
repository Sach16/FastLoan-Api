<?php

namespace Whatsloan\Http\Requests\Admin\V1;

use Whatsloan\Http\Requests\Request;

class StoreBulkProjectsRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (request()->file('upload')->getClientOriginalExtension() != 'xlsx') ? false : true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //'upload' => 'required|mimes:xlsx|max:2048'
            'upload' => 'required||max:2048'
        ];
    }
}
