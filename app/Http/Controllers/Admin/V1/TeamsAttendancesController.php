<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests;
use Whatsloan\Repositories\Teams\Team;

class TeamsAttendancesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $teamId
     * @return \Illuminate\Http\Response
     */
    public function index($teamId)
    {
        $date = Carbon::createFromFormat('m-Y', request()->has('month') ? request('month') : date('m-Y'));
        $start = $date->startOfMonth();

        $date = Carbon::createFromFormat('m-Y', request()->has('month') ? request('month') : date('m-Y'));
        $end = $date->endOfMonth();
        
        $team = Team::onlyMembers()->with(['members.attendances' => function($query) use($start, $end) {
            $query->whereBetween('start_time', [$start, $end]);
        }])->withTrashed()->find($teamId);

        return view('admin.v1.teams.attendances.index')->withTeam($team)->withDate($date);
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
