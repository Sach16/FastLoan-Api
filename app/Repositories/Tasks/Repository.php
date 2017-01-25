<?php

namespace Whatsloan\Repositories\Tasks;

use Illuminate\Http\Request;
use Uuid;
use Whatsloan\Repositories\LoanDocuments\LoanDocument;
use Whatsloan\Repositories\Loans\Loan;
use Whatsloan\Repositories\TaskHistories\TaskHistory;
use Whatsloan\Repositories\TaskStages\TaskStage;
use Whatsloan\Repositories\TaskStatuses\TaskStatus;
use Whatsloan\Repositories\Teams\Team;
use Whatsloan\Repositories\Users\User;

class Repository implements Contract
{
    /**
     * @var Task
     */
    private $task;

    /**
     * History table will be updated if any of below columns updated
     * @var Mixed Boolean/Array
     */
    public $updateTaskHistory = true;

    /**
     * Repository constructor.
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get a paginated list of tasks
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15)
    {
        return $this->task->paginate($limit);
    }

    /**
     * Store a new Task
     *
     * @param $request
     * @return mixed
     */
    public function store($taskableObject, $data)
    {
        $task                 = new Task($data);
        $task->uuid           = Uuid::generate()->string;
        $task->user_id        = authUser()->id;
        $task->task_status_id = TaskStatus::where('uuid', $data['task_status_uuid'])->first()->id;
        $task->task_stage_id  = TaskStage::where('uuid', $data['task_stage_uuid'])->first()->id;
        $task->priority       = $data['priority'];
        $task->description    = $data['description'];
        $task->from           = $data['from'];
        $task->to             = $data['to'];
        $taskableObject->tasks()->save($task);
        $task->members()->attach(User::whereUuid($data['member_uuid'])->first()->id);
        $task->remarks = $task->description;
        $this->updateTaskHistory($task);
        return $task;
    }

    /**
     * Update an existing task
     *
     * @param $id
     * @param $request
     * @return mixed
     */
    public function update($data, $uuid)
    {
        $taskableObject = null;
        $taskableObject = Loan::whereUuid($data['loan_uuid'])->first();

        $data['user_id']        = authUser()->id;
        $data['member_id']      = User::whereUuid($data['member_uuid'])->first()->id;
        $data['task_status_id'] = TaskStatus::where('uuid', $data['task_status_uuid'])->first()->id;
        $data['task_stage_id']  = TaskStage::where('uuid', $data['task_stage_uuid'])->first()->id;
        $data['taskable_id']    = $taskableObject->id;
        $data['taskable_type']  = get_class($taskableObject);

        $task     = $this->task->whereUuid($uuid)->first();
        $taskTemp = $task->replicate();
        $task->update($data);
        $task->members()->detach();
        $task->members()->attach($data['member_id']);
        if ($this->isTaskHistoryRequired($task, $taskTemp)) {
            $this->updateTaskHistory($task);
        }
        return $task;
    }

