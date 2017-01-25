<?php

namespace Whatsloan\Repositories\Loans;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;
use Whatsloan\Repositories\LoanHistories\LoanHistory;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Tasks\Taskable;
use Whatsloan\Repositories\Attachments\Attachable;
use Whatsloan\Repositories\Leads\Lead;
use Whatsloan\Repositories\LoanStatuses\LoanStatus;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Services\Audits\Auditable;
use Carbon\Carbon;

class Loan extends Model
{

    use Taskable,
        PresentableTrait,
        SoftDeletes,
        Relations,
        Attachable,
        Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'type_id',
        'user_id',
        'agent_id',
        'bank_id',
        'amount',
        'eligible_amount',
        'approved_amount',
        'interest_rate',
        'applied_on',
        'approval_date',
        'emi',
        'emi_start_date',
        'appid',
        'loan_status_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Model presenter
     *
     * @var $presenter
     */
    protected $presenter = Presenter::class;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'approval_date',
        'emi_start_date',
        'applied_on',
        'loan_created_at'
    ];

    /**
     * Returns the path of documents
     *
     * @return string
     */
    public function getDocumentPath()
    {
        return 'documents/' . $this->uuid . '/';
    }
    
    /**
     * Returns the path of Loan documents
     *
     * @return string
     */
    public function getLoanDocumentPath()
    {
        return 'loans/documents/' . $this->uuid . '/';
    }

    /**
     * Loan has history
     */
    public function history()
    {
        return $this->hasMany(LoanHistory::class);
    }
    
    /**
     * Loan has history
     */
    public function total_tat()
    {
        return $this->hasMany(LoanHistory::class);
    }

    /**
     * Loan has Documents
     */
    public function loan_documents()
    {
        return $this->hasMany(LoanDocument::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * loan with user trashed 
     * @return [type] [description]
     */
    public function userTrashed()
    {
        return $this->user()->withTrashed();
    }

    /**
     * Lead belongs to User
     * @return Collection
     */
    public function lead()
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * Trashed Lead belongs to User
     * @return Collection
     */
    public function leadTrashed()
    {
        return $this->lead()->withTrashed();
    }

    /**
     * Loan has project
     * @return Item
     */
    public function project()
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }

    /**
     * Loan has a agent
     * @return Item
     */
    public function agent()
    {
        return $this->belongsTo(User::class,'agent_id');
    }
    
    public function loan_statuses()
    {
        return $this->belongsTo(LoanStatus::class,'loan_status_id');
    
    }

    /**
     * Set the approval_date, emi_start_date.
     *
     * @param  string  $value
     * @return string
     */
    public function setEmiStartDateAttribute($value)
    {
        $this->attributes['emi_start_date'] = Carbon::parse($value);
    }

    public function setApprovalDateAttribute($value)
    {
        $this->attributes['approval_date'] = Carbon::parse($value);
    }

    public function setAppliedOnAttribute($value)
    {
        $this->attributes['applied_on'] = Carbon::parse($value);
    }

}
