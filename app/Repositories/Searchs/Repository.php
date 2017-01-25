<?php

namespace Whatsloan\Repositories\Searchs;

use Illuminate\Http\Request;
use Whatsloan\Repositories\Searchs\Contract as Searchs;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Loans\Loan;
use Whatsloan\Repositories\LoanStatuses\LoanStatus;
use Whatsloan\Repositories\Types\Type;
use Whatsloan\Repositories\Builders\Builder;
use Whatsloan\Repositories\Projects\Project;
use Carbon\Carbon;

class Repository implements Contract
{

    /**
     * @var Search
     */
    private $search;

    /**
     * @var Search
     */
    private $users;

    /**
     * Repository constructor.
     * @param Search $search
     */
    public function __construct(Search $search)
    {
        $this->search = $search;
    }

    /**
     * Get a paginated list of tasks
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15)
    {
        return $this->search->paginate($limit);
    }

    /**
     * Store a new Task
     *
     * @param $request
     * @return mixed
     */
    public function store($taskableObject, $data)
    {
        //
    }

    /**
     * Update an existing task
     *
     * @param $id
     * @param $request
     * @return mixed
     */
    public function update($data, $uuid)
    {
        //
    }

    /**
     * 
     * @param type $role
     * @param type $loanTypeCount
     * @return type object
     */
    public function search($role, $loanTypeCount = "")
    {
        if(isset(request()->all) && !empty(request()->all)) {
            
            // if ($loanTypeCount) 
            // {
            //     return 0;
            // }
            
            $loans = Loan::join('users as user', function ($join) {
                        $join->on('user.id', '=', 'loans.user_id')
                                ->whereIn('loans.agent_id', teamMemberIds(true));
                    })                                        
                    ->join('types as type', 'type.id', '=', 'loans.type_id')                    
                    ->join('loan_statuses as ls','ls.id','=','loans.loan_status_id')
                    ->leftjoin('loan_project as lp', 'lp.loan_id','=','loans.id')
                    ->leftjoin('projects as pro','pro.id', '=', 'lp.project_id')
                    ->leftjoin('builders as build','build.id', '=', 'pro.builder_id') 
                    ->where('ls.key','!=','LOGOUT');

                    if($role == 'LEAD') { 
                        $loans->where('user.role', 'LEAD');                
                    }
                    
                    if($role == 'CONSUMER') { 
                        $loans->where('user.role', 'CONSUMER');                
                    }

                $loans->where('user.first_name','LIKE','%'.request()->all.'%')
                    ->orWhere('user.last_name','LIKE','%'.request()->all.'%')
                    ->orWhere('user.phone','LIKE','%'.request()->all.'%')
                    ->orWhere('ls.label','LIKE','%'.request()->all.'%')
                    ->orWhere('pro.unit_details','LIKE','%'.request()->all.'%')
                    ->orWhere('type.key','LIKE','%'.request()->all.'%')
                    ->orWhere('type.name','LIKE','%'.request()->all.'%')
                    ->orWhere('loans.created_at', 'LIKE','%'.request()->all.'%')
                    ->orWhere('pro.name', 'LIKE','%'.request()->all.'%')
                    ->orWhere('build.name', 'LIKE','%'.request()->all.'%');
                    
                    if($role == 'LEAD') { 
                        $loans->having('user.role','=' ,'LEAD');                
                    }
                    
                    if($role == 'CONSUMER') { 
                        $loans->having('user.role','=' ,'CONSUMER');                
                    }
                    $loans->whereNotNull('loans.agent_id');
                    $loans->orderBy('loans.created_at','DESC');

                    if ($loanTypeCount) {
                        return $loans->where('type.key', $loanTypeCount)->count();
                    }
                    
                    return $loans->select(['*','loans.created_at as loan_created_at'])->get();
        //    return $loans->paginate(15,['*','loans.created_at as loan_created_at']);
            
        } else {            
            $loans = Loan::join('users as user','user.id', '=', 'loans.user_id')
            ->join('types as type', 'type.id', '=', 'loans.type_id')                    
            ->join('loan_statuses as ls','ls.id','=','loans.loan_status_id')
            ->leftjoin('loan_project as lp', 'lp.loan_id','=','loans.id')
            ->leftjoin('projects as pro','pro.id', '=', 'lp.project_id')
            ->leftjoin('builders as build','build.id', '=', 'pro.builder_id');

            if(isset(request()->name) && !empty(request()->name))
            {
                // $loans->where('user.first_name','LIKE','%'.request()->name.'%')
                // ->orWhere('user.last_name','LIKE','%'.request()->name.'%');   
                $loans->orWhereRaw("concat(user.first_name, ' ', user.last_name) like '%".request()->name."%' ");
            }

            if(isset(request()->phone) && !empty(request()->phone))
            {
                $loans->where('user.phone','LIKE','%'.request()->phone.'%');
            }

            if(isset(request()->unit_number) && !empty(request()->project_uuid))
            {
                $loans->where('pro.id',Project::whereUuid(request()->project_uuid)->first()->id)
                      ->where('pro.builder_id', Builder::whereUuid(request()->builder_uuid)->first()->id)
                      ->where('pro.unit_details',request()->unit_number);                        
            }

            if(isset(request()->unit_number) && !empty(request()->project_uuid))
            {
                $loans->where('pro.id',Project::whereUuid(request()->project_uuid)->first()->id)
                      ->where('pro.builder_id', Builder::whereUuid(request()->builder_uuid)->first()->id)
                      ->where('pro.unit_details',request()->unit_number);                        
            }

            if(!isset(request()->unit_number) && empty(request()->unit_number)){
                if(isset(request()->builder_uuid) && !empty(request()->builder_uuid))
                {
                    $loans->where('build.name','LIKE','%'.request()->builder_uuid.'%');
                }

                if(isset(request()->project_uuid) && !empty(request()->project_uuid))
                {
                    $loans->where('pro.name','LIKE','%'.request()->project_uuid.'%');
                }
            }

            if (isset(request()->loan_status_name) && !empty(request()->loan_status_name)) {
                $statusIds = LoanStatus::where('label', 'LIKE', '%' . request()->loan_status_name . '%')->get()->lists('id')->all();
                $loans->whereIn('loans.loan_status_id', $statusIds);
            }

            if (isset(request()->loan_type_name) && !empty(request()->loan_type_name)) {
                $typeIds = Type::where('key', 'LIKE', '%' . request()->loan_type_name . '%')
                            ->orWhere('name', 'LIKE', '%' . request()->loan_type_name . '%')
                            ->lists('id')->all();
                $loans->whereIn('loans.type_id', $typeIds);
            }                    

            if (isset(request()->from_date) && !empty(request()->from_date) ) {
                $loans->whereBetween('loans.created_at', [Carbon::parse(request()->from_date)->startOfDay(), Carbon::now()]);
            }

            if (isset(request()->lead_date) && !empty(request()->lead_date) ) {
                $loans->whereBetween('loans.created_at', [Carbon::parse(request()->lead_date)->startOfDay(), Carbon::parse(request()->lead_date)->endOfDay()]);
            }

            if (isset(request()->login_date) && !empty(request()->login_date)) {
                $loans->whereHas('history' , function($q){
                    $loan_stages = LoanStatus::whereIn('key',['BANK_LOGIN','OFFICE_LOGIN'])->lists('id');
                    $q->whereIn('loan_status_id',$loan_stages);
                    $q->whereBetween('created_at',[Carbon::parse(request()->login_date)->startOfDay(), Carbon::parse(request()->login_date)->endOfDay()]);
                }
                );
            }

            if (isset(request()->user_uuid) && !empty(request()->user_uuid)) {
                $loans->where('loans.agent_id', [User::whereUuid(request()->user_uuid)->first()->id]);
            } else {
                $loans->whereIn('loans.agent_id', teamMemberIds(true));
            }
                    
            if (isset(request()->statusgroup) && !empty(request()->statusgroup)) {
                $group = [
                    'logins'       => ['OFFICE_LOGIN', 'BANK_LOGIN', 'RE_LOGIN'],
                    'disbursements'=> ['FIRST_DISB', 'PART_DISB', 'FINAL_DISB'],
                    'sanction'     => ['SANCTION'],
                    'first_disb'  => ['FIRST_DISB'],
                    'part_disb'   => ['PART_DISB'],
                    'full_disb'   => ['FINAL_DISB'],
                ];
                $loanStatusIds = LoanStatus::whereIn('key',$group[request()->statusgroup])->get()->lists('id')->all();
                $loans->whereIn('loans.loan_status_id', $loanStatusIds);
            }
            
            
            if($role == 'LEAD') { 
                $loans->where('user.role', 'LEAD');                
            }
            
            if($role == 'CONSUMER') { 
                $loans->where('user.role', 'CONSUMER');                
            }


            $loans->where('ls.key', '!=','LOGOUT');
            $loans->whereNotNull('loans.agent_id');
            $loans->orderBy('loans.created_at','DESC');

            if ($loanTypeCount) {
                return $loans->where('type.key', $loanTypeCount)->count();
            }
            return $loans->paginate(15,['*','loans.created_at as loan_created_at']);
        }        
    }
}
