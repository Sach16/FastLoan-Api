<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreUserRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateUserRequest;
use Whatsloan\Jobs\SetCredentialsJob;
use Whatsloan\Jobs\StoreUserAttachmentsJob;
use Whatsloan\Jobs\StoreUserJob;
use Whatsloan\Jobs\UpdateUserJob;
use Whatsloan\Repositories\Designations\Designation;
use Whatsloan\Repositories\Users\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->role == 'SUPER_ADMIN') {
            $users = User::with(['designation'])
                ->withTrashed()
                ->orderBy('deleted_at', 'asc')
                ->whereIn('role', ['DSA_OWNER', 'DSA_MEMBER', 'DSA_NONMEMBER'])
                ->paginate();
        } else {
            $user = User::with(['teams'])
                ->whereUuid(\Auth::user()->uuid)
                ->firstOrFail();
            $users = User::with(['designation'])
                ->withTrashed()
                ->orderBy('deleted_at', 'asc')
                ->whereIn('role', ['DSA_OWNER', 'DSA_MEMBER'])
                ->whereIn('id', $user->teams->first()->members->lists('id')->all())
                ->paginate();
        }
        return view('admin.v1.users.index')->withUsers($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $designations = Designation::all();
        return view('admin.v1.users.create')->withDesignations($designations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $request->offsetSet('uuid', uuid());
        $request->offsetSet('role', 'DSA_NONMEMBER');
        $user = $this->dispatch(new StoreUserJob($request->except(['profile_picture', 'address_proof', 'id_proof', 'experience_with_banks', 'products_handled'])));
        $this->dispatch(new SetCredentialsJob($user->id));
        $request = $this->CheckAttachments($request, $user);
        $this->dispatch(new StoreUserAttachmentsJob($request->except(['profile_picture', 'address_proof', 'id_proof', 'experience_with_banks', 'products_handled']), $user));
        return redirect()->route('admin.v1.users.index')->withSuccess('User added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($this->isAuthorized($id)){
            return redirect()->route('admin.v1.users.index')->withSuccess('Access Restricted');
        }
        $user = User::with(['assignee','projects','teams', 'tasks', 'leads', 'loans', 'banks','designation', 'attachments' => function ($query) {
                        $query->whereIn('type', ['PROFILE_PICTURE', 'ID_PROOF', 'ADDRESS_PROOF', 'EXPERIENCE_DOCUMENT', 'PRODUCT_DOCUMENT'])
                        ->orderBy('updated_at', 'desc');
                    }])
                    ->withTrashed()
                    ->find($id);
        return view('admin.v1.users.show')->withUser($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if($this->isAuthorized($id)){
            return redirect()->route('admin.v1.users.index')->withSuccess('Access Restricted');
        }
        $user         = User::withTrashed()->find($id);
        $designations = Designation::all();
        return view('admin.v1.users.edit')->withUser($user)->withDesignations($designations);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateUserRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user    = User::withTrashed()->find($id);
        $request = $this->CheckAttachments($request, $user);
        $this->dispatch(new UpdateUserJob($request->except(['profile_picture', 'address_proof', 'id_proof', 'experience_with_banks', 'products_handled']), $id));
        return redirect()->route('admin.v1.users.show', $id)->withSuccess('User updated successfully');
    }

    private function CheckAttachments($request, $user)
    {
        if ($request->exists('profile_picture')) {
            $upload = upload($user->getUserProfilePicturePath(), $request->file('profile_picture'));
            $request->offsetSet('attachment', $upload);
        }
        if ($request->exists('address_proof')) {
            $upload = upload($user->getUserProfilePicturePath(), $request->file('address_proof'));
            $request->offsetSet('address_attachment', $upload);
        }
        if ($request->exists('id_proof')) {
            $upload = upload($user->getUserProfilePicturePath(), $request->file('id_proof'));
            $request->offsetSet('id_attachment', $upload);
        }
        if ($request->exists('experience_with_banks')) {
            $upload = upload($user->getUserProfilePicturePath(), $request->file('experience_with_banks'));
            $request->offsetSet('experience_with_banks_attachment', $upload);
        }
        if ($request->exists('products_handled')) {
            $upload = upload($user->getUserProfilePicturePath(), $request->file('products_handled'));
            $request->offsetSet('products_handled_attachment', $upload);
        }
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::withTrashed()->find($id);
        $user->trashed() ? $user->restore() : $user->delete();

        return redirect()->route('admin.v1.users.index')->withSuccess('User updated successfully');
    }

    /**
     * Authorized the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    private function isAuthorized($id)
    {
        if(\Auth::user()->role <> 'SUPER_ADMIN')
        {
            $user = User::with(['teams'])
                    ->whereUuid(\Auth::user()->uuid)
                    ->firstOrFail();
                if(!in_array($id,$user->teams->first()->members->lists('id')->all()))
                {
                    return true;
                }
        }
    }
}
