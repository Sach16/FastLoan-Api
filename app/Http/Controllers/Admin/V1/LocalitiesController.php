<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreLocalityRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateLocalityRequest;
use Whatsloan\Jobs\StoreLocalityJob;
use Whatsloan\Jobs\UpdateLocalityJob;
use Whatsloan\Repositories\Localities\Locality;

class LocalitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $localities = Locality::with('state')->withTrashed()->orderBy('deleted_at', 'asc')->paginate();
        return view('admin.v1.localities.index')->withLocalities($localities);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.v1.localities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreLocalityRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLocalityRequest $request)
    {
        $request->offsetSet('uuid', uuid());
        $this->dispatch(new StoreLocalityJob($request->all()));
        return redirect()->route('admin.v1.localities.index')->withSuccess('Locality added successfully');
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
        if (\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.banks.index')->withError("Access Restricted");
        }
        $locality = Locality::with('state')->withTrashed()->find($id);
        return view('admin.v1.localities.edit')->withLocality($locality);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateLocalityRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLocalityRequest $request, $id)
    {
        $this->dispatch(new UpdateLocalityJob($request->all(), $id));

        return redirect()->back()->withSuccess('Locality updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.localities.index')->withError("Access Restricted");
        }
        $locality = Locality::withTrashed()->find($id);
        $locality->trashed() ? $locality->restore() : $locality->delete();
        return redirect()->back()->withSuccess('Locality updated successfully');
    }
}
