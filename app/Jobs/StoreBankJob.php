<?php

namespace Whatsloan\Jobs;

use Uuid;
use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Addresses\Address;
use Whatsloan\Repositories\Banks\Contract;

class StoreBankJob extends Job
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Request parameters
     *
     * @var array
     */
    protected $request;

    /**
     * Create a new job instance.
     * @param array $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @param Contract $banks
     * @return mixed
     */
    public function handle(Contract $banks)
    {
        $bank = $banks->store($this->request);
        $address = new Address([
            'uuid'         => Uuid::generate()->string,
            'alpha_street' => $this->request->alphaStreet,
            'beta_street'  => $this->request->betaStreet,
            'city_id'      => $this->request->city_id,
            'state'        => $this->request->state,
            'country'      => $this->request->country,
            'zip'          => $this->request->zipcode,
        ]);
        $bank->addresses()->save($address);
        return $bank;
    }
}
