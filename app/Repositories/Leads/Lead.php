<?php

namespace Whatsloan\Repositories\Leads;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Whatsloan\Repositories\Addresses\Addressable;
use Whatsloan\Repositories\Builders\Builder;
use Whatsloan\Repositories\Loans\Loan;
use Whatsloan\Repositories\Sources\Source;
use Whatsloan\Repositories\Sources\Sourceable;
use Whatsloan\Repositories\Tasks\Taskable;
use Whatsloan\Repositories\Teams\Team;
use Whatsloan\Repositories\Types\Typeable;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Services\AccessControl\ControlsAccess;
use Whatsloan\Services\Audits\Auditable;
use Whatsloan\Repositories\LoanStatuses\LoanStatus;
/**
 * @property mixed uuid
 * @property mixed name
 * @property mixed loan_amount
 * @property mixed net_salary
 * @property mixed existing_loan_emi
 * @property mixed company_name
 * @property mixed created_by
 * @property mixed attachments
 * @property mixed loans
 * @property mixed source
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed addresses
 * @property mixed sources
 * @property mixed types
 * @property mixed agent
 * @property mixed validation
 */
class Lead extends Model
{

    use ControlsAccess,
    Addressable,
    Sourceable,
    Typeable,
    Auditable,
    SoftDeletes,
        Taskable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        "user_id",
        "loan_id",
        "source_id",
        "assigned_to",
        "existing_loan_emi",
        "created_by",
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'loan_amount'       => 'float',
        'net_salary'        => 'float',
        'existing_loan_emi' => 'float',
    ];

    /**
     * The validation rules of the model
     *
     * @var array
     */
    protected $validations = [
        'uuid'              => 'required',
        'user_uuid'         => 'required|exists:users,uuid',
        'source_uuid'       => 'required|exists:sources,uuid',
        'type_uuid'         => 'required|exists:types,uuid',
        'name'              => 'required',
        'loan_amount'       => 'required|numeric',
        'net_salary'        => 'required|numeric',
        'existing_loan_emi' => 'required|numeric',
        'company_name'      => 'required',
        'created_by'        => 'required',
    ];

    /**
     * Get the validation rules for the model
     *
     * @return array
     */
    public function getValidations()
    {
        return $this->validations;
    }

    /**
     * Lead belongs to
     * @return Collection
     */
    public function cities()
    {
        return $this->belongsTo(Cities::class);
    }

    /**
     * Lead belongs to User
     * @return Collection
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Lead belongs to User
     * @return Collection
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    /**
     * Lead belongs to User
     * @return Collection
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Trashed Lead belongs to User
     * @return Collection
     */
    public function userTrashed()
    {
        return $this->user()->withTrashed();
    }

    /**
     * Each team has multiple members
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function referrals()
    {
        return $this->belongsToMany(User::class, 'lead_referral', 'lead_id', 'referral_id')->withTimestamps();
    }

    /**
     * Lead belongs to User
     * @return Collection
     */
    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    /**
     * Lead belongs to User
     * @return Collection
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Trashed Lead belongs to User
     * @return Collection
     */
    public function loanTrashed()
    {
        return $this->loan()->withTrashed();
    }

    /**
     * Lead belongs to User
     * @return Collection
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
    /**
     * Filter API Query
     *
     * @param $query
     * @param $request
     * @param array $filters
     */
    public function scopeDashboardStats($query, $request, $role, $loan_status = '', $filters = [])
    {
        $query
            ->join('loans as loan', 'leads.loan_id', '=', 'loan.id')
            ->join('users as user', 'loan.user_id', '=', 'user.id');

        if ($role == 'CONSUMER' && $loan_status == 'DISB') {
            $query->join('loan_histories as histories', 'histories.loan_id', '=', 'leads.loan_id')
                ->join('loan_statuses as ls', 'histories.loan_status_id', '=', 'ls.id');
        } else {
            $query->join('loan_statuses as ls', 'loan.loan_status_id', '=', 'ls.id');
        }
        if (!empty($filters)) {
            $query->whereIn('ls.key', $filters);
        }

        if ($role == 'LEAD') {
            if ($request->has('member_uuid')) {
                $query->where('leads.created_by', User::whereUuid($request->member_uuid)->first()->id);
            } else {
                $query->whereIn('leads.created_by', teamMemberIds(true));
            }
        } else {
            if ($request->has('member_uuid')) {
                $query->where('loan.agent_id', User::whereUuid($request->member_uuid)->first()->id);
            } else {
                $query->whereIn('loan.agent_id', teamMemberIds(true));
            }
        }

        $query->where('user.role', $role);

        if ($role == 'LEAD') {
            $query->select(\DB::raw("leads.id, loan.amount as amount"));
        } else if ($role == 'CONSUMER' && $loan_status == 'LOGIN') {
            $query->select(\DB::raw("leads.id, loan.amount as amount"));
        } else if ($role == 'CONSUMER' && $loan_status == 'SANCTION') {
            $query->select(\DB::raw("leads.id, loan.approved_amount as amount"));
        } else if ($role == 'CONSUMER' && $loan_status == 'DISB') {
            $query->select(\DB::raw("leads.id, histories.amount as disb_amount"));
        }

        if ($request->has('from') && $request->has('to')) {
            $query->whereBetween('leads.created_at', [Carbon::parse($request->from)->startOfDay(), Carbon::parse($request->to)->endOfDay()]);
        }

        if ($request->has('period') && $request->period == 'current_month') {
            $query->whereYear('leads.created_at', '=', Carbon::now()->year)
                ->whereMonth('leads.created_at', '=', (strlen(Carbon::now()->month) == 1 ? '0' . Carbon::now()->month : Carbon::now()->month));
        }

        if ($request->has('period') && $request->period == 'current_quarter') {
            $query->whereBetween('leads.created_at', [Carbon::now()->firstOfQuarter(), Carbon::now()->lastOfQuarter()]);
        }

        if ($request->has('period') && $request->period == 'current_financial_year') {
            if (Carbon::now()->month >= 4) {
                $query->whereBetween('leads.created_at', [Carbon::now()->year . '-04-01 00:00:00', Carbon::now()]);
            } else {
                $year           = Carbon::now()->year - 1;
                $financial_year = $year . '-04-01 00:00:00';
                $query->whereBetween('leads.created_at', [$financial_year, Carbon::now()]);
            }
        }
    }

    public function scopeBuilderDashboardStats($query, $request, $role, $loan_status = '', $filters = [])
    {
        $query
            ->join('loans as loan', 'leads.loan_id', '=', 'loan.id')
            // ->join('loan_statuses as ls', 'loan.loan_status_id', '=', 'ls.id')
            ->join('users as user', 'loan.user_id', '=', 'user.id')
            ->join('loan_project as lp', 'loan.id', '=', 'lp.loan_id')
            ->join('projects as project', 'lp.project_id', '=', 'project.id')
            ->join('builders as builder', 'project.builder_id', '=', 'builder.id');
        // ->join('payouts as payout', 'payout.payoutable_id','=','project.id')
        // ->where('payout.payoutable_type','Whatsloan\Repositories\Projects\Project');

        if ($role == 'CONSUMER' && $loan_status == 'DISB') {
            $query->join('loan_histories as histories', 'histories.loan_id', '=', 'leads.loan_id')
                ->join('loan_statuses as ls', 'histories.loan_status_id', '=', 'ls.id');
        } else {
            $query->join('loan_statuses as ls', 'loan.loan_status_id', '=', 'ls.id');
        }

        if ($request->has('member_uuid')) {
            $query->where('loan.agent_id', User::whereUuid($request->member_uuid)->first()->id);
        } else {
            $query->whereIn('loan.agent_id', teamMemberIds(true));
        }

        $query->where('user.role', $role);

        if ($role == 'LEAD') {
            $query->select(\DB::raw("leads.id, loan.amount as amount"));
        } else if ($role == 'CONSUMER' && $loan_status == 'LOGIN') {
            $query->select(\DB::raw("leads.id, loan.amount as amount"));
        } else if ($role == 'CONSUMER' && $loan_status == 'DISB') {
            $LoanStatus = LoanStatus::whereKey('LOGOUT')->first();
            $query->select(\DB::raw("leads.id, histories.amount as disb_amount, (select count(id) from loan_histories as lh where lh.loan_id = loan.id and lh.loan_status_id = ".$LoanStatus->id." ) as logout_count "));
        }

        if ($request->builder_uuid) {
            $builderUuid = $request->builder_uuid;
            $builderId   = Builder::whereUuid($builderUuid)->first()->id;
            $query->where('builder.id', $builderId);
        }

        if (!empty($filters)) {
            $query->whereIn('ls.key', $filters);
        }

        if ($request->has('period') && $request->period == 'current_month') {
            $query->whereYear('leads.created_at', '=', Carbon::now()->year)
                ->whereMonth('leads.created_at', '=', (strlen(Carbon::now()->month) == 1 ? '0' . Carbon::now()->month : Carbon::now()->month));
        }

        if ($request->has('period') && $request->period == 'current_quarter') {
            $query->whereBetween('leads.created_at', [Carbon::now()->firstOfQuarter(), Carbon::now()->lastOfQuarter()]);
        }

        if ($request->has('period') && $request->period == 'current_financial_year') {
            if (Carbon::now()->month >= 4) {
                $query->whereBetween('leads.created_at', [Carbon::now()->year . '-04-01 00:00:00', Carbon::now()]);
            } else {
                $year           = Carbon::now()->year - 1;
                $financial_year = $year . '-04-01 00:00:00';
                $query->whereBetween('leads.created_at', [$financial_year, Carbon::now()]);
            }
        }

        if ($request->has('from') && $request->has('to')) {
            $query->whereBetween('leads.created_at', [Carbon::parse($request->from)->startOfDay(), Carbon::parse($request->to)->endOfDay()]);
        }
    }

    public function scopeReferralDashboardStats($query, $request, $role, $loan_status = '', $filters = [])
    {
        $query
            ->join('loans as loan', 'leads.loan_id', '=', 'loan.id')
            ->join('loan_statuses as ls', 'loan.loan_status_id', '=', 'ls.id')
            ->join('users as user', 'loan.user_id', '=', 'user.id')
            ->join('lead_referral as lr', 'lr.lead_id', '=', 'leads.id')
            ->join('payouts as payout', 'payout.payoutable_id', '=', 'lr.referral_id')
            ->where('payout.payoutable_type', 'Whatsloan\Repositories\Users\User');

        $query->whereIn('loan.agent_id', teamMemberIds(true));

        if ($request->has('referral_uuid')) {
            $query->where('lr.referral_id', User::whereUuid($request->referral_uuid)->first()->id);
        }

        $query->where('user.role', $role);

        if ($role == 'LEAD') {
            $query->select(\DB::raw("leads.id, loan.amount as amount, CEIL((loan.amount * payout.percentage) / 100) as payout, payout.total_paid_amount as total_paid"));
        } else if ($role == 'CONSUMER' && $loan_status == 'LOGIN') {
            $query->select(\DB::raw("leads.id, loan.amount as amount, CEIL((loan.amount * payout.percentage) / 100) as payout, payout.total_paid_amount as total_paid"));
        } else if ($role == 'CONSUMER' && $loan_status == 'SANCTION') {
            $query->select(\DB::raw("leads.id, loan.approved_amount as amount, CEIL((loan.approved_amount * payout.percentage) / 100) as payout, payout.total_paid_amount as total_paid"));
        } else if ($role == 'CONSUMER' && $loan_status == 'DISB') {
            $query->select(\DB::raw("leads.id, loan.approved_amount as amount, CEIL((loan.approved_amount * payout.percentage) / 100) as payout, payout.total_paid_amount as total_paid"));
        }

        if (!empty($filters)) {
            $query->whereIn('ls.key', $filters);
        }

        if ($request->has('period') && $request->period == 'current_month') {
            $query->whereYear('leads.created_at', '=', Carbon::now()->year)
                ->whereMonth('leads.created_at', '=', (strlen(Carbon::now()->month) == 1 ? '0' . Carbon::now()->month : Carbon::now()->month));
        }

        if ($request->has('period') && $request->period == 'current_quarter') {
            $query->whereBetween('leads.created_at', [Carbon::now()->firstOfQuarter(), Carbon::now()->lastOfQuarter()]);
        }

        if ($request->has('period') && $request->period == 'current_financial_year') {
            if (Carbon::now()->month >= 4) {
                $query->whereBetween('leads.created_at', [Carbon::now()->year . '-04-01 00:00:00', Carbon::now()]);
            } else {
                $year           = Carbon::now()->year - 1;
                $financial_year = $year . '-04-01 00:00:00';
                $query->whereBetween('leads.created_at', [$financial_year, Carbon::now()]);
            }
        }

        if ($request->has('from') && $request->has('to')) {
            $query->whereBetween('leads.created_at', [$request->from, $request->to]);
        }
    }
}
