<?php

namespace Whatsloan\Http\Transformers\V1;

use Illuminate\Support\Collection;
use League\Fractal\TransformerAbstract;

class TeamDashboardTransformer extends TransformerAbstract
{

    /**
     * @param array $data
     * @return array
     */
    public function transform(array $data)
    {
        return [
            'attendance'   => [
                'count' => (string) $data['attendance']['count'],
            ],
            'leads'        => [
                'count'  => (string) $data['leads']['count'],
                'amount' => (string) (($data['leads']['amount']) ? $data['leads']['amount'] : 0),
            ],
            'logins'       => [
                'count'  => (string) $data['logins']['count'],
                'amount' => (string) (($data['logins']['amount']) ? $data['logins']['amount'] : 0),
            ],
            'disbursements' => [
                'count'  => (string) $data['disbursements']['count'],
                'amount' => (string) (($data['disbursements']['amount']) ? $data['disbursements']['amount'] : 0),
            ],
            'waivers'      => [
                'count' => (string) 1,
            ],
            'sanctions'    => [
                'count' => (string) $data['sanctions']['count'],
            ],
            'deviations'   => [
                'count' => (string) 3,
            ],
            'target'   => [
                'count' => (string) $data['target'],
            ],
            'achieved'   => [
                'count' => (string) $data['achieved'],
            ],
            'incentive_plan'   => [
                'count' => (string) $data['incentive_plan'],
            ],
            'incentive_earned'   => [
                'count' => (string) $data['incentive_earned'],
            ],            
        ];
    }
}