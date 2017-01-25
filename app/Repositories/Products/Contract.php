<?php

namespace Whatsloan\Repositories\Products;

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
     * Update an existing designation
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function update(array $request, $id);

    /**
     * Store a new designation
     * 
     * @param array $request
     * @return mixed
     */
    public function store(array $request);
   
    
}
