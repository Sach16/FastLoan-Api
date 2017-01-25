<?php

namespace Whatsloan\Repositories\Types;

interface Contract
{

    /**
     * Get a paginated list of type
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15);
    
     /**
     * Update an existing product type
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function update(array $request, $id);

    /**
     * Store a new product type
     * 
     * @param array $request
     * @return mixed
     */
    public function store(array $request);
   
}
