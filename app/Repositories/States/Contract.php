<?php

namespace Whatsloan\Repositories\States;

interface Contract
{

    /**
     * Get a paginated list of all states
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15);

    /**
     * Update an existing state
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function update(array $request, $id);

    /**
     * Store a new state
     * 
     * @param array $request
     * @return mixed
     */
    public function store(array $request);
}
