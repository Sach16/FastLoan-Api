<?php

namespace Whatsloan\Http\Transformers\V1\Consumers;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Leads\Lead;

class LeadTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = [
        'addresses',
//        'sources',
        'types',
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
            'name'              => $lead->name,
            'loan_amount'       => $lead->loan_amount,
            'net_salary'        => $lead->net_salary,
            'existing_loan_emi' => $lead->existing_loan_emi,
            'company_name'      => $lead->company_name,
            'created_by'        => $lead->created_by,
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
     * @return \League\Fractal\Resource\Collection
     */
    public function includeAddresses(Lead $lead)
    {
        return $this->collection($lead->addresses, new AddressTransformer);
    }

    /**
     * @param Lead $lead
     * @return \League\Fractal\Resource\Item
     */
    public function includeSources(Lead $lead)
    {
        return $this->collection($lead->sources(), new SourceTransformer);
    }

    /**
     * @param Lead $lead
     * @return \League\Fractal\Resource\Item
     */
    public function includeTypes(Lead $lead)
    {
        return $this->collection($lead->types(), new TypeTransformer);
    }
}
