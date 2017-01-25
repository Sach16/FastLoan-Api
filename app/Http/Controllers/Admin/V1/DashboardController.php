<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Repositories\Logins\Login;

class DashboardController extends Controller
{
    /**
     * Display the resource index
     *
     * @return mixed
     */
    public function index()
    {
        $login = Login::with(['user' => function ($query) {
            $query->whereId(\Auth::user()->id);
        }])
            ->orderBy('created_at', 'DESC')
            ->first();
        return view('admin.v1.dashboard.index')->withLogin($login);
    }
}
