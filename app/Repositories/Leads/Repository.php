<?php

namespace Whatsloan\Repositories\Leads;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Uuid;
use Whatsloan\Repositories\Cities\City;
use Whatsloan\Repositories\Designations\Designation;
use Whatsloan\Repositories\LoanStatuses\LoanStatus;
use Whatsloan\Repositories\Loans\Loan;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Repositories\Sources\Source;
use Whatsloan\Repositories\Tasks\Task;
use Whatsloan\Repositories\Types\Type;
use Whatsloan\Repositories\Users\User;

class Repository implements Contract
{

    /**
     * @var Lead
     */
    private $lead;

    /**
     * Repository constructor.
     * @param Lead $lead
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Get a paginated list of leads
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15)
    {
        return $this->lead->with(['addresses', 'sources', 'types'])->paginate($limit);
    }

    /**
     * Get the details of a single lead
     *
     * @param $uuid
     * @return mixed
     */
    public function find($uuid)
    {
        return $this->lead->with(['addresses', 'source', 'user', 'assignee'])->whereUuid($uuid)->firstOrFail();
    }

    /**
     * Add a new lead
     *
     * @param Request $request
     * @return mixed
     */
    public function add($data)
    {

        return \DB::transaction(function () use ($data) {

            $authUser = (isset($data['bulk_upload'])) ? authUser("web") : authUser();

            if (isset($data['referral_uuid']) && !empty($data['referral_uuid'])) {
                $data['referral_id'] = User::where('uuid', $data['referral_uuid'])->first()->id;
            }

            if (isset($data['source_uuid']) && !empty($data['source_uuid'])) {
                $data['source_id'] = Source::where('uuid', $data['source_uuid'])->first()->id;
            }

            if (isset($data['city_uuid']) && !empty($data['city_uuid'])) {
                $data['city_id'] = City::where('uuid', $data['city_uuid'])->first()->id;
            }

            if (isset($data['type_uuid']) && !empty($data['type_uuid'])) {
                $data['type_id'] = Type::where('uuid', $data['type_uuid'])->first()->id;
            }

            if (isset($data['project_uuid']) && !empty($data['project_uuid'])) {
                $data['project_id'] = Project::whereUuid($data['project_uuid'])->first()->id;
            }

            $user = User::where('phone', $data['phone'])->first();
            if (!$user) {
                $user = User::create([
                    'uuid'           => uuid(),
                    'first_name'     => $data['name'],
                    'phone'          => $data['phone'],
                    'email'          => $data['email'],
                    'role'           => 'LEAD',
                    'designation_id' => Designation::where('name', 'Lead')->first()->id,
                    'settings'       => [
                        'resident_status' => '',
                        'profession'      => '',
                        'dob'             => '',
                        'age'             => '',
                        'education'       => '',
                        'marital_status'  => '',
                        'company'         => $data['company_name'],
                        'net_income'      => sprintf('%0.2f', $data['net_salary']),
                        'pan'             => '',
                        'salary_bank'     => '',
                        'skype'           => '',
                        'facetime'        => '',
                        'contact_time'    => '',
                        'cibil_score'     => '',
                        'cibil_status'    => '',
                    ],
                ]);

                $user->addresses()->create([
                    'uuid'    => uuid(),
                    'city_id' => $data['city_id'],
                ]);
            }
            $team_user_ids = implode(', ', teamMemberIds(false));
            $owner         = \DB::select('select user_id from team_user where user_id IN (' . $team_user_ids . ') AND is_owner =1');
            $loan          = Loan::create([
                'uuid'           => uuid(),
                'type_id'        => $data['type_id'],
                'amount'         => $data['loan_amount'],
                'applied_on'     => Carbon::now()->toDateTimeString(),
                'loan_status_id' => LoanStatus::whereKey('LEAD')->first()->id,
                'user_id'        => $user->id,
                'agent_id'       => $owner[0]->user_id,
            ]);

            $loan->history()->create([
                'uuid'           => uuid(),
                'loan_id'        => $loan->id,
                'type_id'        => $loan->type_id,
                'user_id'        => $loan->user_id,
                'agent_id'       => $loan->agent_id,
                'modified_by'    => $authUser->id,
                'amount'         => $loan->amount,
                'applied_on'     => Carbon::now()->toDateTimeString(),
                'loan_status_id' => $loan->loan_status_id,
            ]);

            if (Type::whereId($data['type_id'])->first()->key == 'HL' && (isset($data['project_id']) && !empty($data['project_id']))) {
                $loan->project()->attach($data['project_id']);
            }

            $user->loans()->save($loan);
            $user->save();

            $lead = $this->lead->create([
                'uuid'              => Uuid::generate()->string,
                'user_id'           => $user->id,
                'loan_id'           => $loan->id,
                'source_id'         => $data['source_id'],
                'assigned_to'       => $owner[0]->user_id,
                'created_by'        => authUser()->id,
                'existing_loan_emi' => $data['existing_loan_emi'],
            ]);

            if (Source::find($data['source_id'])->key == 'REFERRAL') {
                $lead->referrals()->attach(User::find($data['referral_id'])->id);
            }

            return $lead;
        });
    }

