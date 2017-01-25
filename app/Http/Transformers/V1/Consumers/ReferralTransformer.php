<?php

namespace Whatsloan\Http\Transformers\V1\Consumers;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Leads\Lead;

class ReferralTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = [
        'user',
        'loan'
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * @param Lead $lead
     * @return array
     */
    public function transform(Lead $lead)
    {
        return [
            'uuid'              => $lead->uuid,
            'existing_loan_emi' => $lead->existing_loan_emi,
            'created_at'        => $lead->created_at,
            'updated_at'        => $lead->updated_at,
            
        ];
    }

   
    public function includeLoan(Lead $lead)
    {
        return $this->item($lead->loan, new LoanTransformer);
    }
    public function includeUser(Lead $lead)
    {
        if(!empty($lead->loan->user)){
            return $this->item($lead->loan->user, new UserTransformer );
        }
    }
}
