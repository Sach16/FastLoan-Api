<?php
namespace Whatsloan\Validators;

use Illuminate\Validation\Validator;
use Carbon\Carbon;

class CustomValidator extends Validator
{
    public function validateAlphaSpaces($attribute, $value, $params)
    {
        return preg_match('/^[\pL\s]+$/u', $value);
    }
    public function validateIfscCode($attribute, $value, $params)
    {
        return preg_match('/[A-Z|a-z]{4}[0][0-9|A-Z|a-z]{6}$/', $value);
    }
    public function validateSkype($attribute, $value, $params)
    {
        return preg_match('/^[a-z][a-z0-9\.,\-_]{5,31}$/i', $value);
    }
    public function validateOlderThan($attribute, $value, $params)
    {
        $minAge = ( ! empty($params)) ? (int) $params[0] : 18;
        // return (new DateTime)->diff(new DateTime($value))->y >= $minAge;

        // or the same using Carbon:
        return Carbon::now()->diff(new Carbon($value))->y >= $minAge;
    }
}