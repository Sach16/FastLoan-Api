<?php

namespace Whatsloan\Repositories\TaskStages;

use Illuminate\Http\Request;

interface Contract
{

    /**
     * Get a paginated list of TaskStages
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15);

    public function getTaskStages();

    /**
     * Store a new task stage as admin
     * 
     * @param array $request
     * @return mixed
     */
    public function storeAsAdmin(array $request);

    /**
     * Update task stage as admin
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function updateAsAdmin(array $request, $id);

}
