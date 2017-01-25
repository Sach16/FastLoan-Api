<?php

namespace Whatsloan\Jobs;

use Carbon\Carbon;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Whatsloan\Jobs\Job;
use Whatsloan\Repositories\Designations\Designation;
use Whatsloan\Repositories\LoanHistories\LoanHistory;
use Whatsloan\Repositories\LoanStatuses\LoanStatus;
use Whatsloan\Repositories\Loans\Loan;
use Whatsloan\Repositories\Users\User;

class StoreBulkCustomersJob extends Job implements ShouldQueue
{

    use InteractsWithQueue, SerializesModels;
    /**
     * @var $allowed Allowed settings keys
     */
    protected $allowed = [
        'resident_status', 'profession', 'dob', 'age', 'education', 'marital_status',
        'company', 'net_income', 'pan', 'salary_bank', 'skype', 'facetime',
        'contact_time', 'cibil_score', 'cibil_status',
    ];
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
    public function handle()
    {
        $designation = Designation::whereName('Consumer')->first();
        DB::transaction(function () use ($designation) {
            foreach ($this->rows->first() as $row) {
                $user_exists = User::where('phone', $row['phone_number'])->withTrashed()->first();
                if ($user_exists) {
                    foreach ($user_exists->settings as $key => $value) {
                        $data[$key] = $value;
                    }
                }
                if (isset($row['date_of_birth']) && !empty($row['date_of_birth'])) {
                    $row['dob'] = date('d-m-Y', strtotime($row['date_of_birth']));
                    $row['age'] = Carbon::parse($row['dob'])->diff(Carbon::now())->format('%y');
                }
                if (isset($row['net_income'])) {
                    $row['net_income'] = sprintf('%0.2f', $row['net_income']);
                }
                $data['pan']             = (isset($row["permanent_account_number"])) ? $row['permanent_account_number'] : "";
                $data['dob']                = (isset($row["dob"])) ? $row['dob'] : "";
                $data['salary_bank']        = (isset($row["salary_bank_name"])) ? $row['salary_bank_name'] : "";
                $data['company']            = (isset($row["company_name"])) ? $row['company_name'] : "";
                $data['profession']         = (isset($row["profession_id"])) ? $row['profession_id'] : "";
                $data['cibil_status']       = (isset($row["cibil_status_id"])) ? $row['cibil_status_id'] : "";
                $data['marital_status']     = (isset($row["marital_status_id"])) ? $row['marital_status_id'] : "";
                $data['resident_status']    = (isset($row["resident_status_id"])) ? $row['resident_status_id'] : "";
                $row['settings']            = array_merge($data, array_only($row, $this->allowed));
                $collection              = [
                    'uuid'           => uuid(),
                    'first_name'     => $row['first_name'],
                    'last_name'      => $row['last_name'],
                    'email'          => $row['email_id'],
                    'phone'          => $row['phone_number'],
                    'designation_id' => $designation->id,
                    'role'           => 'CONSUMER',
                    'settings'       => $row['settings'],
                ];
                if ($user_exists) {
                    if ($user_exists->role == 'LEAD') {
                        $user_exists->role     = 'CONSUMER';
                        $user_exists->settings = $row['settings'];
                        $user_exists->save();
                        $loan                 = Loan::where('user_id', $user_exists->id)->withTrashed()->first();
                        $loan_status          = LoanStatus::where('key', $row['loan_status'])->first();
                        $loan->loan_status_id = $loan_status->id;
                        $loan->save();
                        LoanHistory::create([
                            'uuid'            => uuid(),
                            'loan_id'         => $loan->id,
                            'type_id'         => $loan->type_id,
                            'user_id'         => $loan->user_id,
                            'agent_id'        => $loan->agent_id,
                            'modified_by'     => $loan->user_id,
                            'amount'          => $loan->amount,
                            'eligible_amount' => $loan->eligible_amount,
                            'approved_amount' => $loan->approved_amount,
                            'interest_rate'   => $loan->interest_rate,
                            'applied_on'      => $loan->applied_on,
                            'approval_date'   => $loan->approval_date,
                            'emi'             => $loan->emi,
                            'emi_start_date'  => $loan->emi_start_date,
                            'appid'           => $loan->appid,
                            'loan_status_id'  => $loan->loan_status_id,
                        ]);
                    }
                } else {
                    $user = User::create($collection);
                    $user->addresses()->create([
                        "uuid"         => uuid(),
                        "alpha_street" => (isset($row["alphaStreet"])) ? $row["alphaStreet"] : "",
                        "beta_street"  => (isset($row["betaStreet"])) ? $row["betaStreet"] : "",
                        "city_id"      => (isset($row["city_id"])) ? $row["city_id"] : "",
                        "state"        => (isset($row["state"])) ? $row["state"] : "",
                        "country"      => (isset($row["country"])) ? $row["country"] : "",
                        "zip"          => (isset($row["pin_code"])) ? $row["pin_code"] : "",
                    ]);

                    $user->delete();
                }
            }

        });
    }
}
