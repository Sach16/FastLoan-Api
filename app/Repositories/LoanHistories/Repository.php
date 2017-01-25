<?php

namespace Whatsloan\Repositories\LoanHistories;

class Repository implements Contract
{

    /**
     * LoanHistory Model object
     * @var $loanHistory 
     */
    private $loanHistory;

    /**
     * Loan history repository constructor
     * @param \Whatsloan\Repositories\LoanHistories\LoanHistory $loanHistory
     */
    public function __construct(LoanHistory $loanHistory)
    {
        $this->loanHistory = $loanHistory;
    }

    /**
     * Store loan history data
     * @param array $data
     */
    public function store($data)
    {
        return $this->loanHistory->create($data);
    }

}
