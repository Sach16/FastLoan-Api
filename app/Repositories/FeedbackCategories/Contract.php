<?php

namespace Whatsloan\Repositories\FeedbackCategories;

interface Contract
{

    /**
     * Get a paginated list
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15);
    
    public function find($uuid);

    /**
     * Store a new feedback category as admin
     *
     * @param array $request
     * @return mixed
     */
    public function storeAsAdmin(array $request);

    /**
     * Update a feedback category as admin
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function updateAsAdmin(array $request, $id);
}
