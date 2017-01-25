<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Whatsloan\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Whatsloan\Http\Requests\Admin\V1\ChangePasswordRequest;
use Whatsloan\Repositories\Users\User;
use Illuminate\Support\Facades\Auth;
use Hash;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
    /**
     * Where override Auth view paths
     *
     * @var string
     */
    protected $linkRequestView = 'admin.v1.auth.password';
    protected $resetView = 'admin.v1.auth.reset';
    /**
     * Where to redirect the user after their password has been successfully reset
     *
     * @var string
     */
    protected $redirectPath = 'admin/v1/';

    /**
     * Render the Password Change view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showChangePasswordForm()
    {
        return view('admin.v1.auth.change');
    }

    /**
     * @param ChangePasswordRequest $request
     * @return mixed
     */
    public function changePassword(ChangePasswordRequest $request) {
        $user = User::findOrFail(Auth::user()->id);
        if (Hash::check($request['old_password'], $user->password)) {
            $user->password = bcrypt($request['password']);
            $user->save();
            return redirect()->back()->withSuccess('Password successfully changed.');
        }else{
            return redirect()->back()->withError('That is not your old password.');
        }
    }
}
