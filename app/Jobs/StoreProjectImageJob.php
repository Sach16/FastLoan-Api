<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Projects\Contract;

class StoreProjectImageJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var array
     */
    public $request;
    /**
     * @var project
     */
    public $project;

    /**
     * Create a new job instance.
     *
     * @param array $request
     */
    public function __construct(array $request,$project)
    {
        $this->request = $request;
        $this->project    = $project;
    }

    /**
     * Execute the job.
     *
     * @param Contract $projects
     * @return mixed
     */
    public function handle(Contract $projects)
    {
        if (isset($this->request['attachment'])) {
            $attachment = $this->project->attachments()->whereType('PROJECT_PICTURE')->first();
            
            if( $attachment == null ){
                $attachment = $this->project->attachments()->create([
                  'uuid' => uuid(),
                  'type' => 'PROJECT_PICTURE',
                  'uri'  => $this->request['attachment'],
                ]);               
            }else{
              $attachment->update([
                  'uri'  => $this->request['attachment'],
              ]);
            }            
        }
    }
}
