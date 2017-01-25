<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreStateRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateStateRequest;
use Whatsloan\Jobs\StoreStateJob;
use Whatsloan\Jobs\UpdateStateJob;
use Whatsloan\Repositories\States\State;

class StatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $states = State::with('localities')->withTrashed()->orderBy('deleted_at', 'asc')->paginate();
        return view('admin.v1.states.index')->withStates($states);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.banks.index')->withError("Access Restricted");
        }
        return view('admin.v1.states.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreStateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStateRequest $request)
    {
        $request->offsetSet('uuid', uuid());
        $this->dispatch(new StoreStateJob($request->all()));
        return redirect()->route('admin.v1.states.index')->withSuccess('State added successfully');
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
        $state = State::withTrashed()->find($id);
        return view('admin.v1.states.edit')->withState($state);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateStateRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStateRequest $request, $id)
    {
        $this->dispatch(new UpdateStateJob($request->all(), $id));

        return redirect()->back()->withSuccess('State updated successfully');
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
            return redirect()->route('admin.v1.banks.index')->withError("Access Restricted");
        }
        $state = State::withTrashed()->find($id);
        $state->trashed() ? $state->restore() : $state->delete();
        return redirect()->back()->withSuccess('State updated successfully');
    }
}
