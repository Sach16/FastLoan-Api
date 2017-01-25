<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;

use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Requests\Admin\V1\UpdateUserPhoneRequest;
use Whatsloan\Jobs\UpdateUserPhoneJob;
use Whatsloan\Repositories\Users\User;

class UsersPhoneController extends Controller
{
    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        if(\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.users.index')->withError("Access Restricted");
        }
        $user = User::find($id);
        $phones = User::whereIn('role', ['DSA_OWNER', 'DSA_MEMBER', 'DSA_NONMEMBER'])
                            ->onlyTrashed()
                            ->get();
        return view('admin.v1.users.phone.show')->withPhones($phones)->withUser($user);
    }

    /**
     * Update a users phone number
     * @param UpdateUserPhoneRequest $request
     */
    public function update(UpdateUserPhoneRequest $request)
    {
        $this->dispatch(new UpdateUserPhoneJob($request->all()));
        return redirect()->route('admin.v1.users.index')->withSuccess('Phone number transferred successfully');
    }
}
