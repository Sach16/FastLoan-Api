<?php

namespace Whatsloan\Http\Requests\Admin\V1;

use Whatsloan\Http\Requests\Request;

class UpdateProjectRequest extends Request
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
            'project'     => 'required|min:3',
            'builder_id'  => 'required|numeric|exists:builders,id',
            'owner_id'    => 'required|numeric|exists:users,id',
            'units'       => 'required|numeric',
            'possession'  => 'required|date',
            'alphaStreet' => 'required|min:3',
            'betaStreet'  => 'min:3',
            'city'        => 'required|numeric|exists:cities,id',
            'state'       => 'required|min:2',
            'country'     => 'required|min:2',
            'zip'         => 'required|digits:6',
            'project_picture'   => 'max:4096|image|image_size:<=100',
            'status_id'         => 'required|exists:project_statuses,id'
        ];
    }
}
