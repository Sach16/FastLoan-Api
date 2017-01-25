<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class ReferralDashboardTransformer extends TransformerAbstract
{

    /**
     * @param array $data
     * @return array
     */
    public function transform(array $data)
    {
        return [
            'leads'               => [
                'count'  => (string) $data['leads']['count'],
                'amount' => (string) (($data['leads']['amount']) ? $data['leads']['amount'] : 0),
                'payout' => (string) (($data['leads']['payout']) ? $data['leads']['payout'] : 0),
            ],
            'logins'              => [
                'count'  => (string) $data['logins']['count'],
                'amount' => (string) (($data['logins']['amount']) ? $data['logins']['amount'] : 0),
                'payout' => (string) (($data['logins']['payout']) ? $data['logins']['payout'] : 0),
            ],
            'sanctions'           => [
                'count'  => (string) $data['sanctions']['count'],
                'amount' => (string) (($data['sanctions']['amount']) ? $data['sanctions']['amount'] : 0),
                'payout' => (string) (($data['sanctions']['payout']) ? $data['sanctions']['payout'] : 0),
            ],
            'disbursals'          => [
                'count'  => (string) $data['disbursals']['count'],
                'amount' => (string) (($data['disbursals']['amount']) ? $data['disbursals']['amount'] : 0),
                'payout' => (string) (($data['disbursals']['payout']) ? $data['disbursals']['payout'] : 0),
            ],
            'total_paid'          => [
                'amount' => (string) (($data['total_paid']['amount']) ? $data['total_paid']['amount'] : 0),
            ],
            'total_payout_earned' => [
                'amount' => (string) (($data['total_payout_earned']['amount']) ? $data['total_payout_earned']['amount'] : 0),
            ],
            'balance'             => [
                'amount' => (string) (($data['balance']['amount']) ? $data['balance']['amount'] : 0),
            ],
        ];
    }
}