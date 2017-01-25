<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Banks\Contract;

class UpdateBankDocumentJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var
     */
    public $id;

    /**
     * @var
     */
    public $request;
    /**
     * @var
     */
    private $documentId;

    /**
     * Create a new job instance.
     *
     * @param $id
     * @param $documentId
     * @param $request
     */
    public function __construct($id, $documentId, $request)
    {
        $this->id = $id;
        $this->request = $request;
        $this->documentId = $documentId;
    }

    /**
     * Execute the job.
     *
     * @param Contract $banks
     */
    public function handle(Contract $banks)
    {
        $banks->updateBankDocument($this->id, $this->documentId, $this->request);
    }
}
