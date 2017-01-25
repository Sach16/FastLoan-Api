<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Whatsloan\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Repositories\Payouts\Contract as IPayout;
use Whatsloan\Repositories\Payouts\Payout;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Http\Requests\Admin\V1\UpdatePayoutRequest;
use Whatsloan\Http\Requests\Admin\V1\StorePayoutRequest;
use Whatsloan\Jobs\UpdatePayoutJob;
use Whatsloan\Jobs\StorePayoutJob;
use Whatsloan\Repositories\Users\User;

class PayoutsController extends Controller
{

    /**
     * IPayout
     * @var $payouts
     */
    public $payouts;

    public function __construct(IPayout $payouts)
    {
        $this->payouts = $payouts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::whereHas('payoutsTrashed',function($q){})
                                ->with('payoutsTrashed','builder')
                                ->withTrashed()
                                ->orderBy('deleted_at')
                                ->paginate();

        $referrals = User::whereHas('payoutsTrashed',function($q){})
                                    ->with('payoutsTrashed')
                                    ->withTrashed()
                                    ->orderBy('deleted_at')
                                    ->paginate();
        return view('admin.v1.payouts.index')->withProjects($projects)
                                             ->withReferrals($referrals);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.v1.payouts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePayoutRequest $request)
    {
//
    }

    /**
     * Display the specified resource.
     *en le dasi nin login time?? 4pm?
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
//
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePayoutRequest $request, $id)
    {
//
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {  
        $payout = $this->payouts->find($id);
        $payout->trashed() ? $payout->restore() : $payout->delete();
        return redirect()->route('admin.v1.payouts.index')->withSuccess('Payout updated successfully');
    }

}