    /**
     * Update an existing lead
     *
     * @param $id
     * @param $request
     * @return mixed
     */
    public function update($data, $uuid)
    {
        $lead                = $this->lead->where('uuid', $uuid)->find($this->lead->where('uuid', $uuid)->first()->id);
        $data['referral_id'] = User::where('uuid', $data['referral_uuid'])->first()->id;
        $data['source_id']   = Source::where('uuid', $data['source_uuid'])->first()->id;
        $data['city_id']     = City::where('uuid', $data['city_uuid'])->first()->id;
        $data['type_id']     = Type::where('uuid', $data['type_uuid'])->first()->id;
        $lead->update($data);

        return $lead;
    }

    /**
     * Get the validation rules for the model
     *
     * @return mixed
     */
    public function getValidations()
    {
        return $this->lead->getValidations();
    }

    /**
     * Lead count
     * @return [type] [description]
     */
    public function getCount()
    {
        return $this->lead->count();
    }

    /**
     * Getting Leads based on User
     * @return Lead
     */
    public function getLeadsByUserIds($ids)
    {
        return $this->lead->with(['assignee', 'user.designation', 'user.addresses.city', 'source', 'loan'])
            ->whereIn('user_id', $ids)
            ->paginate();
    }

    /**
     * Getting Leads based on User
     * @return Lead
     */
    public function getLeadsCount($type, $ids)
    {
        return $this->lead->with(['assignee', 'user.designation', 'user.addresses.city', 'source', 'loan.type'])
            ->whereHas('loan.type', function ($query) use ($type, $ids) {
                if (isset($type)) {
                    $query->where('key', $type);
                }
            })
            ->whereIn('user_id', $ids)
            ->count();
    }

    /**
     * Store Lead  data
     * @param array $data
     */
    public function store($data)
    {

    }

    public function getLeadsByUserIdAsConsumers($userId)
    {
        return $this->lead->where('user_id', $userId)->paginate();
    }

