<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Leads\Contract;

class UpdateLeadJob extends Job implements ShouldQueue
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
    public function handle(Contract $leads)
    {
        $lead = $leads->updateAsAdmin($this->request, $this->id);

        if (isset($this->request['attachment'])) {
            $attachment = $lead->user->attachments()->whereType('PROFILE_PICTURE')->first();
            if( $attachment == null ){
              $attachment = $lead->user->attachments()->create([
                  'uri'  => $this->request['attachment'],
                  'uuid' => uuid(),
                  'type' => 'PROFILE_PICTURE'
              ]);
            }else{
              $attachment->update([
                  'uri'  => $this->request['attachment'],
              ]);
            }
        }
        if (isset($this->request['address_attachment'])) {
            $attachment = $lead->user->attachments()->whereType('ADDRESS_PROOF')->firstOrNew([
                'uuid' => uuid(),
                'type' => 'ADDRESS_PROOF'
            ]);
            $attachment->save();
            $attachment->update([
                'uri'  => $this->request['address_attachment'],
            ]);
        }
        if (isset($this->request['id_attachment'])) {
            $attachment = $lead->user->attachments()->whereType('ID_PROOF')->firstOrNew([
                'uuid' => uuid(),
                'type' => 'ID_PROOF'
            ]);
            $attachment->save();
            $attachment->update([
                'uri'  => $this->request['id_attachment'],
            ]);
        }
    }
}
