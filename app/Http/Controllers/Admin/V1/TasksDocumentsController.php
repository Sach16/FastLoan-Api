<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Dotenv\Exception\InvalidFileException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Webpatser\Uuid\Uuid;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreTaskDocumentRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateTaskDocumentRequest;
use Whatsloan\Jobs\StoreLoanDocumentJob;
use Whatsloan\Jobs\UpdateTaskLoanDocumentJob;
use Whatsloan\Repositories\Attachments\Attachment;
use Whatsloan\Repositories\Loans\Loan;
use Whatsloan\Repositories\Tasks\Task;

class TasksDocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        return view('admin.v1.tasks.documents.create')->withId($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreTaskDocumentRequest $request
     * @param $taskId
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskDocumentRequest $request, $taskId)
    {
        $task = Task::find($taskId);  
        $loan = Loan::whereId($task->taskable_id)->first();  
        if($request->file('document')) { 
            $upload = upload($loan->getLoanDocumentPath(), $request->file('document'));
            
            if ($upload) {
                $request->offsetSet('attachment', $upload);
                $this->dispatch(new StoreLoanDocumentJob($request->except('document'), $loan));  
                return redirect()->route('admin.v1.tasks.show', $task->id)->withSuccess('Document uploaded');              
            }    
        }

        return redirect()->route('admin.v1.tasks.show', $task->id)->withError('Document could not be uploaded');
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
     * @param $taskId
     * @param $documentId
     * @return \Illuminate\Http\Response
     */
    public function edit($taskId, $documentId)
    {
        $task = Task::find($taskId);  
        $loan = Loan::with(['attachments' => function($query) use ($documentId){
                            $query->whereId($documentId)->take(1);
                        }])
                        ->whereId($task->taskable_id)->first();

        return view('admin.v1.tasks.documents.edit')
                        ->withAttachment($loan->attachments->first())
                        ->withTask($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateTaskDocumentRequest $request
     * @param $taskId
     * @param $documentId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskDocumentRequest $request, $taskId, $documentId)
    {
        $task = Task::find($taskId);  
        $loan = Loan::whereId($task->taskable_id)->first();  
        if($request->file('document')) { 
            $upload = upload($loan->getLoanDocumentPath(), $request->file('document'));
            
            if ($upload) {
                $request->offsetSet('attachment', $upload);

            }    
        }
        $this->dispatch(new UpdateTaskLoanDocumentJob($request->except('document'), $loan));  
        return redirect()->route('admin.v1.tasks.show', $task->id)->withSuccess('Task Document updated');              
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $taskId
     * @param $documentId
     * @return \Illuminate\Http\Response
     */
    public function destroy($taskId, $documentId)
    {
        $task = Task::find($taskId);  
        $loan = Loan::whereId($task->taskable_id)->first();

        Loan::with('attachments')
                        ->find($task->taskable_id)
                        ->attachments()
                        ->where('id', $documentId)
                        ->delete();

        return redirect()->back()->withSuccess('Document deleted');
    }
}
