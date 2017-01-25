<?php

namespace Whatsloan\Http\Controllers\Api\V1;
use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Transformers\V1\TaskStatusTransformer;
use Whatsloan\Repositories\TaskStatuses\Contract as TaskStatuses;
use Whatsloan\Http\Transformers\V1\ResourceCreated;
use Whatsloan\Http\Transformers\V1\ValidationError;

class TaskStatusesController extends Controller
{
    /**
     * @var TaskStages
     */
    private $task_statuses;
    
    /**
     * TaskStagesController constructor
     *
     * @param TaskStage $task_stages
     */
    public function __construct(TaskStatuses $task_statuses)
    {
        $this->task_statuses = $task_statuses;         
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $task_statuses = $this->task_statuses->getTaskStatuses();
        return $this->transformCollection($task_statuses, TaskStatusTransformer::class);
    }
}