    public function newLeadAsConsumers($data)
    {
        return \DB::transaction(function () use ($data) {
            $userExist = User::where('phone', $data['phone'])->first();
            if (!empty($userExist)) {
                $user = $userExist;
            } else {
                $user = User::create([
                    'uuid'           => uuid(),
                    'first_name'     => $data['name'],
                    'phone'          => $data['phone'],
                    'email'          => $data['email'],
                    'role'           => 'LEAD',
                    'api_token'      => str_random(60),
                    'remember_token' => str_random(10),
                    'designation_id' => Designation::where('name', 'Consumer')->first()->id,
                    'settings'       => [
                        'resident_status' => '',
                        'profession'      => '',
                        'dob'             => '',
                        'age'             => '',
                        'education'       => '',
                        'marital_status'  => '',
                        'company'         => $data['company_name'],
                        'net_income'      => $data['net_salary'],
                        'pan'             => '',
                        'salary_bank'     => '',
                        'skype'           => '',
                        'facetime'        => '',
                        'contact_time'    => '',
                        'cibil_score'     => '',
                        'cibil_status'    => '',
                    ],
                ]);

                $user->addresses()->create([
                    'uuid'    => uuid(),
                    'email'   => $data['email'],
                    'phone'   => $data['phone'],
                    'city_id' => City::where('uuid', $data['city_uuid'])->first()->id,
                ]);
            }

            if ($data['screen'] == 'I_HAVE_A_LOAN') {
                $loanStatus = LoanStatus::whereKey('TAKE_OVER')->first()->id;
            } else {
                $loanStatus = LoanStatus::whereKey('LEAD')->first()->id;
            }
            $loan = Loan::create([
                'uuid'           => uuid(),
                'type_id'        => Type::where('uuid', $data['type_uuid'])->first()->id,
                'amount'         => $data['loan_amount'],
                'applied_on'     => Carbon::now()->toDateTimeString(),
                'loan_status_id' => $loanStatus,
                'user_id'        => $user->id,
            ]);

            $loan->history()->create([
                'uuid'           => uuid(),
                'loan_id'        => $loan->id,
                'type_id'        => $loan->type_id,
                'user_id'        => $loan->user_id,
                'modified_by'    => $user->id,
                'amount'         => $loan->amount,
                'applied_on'     => Carbon::now()->toDateTimeString(),
                'loan_status_id' => $loan->loan_status_id,
            ]);

            $user->loans()->save($loan);
            $user->save();

            return $this->lead->create([
                'uuid'              => Uuid::generate()->string,
                'user_id'           => $user->id,
                'assigned_to'       => User::where('role', 'SUPER_ADMIN')->first()->id,
                'source_id'         => Source::where('uuid', $data['source_uuid'])->first()->id,
                'existing_loan_emi' => $data['existing_loan_emi'],
                'loan_id'           => $loan->id,
            ]);
        });
    }

    /**
     * Leads listings for admin
     * @return Collection
     */
    public function listAsAdmin()
    {

        $request = request()->all();
        $leads   = $this->lead->with(['userTrashed', 'loanTrashed', 'loanTrashed.tasks', 'loanTrashed.status']);

        if (\Auth::user()->role != 'SUPER_ADMIN') {
            $leads = $leads->whereIn('assigned_to', teamMemberIdsAsAdmin(true));
        }

        $leads = $leads->whereHas('userTrashed', function ($q) use ($request) {
            $q->where('role', 'NOT LIKE', "%CONSUMER%");
        });

        if (isset($request['filter']) && $request['filter'] == 'without-tasks') {
            $leads = $leads->whereHas('loanTrashed', function ($q) {
                $q->whereNotIn('id', Task::where('taskable_type', Loan::class)->lists('taskable_id')->all());
            });
        }

        if (isset($request['name-filter']) && !empty($request['name-filter'])) {
            $leads = $leads->whereHas('userTrashed', function ($q) use ($request) {
                $q->where('first_name', 'LIKE', "%{$request['name-filter']}%")
                    ->orWhere('last_name', 'LIKE', "%{$request['name-filter']}%");
            });
        }

        if (isset($request['phone-filter']) && !empty($request['phone-filter'])) {
            $leads = $leads->whereHas('userTrashed', function ($q) use ($request) {
                $q->where('phone', 'LIKE', "%{$request['phone-filter']}%");
            });
        }

        if (isset($request['email-filter']) && !empty($request['email-filter'])) {
            $leads = $leads->whereHas('userTrashed', function ($q) use ($request) {
                $q->where('email', 'LIKE', "%{$request['email-filter']}%");
            });
        }

        if (isset($request['status-filter']) && !empty($request['status-filter'])) {
            $leads = $leads->whereHas('loanTrashed.status', function ($q) use ($request) {
                $q->where('label', 'LIKE', "%{$request['status-filter']}%");
            });
        }

        // $leads = $leads->whereNotNull('created_by');
        $leads = $leads->orderBy('deleted_at', 'asc');

        $leads = $leads->withTrashed();

        if (isset($request['paginate']) && $request['paginate'] == 'all') {
            return $leads->get();
        } else {
            return $leads->paginate();
        }

    }

