<?php

namespace Whatsloan\Http\Controllers\Api\V1;
use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Transformers\V1\TaskStageTransformer;
use Whatsloan\Repositories\TaskStages\Contract as TaskStages;
use Whatsloan\Http\Transformers\V1\ResourceCreated;
use Whatsloan\Http\Transformers\V1\ValidationError;

class TaskStagesController extends Controller
{
    /**
     * @var TaskStages
     */
    private $task_stages;
    
    /**
     * TaskStagesController constructor
     *
     * @param TaskStage $task_stages
     */
    public function __construct(TaskStages $task_stages)
    {
        $this->task_stages = $task_stages;         
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $task_stages = $this->task_stages->getTaskStages();        
        return $this->transformCollection($task_stages, TaskStageTransformer::class);
    }
}
