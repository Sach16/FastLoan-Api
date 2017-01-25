<?php

namespace Whatsloan\Repositories\Payouts;

trait Payoutable
{

    /**
     * Model has many payouts
     */
    public function payouts()
    {
        return $this->morphMany(Payout::class, 'payoutable');
    }

    /**
     * Model has many payouts
     */
    public function payoutsTrashed()
    {
        return $this->morphMany(Payout::class, 'payoutable')->withTrashed();
    }
}
