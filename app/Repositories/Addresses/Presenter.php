<?php

namespace Whatsloan\Repositories\Addresses;

class Presenter extends \Laracasts\Presenter\Presenter
{

    /**
     * Present the full address of the bank
     *
     * @return mixed
     */
    public function address()
    {
        $address = $this->alpha_street;
        $address .= ', ';
        if ($this->beta_street) {
            $address .= $this->beta_street;
            $address .= ', ';
        }
        $address .= $this->city->name;
        $address .= ', ';
        $address .= $this->state;
        $address .= ', ';
        $address .= $this->country;
        $address .= ', ';
        $address .= $this->zip;
        return $address;
    }
}
