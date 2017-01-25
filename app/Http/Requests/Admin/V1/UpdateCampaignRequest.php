<?php

namespace Whatsloan\Http\Requests\Admin\V1;

use Whatsloan\Http\Requests\Request;

class UpdateCampaignRequest extends Request
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
            "name"         => "required|min:3|alpha_spaces",
            "organizer"    => "required|min:3|alpha_spaces",
            "description"  => "required|min:3",
            "promotionals" => "required|min:3",
            "from"         => "required|date|before:to",
            "to"           => "required|date|after:from",
            "alphaStreet"  => "required|min:3",
            "city"         => "required|exists:cities,id",
            "state"        => "required|min:2|alpha_spaces",
            "country"      => "required|min:3|alpha_spaces",
            "zip"          => "required|digits:6",
            "team"         => "required|numeric|exists:teams,id",
            "members"      => "required|array",
        ];
    }
}
