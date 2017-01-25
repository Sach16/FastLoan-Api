<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;

use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Requests\Admin\V1\StoreLoanRequest;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Jobs\StoreCustomerLoanJob;

class CustomersLoansController extends Controller
{


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
     *
     * @param $customerId
     * @return \Illuminate\Http\Response
     */
    public function create($customerId)
    {
        $customer = User::customers()->find($customerId);
        return view('admin.v1.customers.loans.create')->withCustomer($customer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreCustomerDocumentRequest $request
     * @param $customerId
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLoanRequest $request, $customerId)
    {
        $request->offsetSet('user_id', $customerId);
        $this->dispatch(new StoreCustomerLoanJob($request->all()));
        return redirect()->route('admin.v1.customers.show', $customerId)->withSuccess('Loan Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
      //
    }

}
