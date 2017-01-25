<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreDesignationRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateDesignationRequest;
use Whatsloan\Jobs\StoreDesignationJob;
use Whatsloan\Jobs\UpdateDesignationJob;
use Whatsloan\Repositories\Designations\Designation;

class DesignationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->role == 'DSA_OWNER') {
            $designations = Designation::with('user')->withTrashed()->where('id', \Auth::user()->designation_id)->paginate();
        } else {
            $designations = Designation::with('user')->withTrashed()->orderBy('deleted_at', 'asc')->paginate();
        }
        return view('admin.v1.designations.index')->withDesignations($designations);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.v1.designations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreDesignationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDesignationRequest $request)
    {

        $request->offsetSet('uuid', uuid());
        $this->dispatch(new StoreDesignationJob($request->all()));
        return redirect()->route('admin.v1.designations.index')->withSuccess('Designation added successfully');
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
        if($this->isAuthorized($id)){
            return redirect()->route('admin.v1.designations.index')->withSuccess('Access Restricted');
        }
        $designation = Designation::withTrashed()->find($id);
        return view('admin.v1.designations.edit')->withDesignation($designation);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateDesignationRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDesignationRequest $request, $id)
    {
        $this->dispatch(new UpdateDesignationJob($request->all(), $id));

        return redirect()->route('admin.v1.designations.index')->withSuccess('Designation updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $designation = Designation::withTrashed()->find($id);
        $designation->trashed() ? $designation->restore() : $designation->delete();

        return redirect()->back()->withSuccess('Designation updated successfully');
    }

    /**
     * Authorized the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    private function isAuthorized($id)
    {
        if(\Auth::user()->role <> 'SUPER_ADMIN')
        {
            $designations = Designation::with('user')->withTrashed()->where('id', \Auth::user()->designation_id)->first();

                if($designations->id <> $id)
                {
                    return true;
                }
        }
    }
}
