<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Tasks\Task;
use Whatsloan\Repositories\Loans\Loan;
Use Whatsloan\Repositories\Users\User;

class TaskTransformer extends TransformerAbstract {

    /**
     * @var array
     */
    protected $defaultIncludes = [        
        
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [
        'user',
        'assignee',
        'status',
        'stage',
        'members',
        'customer'
    ];

    /**
     * @param $e
     * @return array
     */
    public function transform(Task $task) {
        return [
            'uuid' => $task->uuid,
            'priority' => $task->priority,
            'description' => $task->description,
            'from' => $task->from,
            'to' => $task->to,
            'task_status_id' => $task->task_status_id,
            'task_stage_id' => $task->task_stage_id,
            'created_at' => $task->created_at,
        ];
    }

    /**
     * include users
     * @return [type] [description]
     */
    public function includeUser(Task $task) {
        return $this->item($task->user, new UserTransformer);
    }

    /**
     * include assignee
     * @return [type] [description]
     */
    public function includeAssignee(Task $task) {
        return $this->item($task->assignee, new UserTransformer);
    }

    /**
     * include Status
     * @return [type] [description]
     */
    public function includeStatus(Task $task) {
        return $this->item($task->status, new TaskStatusTransformer);
    }
    
    /**
     * include Status
     * @return [type] [description]
     */
    public function includeStage(Task $task) {
        return $this->item($task->stage, new TaskStageTransformer);
    }
    
    /**
     * include Status
     * @return [type] [description]
     */
    public function includeMembers(Task $task) {
        return $this->collection($task->members, new UserTransformer);
    }

    /**
     * include customer
     * @return [type] [description]
     */
    public function includeCustomer(Task $task) {
        // $user_id = Loan::find($task->taskable_id)->user_id;
        if(Loan::find($task->taskable_id))
        return $this->item(User::find(Loan::find($task->taskable_id)->user_id), new UserTransformer);
    }

}
