<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Whatsloan\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Repositories\Sources\Contract as ISources;
use Whatsloan\Jobs\StoreSourceJob;
use Whatsloan\Jobs\UpdateSourceJob;
use Whatsloan\Http\Requests\Admin\V1\StoreSourceRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateSourceRequest;
use Whatsloan\Repositories\Leads\Lead;

class SourcesController extends Controller
{

    /**
     * ISources
     * @var $sources 
     */
    public $sources;

    /**
     * Sources controller contructor
     * @param ISources $sources
     */
    public function __construct(ISources $sources)
    {
        $this->sources = $sources;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sources = $this->sources->paginate();
        return view('admin.v1.sources.index')->withSources($sources);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.v1.sources.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSourceRequest $request)
    {
        $this->dispatch(new StoreSourceJob($request->all()));
        return redirect()->route('admin.v1.sources.index')->withSuccess('Source added successfully');
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
        $source = $this->sources->find($id);
        return view('admin.v1.sources.edit')->withSource($source);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSourceRequest $request, $id)
    {
        $this->dispatch(new UpdateSourceJob($request->all(), $id));
        return redirect()->route('admin.v1.sources.index')->withSuccess('Source updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (Lead::where('source_id', $id)->get()->count()) {
            return redirect()->route('admin.v1.sources.index')->withError('Can\'t be deleted');
        }

        $source = $this->sources->find($id);
        $source->trashed() ? $source->restore() : $source->delete();

        return redirect()->route('admin.v1.sources.index')->withSuccess('Source updated successfully');
    }

}
