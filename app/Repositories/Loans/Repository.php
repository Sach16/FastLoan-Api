<?php

namespace Whatsloan\Repositories\Loans;

use Illuminate\Http\Request;
use Whatsloan\Repositories\Types\Type;
use Whatsloan\Repositories\LoanDocuments\LoanDocument;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Repositories\LoanStatuses\LoanStatus;
use Whatsloan\Repositories\LoanHistories\LoanHistory;
use Carbon\Carbon;
use Whatsloan\Repositories\Users\Contract as Users;
use Whatsloan\Repositories\Tasks\Task;
use Whatsloan\Repositories\Leads\Lead;
use Whatsloan\Repositories\Sources\Source;

class Repository implements Contract
{

    private $loan;
    private $user;

    /**
     * History table will be updated if any of below columns updated
     * @var Mixed Boolean/Array
     */
    public $updateHistory = ['loan_status_id'];

    /**
     * Loan repository constructor
     * @param \Whatsloan\Repositories\Loans\Loan $loan
     */
    public function __construct(Loan $loan,Users $user)
    {
        $this->loan = $loan;
        $this->user = $user;
    }

    /**
     * Get a paginated list of loans
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15)
    {
        $loans = $this->loan->with(['bank', 'type', 'customer', 'agent', 'agent.banks', 'history']);
        

        if(isset(request()->task_uuid) && !empty(request()->task_uuid)){
            $task = Task::whereUuid(request()->task_uuid)->first();
            $loans = $loans->whereId($task->taskable_id);
        }else{
            $authUser = \Auth::guard('api')->user();
            $userTeam =($authUser->role == 'DSA_OWNER')
                ? $this->user->getDsaOwnerTeam($authUser->uuid)
                : $this->user->getDsaMemberTeam($authUser->uuid);
            $loans = $loans->whereIn('agent_id', $userTeam->teams->first()->members->lists('id'));
        }

        if(isset(request()->loan_uuid) && !empty(request()->loan_uuid)){
            $loans = $loans->whereUuid(request()->loan_uuid);
        }

        if (isset(request()->user_uuid) && !empty(request()->user_uuid)) {
            $loans = $loans->where('user_id', User::whereUuid(request()->user_uuid)->first()->id);
        } else if (isset(request()->agent_uuid) && !empty(request()->agent_uuid)) {
            $loans = $loans->where('agent_id', User::whereUuid(request()->agent_uuid)->first()->id);
        }

        return $loans->paginate();
    }

    /**
     * Get a single loan detail
     * @param string $uuid
     * @return Item
     */
    public function show($uuid)
    {
        return $this->loan->with(['bank', 'type', 'customer', 'agent', 'agent.banks', 'history'])->whereUuid($uuid)->firstOrfail();
    }

    /**
     * Update a single loan details
     * @param array $data
     * @param string $uuid
     */
    public function update($data, $uuid)
    {
        return \DB::transaction(function () use ($data, $uuid) {
                    $loan = $this->loan->whereUuid($uuid)->first();
                    $loanTemp = $loan->replicate();
                    $loan->update($data);

                    //Converting lead to customer
                    if(LoanStatus::find($loan->loan_status_id)->key == 'BANK_LOGIN') {
                        $loan->user()->update(['role' => 'CONSUMER']);
                    }


                    if ($this->isHistoryRequired($loan, $loanTemp)) {
                        $this->updateHistory($loan);
                    }

                    return $loan;
                });
    }

    /**
     * is Loan status Allowed to change
     * @param $new_loan_status_id
     * @param $old_loan_status_id
     */
    public function isLoanStatusAllowed($new_loan_status_id, $old_loan_status_id)
    {
        $new_status_key = LoanStatus::find($new_loan_status_id)->key;
        $old_status_key = LoanStatus::find($old_loan_status_id)->key;
        switch ($old_status_key) {
                case "FIRST_DISB":
                        if(in_array($new_status_key, ['FIRST_DISB','PART_DISB','FINAL_DISB'])){
                            return true;
                        }
                        else {
                            return false;
                        }
                        break;
                case "PART_DISB":
                        if(in_array($new_status_key, ['PART_DISB','FINAL_DISB'])){
                            return true;
                        }
                        else {
                            return false;
                        }
                        break;
                default:
                    return true;
                    break;
            }
    }  

