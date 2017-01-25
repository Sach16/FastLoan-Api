<?php

namespace Whatsloan\Repositories\Cities;

interface Contract
{

    /**
     * Get a paginated list of cities
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15);
    public function paginateAsConsumers($limit = 15);

    /**
     * Store a new city as admin
     *
     * @param array $request
     * @return mixed
     */
    public function storeAsAdmin(array $request);

    /**
     * Update a city as admin
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function updateAsAdmin(array $request, $id);
}
