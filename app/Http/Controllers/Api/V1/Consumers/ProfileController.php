<?php

namespace Whatsloan\Http\Controllers\Api\V1\Consumers;

use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Transformers\V1\Consumers\ForbiddenActionTransformer;
use Whatsloan\Http\Transformers\V1\Consumers\UserTransformer;
use Whatsloan\Http\Transformers\V1\Consumers\AttachmentTransformer;
use Whatsloan\Repositories\Users\Contract as IUsers;

class ProfileController extends Controller
{
    /**
     * @var
     */
    protected $users;

    /**
     * ProfileController constructor.
     * @param $users
     */
    public function __construct(IUsers $users)
    {
        $this->users = $users;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = $this->users->show(\Auth::guard('api')->user()->uuid);
        return $this->transformItem($user, UserTransformer::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        if ($uuid != \Auth::guard('api')->user()->uuid) {
            return $this->transformItem('', ForbiddenActionTransformer::class, 403);
        }

        $user = $this->users->update($request, $uuid);
        return $this->transformItem($user, UserTransformer::class);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
