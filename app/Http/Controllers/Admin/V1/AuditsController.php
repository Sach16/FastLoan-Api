<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use ConceptByte\TimeTraveller\Models\Revision;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Repositories\Logins\Login;
use Whatsloan\Repositories\Users\User;

class AuditsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $revisions = Revision::orderBy('updated_at', 'desc')->paginate();
        return view('admin.v1.audits.index')->withRevisions($revisions);
    }

    /**
     * Display the admin logins
     *
     * @return mixed
     */
    public function logins()
    {
        $logins = Login::with(['user' => function ($query) {
            $query->withTrashed();
        }]);
        if (\Auth::user()->role == 'DSA_OWNER') {
            $userTeam = User::with(['teams', 'teams.members'])->find(\Auth::user()->id)->teams()->first();
            $logins->whereIn('user_id', $userTeam->members->lists('id')->all());
        }
        return view('admin.v1.audits.logins')->withLogins($logins->paginate());
    }
}
