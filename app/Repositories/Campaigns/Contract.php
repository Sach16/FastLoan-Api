<?php

namespace Whatsloan\Repositories\Campaigns;

interface Contract
{

    /**
     * Get a paginated list of campaigns
     *
     * @param input request, TeamID, int $limit
     * @return mixed
     */
    public function paginate($request, $team_id, $limit = 15);

    /**
     * Store a new campaign
     *
     * @param array $request
     * @return mixed
     */
    public function store(array $request);

    /**
     * Update a campaign
     * 
     * @param $id
     * @param $request
     * @return mixed
     */
    public function update($id, $request);
}
