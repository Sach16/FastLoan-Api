<?php

namespace Whatsloan\Http\Controllers\Api\V1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Repositories\Loans\Loan;
use Whatsloan\Repositories\Tasks\Task;
use Whatsloan\Repositories\Leads\Lead;
use Whatsloan\Repositories\Teams\Team;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\TaskStages\TaskStage;
use Whatsloan\Repositories\TaskStatuses\TaskStatus;
use Whatsloan\Http\Transformers\V1\TaskTransformer;
use Whatsloan\Repositories\Tasks\Contract as Tasks;
use Whatsloan\Repositories\Users\Contract as Users;
use Whatsloan\Http\Requests\Api\V1\StoreTaskRequest;
use Whatsloan\Http\Requests\Api\V1\UpdateTaskRequest;
use Whatsloan\Http\Requests\Api\V1\UpdateTaskStatusRequest;
use Whatsloan\Http\Transformers\V1\ResourceCreated;
use Whatsloan\Http\Transformers\V1\ResourceUpdated;
use Whatsloan\Jobs\StoreLoanDocumentJob;
use Whatsloan\Http\Transformers\V1\ValidationError;


class TasksController extends Controller
{
    /**
     * @var Tasks
     */
    private $tasks;
    
     /**
     * @var Leads
     */
    private $users;
    
    /**
     * TasksController constructor
     *
     * @param Tasks $tasks
     */
    public function __construct(Tasks $tasks,Users $users)
    {
        $this->tasks = $tasks; 
        $this->users = $users;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authUser = \Auth::guard('api')->user();
        $ids = (authUser()->role == 'DSA_OWNER')
                    ? $this->users->getMemberIds(authUser()->uuid)
                    : [authUser()->id];
        $tasks = $this->tasks->getTasksByUserIds($ids);        
        return $this->transformCollection($tasks, TaskTransformer::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request)
    {
        $taskableObject = null;        
        $taskableObject = Loan::whereUuid($request['loan_uuid'])->first();
        $data = $request->all();
        $task = $this->tasks->store($taskableObject,$data);
        return $this->transformItem($task, ResourceCreated::class, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskRequest $request, $uuid)
    {
        $data = $request->all();        
        $task = $this->tasks->update($data, $uuid);
        return $this->transformItem($task, ResourceUpdated::class);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    } 
    
    /**
     * 
     * @param type $uuid
     * @return type
     */
    public function updateStatus(UpdateTaskStatusRequest $request, $taskId)
    {
        $task = Task::whereUuid($taskId)->first();  
        $loan = Loan::whereId($task->taskable_id)->first();  
        
        if($request->file('document')) { 
            $upload = upload($loan->getLoanDocumentPath(), $request->file('document'));
            
            if ($upload) {
                $request->offsetSet('attachment', $upload);
                $request->offsetSet('name', $request->name); 
                $request->offsetSet('description', $request->remarks);                  
                $this->dispatch(new StoreLoanDocumentJob($request->except('document'), $loan));                
            }    
        }
        
        $task = $this->tasks->updateStatus($request, $taskId);
        return $this->transformItem($task, ResourceUpdated::class);
    }
    
    /**
     * 
     * @param type $uuid
     * @return type
     */
    public function todayTasks()
    {
        $authUser = \Auth::guard('api')->user();
        $input = request()->all();       
        if(isset($input['assigned_to_uuid']) && !empty($input['assigned_to_uuid'])) { 
            $ids = [User::whereUuid($input['assigned_to_uuid'])->first()->id];             
        } else {            
             $ids = (authUser()->role == 'DSA_OWNER')
                    ? $this->users->getMemberIds(authUser()->uuid)
                    : [authUser()->id];
             $task_status = '';
        } 
        if(isset($input['task_status_uuid']) && !empty($input['task_status_uuid'])) { 
            $task_status = [TaskStatus::whereUuid($input['task_status_uuid'])->first()->key];              
        } else {
             $task_status = '';
        } 
        
        $tasks = $this->tasks->getTodayTasks($ids, $task_status); 
        return $this->transformCollection($tasks, TaskTransformer::class);
    }
    
    /**
     * Get User tasks by user ID
     * @param type $uuid
     */    
    public function getUserTasks()
    {
        $tasks = $this->tasks->getUserTasks();
        return $this->transformCollection($tasks, TaskTransformer::class);
    }
    
}
