<?php

namespace Whatsloan\Repositories\Types;

trait Typeable
{

    /**
     * Model has many addresses
     */
    public function types()
    {
        return $this->belongsTo(Type::class);
    }
}
