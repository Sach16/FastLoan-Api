<?php

namespace Whatsloan\Repositories\Addresses;

trait Addressable
{

    /**
     * Model has many addresses
     */
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }
}