    /**
     * Make a copy in Task history
     * @param \Whatsloan\Repositories\Tasks\Task $newObject
     * @param \Whatsloan\Repositories\Tasks\Task $oldObject
     */
    public function isTaskHistoryRequired(Task $newObject, Task $oldObject)
    {
        if ($this->updateTaskHistory === true) {
            return true;
        }

        if (is_array($this->updateTaskHistory)) {
            foreach ($this->updateTaskHistory as $columns) {
                if ($newObject[$columns] != $oldObject[$columns]) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Getting Tasks based on User
     * @return Tasks
     */
    public function getTasksByUserIds($ids)
    {
        return $this->task
            ->with(['status', 'stage', 'user', 'members'])
            ->whereHas('members', function ($query) use ($ids) {
                $query->whereIn('user_id', $ids);
            })->paginate();
    }

    /**
     * Get Member Ids
     * @param Member $uuids
     * @return member ids array
     */
    public function getMemeberIds($uuids)
    {
        foreach ($uuids as $uuid) {
            $ids[] = User::whereUuid($uuid)->first()->id;
        }
        return $ids;
    }

    /**
     * Get TeamMember Ids
     * @param Team $uuid
     * @return Team Member ids array
     */
    public function getTeamMemberIds($uuid)
    {
        $team_members_ids = Team::with(['members'])->where('uuid', $uuid)->first()->members()->lists("id")->all();
        return $team_members_ids;
    }
    /**
     * Update Task history table
     * @param Task $task
     * @return boolean
     */
    public function updateTaskHistory($task)
    {
        if ($task->remarks) {
            $remarks = $task->remarks;
        } else {
            $remarks = $task->description;
        }
        return TaskHistory::create([
            'uuid'           => uuid(),
            'user_id'        => $task->user_id,
            'modified_by'    => authUser()->id,
            'task_id'        => $task->id,
            'taskable_id'    => $task->taskable_id,
            'taskable_type'  => $task->taskable_type,
            'task_status_id' => $task->task_status_id,
            'task_stage_id'  => $task->task_stage_id,
            'priority'       => $task->priority,
            'description'    => $task->description,
            'remarks'        => $remarks,
            'from'           => $task->from,
            'to'             => $task->to,
        ]);
    }

    /**
     *
     * @param array $data
     * @param type $uuid
     * @return type
     */

    public function updateStatus($request, $uuid)
    {
        $data['task_status_uuid'] = $request['task_status_uuid'];
        $data['remarks']          = $request['remarks'];
        $data['task_status_id']   = TaskStatus::where('uuid', $data['task_status_uuid'])->first()->id;
        $task                     = $this->task->whereUuid($uuid)->first();
        $task->update($data);
        $task->remarks = $data['remarks'];
        $this->updateTaskHistory($task);
        return $task;
    }

    /**
     *
     * @param type $ids
     * @return type
     */
    public function getTodayTasks($ids, $task_status)
    {
        return $this->task->with(['stage', 'user'])
            ->with(['status' => function ($query) use ($task_status) {
                if (isset($task_status) && !empty($task_status)) {
                    $query->whereIn('key', $task_status);
                } else {
                    $query->whereNotIn('key', ['COMPLETED', 'CANCELLED']);
                }
            }])
            ->with(['members' => function ($query) use ($ids) {
                $query->whereIn('user_id', $ids);
            }])
            ->whereHas('status', function ($query) use ($task_status) {
                if (isset($task_status) && !empty($task_status)) {
                    $query->whereIn('key', $task_status);
                } else {
                    $query->whereNotIn('key', ['COMPLETED', 'CANCELLED']);
                }
            })
            ->whereHas('members', function ($query) use ($ids) {
                $query->whereIn('user_id', $ids);
            })
            ->whereDate('from', '<=', date('Y-m-d'))
            ->whereDate('to', '>=', date('Y-m-d'))
            ->paginate();
    }

    public function uploadTaskDocument($request, $taskId)
    {
        $task = $this->task->whereUuid($taskId)->first();
        $loan = Loan::with(['user'])->whereId($task->taskable_id)->first();

        if ($request->file('document')) {
            $upload = upload($loan->getDocumentPath(), $request->file('document'), true);
            if ($upload) {
                $request->offsetSet('uri', $upload);
                $document_attached = $this->dispatch(new UpdateCustomerDocumentJob($request->except('document'), $loan->user->id));
                $this->loans->saveAttachedDocument($document_attached->id, $loan->id);
            }
        }

        return $loan;

    }

    /**
     *
     * @param type $attachmentId
     * @param type $loanId
     * @return LoanDocument
     */
    public function saveAttachedDocument($attachmentId, $loanId)
    {
        $loan_document = new LoanDocument();

        $loan_document->uuid          = \Webpatser\Uuid\Uuid::generate()->string;
        $loan_document->loan_id       = $loanId;
        $loan_document->attachment_id = $attachmentId;
        $loan_document->save();

        return $loan_document;
    }

    /**
     * Update a task as admin
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function updateAsAdmin(array $request, $id)
    {
        $task = $this->task->withTrashed()->find($id);
        $task->update($request);

        return $task;
    }

    /**
     * Store a task as admin
     *
     * @param array $request
     * @return mixed
     */
    public function storeAsAdmin(array $request)
    {
        $task = $this->task->create($request);
        $loan = Loan::find($request['loan_id']);
        $loan->tasks()->save($task);
        $task->members()->attach($loan->agent_id);
        $this->updateTaskHistory($task);

        return $task;
    }

    /**
     * Get Customer tasks
     * @return type
     */
    public function getUserTasks()
    {
        if (empty(request()->user_uuid) || !isset(request()->user_uuid)) {
            return collect();
        }

        return $tasks = Task::with(['members'])
            ->whereHas('members', function ($query) {
                $query->where('user_id', User::whereUuid(request()->user_uuid)->first()->id);
            })->distinct()->paginate();
    }

    /**
     * Transfer all tasks to a new user
     *
     * @param array $request
     * @return mixed
     */
    public function transferOwnershipAsAdmin(array $request)
    {
        return $this->task->where('user_id', $request['from'])
            ->update(['user_id' => $request['to']]);
    }
}
