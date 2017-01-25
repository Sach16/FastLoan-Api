<?php

namespace Whatsloan\Repositories\Users;

class Settings
{
    /**
     * @var
     */
    protected $user;

    /**
     * @var $allowed Allowed settings keys
     */
    protected $allowed = [
        'resident_status', 'profession', 'dob', 'age', 'education', 'marital_status',
        'company', 'net_income', 'pan', 'salary_bank', 'skype', 'facetime',
        'contact_time', 'cibil_score', 'cibil_status','DOJ','exp_on_DOJ','gender','joined_as'
    ];

    /**
     * Settings constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function merge(array $attributes)
    {
        $settings = array_merge($this->user->settings, array_only($attributes, $this->allowed));
        return $this->user->update(['settings' => $settings]);
    }
}