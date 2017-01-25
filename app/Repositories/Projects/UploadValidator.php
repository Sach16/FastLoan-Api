<?php

namespace Whatsloan\Repositories\Projects;

class UploadValidator
{

    /**
     * Validation rules for projects bulk
     * upload
     *
     * @return array
     */
    public static function rules()
    {
        return [
            "*.name"            => "required|min:3|alpha_spaces",
            "*.builder_id"      => "required|numeric|exists:builders,id",
          //  "*.owner_id"        => "required|numeric|exists:users,id",
            "*.units_available" => "required|numeric",
            "*.status_id"       => "required|numeric|exists:project_statuses,id",
            "*.street_1"        => "required|min:3",
            "*.street_2"        => "min:3",
            "*.city_id"         => "required|numeric|exists:cities,id",
            "*.state"           => "required|min:2|alpha_spaces",
            "*.country"         => "required|min:2|alpha_spaces",
            "*.pin_code"        => "required|digits:6",
            "*.possession_date" => "required|date|date_format:d-m-Y",
        ];
    }
}
