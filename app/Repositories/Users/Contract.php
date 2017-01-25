<?php

namespace Whatsloan\Repositories\Users;

interface Contract
{

    /**
     * Get all details of a user
     *
     * @param $uuid
     * @return mixed
     */
    public function show($uuid);

    /**
     * Update the current user
     *
     * @param $request
     * @param $uuid
     * @return mixed
     */
    public function update($request, $uuid);

    /**
     * Get User team
     * @param  string $role Role of user
     * @param  string $uuid uuid of user
     * @return Collection
     */
    public function getTeam($role, $uuid);

    /**
     * Get DSA owner team
     * @param $uuid
     * @return User
     */
    public function getDsaOwnerTeam($uuid);

    /**
     * Function get DSA member team details
     * @param  string $uuid
     * @return User
     */
    public function getDsaMemberTeam($uuid);



    /**
     * Get team members of a user
     * @param string $role
     * @param string $uuid
     * @param array $request
     */
    public function getTeamMembers($role, $uuid,$request);



    /**
     * Show customer details
     * @param type $uuid
     * @return type
     */
    public function showCustomer($uuid);



    /**
     * Paginated list of customers
     * @param int $paginate
     */
    public function customers($paginate = 15);



    /**
     * Function get Member IDs
     * @param  string $uuid
     * @return User IDs
     */
    public function getMemberIds($uuid);

    /**
     * Function get Task IDs
     * @param  string $uuid
     * @return Task IDs
     */
    public function getTaskIds($uuid);

    /**
     * Function get Team IDs
     * @param  string $uuid
     * @return Team IDs
     */
    public function getTeamId($uuid);

    /**
     * Update the user profile as admin
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function updateAsAdmin(array $request, $id);

    /**
     * Store a new user as admin
     *
     * @param $request
     * @return mixed
     */
    public function storeAsAdmin($request);

    /**
     * Update a customer document
     *
     * @param array $request
     * @param $customerId
     * @param $documentId
     * @return mixed
     */
    public function updateDocumentAsAdmin(array $request, $customerId, $documentId);

    /**
     * Store a customer document
     *
     * @param array $request
     * @param $customerId
     * @return mixed
     */
    public function storeDocumentAsAdmin(array $request, $customerId);

    /**
     *
     * @param type $request
     * @param type $uuid
     */
    public function updateCustomer($request, $uuid);

    /**
     * Transfer a phone number as admin
     *
     * @param array $request
     * @return mixed
     */
    public function transferPhoneAsAdmin(array $request);

    /**
     * set the user login credentials
     *
     * @param int $id
     * @return mixed
     */
    public function setCredentials($id);

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
