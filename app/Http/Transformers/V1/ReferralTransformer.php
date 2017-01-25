<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class ReferralTransformer extends TransformerAbstract
{


    /**
     * @param $e
     * @return array
     */
    public function transform(Referral $referrals)
    {
        return [];
    }
}
