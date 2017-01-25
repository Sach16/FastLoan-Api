<?php
namespace Whatsloan\Repositories\TaskStatuses;

use Illuminate\Http\Request;

class Repository implements Contract
{
    /**
     * Team Model
     * @var
     */
    protected $task_status;


    /**
     * Constructor
     * @param TaskStage $task_stage - TaskStage Model
     */
    public function __construct(TaskStatus $task_status)
    {
        $this->task_status = $task_status;
    }

    /**
     * Get a paginated list of task_stages
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15)
    {
         return $this->task_status->paginate($limit);
    }

    public function getTaskStatuses()
    {
        $task_status = TaskStatus::get();
        return $task_status;
    }

    /**
     * Update task status as admin
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function updateAsAdmin(array $request, $id)
    {
        $status = $this->task_status->find($id);
        $status->update($request);

        return $status;
    }

    /**
     * Store a task status as admin
     *
     * @param array $request
     * @return mixed
     */
    public function storeAsAdmin(array $request)
    {   $request['uuid'] = uuid();
        return $this->task_status->create($request);
    }
}
