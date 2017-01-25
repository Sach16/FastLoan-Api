<?php

if (!function_exists('label')) {

    /**
     * @param $string
     * @return string
     */
    function label($string)
    {
        return strtolower($string);
    }

}

if (!function_exists('upload')) {

    /**
     * Upload a file to default disk
     *
     * @param $path
     * @param $file
     * @param bool $pretend
     * @return mixed
     */
    function upload($path, $file, $pretend = false)
    {
        if ($pretend) {
            return $path . str_random(10);
        }

        $path = $path . time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        if (Storage::put($path, file_get_contents($file))) {
            return $path;
        }

        return false;
    }

}

if (!function_exists('uploaded')) {

    /**
     * Get the full path to uploaded file
     * @param $path
     * @return string
     */
    function uploaded($path)
    {
        return "https://s3.amazonaws.com/ink-test-bucket/" . $path;
    }

}

if (!function_exists('uuid')) {

    /**
     * Generate a uuid
     *
     * @return string
     * @throws Exception
     */
    function uuid()
    {
        return \Webpatser\Uuid\Uuid::generate()->string;
    }

}

if (!function_exists('withOldInputs')) {

    /**
     * Adds the old inputs to current form
     *
     * @param $inputs
     */
    function withOldInputs($inputs)
    {
        foreach ($inputs as $name => $value) {
            echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">";
        }
    }

}

if (!function_exists('hasErrors')) {

    /**
     * Returns a has error class
     *
     * @param $name
     * @param $errors
     * @return string
     */
    function hasErrors($name, $errors)
    {
        if (count($errors) > 0) {
            if (count($errors->getBag('default')->get($name)) > 0) {
                echo 'has-error';
            }
        }
    }

    if (!function_exists('isSelected')) {

        /**
         * @param $key
         * @param $array
         */
        function isSelected($key, $array)
        {
            if (in_array($key, $array)) {
                echo 'selected';
            }
        }

    }
}

if (!function_exists('authUser')) {

    /**
     * Get authUser info
     * return object
     */
    function authUser($guard = 'api')
    {
        return (\Auth::guard($guard)->user() != null) ? \Auth::guard($guard)->user() : \Auth::user();
    }

}

if (!function_exists('isMatching')) {

    /**
     * @param $key
     * @param $value
     */
    function isMatching($key, $value)
    {
        if ($key == $value) {
            echo 'selected';
        }
    }

}

if (!function_exists('rn')) {

    /**
     * Get the repository name from class path
     *
     * @param $class
     * @return mixed
     */
    function rn($class)
    {
        return explode('\\', $class)[3];
    }

}

if (!function_exists('s2l')) {

    /**
     * Convert a slug to a label
     *
     * @param $slug
     * @return string
     */
    function s2l($slug)
    {
        if ($slug == '-1' or $slug == '') {
            return 'NONE';
        }
        if (in_array($slug, ['pan'])) {
            return strtoupper(str_replace('_', ' ', $slug));
        }

        if (in_array($slug, ['salary_bank'])) {
            return 'Salary Bank Name';
        }

        return ucfirst(str_replace('_', ' ', $slug));
    }

}

if (!function_exists('teamMemberIds')) {

    /**
     *
     * @param boolean $acl - only member id returns if value is true and logged in user is member. Otherwise all team members
     * @return type
     */
    function teamMemberIds($acl = false, $guard = 'api')
    {
        if ($acl == true && authUser()->role == 'DSA_MEMBER') {
            return [authUser()->id];
        }
        $userTeam = \Whatsloan\Repositories\Users\User::with(['teams', 'teams.members'])->find(authUser()->id)->teams()->first();
        return $userTeam->members->lists('id')->all();
    }

}

if (!function_exists('teamMemberIdsAsAdmin')) {

    /**
     *
     * @param boolean $acl - only member id returns if value is true and logged in user is member. Otherwise all team members
     * @return type
     */
    function teamMemberIdsAsAdmin($acl = false)
    {
        $user = authUser('web');
        if ($acl == true && $user->role == 'DSA_MEMBER') {
            return [$user->id];
        }
        $userTeam = \Whatsloan\Repositories\Users\User::with(['teams', 'teams.members'])->find($user->id);

        if (!$userTeam->teams->isEmpty()) {
            return $userTeam->teams()->first()->members->lists('id')->all();
        }
    }

}

if (!function_exists('selectDropdown')) {

    function selectDropdown($id)
    {
        $selected = "";
        $data     = request()->all();
        if (isset($data['selected']) && !empty($data['selected']) && $data['selected'] != 'undefined') {
            $selected = $data['selected'];
        }
        return ($selected == $id) ? 'selected' : "";
    }

}

if (!function_exists('selectMultiDropdown')) {

    function selectMultiDropdown($id)
    {
        $selected = [];
        $data     = request()->all();
        if (isset($data['multiSelected']) && !empty($data['multiSelected']) && $data['multiSelected'] != 'undefined') {
            $selected = explode(',', $data['multiSelected']);
        }

        return (in_array($id, $selected)) ? 'selected' : "";
    }

}

if (!function_exists('panelActive')) {

    function panelActive($routeName)
    {
        return (str_contains(request()->route()->getName(), $routeName)) ? "collapse in" : "";
    }

}

if (!function_exists('selectedLink')) {

    function selectedLink($routeName)
    {
        echo ($routeName == request()->route()->getName()) ? '<i class="glyphicon glyphicon-chevron-right pull-right"></i>' : "";
    }

}

if (!function_exists('UserPesentName')) {

    /**
     * accepts users id and return the user present name
     * @param int $id user id
     * @return string
     */
    function UserPesentName($id)
    {
        $user = \Whatsloan\Repositories\Users\User::find($id);

        if ($user) {
            echo $user->present()->name;
        }
    }

}

if (!function_exists('indexStatus')) {

    /**
     * accepts users id and return the user present name
     * @param int $id user id
     * @return string
     */
    function indexStatus($model)
    {
        echo '<span class="label label-'.($model->trashed()? 'danger' : 'success').'">'.($model->trashed()? 'Disabled' : 'Enabled') .'</span>';
    }

}