    public function updateAsAdmin($data, $id)
    {
        return \DB::transaction(function () use ($data, $id) {
            $lead = $this->lead->with(['user'])->withTrashed()->find($id);
            $lead->update($data);
            $lead->user->update($data);
            if (isset($data['settings']['dob'])) {
                $data['settings']['dob'] = Carbon::parse($data['settings']['dob'])->format('Y-m-d');
            }
            $lead->user->settings()->merge($data['settings']);
            $lead->save();
            $lead->user->addresses()->update([
                "alpha_street" => (isset($data["alphaStreet"])) ? $data["alphaStreet"] : "",
                "beta_street"  => (isset($data["betaStreet"])) ? $data["betaStreet"] : "",
                "city_id"      => (isset($data["city_id"])) ? $data["city_id"] : "",
                "state"        => (isset($data["state"])) ? $data["state"] : "",
                "country"      => (isset($data["country"])) ? $data["country"] : "",
                "zip"          => (isset($data["zipcode"])) ? $data["zipcode"] : "",
            ]);
            $sourceKey = Source::whereId($data['source_id'])->get()->first()->key;
            if ($sourceKey == 'REFERRAL') {
                $lead->referrals()->detach();
                $lead->referrals()->attach($data['referral_id']);
            } else {
                $lead->referrals()->detach();
            }

            return $lead;
        });
    }

    public function storeAsAdmin($data)
    {
        return \DB::transaction(function () use ($data) {

            $data['uuid']           = uuid();
            $data['role']           = 'LEAD';
            $data['designation_id'] = Designation::where('name', 'Lead')->first()->id;
            $data['created_by']     = authUser('web')->id;
            if (isset($data['dob'])) {
                $row['age'] = Carbon::parse($data['dob'])->diff(Carbon::now())->format('%y');
            }
            $data['settings'] = [
                'resident_status' => isset($data['resident_status']) ? $data['resident_status'] : "",
                'profession'      => isset($data['profession']) ? $data['profession'] : "",
                'dob'             => isset($data['dob']) ? $data['dob'] : "",
                'age'             => isset($row['age']) ? $row['age'] : "",
                'education'       => isset($data['education']) ? $data['education'] : "",
                'marital_status'  => isset($data['marital_status']) ? $data['marital_status'] : "",
                'company'         => isset($data['company_name']) ? $data['company_name'] : "",
                'net_income'      => isset($data['net_salary']) ? sprintf('%0.2f', $data['net_salary']) : "",
                'pan'             => isset($data['pan']) ? $data['pan'] : "",
                'salary_bank'     => isset($data['salary_bank']) ? $data['salary_bank'] : "",
                'skype'           => isset($data['skype']) ? $data['skype'] : "",
                'facetime'        => isset($data['facetime']) ? $data['facetime'] : "",
                'contact_time'    => isset($data['contact_time']) ? $data['contact_time'] : "",
                'cibil_score'     => isset($data['cibil_score']) ? $data['cibil_score'] : "",
                'cibil_status'    => isset($data['cibil_status']) ? $data['cibil_status'] : "",
            ];
            $user = User::create($data);

            $user->addresses()->create([
                "uuid"         => uuid(),
                "alpha_street" => (isset($data["alphaStreet"])) ? $data["alphaStreet"] : "",
                "beta_street"  => (isset($data["betaStreet"])) ? $data["betaStreet"] : "",
                "city_id"      => (isset($data["city_id"])) ? $data["city_id"] : "",
                "state"        => (isset($data["state"])) ? $data["state"] : "",
                "country"      => (isset($data["country"])) ? $data["country"] : "",
                "zip"          => (isset($data["zipcode"])) ? $data["zipcode"] : "",
            ]);

            $data['applied_on']     = Carbon::now()->toDateTimeString();
            $data['user_id']        = $user->id;
            $data['agent_id']       = $data['assigned_to'];
            $data['loan_status_id'] = LoanStatus::where('key', 'LEAD')->first()->id;
            $data['uuid']           = uuid();

            $loan = Loan::create($data);
            if ((Type::whereId($data['type_id'])->first()->key) == 'HL') {
                if (isset($data['property_verified']) && $data['property_verified'] == 1) {
                    $loan->project()->attach(Project::find($data['project_id'])->first()->id);
                }

            }

            $loan->history()->create([
                'uuid'           => uuid(),
                'loan_id'        => $loan->id,
                'type_id'        => $loan->type_id,
                'user_id'        => $loan->user_id,
                'agent_id'       => $loan->agent_id,
                'modified_by'    => authUser('web')->id,
                'amount'         => $loan->amount,
                'applied_on'     => Carbon::now()->toDateTimeString(),
                'loan_status_id' => $loan->loan_status_id,
            ]);

            $user->loans()->save($loan);
            $data['loan_id']     = $loan->id;
            $data['assigned_to'] = $data['assigned_to'];
            $lead                = Lead::create($data);

            $sourceKey = Source::whereId($data['source_id'])->get()->first()->key;
            if ($sourceKey == 'REFERRAL') {
                $lead->referrals()->attach(User::find($data['referral_id'])->first()->id);
            }

            if(isset($data['bulk_upload']) && $data['bulk_upload']){
                $user->delete();
                $lead->delete();
                $loan->delete();
            }

            return $user;
        });
    }

