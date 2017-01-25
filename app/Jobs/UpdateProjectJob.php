<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Projects\Contract;

class UpdateProjectJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var array
     */
    private $request;

    /**
     * Create a new job instance.
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @param Contract $projects
     * @return mixed
     */
    public function handle(Contract $projects)
    {
        $project=  $projects->updateAsAdmin($this->request);
        if (isset($this->request['attachment'])) {
            if(isset($project->attachments->first()->uri)) {
                $attachment = $project->attachments()->first();
                $attachment->update([
                    'uri'  => $this->request['attachment'],
                ]);
            }else{
                $attachment = $project->attachments()->whereType('PROJECT_PICTURE')->firstOrNew([
                    'uuid' => uuid(),
                    'type' => 'PROJECT_PICTURE',
                    'uri'  => $this->request['attachment']
                ]);
                $attachment->save();
            }
        }
    }
}
