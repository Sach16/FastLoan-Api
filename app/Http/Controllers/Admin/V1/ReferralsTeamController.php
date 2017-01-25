<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;

use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Teams\Team;
use Whatsloan\Http\Requests\Admin\V1\UpdateReferralTeamRequest;
use Whatsloan\Repositories\Loans\Loan;
use Whatsloan\Repositories\Leads\Lead;
use Whatsloan\Repositories\Designations\Designation;

class ReferralsTeamController extends Controller
{
    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        if(\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.referrals.index')->withError("Access Restricted");
        }
        $referral = User::withTrashed()->find($id);
        $teams = Team::with('members')->OnlyOwners()->get();
        return view('admin.v1.referrals.team.show')->withTeams($teams)->withReferral($referral);
    }

    /**
     * Update a users phone number
     * @param UpdateUserPhoneRequest $request
     */
    public function update(UpdateReferralTeamRequest $request)
    {
        $loans = Loan::where('user_id', $request['referral_id'])
            ->get();
        foreach ($loans as $loan) {
            $loan->update(['agent_id' => $request['team']]);
            Lead::where('loan_id',$loan->id)->update(['user_id' => $request['referral_id'],'assigned_to' => $request['team'] ]);
        }
        $designation = Designation::where('name','Lead')->first();
        $user = User::where('id', $request['referral_id'])->update(['role' => 'LEAD','designation_id' => $designation->id]);
        // $team = Team::whereId($request->team)->first();
        // $team->referrals()->attach($request->referral_id);        
        return redirect()->route('admin.v1.referrals.index')->withSuccess('Referral assigned to team successfully');
    }
}
