<?php

use Illuminate\Database\Seeder;
use Whatsloan\Repositories\Loans\Loan;
use Whatsloan\Repositories\Types\Type;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Statuses\Status;
use Whatsloan\Repositories\LoanStatuses\LoanStatus;
use Whatsloan\Repositories\Attachments\Attachment;
use Whatsloan\Repositories\LoanDocuments\LoanDocument;
use Whatsloan\Repositories\LoanHistories\LoanHistory;
use Whatsloan\Repositories\Projects\Project;

class LoansSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $types = Type::take(10)->get();
        $customers = User::whereRole('CONSUMER')->take(10)->get();
        $members = User::whereRole('DSA_MEMBER')->take(10)->get();
        $loanStatus = LoanStatus::take(10)->get();

        $projects = Project::take(50)->get();
        foreach(range(1,50) as $range) {
            
            $loan = factory(Loan::class)->create([
                'type_id' => $faker->randomElement($types->lists('id')->all()),
                'user_id' => $faker->randomElement($customers->lists('id')->all()),
                'agent_id' => $faker->randomElement($members->lists('id')->all()),
                'loan_status_id' => $faker->randomElement($loanStatus->lists('id')->all()),
            ]);
            
            $type = Type::find($loan->type_id);
            if($type->key == 'HL') {
                $loan->project()->save($projects->random());
            }

            factory(LoanHistory::class)->create([
                'loan_id' => $loan->id,
                'type_id' => $loan->type_id,
                'user_id' => $loan->user_id,
                'agent_id' => $loan->agent_id,
              //  'bank_id' => $loan->bank_id,
                'loan_status_id' => $loan->loan_status_id,
                'modified_by' => $faker->randomElement($members->lists('id')->all()),

                'amount' => $loan->amount,
                'eligible_amount' => $loan->eligible_amount,
                'approved_amount' => $loan->approved_amount,
                'interest_rate' => $loan->interest_rate,
                'applied_on' =>  $loan->applied_on,
                'approval_date' => $loan->approval_date,
                'emi' => $loan->emi,
                'emi_start_date' => $loan->emi_start_date,
                'appid' => $loan->appid,

            ]);
            
            $attachments = factory(Attachment::class, 1)->make();
            $loan->attachments()->save($attachments);
            factory(LoanDocument::class)->create([
               'attachment_id' => $attachments->id,
               'loan_id'       => $loan->id,              
            ]);    
           
        }
        
    }

}
