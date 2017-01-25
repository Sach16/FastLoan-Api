<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Users\Contract;

class StoreUserAttachmentsJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var array
     */
    public $request;
    /**
     * @var user
     */
    public $user;

    /**
     * Create a new job instance.
     *
     * @param array $request
     */
    public function __construct(array $request,$user)
    {
        $this->request = $request;
        $this->user    = $user;
    }

    /**
     * Execute the job.
     *
     * @param Contract $users
     * @return mixed
     */
    public function handle(Contract $users)
    {
        if (isset($this->request['attachment'])) {
            $attachment = $this->user->attachments()->whereType('PROFILE_PICTURE')->firstOrNew([
                'uuid' => uuid(),
                'type' => 'PROFILE_PICTURE'
            ]);
            $attachment->save();
            $attachment->update([
                'uri'  => $this->request['attachment'],
            ]);
        }
        if (isset($this->request['address_attachment'])) {
            $attachment = $this->user->attachments()->whereType('ADDRESS_PROOF')->firstOrNew([
                'uuid' => uuid(),
                'type' => 'ADDRESS_PROOF'
            ]);
            $attachment->save();
            $attachment->update([
                'uri'  => $this->request['address_attachment'],
            ]);
        }
        if (isset($this->request['id_attachment'])) {
            $attachment = $this->user->attachments()->whereType('ID_PROOF')->firstOrNew([
                'uuid' => uuid(),
                'type' => 'ID_PROOF'
            ]);
            $attachment->save();
            $attachment->update([
                'uri'  => $this->request['id_attachment'],
            ]);
        }

        if (isset($this->request['products_handled_attachment'])) {
            $attachment = $this->user->attachments()->whereType('PRODUCT_DOCUMENT')->firstOrNew([
                'uuid' => uuid(),
                'type' => 'PRODUCT_DOCUMENT'
            ]);
            $attachment->save();
            $attachment->update([
                'uri'  => $this->request['products_handled_attachment'],
            ]);
        }

        if (isset($this->request['experience_with_banks_attachment'])) {
            $attachment = $this->user->attachments()->whereType('EXPERIENCE_DOCUMENT')->firstOrNew([
                'uuid' => uuid(),
                'type' => 'EXPERIENCE_DOCUMENT'
            ]);
            $attachment->save();
            $attachment->update([
                'uri'  => $this->request['experience_with_banks_attachment'],
            ]);
        }
    }
}
