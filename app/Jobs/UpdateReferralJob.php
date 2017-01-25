<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Users\Contract;

class UpdateReferralJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var array
     */
    public $request;

    /**
     * @var
     */
    public $id;

    /**
     * Create a new job instance.
     *
     * @param array $request
     * @param $id
     */
    public function __construct(array $request, $id)
    {
        $this->request = $request;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @param Contract $users
     * @return mixed
     */
    public function handle(Contract $users)
    {
        $user = $users->updateAsAdmin($this->request, $this->id);
        if (isset($this->request['attachment'])) {
            $attachment = $user->attachments()->whereType('PROFILE_PICTURE')->firstOrNew([
                'uuid' => uuid(),
                'type' => 'PROFILE_PICTURE'
            ]);
            $attachment->save();
            $attachment->update([
                'uri'  => $this->request['attachment'],
            ]);
        }

        return $user;
    }
}
