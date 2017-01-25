<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Whatsloan\Repositories\Queries;

interface Contract
{

    /**
     * Get a paginated list of lsr queries
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15);

    /**
     * Save new lsr query
     *
     * @param array $data
     */
    public function store($data);


    /**
     * Update lsr query
     * @param string $uuid
     * @param array $data
     */
    public function update($uuid,$data);

    /**
     * Update a query as admin
     * 
     * @param $request
     * @param $queryId
     * @return mixed
     */
    public function updateAsAdmin($request, $queryId);

    /**
     * Store a query as admin
     *
     * @param array $request
     * @return mixed
     */
    public function storeAsAdmin(array $request);
}
