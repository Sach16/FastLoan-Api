<?php

namespace Whatsloan\Http\Requests\Api\V1;

use Whatsloan\Http\Requests\Api\V1\Request;

class UpdateUserTrackingStatusRequest extends Request
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
            "tracking_status" => "required|exists:track_users,uuid",            
        ];
    }

}
