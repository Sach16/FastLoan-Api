<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Whatsloan\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Repositories\Payouts\Contract as IPayout;
use Whatsloan\Repositories\Payouts\Payout;
use Whatsloan\Http\Requests\Admin\V1\UpdateReferalPayoutRequest;
use Whatsloan\Http\Requests\Admin\V1\StoreReferalPayoutRequest;
use Whatsloan\Jobs\UpdateReferalPayoutJob;
use Whatsloan\Jobs\StoreReferalPayoutJob;
use Whatsloan\Repositories\Users\User;

class ReferralPayoutsController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.v1.payouts.referral.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReferalPayoutRequest $request)
    {
        $request->offsetSet('uuid', uuid());
        $this->dispatch(new StoreReferalPayoutJob($request->all()));
        return redirect()->route('admin.v1.payouts.index')->withSuccess('Referral payouts added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $referral = User::with(['payouts'])->find($id);
        return view('admin.v1.payouts.referral.edit')->withReferral($referral);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReferalPayoutRequest $request, $id)
    {
        $this->dispatch(new UpdateReferalPayoutJob($request->all(), $id));
        return redirect()->route('admin.v1.payouts.index')->withSuccess('Referral payout updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//
    }

}