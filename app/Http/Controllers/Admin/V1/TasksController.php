<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Requests\Admin\V1\StoreTaskRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateTaskRequest;
use Whatsloan\Jobs\StoreTaskJob;
use Whatsloan\Jobs\UpdateTaskJob;
use Whatsloan\Repositories\Loans\Loan;
use Whatsloan\Repositories\Tasks\Task;
use Whatsloan\Repositories\TaskStages\TaskStage;
use Whatsloan\Repositories\TaskStatuses\TaskStatus;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Jobs\StoreLoanDocumentJob;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::with('teams')->find(\Auth::user()->id);
        $tasks = (new Task)->newQuery()->with(['user', 'status', 'stage']);

        if ($user->role == 'DSA_OWNER') {
            $tasks = $tasks->whereIn('user_id',  teamMemberIds(true));
        }
        $tasks = $tasks->withTrashed();
        return view('admin.v1.tasks.index')->withTasks($tasks->orderBy('deleted_at', 'asc')->paginate());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = TaskStatus::where('key','TO_BE_STARTED')->get();
        $stages = TaskStage::all();

        return view('admin.v1.tasks.create')
            ->withStatuses($statuses)
            ->withStages($stages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreTaskRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request)
    {
        $request->offsetSet('user_id', request()->user()->id);
        $task = $this->dispatch(new StoreTaskJob($request->except('document')));
        
        $loan = Loan::whereId($task->taskable_id)->first();  
        if($request->file('document')) { 
            $upload = upload($loan->getLoanDocumentPath(), $request->file('document'));
            
            if ($upload) {
                $request->offsetSet('attachment', $upload);
                $this->dispatch(new StoreLoanDocumentJob($request->except('document'), $loan));  
            }    
        }
        
        return redirect()->route('admin.v1.tasks.index')->withSuccess('Task added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($this->isAuthorized($id)){
            return redirect()->route('admin.v1.tasks.index')->withSuccess('Access Restricted');
        }
        $user = User::with('teams')->find(\Auth::user()->id);
        $task = (new Task)->newQuery()->with(['user', 'status', 'stage'])->withTrashed();
        
        $task_id = Task::withTrashed()->find($id);  
        $taskDocuments = Loan::withTrashed()->with(['attachments' => function($query) use($task_id) {
                    $query->whereIn('type', ['LOAN_DOCUMENT']);
                }])->find($task_id->taskable_id);
        return view('admin.v1.tasks.show')->withTask($task->find($id))->withDocuments($taskDocuments);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if($this->isAuthorized($id)){
            return redirect()->route('admin.v1.tasks.index')->withSuccess('Access Restricted');
        }
        $user = User::with('teams')->find(\Auth::user()->id);

        $task = (new Task)->newQuery()->with(['user', 'status', 'stage'])
                ->withTrashed();

        $statuses = TaskStatus::all();
        $stages = TaskStage::all();

        return view('admin.v1.tasks.edit')
                    ->withStatuses($statuses)
                    ->withStages($stages)
                    ->withTask($task->find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateTaskRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskRequest $request, $id)
    {
        $this->dispatch(new UpdateTaskJob($request->all(), $id));

        $task = Task::withTrashed()->find($id);  
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

        return redirect()->route('admin.v1.tasks.show', $id)->withSuccess('Task updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::withTrashed()->findOrFail($id);
        $task->trashed() ? $task->restore() : $task->delete();
        return redirect()->route('admin.v1.tasks.index')->withSuccess('Task updated successfully');
    }

    /**
     * Authorized the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    private function isAuthorized($id)
    {
        if(\Auth::user()->role <> 'SUPER_ADMIN')
        {
            $tasks = (new Task)->newQuery()->with(['user', 'status', 'stage'])->whereId($id)->withTrashed()->first();
            if(!in_array($tasks->user_id,teamMemberIds(true)))
            {
                return true;
            }
        }
    }
}
