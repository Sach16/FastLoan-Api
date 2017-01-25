<?php

namespace Whatsloan\Repositories\Teams;

interface Contract
{

    /**
     * Get a paginated list of teams
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15);



     /**
     * Get the details of a single team
     *
     * @param $uuid
     * @return mixed
     */
    public function find($uuid);

    /**
     * Update a team as admin
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function updateAsAdmin(array $request, $id);

    /**
     * Store a new team
     *
     * @param array $request
     * @return mixed
     */
    public function storeAsAdmin(array $request);

    /**
     * Update a team settings
     *
     * @param array $request
     * @param $team_id
     * @param $member_id
     * @return mixed
     */
    public function updateSettings(array $request, $team_id,$member_id);

    /**
     * Update a Remove DSA from Team and assign his cutomer to Other Member
     *
     * @param array $request
     * @param $team_id
     * @param $member_id
     * @return mixed
     */
    public function updateRemoveDsaFromTeam(array $request, $team_id,$member_id);

    /**
     * Store a multiple owner to team
     *
     * @param array $request
     * @return mixed
     */
    public function addMultiOwner($owner_id, $team_id);
}
