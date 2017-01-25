<?php

namespace Whatsloan\Repositories\Feedbacks;

interface Contract
{

    /**
     * Get a paginated list of cities
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($categoryId, $limit = 15);
    public function paginateAsConsumers($categoryId, $limit = 15);
    public function submitFeedback($request);
    /**
     * create new record
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function storeAsAdmin(array $data);

    /**
     * create new record
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function updateAsAdmin($id, array $data);
}
