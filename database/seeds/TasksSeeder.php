<?php

use Illuminate\Database\Seeder;
use Whatsloan\Repositories\Tasks\Task;
use Whatsloan\Repositories\TaskStatuses\TaskStatus;
use Whatsloan\Repositories\TaskStages\TaskStage;
use Whatsloan\Repositories\TaskHistories\TaskHistory;
use Whatsloan\Repositories\Leads\Lead;
use Whatsloan\Repositories\Loans\Loan;
use Whatsloan\Repositories\Teams\Team;
use Whatsloan\Repositories\Users\User;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $task_status = $this->taskStatuses();
        $task_stages = $this->taskStages();
        $member = factory(User::class)->create(['role' => 'DSA_MEMBER']);
        foreach (range(1, 30) as $taskCount) {
            $task = factory(Task::class)->create([
                'taskable_type' => get_class(Loan::first()),
                'taskable_id'   => $faker->randomElement(Loan::lists('id')->all()),
                'user_id'       => $member->id,                
                'task_status_id'=> $faker->randomElement($task_status->toArray()),
                'task_stage_id'=> $faker->randomElement($task_stages->toArray()),                
            ]);  
           
            factory(TaskHistory::class)->create([                   
                'modified_by' => $faker->randomElement(User::lists('id')->all()),
                'user_id' => $task->user_id,
                'task_id' => $task->id,                
                'taskable_id' => $task->taskable_id,
                'taskable_type' => $task->taskable_type,                
                'task_status_id'=> $task->task_status_id,
                'task_stage_id'=> $task->task_stage_id,                
                'priority' => $task->priority,
                'description' => $task->description,
                'remarks' => $task->description,
                'from' => $task->from,
                'to' => $task->to,                
            ]);
        }
    }

      /**
     * [seedTaskStatus]
     * @return [type] array
     */
    public function taskStatuses()
    {
        factory(TaskStatus::class)->create([
            'key' => 'TO_BE_STARTED',
            'label'  => 'To be Started',
        ]);        
        factory(TaskStatus::class)->create([
            'key' => 'IN_PROGRESS',
            'label'  => 'In Progress',
        ]);
        factory(TaskStatus::class)->create([
            'key' => 'ON_HOLD',
            'label'  => 'On Hold',
        ]);
        factory(TaskStatus::class)->create([
            'key' => 'COMPLETED',
            'label'  => 'Completed',
        ]);
        factory(TaskStatus::class)->create([
            'key' => 'CANCELLED',
            'label'  => 'Cancelled',
        ]);
        factory(TaskStatus::class)->create([
            'key' => 'OVERDUE',
            'label'  => 'OverDue',
        ]);
        return TaskStatus::lists('id');
    }
    
      /**
     * [seedTaskStages]
     * @return [type] array
     */
    public function taskStages()
    {   
        factory(TaskStage::class)->create([
            'key' => 'NEW',
            'label'  => 'New',
        ]);        
        factory(TaskStage::class)->create([
            'key' => 'FOLLOW_UP',
            'label'  => 'Follow Up',
        ]);
        factory(TaskStage::class)->create([
            'key' => 'OFFICE_LOGIN',
            'label'  => 'Office Login',
        ]);
        factory(TaskStage::class)->create([
            'key' => 'BANK_LOGIN',
            'label'  => 'Bank Login',
        ]);
        factory(TaskStage::class)->create([
            'key' => 'SANCTION',
            'label'  => 'Sanction',
        ]);
        factory(TaskStage::class)->create([
            'key' => 'FIRST_DISB',
            'label'  => 'First Disb',
        ]);
        factory(TaskStage::class)->create([
            'key' => 'PART_DISB',
            'label'  => 'Part Disb',
        ]);
        factory(TaskStage::class)->create([
            'key' => 'FINAL_DISB',
            'label'  => 'Final Disb',
        ]);
        factory(TaskStage::class)->create([
            'key' => 'CUST_DECLINE',
            'label'  => 'Cust Decline',
        ]);
        factory(TaskStage::class)->create([
            'key' => 'BANK_DECLINE',
            'label'  => 'Bank Decline',
        ]);
        factory(TaskStage::class)->create([
            'key' => 'RE_LOGIN',
            'label'  => 'Re-Login',
        ]);
        factory(TaskStage::class)->create([
            'key' => 'LOGOUT',
            'label'  => 'Logout',
        ]);
        return TaskStage::lists('id');
    }
}
