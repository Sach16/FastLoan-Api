<?php

namespace Whatsloan\Repositories\Sources;

trait Sourceable
{

    /**
     * Model has many addresses
     */
    public function sources()
    {
        return $this->belongsTo(Source::class);
    }
}