<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Campaigns\Campaign;

class CampaignTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = [
        'team',
        'addresses',
        'campaignMembers'
        
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [];
    /**
     * @param Campaign $campaign
     * @return array
     */
    public function transform(Campaign $campaign)
    {
        return [
            'uuid'         => $campaign->uuid,
            'organizer'    => $campaign->organizer,
            'name'         => $campaign->name,
            'description'  => $campaign->description,
            'promotionals' => $campaign->promotionals,
            'type'         => $campaign->type,
            'from'         => $campaign->from,
            'to'           => $campaign->to,
            'created_at'   => $campaign->created_at,
            'updated_at'   => $campaign->updated_at,
            'links'        => [
                'rel' => 'self',
                'url' => route('api.v1.campaigns.show', $campaign->uuid)
            ]
        ];
    }

    /**
     * @param Campaign $campaign
     * @return \League\Fractal\Resource\Item
     */
    public function includeTeam(Campaign $campaign)
    {
        return $this->item($campaign->team()->first(), new TeamTransformer);
    }

    /**
     * @param Campaign $campaign
     * @return \League\Fractal\Resource\Collection
     */
    public function includeAddresses(Campaign $campaign)
    {
        return $this->collection($campaign->addresses(), new AddressTransformer);
    }
    
    /**
     * @param Campaign $campaign
     * @return \League\Fractal\Resource\Campaign Members
     */
    public function includeCampaignMembers(Campaign $campaign)
    {
        return $this->collection($campaign->members, new UserTransformer);
    }
}