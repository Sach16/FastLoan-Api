<?php

namespace Whatsloan\Repositories\Searchs;

use Illuminate\Http\Request;

interface Contract
{
    /**
     * Get a paginated list of tasks
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15); 
    
    /**
     * Store a new task
     *
     * @param $request
     * @return mixed
     */
    public function store($taskableObject, $request);

    /**
     * Update an existing task
     *
     * @param $id
     * @param $request
     * @return mixed
     */
    public function update($data, $uuid);   
    
    /**
     * Search
     */
    public function search($role);
 
}
