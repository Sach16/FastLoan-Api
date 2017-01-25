<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;

use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests;
use Whatsloan\Repositories\Designations\Designation;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Http\Requests\Admin\V1\StoreReferralRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateReferralRequest;
use Whatsloan\Jobs\StoreReferralJob;
use Whatsloan\Jobs\UpdateReferralJob;

class ReferralsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.dashboard.index')->withError("Access Restricted");
        }
        $referrals = User::with(['designation','referrals'])
                        ->withTrashed()
                        ->where('role', 'REFERRAL')
                        ->orderBy('deleted_at','asc')
                        ->paginate();

        return view('admin.v1.referrals.index')->withReferrals($referrals);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.dashboard.index')->withError("Access Restricted");
        }
        $designations = Designation::all();
        return view('admin.v1.referrals.create')->withDesignations($designations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreStateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReferralRequest $request)
    {
        $request->offsetSet('uuid', uuid());
        $request->offsetSet('role', 'REFERRAL');
        $this->dispatch(new StoreReferralJob($request->all()));
        return redirect()->route('admin.v1.referrals.index')->withSuccess('Referral added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.dashboard.index')->withError("Access Restricted");
        }
        $referral = User::with(['designation', 'attachments' => function($query) {
            $query->whereType('PROFILE_PICTURE')->orderBy('updated_at', 'desc');
        }])->withTrashed()->find($id);
        return view('admin.v1.referrals.show')->withReferral($referral);
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
            return redirect()->route('admin.v1.dashboard.index')->withError("Access Restricted");
        }
        $referral = User::withTrashed()->find($id);
        $designations = Designation::all();
        return view('admin.v1.referrals.edit')->withReferral($referral)->withDesignations($designations);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateStateRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReferralRequest $request, $id)
    {
        $referral = User::withTrashed()->find($id);
        if ($request->exists('profile_picture')) {
            $upload = upload($referral->getUserProfilePicturePath(), $request->file('profile_picture'), true);
            $request->offsetSet('attachment', $upload);
        }
        $this->dispatch(new UpdateReferralJob($request->except('profile_picture'), $id));
        return redirect()->route('admin.v1.referrals.show', $id)->withSuccess('Referral updated successfully');
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
            return redirect()->route('admin.v1.dashboard.index')->withError("Access Restricted");
        }
        $referral = User::withTrashed()->find($id);
        $referral->trashed() ? $referral->restore() : $referral->delete();
        return redirect()->route('admin.v1.referrals.index')->withSuccess('Referral updated successfully');
    }
}
