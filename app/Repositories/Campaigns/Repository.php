<?php

namespace Whatsloan\Repositories\Campaigns;

class Repository implements Contract
{

    /**
     * @var Campaign
     */
    private $campaign;

    /**
     * Repository constructor.
     * @param Campaign $campaign
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Get a paginated list of campaigns
     * @param input request, TeamID, int $limit
     * @return mixed
     */
    public function paginate($request, $team_id, $limit = 15)
    {
        $query = $this->campaign
            ->where('team_id', isset($team_id) ? $team_id : null)
            ->with(['team', 'members']);

        if (isset($request['from_date']) && isset($request['to_date'])) {
            $query->whereDate('from', '>=', $request['from_date']);
            $query->whereDate('to', '<=', $request['to_date']);
        }
        $query->orderBy('updated_at', 'DESC');
        return $query->paginate($limit);
    }

    /**
     * Store a new campaign
     *
     * @param array $request
     * @return mixed
     */
    public function store(array $request)
    {
        $campaign = $this->campaign->create([
            'uuid'         => uuid(),
            'organizer'    => $request['organizer'],
            'name'         => $request['name'],
            'description'  => $request['description'],
            'promotionals' => $request['promotionals'],
            'from'         => $request['from'],
            'to'           => $request['to'],
            'type'         => $request['type'],
            'team_id'      => $request['team'],
        ]);
        $campaign->members()->sync($request['members']);
        $campaign->addresses()->create([
            'uuid'         => uuid(),
            'alpha_street' => $request['alphaStreet'],
            'beta_street'  => $request['betaStreet'],
            'city_id'      => $request['city_id'],
            'state'        => $request['state'],
            'country'      => $request['country'],
            'zip'          => $request['zipcode'],
        ]);
        return $campaign;
    }

    /**
     * Update a campaign
     *
     * @param $id
     * @param $request
     * @return mixed
     */
    public function update($id, $request)
    {
        $campaign = $this->campaign->find($id);
        $campaign->update($request);
        $campaign->members()->sync($request['members']);
        $campaign->addresses()->update([
            "alpha_street" => (isset($request["alphaStreet"])) ? $request["alphaStreet"] : "",
            "beta_street"  => (isset($request["betaStreet"])) ? $request["betaStreet"] : "",
            "city_id"      => (isset($request["city"])) ? $request["city"] : "",
            "state"        => (isset($request["state"])) ? $request["state"] : "",
            "country"      => (isset($request["country"])) ? $request["country"] : "",
            "zip"          => (isset($request["zip"])) ? $request["zip"] : "",
        ]);
        return $campaign;
    }

}
