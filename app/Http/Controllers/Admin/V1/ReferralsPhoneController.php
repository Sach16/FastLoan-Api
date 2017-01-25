<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\UpdateReferralPhoneRequest;
use Whatsloan\Jobs\UpdateReferralPhoneJob;
use Whatsloan\Repositories\Users\User;

class ReferralsPhoneController extends Controller
{
    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        if (\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.referrals.index')->withError("Access Restricted");
        }
        $referral = User::find($id);
        $phones   = User::whereIn('role', ['DSA_OWNER', 'DSA_MEMBER', 'DSA_NONMEMBER', 'REFERRAL'])
            ->orderBy('updated_at', 'desc')
            ->onlyTrashed()
            ->get();

        return view('admin.v1.referrals.phone.show')->withPhones($phones)->withReferral($referral);
    }

    /**
     * Update a users phone number
     * @param UpdateReferralPhoneRequest $request
     */
    public function update(UpdateReferralPhoneRequest $request)
    {
        $this->dispatch(new UpdateReferralPhoneJob($request->all()));
        return redirect()->route('admin.v1.referrals.index')->withSuccess('Phone number transferred successfully');
    }
}
