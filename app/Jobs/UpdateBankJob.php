<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Banks\Contract;

class UpdateBankJob extends Job
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
        $bank = $banks->update($this->id, $this->request);
        $bank->addresses()->first()->update([
            'alpha_street' => $this->request['alphaStreet'],
            'beta_street'  => $this->request['betaStreet'],
            'city_id'      => $this->request['city'],
            'state'        => $this->request['state'],
            'country'      => $this->request['country'],
            'zip'          => $this->request['zip'] ?: null,
        ]);
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
