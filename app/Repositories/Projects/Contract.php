<?php

namespace Whatsloan\Repositories\Projects;

interface Contract
{

    /**
     * Add new project
     * @param  array $data
     * @return mixed
     */
    public function store($data);

    /**
     * Add a new project as admin
     *
     * @param array $request
     * @return mixed
     */
    public function storeAsAdmin(array $request);

    /**
     * Get single project details
     * @param string $uuid
     */
    public function find($uuid);

    /**
     * Projects to be approved
     */
    public function toBeApproved();

    /**
     * Checkes whether project and bank already associated
     * @param integer $agentId
     * @param string $bankUuid
     * @param string $projectUuid
     * @return boolean
     */
    public function isBankAssociated($agentId, $bankUuid, $projectUuid);

    /**
     * Paginated list of approved projects
     * @param array $params
     * @param type $limit
     */
    public function approved($params, $limit = 15);

    /**
     * Update an existing project
     * 
     * @param array $request
     * @return mixed
     */
    public function updateAsAdmin(array $request);

    /**
     * Update a single project
     * @param string $uuid
     * @param array $data
     */
    public function update($uuid, $data);
    
    /**
     * 
     * @param type $data
     */
    public function getProjectsLists($data);

    /**
     * Create an Payout % for project
     * 
     * @param array $request
     * @return mixed
     */
    public function StorePayoutAsAdmin(array $request);

    /**
     * Update an existing project payout %
     * 
     * @param array $request
     * @return mixed
     */
    public function UpdatePayoutAsAdmin(array $request);
}
