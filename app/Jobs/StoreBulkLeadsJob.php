<?php

namespace Whatsloan\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Whatsloan\Jobs\Job;
use Whatsloan\Repositories\Leads\Contract;

class StoreBulkLeadsJob extends Job implements ShouldQueue
{

    use InteractsWithQueue,
        SerializesModels;

    /**
     * @var Collection
     */
    private $rows;

    /**
     * Create a new job instance.
     * @param Collection $rows
     */
    public function __construct(Collection $rows)
    {
        $this->rows = $rows;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Contract $lead)
    {
        DB::transaction(function () use ($lead) {
            foreach ($this->rows->first() as $row) {
                $row['phone']       = $row['phone_number'];
                $row['first_name']  = $row['first_name'];
                $row['last_name']   = $row['last_name'];
                $row['email']       = $row['email_id'];
                $row['company']     = $row['company_name'];
                $row['dob']         = date('Y-m-d', strtotime($row['date_of_birth']));
                $row['pan']         = $row['permanent_account_number'];
                $row['company']     = $row['company_name'];
                $row['net_salary']  = $row['net_income'];
                $row['bulk_upload'] = true;
                $lead->storeAsAdmin($row);
            }
        });
    }

}
