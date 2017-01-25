<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Support\Facades\Auth;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\LoginRequest;
use Whatsloan\Repositories\Logins\Login;

class AuthController extends Controller
{
    /**
     * Render the login view
     *
     * @return mixed
     */
    public function login()
    {
        return view('admin.v1.auth.login');
    }

    /**
     * @param LoginRequest $request
     */
    public function postLogin(LoginRequest $request)
    {
        if (Auth::attempt($request->only(['phone', 'password'])))
        {
            if ($request->user()->can('viewAdminPanel')) {
                Login::create([
                    'uuid'    => uuid(),
                    'user_id' => Auth::user()->id,
                    'as'      => Auth::user()->role,
                    'action'  => 'LOGIN',
                ]);
                  return redirect()->route('admin.v1.dashboard.index');
            }
            Auth::logout();
            return redirect()->route('admin.v1.auth.login.get')
                            ->withError('User is not allowed');
        } else {
            return redirect()->back()->withInput()->withError('User credentials are not correct');
        }
    }

    /**
     * @return mixed
     */
    public function logout()
    {
        $user_id = Auth::user()->id;
        $role = Auth::user()->role;
        Auth::logout();
        Login::create([
            'uuid'    => uuid(),
            'user_id' => $user_id,
            'as'      => $role,
            'action'  => 'LOGOUT',
        ]);
        return redirect()->route('admin.v1.auth.login.get');
    }
}
