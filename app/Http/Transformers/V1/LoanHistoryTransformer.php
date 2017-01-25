<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\LoanHistories\LoanHistory;

class LoanHistoryTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = [
        'status'
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
    public function transform(LoanHistory $loan)
    {
        return [
            'uuid' => $loan->uuid,
            'amount' => $loan->amount,
            'eligible_amount' => $loan->eligible_amount,
            'approved_amount' => $loan->approved_amount,
            'emi' => $loan->emi,
            'appid' => $loan->appid,
            'interest_rate' => $loan->interest_rate,
            'applied_on' => $loan->applied_on,
            'approval_date' => $loan->approval_date,
            'emi_start_date' => $loan->emi_start_date,
            'created_at' => $loan->created_at,
            'updated_at' => $loan->updated_at,
        ];
    }

    
    /**
     * Status of loan history
     * @param LoanHistory $loan
     */
    public function includeStatus(LoanHistory $loan)
    {
        return $this->item($loan->status, new LoanStatusTransformer);
    }

}
