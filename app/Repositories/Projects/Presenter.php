<?php

namespace Whatsloan\Repositories\Projects;

class Presenter extends \Laracasts\Presenter\Presenter
{

    /**
     * Present the full address of the bank
     *
     * @return mixed
     */
    public function address()
    {
        if (count($this->addresses))
        {
            $address = $this->addresses->first()->alpha_street;
            $address .= ', ';
            $address .= $this->addresses->first()->beta_street;
            $address .= ', ';
            $address .= $this->addresses->first()->city->name;
            $address .= ', ';
            $address .= $this->addresses->first()->country;
            return $address;
        }
    }

    /**
     * Present the possession date
     * 
     * @return mixed
     */
    public function possessionDate()
    {
        return $this->possession_date->format('d-m-Y');
    }

    /**
     * Present the possession date
     *
     * @return mixed
     */
    public function possessionInput()
    {
        return $this->possession_date->format('d-m-Y');
    }
}