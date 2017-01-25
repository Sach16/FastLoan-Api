<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class BuilderDashboardTransformer extends TransformerAbstract
{

    /**
     * @param array $data
     * @return array
     */
    public function transform(array $data)
    {
        return [
            'leads'              => [
                'count'  => (string) $data['leads']['count'],
                'amount' => (string) $data['leads']['amount'],
            ],
            'logins'             => [
                'count'  => (string) $data['logins']['count'],
                'amount' => (string) (($data['logins']['amount']) ? $data['logins']['amount'] : 0),
            ],
            'first_disbursement' => [
                'count'  => (string) $data['first_disbursement']['count'],
                'amount' => (string) (($data['first_disbursement']['amount']) ? $data['first_disbursement']['amount'] : 0),
            ],
            'part_disbursement'  => [
                'count'  => (string) $data['part_disbursement']['count'],
                'amount' => (string) (($data['part_disbursement']['amount']) ? $data['part_disbursement']['amount'] : 0),
            ],
            'final_disbursement'  => [
                'count'  => (string) $data['final_disbursement']['count'],
                'amount' => (string) (($data['final_disbursement']['amount']) ? $data['final_disbursement']['amount'] : 0),
            ],
            'payout'             => [
                'percentage' => (string) 25,
                'amount'     => (string) 10000,
                //'amount'     => (string) (((( $data['leads']['amount'] / 100 ) * $data['leads']['payout'])) ? (( $data['leads']['amount'] / 100 ) * $data['leads']['payout']) : 0),
            ],
        ];
    }
}