<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Leads\Lead;

class LeadTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = [
        'source',
        'assignee',
        'user',
        'loan',
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
            'links'             => [
                'rel' => 'self',
                'url' => route('api.v1.leads.show', $lead->uuid),
            ]
        ];
    }


    /**
     * @param Lead $lead
     * @return \League\Fractal\Resource\Item
     */
    public function includeSource(Lead $lead)
    {
        return $this->item($lead->source, new SourceTransformer);
    }

    
    /**
     * Include lead assigneee
     * @param Lead $lead
     * @return type
     */
    public function includeAssignee(Lead $lead) {
        return $this->item($lead->assignee, new UserTransformer);
    }
    
    
    
    /**
     * Include lead assigneee
     * @param Lead $lead
     * @return type
     */
    public function includeUser(Lead $lead) {
        return $this->item($lead->user, new UserTransformer);
    }
    
    /**
     * Include lead assigneee
     * @param Lead $lead
     * @return type
     */
    public function includeLoan(Lead $lead) {
        return $this->item($lead->loan, new LoanTransformer);
    }
    
    
    
}
