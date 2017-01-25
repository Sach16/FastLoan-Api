<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Whatsloan\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Whatsloan\Http\Requests;

use Whatsloan\Repositories\Banks\BankProject;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Http\Requests\Admin\V1\UpdateBankProjectRequest;

class ProjectBanksController extends Controller
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
        return view('admin.v1.project-banks.create')->withProject(request()->all()['project']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UpdateProjectApprovalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UpdateBankProjectRequest $request)
    {
        $bankProject = BankProject::where('bank_id',$request->bank_id)
                                    ->where('project_id',$request->project_id)
                                    ->where('agent_id',$request->agent_id)->first();

        if($bankProject) {
            return redirect()->route('admin.v1.projectbanks.create',['project' => $request->project_id])->withError('Agent is already associated with the project');
        } else {
            $request->offsetSet('status', 'PENDING');
            BankProject::create($request->all());
            return redirect()->route('admin.v1.projects.show',['id' => $request->project_id])->withSuccess('Approval request submited successfully');
        }

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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
        //
    }
}
