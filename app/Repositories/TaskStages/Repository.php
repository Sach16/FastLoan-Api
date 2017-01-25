<?php
namespace Whatsloan\Repositories\TaskStages;

use Illuminate\Http\Request;

class Repository implements Contract
{
    /**
     * Team Model
     * @var
     */
    protected $task_stages;


    /**
     * Constructor
     * @param TaskStage $task_stage - TaskStage Model
     */
    public function __construct(TaskStage $task_stage)
    {
        $this->task_stage = $task_stage;
    }

    /**
     * Get a paginated list of task_stages
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15)
    {
         return $this->task_stage->paginate($limit);
    }

    public function getTaskStages()
    {
        $task_stages = TaskStage::get();
        return $task_stages;
    }

    /**
     * Update task stage as admin
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function updateAsAdmin(array $request, $id)
    {
        $stage = $this->task_stage->withTrashed()->find($id);
        $stage->update($request);

        return $stage;
    }

    /**
     * Store a new task stage as admin
     *
     * @param array $request
     * @return mixed
     */
    public function storeAsAdmin(array $request)
    {
        $request['uuid'] = uuid();
        return $this->task_stage->create($request);
    }
}
