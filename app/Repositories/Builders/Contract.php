<?php

namespace Whatsloan\Repositories\Builders;

interface Contract
{


    /**
     * Paginated list of builder
     * @param integer $limit
     */
    public function paginate($limit = 15);

    /**
     * Update an existing builder
     * 
     * @param $request
     * @param $id
     * @return mixed
     */
    public function update($request, $id);
    
    /**
     * Getting builder who has the projects
     * @return type
     */
    public function getBuilders();
}