    /**
     * Make a copy in Loan history
     * @param \Whatsloan\Repositories\Loans\Loan $newObject
     * @param \Whatsloan\Repositories\Loans\Loan $oldObject
     */
    public function isHistoryRequired(Loan $newObject, Loan $oldObject)
    {

        if ($this->updateHistory === true) {
            return true;
        }

        if (is_array($this->updateHistory)) {
            foreach ($this->updateHistory as $columns) {
                $new_status_key = LoanStatus::find($newObject[$columns])->key;
                $old_status_key = LoanStatus::find($oldObject[$columns])->key;
                if($new_status_key == 'PART_DISB' && $old_status_key == 'PART_DISB'){
                    return true;
                }
                if ($newObject[$columns] != $oldObject[$columns]) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Update loan history table
     * @param Loan $loan
     * @return boolean
     */
    public function updateHistory($loan,$amount)
    {

        return LoanHistory::create([
                    'uuid' => uuid(),
                    'loan_id' => $loan->id,
                    'type_id' => $loan->type_id,
                    'user_id' => $loan->user_id,
                    'agent_id' => $loan->agent_id,
                    'modified_by' => authUser()->id,
                    'amount' => $amount,
                    'eligible_amount' => $loan->eligible_amount,
                    'approved_amount' => $loan->approved_amount,
                    'interest_rate' => $loan->interest_rate,
                    'applied_on' => $loan->applied_on,
                    'approval_date' => $loan->approval_date,
                    'emi' => $loan->emi,
                    'emi_start_date' => $loan->emi_start_date,
                    'appid' => $loan->appid,
                    'loan_status_id' => $loan->loan_status_id,
        ]);
    }

    /**
     * Add a loan
     * @param array $data
     */
    public function store($data)
    {

        return \DB::transaction(function () use ($data) {
                    $projectId = Project::whereUuid($data['project_uuid'])->first()->id;

                    $data['uuid'] = uuid();
                    $loan = $this->loan->create($data);

                    if ((Type::whereId($data['type_id'])->first()->key) == 'HL') {
                        if($data['project_id'] > 0)
                            $loan->project()->attach(Project::find($data['project_id'])->first()->id);
                    }

                    return $loan;
                });
    }

    /**
     * store loan from admin panel
     * @param array $data
     */
    public function storeAsAdmin($data)
    {
         return \DB::transaction(function () use ($id,$data) {

            $data['uuid'] = uuid();
            $loan = $this->loan->create($data);
            $loan_project['loan_id'] = $loan->id;
            $loan_project['project_id'] = $projectId;
            $loan->project()->attach($loan_project);

            return $loan;
         });

    }

    /**
     * Include Ids of Uuid
     * @param array $data
     * @return array
     */
    public function includeIdsOfUuids($data)
    {

        $data['type_id'] = Type::whereUuid($data['type_uuid'])->first()->id;
        $data['user_id'] = User::whereUuid($data['user_uuid'])->first()->id;
        $data['agent_id'] = User::whereUuid($data['agent_uuid'])->first()->id;
        //$data['bank_id'] = Bank::whereUuid($data['bank_uuid'])->first()->id;
        $data['loan_status_id'] = LoanStatus::whereUuid($data['loan_status_uuid'])->first()->id;

        return $data;
    }

    /**
     * Get a paginated list of loans
     *
     * @param int $limit
     * @return mixed
     */
    public function paginateAsConsumers($userId, $limit = 15)
    {
        $loans = $this->loan->with(['bank', 'type', 'customer', 'agent', 'agent.banks', 'agent.teams', 'agent.teams.members', 'history', 'attachments'])->where('user_id', $userId);
        return $loans->paginate();
    }

    /**
     *
     * @param type $attachmentId
     * @param type $loanId
     * @return LoanDocument
     */
    public function saveAttachedDocument($attachmentId, $loanId)
    {
        $loan_document = new LoanDocument();

        $loan_document->uuid = \Webpatser\Uuid\Uuid::generate()->string;
        $loan_document->loan_id = $loanId;
        $loan_document->attachment_id = $attachmentId;
        $loan_document->save();

        return $loan_document;
    }

    /**
     * Get customer count
     * @return int
     */
    public function getLoanUserCount($ids, $userRole)
    {

        return Loan::with(['user'])
                        ->whereHas('user', function($query) use ($userRole) {
                            $query->where('role', $userRole);
                        })
                        ->whereIn('agent_id', $ids)
                        //->distinct('user_id')
                        ->count();
    }

    public function getLoanTasks()
    {
        return Loan::with(['tasks'])->whereUuid(request()->loan_uuid)->first();
    }

    /**
     * Get single loan detils
     * @param integer $id
     */
    public function find($id)
    {
        return $this->loan
                ->with(['type', 'userTrashed', 'agent', 'status','lead'])
                ->with(['tasks' => function($q){
                    $q->whereDate('from', '<=', date('Y-m-d'));
                    $q->whereDate('to', '>=', date('Y-m-d'));
                }])
                ->withTrashed()
                ->find($id);
    }


    /**
     * Update Single loan from admin panel
     * @param type $id
     * @param type $data
     */
    public function updateAsAdmin($id, $data)
    {

         return \DB::transaction(function () use ($id,$data) {
             $loan = $this->loan->with(['user'])->withTrashed()->find($id);
             $loanTemp = $loan->replicate();
             $loan->update($data);

            //Loan Approved
            if(LoanStatus::find($loan->loan_status_id)->key =='SANCTION') {
                $loan->update(['approval_date' => Carbon::now()->toDateTimeString()]);
            }

            if ((Type::whereId($data['type_id'])->first()->key) == 'HL') {
                if(isset($data['project_id']) && $data['project_id'] > 0){
                    $loan->project()->sync([$data['project_id']]);
                }else{
                    $loan->project()->detach();
                }
            }

            //Converting lead to customer
            if(in_array(LoanStatus::find($loan->loan_status_id)->key ,['BANK_LOGIN','OFFICE_LOGIN'])) {
                $loan->user()->update(['role' => 'CONSUMER']);
            }

            if ($this->isHistoryRequired($loan, $loanTemp)) {
                if(isset($data['disb'])){
                    $amount = $data['disbursement_amount'];
                }else{
                    $amount = $loan->amount;
                }
                $this->updateHistory($loan,$amount);
            }

            return $loan;
         });

    }
    
    /**
     * Updating Loan Statuses
     * @param type $request
     * @param type $uuid
     * @return type
     */
    public function updateStatus($request, $uuid)
    {
        $data['loan_status_id'] = LoanStatus::where('uuid',$request['loan_status_uuid'])->first()->id;
        $loan = $this->loan->whereUuid($uuid)->first();
//        if(LoanStatus::whereUuid($request['loan_status_uuid'])->first()->key == 'BANK_LOGIN') {
//            $data['approval_date'] = Carbon::now()->toDateTimeString();
//            $data['emi_start_date'] = NULL;
//        }
//        
//        $loan->update($data);
//        $this->updateHistory($loan);
//
//        if(LoanStatus::find($loan->loan_status_id)->key == 'BANK_LOGIN') {
//            $loan->user()->update(['role' => 'CONSUMER']);
//        }

        return $loan;
    }

    /**
     * Add a loan for customer
     * @param array $data
     */
    public function StoreCustomerLoan($data)
    {
        $data['uuid'] = uuid();
        $data['applied_on'] = Carbon::now()->toDateTimeString();
        $loan = $this->loan->create($data);
        $loan->history()->create([
            'uuid' => uuid(),
            'loan_id' => $loan->id,
            'type_id' => $loan->type_id,
            'user_id' => $loan->user_id,
            'agent_id' => $loan->agent_id,
            'modified_by' => \Auth::guard('web')->user()->id,
            'amount' => $loan->amount,
            'applied_on' => Carbon::now()->toDateTimeString(),
            'loan_status_id' => $loan->loan_status_id,
        ]);

        if (Type::whereId($data['type_id'])->first()->key == 'HL' && (isset($data['project_id']) && !empty($data['project_id']))) {
            $loan->project()->attach($data['project_id']);
        }

        $lead = Lead::create([
            'uuid' => uuid(),
            'user_id' => $loan->user_id,
            'loan_id' => $loan->id,
            'source_id' => $data['source_id'],
            'assigned_to' => \Auth::guard('web')->user()->id,
            'created_by' => \Auth::guard('web')->user()->id,
            'existing_loan_emi' => 0,
        ]);


        if (Source::find($data['source_id'])->key == 'REFERRAL') {
            $lead->referrals()->attach(User::find($data['referral_id'])->id);
        }

        return $loan;

    }
    
    /**
     * Fetching Loan Statuses list
     * @return type
     */
    public function loanStatuses()
    {
        return $loan_statuses = LoanStatus::get();
    }

    /**
     * Update a loan document
     *
     * @param $id
     * @param $documentId
     * @param array $request
     * @return mixed
     */
    public function updateLoanDocument($id, $documentId, array $request)
    {
        $loan = $this->loan->with(['attachments' => function($query) use ($documentId) {
                        $query->whereId($documentId)->take(1);
                    }])->where('id', $id)->first();

        return $loan->attachments->first()->update($request);
    }

}
