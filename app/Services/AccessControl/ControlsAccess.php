<?php

namespace Whatsloan\Services\AccessControl;

use Whatsloan\Repositories\Users\User;

trait ControlsAccess
{

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // if (\Auth::guard('api')->check()) {
        //     $user = \Auth::guard('api')->user();
        //     $user = User::with(['team', 'team.members'])->find($user->id);
        //     $members = collect();

        //     if (!is_null($user->team->first())) {
        //         $members = $user->team->first()->members;
        //     }
        //     static::addGlobalScope(new AccessControlScope($user, $members));
        // }
    }
}
