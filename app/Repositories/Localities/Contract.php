<?php

namespace Whatsloan\Repositories\Localities;

interface Contract
{

    /**
     * Get a paginated list of all Localities
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15);

    /**
     * Update an existing Locality
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function update(array $request, $id);

    /**
     * Store a new Locality
     * 
     * @param array $request
     * @return mixed
     */
    public function store(array $request);
}
