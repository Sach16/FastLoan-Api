<?php

namespace Whatsloan\Repositories\Sources;

interface Contract
{

    /**
     * Get a paginated list of sources
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15);
    
    
    /**
     * Store source
     * @param array $data
     */
    public function store($data);
    
    
    /**
     * Get single source details
     * @param integer $id
     */
    public function find($id);
    
    /**
     * Update single source 
     * @param array $request
     * @param integer $id
     */
    public function update($request, $id);
}
