<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Leads\Lead;
use Whatsloan\Repositories\Teams\Team;

class HomeTransformer extends TransformerAbstract
{

    /**
     * @param $e
     * @return array
     */
    public function transform($data)
    {
        return [
            'lead' => [
                'count' => $data['leadCount']->count,
            ],
            'customer' => [
                'count' => $data['customerCount']->count,
            ],
            'team' => [
                'data' => $data['team']
            ],
            'tracking_status' => $data['tracking_status'],
        ];
    }
}
