<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Teams\Contract;

class UpdateTeamMultiOwnerJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var
     */
    public $team_id;
    /**
     * @var
     */
    public $member_id;


    /**
     * Create a new job instance.
     *
     * @param array $request
     * @param $id
     */
    public function __construct($team_id,$member_id)
    {
        $this->team_id   = $team_id;
        $this->member_id = $member_id;
    }

    /**
     * Execute the job.
     *
     * @param Contract $teams
     * @return mixed
     */
    public function handle(Contract $teams)
    {
        return $teams->addMultiOwner($this->member_id,$this->team_id);
    }
}
