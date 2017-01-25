<?php

use Illuminate\Database\Seeder;
use Whatsloan\Repositories\LoanStatuses\LoanStatus;

class LoanStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            "LEAD",
            "FOLLOW_UP",
            "OFFICE_LOGIN",
            "BANK_LOGIN",
            "SANCTION",
            "FIRST_DISB",
            "PART_DISB",
            "FINAL_DISB",
            "CUST_DECLINE",
            "BANK_DECLINE",
            "RE_LOGIN",
            "LOGOUT",
            "TAKE_OVER",
        ];

        $statusIds = [];
        foreach ($statuses as $status) {
            $status = factory(LoanStatus::class)->create([
                'key' => $status,
                'label' => ucwords(strtolower(str_replace('_',' ',$status)))
            ]);
            $statusIds[] = $status->id;
        }
   
    }
}
