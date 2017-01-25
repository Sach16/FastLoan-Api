<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Banks\Contract as Banks;
use Whatsloan\Repositories\Projects\Contract as Projects;


class UpdateProjectApiJob extends Job //implements ShouldQueue
{

    use InteractsWithQueue,
        SerializesModels;

    /**
     * @var
     */
    public $request;

    /**
     * @var
     */
    public $bankId;

    /**
     * @var
     */
    public $projectId;

    /**
     * Create a new job instance.
     * @param $request
     * @param $bankId
     * @param $projectId
     */
    public function __construct($request, $bankId, $projectId)
    {
        $this->request = $request;
        $this->bankId = $bankId;
        $this->projectId = $projectId;
    }

    /**
     * Execute the job.
     * @param Contract $banks
     * @return mixed
     */
    public function handle(Banks $banks, Projects $projects)
    {

        return \DB::transaction(function () use($banks, $projects) {
            
            $project = $projects->update($this->projectId, $this->request);
            return $banks->updateBankProject(
                [
                    //'status' => $this->request['status'],
                    'agent_id' => $this->request['agent_id'],
                    'bank_id' => $this->request['bank_id'],
                ], 
                $this->bankId, 
                $this->projectId
            );
            
        });
    }

}
