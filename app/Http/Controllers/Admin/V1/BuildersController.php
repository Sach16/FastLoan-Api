<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\UpdateBuilderRequest;
use Whatsloan\Jobs\UpdateBuilderJob;
use Whatsloan\Repositories\Builders\Builder;

class BuildersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $builders = Builder::with(['addresses', 'addresses.city'])->orderBy('deleted_at','asc')->withTrashed()->paginate();
        return view('admin.v1.builders.index')->withBuilders($builders);
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
        $builder = Builder::with([
            'projects', 'projects.addresses', 'projects.addresses.city', 'addresses', 'addresses.city'
        ])->withTrashed()->find($id);
        $canDelete = $builder->projects()->count() > 0 ? false : true;
        return view('admin.v1.builders.show')->withBuilder($builder)->withCanDelete($canDelete);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $builder = Builder::withTrashed()->find($id);
        return view('admin.v1.builders.edit')->withBuilder($builder);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateBuilderRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBuilderRequest $request, $id)
    {
        $request->offsetSet('zip',$request->zipcode);
        $this->dispatch(new UpdateBuilderJob($request->all(), $id));
        return redirect()->route('admin.v1.builders.show', $id)->withSuccess('Builder updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $builder = Builder::withTrashed()->findOrFail($id);
        $builder->trashed() ? $builder->restore() : $builder->delete();
        return redirect()->route('admin.v1.builders.index')->withSuccess('Successfully updated builder');
    }
}
