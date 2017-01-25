<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreTeamRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateDsaRemoveRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateTeamRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateTeamSettingsRequest;
use Whatsloan\Jobs\StoreTeamJob;
use Whatsloan\Jobs\UpdateRemoveDsaMemberJob;
use Whatsloan\Jobs\UpdateTeamJob;
use Whatsloan\Jobs\UpdateTeamSettingsJob;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Teams\Team;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Jobs\UpdateTeamMultiOwnerJob;

class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->role == 'SUPER_ADMIN') {
            $teams = Team::with('members')->withTrashed()->orderBy('deleted_at', 'asc')->paginate();
        } else {
            $teams = Team::whereHas('members', function ($q) {
                $q->where('user_id', \Auth::user()->id);
            })
                ->withTrashed()
                ->orderBy('deleted_at', 'asc')->paginate();
        }
        return view('admin.v1.teams.index')->withTeams($teams);
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
        $banks = Bank::get();
        return view('admin.v1.teams.create')->withBanks($banks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreTeamRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTeamRequest $request)
    {
        $this->dispatch(new StoreTeamJob($request->all()));

        return redirect()->route('admin.v1.teams.index')->withSuccess('Team added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($this->isAuthorized($id)){
            return redirect()->route('admin.v1.teams.index')->withSuccess('Access Restricted');
        }
        $team = Team::with('members')->withTrashed()->find($id);
        return view('admin.v1.teams.show')->withTeam($team);
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
            return redirect()->route('admin.v1.teams.index')->withError("Access Restricted");
        }

        $team = Team::with('members')->find($id);
        if(!$team->members->isEmpty()){
           $user = User::with('banks')->find($team->members->first()->id);
           $bankName = $user->banks->first()->name;

            return view('admin.v1.teams.edit')->withTeam($team)->with('bankName', $bankName);
        }
        $banks = Bank::get();
        return view('admin.v1.teams.editTrashed')->withTeam($team)->with('banks', $banks);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateTeamRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTeamRequest $request, $id)
    {
        $this->dispatch(new UpdateTeamJob($request->all(), $id));

        return redirect()->route('admin.v1.teams.show', $id)->withSuccess('Team updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $team = Team::withTrashed()->find($id);
        $team->trashed() ? $team->restore() : $team->delete();

        return redirect()->route('admin.v1.teams.index')->withSuccess('Team updated successfully');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editSettings($teamId, $memberId)
    {
        $team = Team::with(['members' => function ($query) use ($memberId) {
            $query->where('user_id', $memberId);
        }])->find($teamId);
        $user       = User::with('reportsTo')->find($memberId);
        $reports_to = Team::with(['members' => function ($query) use ($memberId) {
            $query->where('user_id', '!=', $memberId);
        }])->find($teamId);
        return view('admin.v1.teams.settings')->withTeam($team)->withReportsTo($reports_to)->withUser($user);
    }

    /**
     * pdate the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSettings(UpdateTeamSettingsRequest $request, $teamId, $memberId)
    {
        $this->dispatch(new UpdateTeamSettingsJob($request->only('target', 'achieved', 'incentive_plan', 'incentive_earned', 'reports_to'), $teamId, $memberId));
        return redirect()->back()->withSuccess('Settings updated successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeDsaMember($teamId, $memberId)
    {
        $team = Team::with(['members' => function ($query) use ($memberId) {
            $query->where('user_id', '!=', $memberId);
        }])->find($teamId);
        $user = User::find($memberId);
        return view('admin.v1.teams.remove')->withTeam($team)->withUser($user);
    }

    /**
     * pdate the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateRemoveDsaMember(UpdateDsaRemoveRequest $request, $teamId, $memberId)
    {
        $this->dispatch(new UpdateRemoveDsaMemberJob($request->only('assigned_to'), $teamId, $memberId));
        return redirect()->route('admin.v1.teams.show', $teamId)->withSuccess('DSA removed successfully.');
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
            $teams = Team::whereHas('members', function ($q) {
                $q->where('user_id', \Auth::user()->id);
            })
               ->whereId($id)->first();
                if(!$teams)
                {
                    return true;
                }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeDsaOwner($teamId, $memberId)
    {
        $teams = Team::with(['members' => function ($query) use ($memberId) {
            $query->where('user_id', '!=', $memberId);
        }])->get();
        $team = Team::find($teamId);
        $user = User::find($memberId);
        return view('admin.v1.teams.removeOwner')->withTeam($team)->withUser($user)->withTeams($teams);
    }

    /**
     * pdate the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateRemoveDsaOwner(UpdateDsaRemoveRequest $request, $teamId, $memberId)
    {
        $this->dispatch(new UpdateRemoveDsaMemberJob($request->only('assigned_to'), $teamId, $memberId));
        return redirect()->route('admin.v1.teams.show', $teamId)->withSuccess('DSA removed successfully.');
    }

    /**
     * add multiple owner for the team the specified resource.
     *
     * @param  int  $teamId
     * @param  int  $memberId
     * @return \Illuminate\Http\Response
     */
    public function updateMultiOwner($teamId, $memberId)
    {
        $this->dispatch(new UpdateTeamMultiOwnerJob($teamId, $memberId));
        return redirect()->route('admin.v1.teams.show', $teamId)->withSuccess('Team updated successfully.');
    }
}
