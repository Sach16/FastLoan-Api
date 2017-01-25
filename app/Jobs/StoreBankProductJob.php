<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Banks\BankProduct;

class StoreBankProductJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var array
     */
    public $request;
    /**
     * @var
     */
    private $id;
    /**
     * Create a new job instance.
     * @param $id
     * @param array $request
     */
    public function __construct($id, array $request)
    {
        $this->request = $request;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @param Contract $bank_project
     * @return mixed
     */
    public function handle(BankProduct $bank_product)
    {
        return $bank_product->storeBankProductDocument($this->id, $this->request);
    }
}
