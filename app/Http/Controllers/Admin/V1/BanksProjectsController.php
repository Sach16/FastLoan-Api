<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Jobs\UpdateProjectApprovalStatusJob;

class BanksProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $bank = Bank::with(['projects' => function($query) {
            $query->orderBy('bank_project.updated_at', 'desc');
        }, 'projects.builder'])->orderBy('updated_at','DESC')->find($id);
        return view('admin.v1.banks.projects.index')->withBank($bank);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @param  \Illuminate\Http\Request $request
     * @param $bankId
     * @param $projectId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $bankId, $projectId)
    {
        $this->dispatch(new UpdateProjectApprovalStatusJob($request->all(), $bankId, $projectId));
        return redirect()->back()->withSuccess('Updated Approval status');
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
