<?php

namespace Whatsloan\Repositories\Tasks;

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
     * Getting Tasks based on User
     * @return Task
     */
    public function getTasksByUserIds($ids);

    /**
     * Getting Member ID from uuid
     * @return array
     */
    public function getMemeberIds($uuids);

     /**
     * Update loan Task table
     * @param Task $task
     * @return boolean
     */
    public function updateTaskHistory($task);

     /**
     * Make a copy in Task history
     * @param \Whatsloan\Repositories\Tasks\Task $newObject
     * @param \Whatsloan\Repositories\Tasks\Task $oldObject
     */
    public function isTaskHistoryRequired(Task $newObject, Task $oldObject);

    /**
     *
     * @param type $input
     * @param type $uuid
     */
    public function updateStatus($input, $uuid);

    /**
     *
     * @param type $ids
     */
    public function getTodayTasks($ids, $task_status);

    /**
     * Update a task as admin
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function updateAsAdmin(array $request, $id);

    /**
     * Store a task as admin
     *
     * @param array $request
     * @return mixed
     */
    public function storeAsAdmin(array $request);

    /**
     * Transfer all tasks to a new user
     * 
     * @param array $request
     * @return mixed
     */
    public function transferOwnershipAsAdmin(array $request);

}
