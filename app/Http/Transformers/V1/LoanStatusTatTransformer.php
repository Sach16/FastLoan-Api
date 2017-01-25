<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\LoanHistories\LoanHistory;

class LoanStatusTatTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = [
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [
    ];

    /**
     * Loan history transform
     * @param LoanHistory $loan
     * @return type
     */
    public function transform($status_tat)
    {
        return [
            'status_tat' => $status_tat['status_tat'],
            'status'     => $status_tat['status'],

        ];
    }

}
