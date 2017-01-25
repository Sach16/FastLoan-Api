<?php

namespace Whatsloan\Repositories\Loans;

use Illuminate\Http\Request;

interface Contract
{

    /**
     * Get a paginated list of loans
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15);

    /**
     * Show single loan details
     * @param string $uuid
     */
    public function show($uuid);

    /**
     * Update a single loan
     * @param array $data
     * @param string $uuid
     */
    public function update($data, $uuid);

    /**
     * is Loan status Allowed to change
     * @param $new_loan_status_id
     * @param $old_loan_status_id
     */
    public function isLoanStatusAllowed($new_loan_status_id, $old_loan_status_id);

    /**
     * Make a copy in Loan history
     * @param \Whatsloan\Repositories\Loans\Loan $newObject
     * @param \Whatsloan\Repositories\Loans\Loan $oldObject
     */
    public function isHistoryRequired(Loan $newObject, Loan $oldObject);

    /**
     * Update loan history table
     * @param Loan $loan
     * @return boolean
     */
    public function updateHistory($loan,$amount);


    /**
     * Get a loan
     * @param integer $id
     */
    public function find($id);
    /**
     * store the loan for customer
     * @param array $data
     */
    public function StoreCustomerLoan($data);

    /**
     * Update a loan document
     *
     * @param $id
     * @param $documentId
     * @param array $request
     * @return mixed
     */
    public function updateLoanDocument($id, $documentId, array $request);
    
    /**
     * Fetching Loan Statuses list
     * @return type
     */
    public function loanStatuses();
    
    /**
     * Update loan status
     * @param type $request
     * @param type $uuid
     */
    public function updateStatus($request, $uuid);
}
