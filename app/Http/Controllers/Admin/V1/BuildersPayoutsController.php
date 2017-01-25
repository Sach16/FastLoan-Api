<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Whatsloan\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Repositories\Payouts\Contract as IPayout;
use Whatsloan\Repositories\Payouts\Payout;
use Whatsloan\Http\Requests\Admin\V1\UpdateBuilderPayoutRequest;
use Whatsloan\Http\Requests\Admin\V1\StoreBilderPayoutRequest;
use Whatsloan\Jobs\UpdateBuilderPayoutJob;
use Whatsloan\Jobs\StoreBuilderPayoutJob;
use Whatsloan\Repositories\Projects\Project;

class BuildersPayoutsController extends Controller
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
        return view('admin.v1.payouts.builders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBilderPayoutRequest $request)
    {
        $request->offsetSet('uuid', uuid());
        $this->dispatch(new StoreBuilderPayoutJob($request->all()));
        return redirect()->route('admin.v1.payouts.index')->withSuccess('Builder payouts added successfully');
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
        $project = Project::with('payouts','builder')
                    ->find($id);
        return view('admin.v1.payouts.builders.edit')->withProject($project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBuilderPayoutRequest $request, $id)
    {
        $this->dispatch(new UpdateBuilderPayoutJob($request->all(), $id));
        return redirect()->route('admin.v1.payouts.index')->withSuccess('Builder payout updated successfully');
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