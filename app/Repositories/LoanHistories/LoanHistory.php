<?php

namespace Whatsloan\Repositories\LoanHistories;

use Illuminate\Database\Eloquent\Model;
use Whatsloan\Repositories\Loans\Relations;
use Whatsloan\Services\Audits\Auditable;

class LoanHistory extends Model
{

    use Relations, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'loan_id',
        'type_id',
        'user_id',
        'agent_id',
        'bank_id',
        'modified_by',
        'amount',
        'eligible_amount',
        'approved_amount',
        'interest_rate',
        'applied_on',
        'approval_date',
        'emi',
        'emi_start_date',
        'appid',
        'loan_status_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

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
        'applied_on'
    ];

}
