<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;

use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Requests\Admin\V1\StoreCalendarRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateCalendarRequest;
use Whatsloan\Jobs\StoreCalendarJob;
use Whatsloan\Jobs\UpdateCalendarJob;
use Whatsloan\Repositories\Calendars\Calendar;
use Whatsloan\Repositories\Teams\Team;

class TeamsCalendersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $teamId
     * @return \Illuminate\Http\Response
     */
    public function index($teamId)
    {
        $team = Team::withTrashed()->find($teamId);
        $calendars = Calendar::whereTeamId($teamId)
                        ->orderBy('date', 'desc')
                        ->paginate();

        return view('admin.v1.teams.calendars.index')
                    ->withTeam($team)
                    ->withCalendars($calendars);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($teamId)
    {
        $team = Team::find($teamId);
        return view('admin.v1.teams.calendars.create')->withTeam($team);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreCalendarRequest $request
     * @param $teamId
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCalendarRequest $request, $teamId)
    {
        $request->offsetSet('team_id', $teamId);
        $request->offsetSet('uuid', uuid());
        $this->dispatch(new StoreCalendarJob($request->all(), $teamId));

        return redirect()->route('admin.v1.teams.calendars.index', $teamId)->withSuccess('Holiday added successfully');
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
     * @param $teamId
     * @param $calendarId
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function edit($teamId, $calendarId)
    {
        $calendar = Calendar::find($calendarId);
        $team = Team::find($teamId);

        return view('admin.v1.teams.calendars.edit')
                    ->withCalendar($calendar)
                    ->withTeam($team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateCalendarRequest $request
     * @param $teamId
     * @param $calendarId
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(UpdateCalendarRequest $request, $teamId, $calendarId)
    {
        $this->dispatch(new UpdateCalendarJob($request->all(), $calendarId));
        return redirect()->back()->withSuccess('Calendar updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $teamId
     * @param $calendarId
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy($teamId, $calendarId)
    {
        $calendar = Calendar::find($calendarId);
        $calendar->delete();

        return redirect()->route('admin.v1.teams.calendars.index', $teamId)->withSuccess('Holiday deleted successfully');
    }
}
