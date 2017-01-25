<?php

namespace Whatsloan\Repositories\Banks;

interface Contract
{

    /**
     * Get a single bank
     * @param  string $uuid
     * @return Item
     */
    public function find($uuid);

    /**
     * Get a paginated list of banks
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($params, $limit = 15);

    /**
     * Store a new bank
     *
     * @param $request
     * @return mixed
     */
    public function store($request);

    /**
     * Update an existing bank
     *
     * @param $id
     * @param $request
     * @return mixed
     */
    public function update($id, $request);

    /**
     * Update project approval status for bank
     *
     * @param $request
     * @param $bankId
     * @param $projectId
     * @return mixed
     */
    public function updateProjectApproval($request, $bankId, $projectId);


    /**
     * Store a new bank document
     *
     * @param $id
     * @param array $request
     * @return mixed
     */
    public function storeBankDocument($id, array $request);

    /**
     * Update a new bank document
     *
     * @param $id
     * @param $documentId
     * @param array $request
     * @return mixed
     */
    public function updateBankDocument($id, $documentId, array $request);

    /**
     * Update bank project details
     *
     * @param array $request
     * @param int $bankId
     * @param int $projectId
     */
    public function updateBankProject($request, $bankId, $projectId);

    /**
     * Get team members of a user
     * @param string $role
     * @param string $uuid
     * @param array $request
     */
    public function membersBanks();
     public function paginateAsConsumers($params, $limit = 15);
}