    public function referralAsConsumers($data)
    {
        return \DB::transaction(function () use ($data) {
            $user = User::create([
                'uuid'           => uuid(),
                'first_name'     => $data['name'],
                'phone'          => $data['phone'],
                'email'          => $data['email'],
                'role'           => 'REFERRAL',
                'api_token'      => str_random(60),
                'remember_token' => str_random(10),
                'designation_id' => Designation::where('name', 'Consumer')->first()->id,
                'settings'       => [
                    'resident_status' => '',
                    'profession'      => isset($data['profession']) ? $data['profession'] : '',
                    'dob'             => '',
                    'age'             => '',
                    'education'       => '',
                    'marital_status'  => '',
                    'company'         => $data['company_name'],
                    'net_income'      => $data['net_salary'],
                    'pan'             => '',
                    'salary_bank'     => '',
                    'skype'           => '',
                    'facetime'        => '',
                    'contact_time'    => '',
                    'cibil_score'     => '',
                    'cibil_status'    => '',
                ],
            ]);

            $user->addresses()->create([
                'uuid'    => uuid(),
                'email'   => $data['email'],
                'phone'   => $data['phone'],
                'city_id' => City::where('uuid', $data['city_uuid'])->first()->id,
            ]);

            $loanStatus = LoanStatus::whereKey('LEAD')->first()->id;

            $loan = Loan::create([
                'uuid'           => uuid(),
                'type_id'        => Type::where('uuid', $data['type_uuid'])->first()->id,
                'amount'         => $data['loan_amount'],
                'applied_on'     => Carbon::now()->toDateTimeString(),
                'loan_status_id' => $loanStatus,
                'user_id'        => $user->id,
                'agent_id'       => User::where('role', 'SUPER_ADMIN')->first()->id,
            ]);

            $loan->history()->create([
                'uuid'           => uuid(),
                'loan_id'        => $loan->id,
                'type_id'        => $loan->type_id,
                'user_id'        => $loan->user_id,
                'agent_id'       => $loan->agent_id,
                'modified_by'    => $user->id,
                'amount'         => $loan->amount,
                'applied_on'     => Carbon::now()->toDateTimeString(),
                'loan_status_id' => $loan->loan_status_id,
            ]);

            $user->loans()->save($loan);
            $user->save();

            return $this->lead->create([
                'uuid'        => Uuid::generate()->string,
                'user_id'     => \Auth::guard('api')->user()->id,
                'assigned_to' => User::where('role', 'SUPER_ADMIN')->first()->id,
                'source_id'   => Source::where('key', 'REFERRAL')->first()->id,
                'loan_id'     => $loan->id,
            ]);
        });
    }

    public function getReferralsAsConsumers($userId)
    {
        return $this->lead->with(['source', 'loan', 'loan.type', 'loan.user'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->paginate();
    }

}
