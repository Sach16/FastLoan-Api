<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreLoanRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateLoanRequest;
use Whatsloan\Jobs\StoreLoanJob;
use Whatsloan\Jobs\UpdateAdminLoanJob;
use Whatsloan\Repositories\Leads\Lead;
use Whatsloan\Repositories\LoanHistories\LoanHistory;
use Whatsloan\Repositories\LoanStatuses\LoanStatus;
use Whatsloan\Repositories\Loans\Contract as ILoans;

class LoansController extends Controller
{

    /**
     * @var $loans
     */
    public $loans;

    /**
     * Loans controller constructor
     * @param ILoans $loans
     */
    public function __construct(ILoans $loans)
    {
        $this->loans = $loans;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *$data
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $lead = Lead::with(['user'])->find($request->id);
        return view('admin.v1.loans.create')->withLead($lead);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLoanRequest $request)
    {
        $this->dispatch(new StoreLoanJob($request->all()));
        return redirect()->route('admin.v1.leads.show', $request->lead_id)->withSuccess("Loan created successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loan = $this->loans->find($id);
        return view('admin.v1.loans.show')->withLoan($loan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $loan = $this->loans->find($id);
        return view('admin.v1.loans.edit')->withLoan($loan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLoanRequest $request, $id)
    {
        $loan      = $this->loans->find($id);
        $isAllowed = $this->loans->isLoanStatusAllowed($request->loan_status_id, $loan->loan_status_id);
        if (!$isAllowed) {
            return redirect()->back()->withError("Changing loan status to previous status is not allowed.");
        }

        $loan_status   = LoanStatus::find($request->loan_status_id);
        $loan_statuses = LoanStatus::whereIn('key', ['FIRST_DISB', 'PART_DISB', 'FINAL_DISB'])->get()->lists('id');

        if (in_array($loan_status->key, ['FIRST_DISB', 'PART_DISB', 'FINAL_DISB'])) {
            $sanction_status = LoanStatus::whereIn('key', ['SANCTION'])->count();
            if ($sanction_status <= 0) {
                return redirect()->back()->withError("Loan is not sanctioned.");
            }
            //check approved amount is set
            if ($loan->approved_amount <= 0) {
                return redirect()->back()->withError("Approved amount required.");
            }

            $disb_count = LoanHistory::whereIn('loan_status_id', $loan_statuses)->where('loan_id', $id);
            if (($amount = ($disb_count->sum('amount') + $request->disbursement_amount)) > $loan->approved_amount) {
                return redirect()->back()->withError("Already Disbursement amount is released.");
            }
        }

        $this->dispatch(new UpdateAdminLoanJob($id, $request->all()));
        return redirect()->route('admin.v1.loans.show', $id)->withSuccess("Loan updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loan = $this->loans->find($id);
        $loan->trashed() ? $loan->restore() : $loan->delete();
        $loan->leadTrashed->first()->trashed() ? $loan->leadTrashed->first()->restore() : $loan->leadTrashed->first()->delete();
        return redirect()->back()->withSuccess('Loan updated successfully');
    }

}
