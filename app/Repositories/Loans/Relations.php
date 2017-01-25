<?php

namespace Whatsloan\Repositories\Loans;

use Whatsloan\Repositories\LoanStatuses\LoanStatus;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Types\Type;

trait Relations
{
    
    /**
     * Loan has a bank
     * @return type
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * Loan has a bank
     * @return type
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Loan has a bank
     * @return type
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Loan has a bank
     * @return type
     */
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Trashed Loan has a bank
     * @return type
     */
    public function typeTrashed()
    {
        return $this->type()->withTrashed();
    }

    /**
     * Loan has status
     * @return type
     */
    public function status()
    {
        return $this->belongsTo(LoanStatus::class, 'loan_status_id');
    }

    /**
     * Trashed Loan has status
     * @return type
     */
    public function statusTrashed()
    {
        return $this->status()->withTrashed();
    }

}
