<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Banks\Contract;
use Whatsloan\Repositories\Banks\Bank;

class UpdateBankPictureJob extends Job
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var
     */
    private $request;

    /**
     * @var
     */
    private $id;

    /**
     * Create a new job instance.
     * @param $id
     * @param $request
     */
    public function __construct($id, $request)
    {
        $this->request = $request;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @param Contract $banks
     * @return mixed
     */
    public function handle(Contract $banks)
    {
        $bank = Bank::find($this->id);
        if (isset($this->request['attachment'])) {
            if(isset($bank->attachments()->whereType('BANK_PICTURE')->first()->uri)) {
                $attachment = $bank->attachments()->first();
                $attachment->update([
                    'uri'  => $this->request['attachment'],
                ]);
            }else{
                $attachment = $bank->attachments()->whereType('BANK_PICTURE')->firstOrNew([
                    'uuid' => uuid(),
                    'type' => 'BANK_PICTURE',
                    'uri'  => $this->request['attachment']
                ]);
                $attachment->save();
            }
        }
        return $bank;
    }
}
