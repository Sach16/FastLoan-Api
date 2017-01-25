<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreCampaignRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateCampaignRequest;
use Whatsloan\Jobs\StoreCampaignJob;
use Whatsloan\Jobs\UpdateCampaignJob;
use Whatsloan\Repositories\Campaigns\Campaign;
use Whatsloan\Repositories\Users\User;

class CampaignsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->role == 'SUPER_ADMIN') {
            $campaigns = Campaign::withTrashed()->orderBy('deleted_at', 'asc')->paginate();
        } else {
            $user = User::with(['teams'])
                ->whereUuid(\Auth::user()->uuid)
                ->firstOrFail();
            $campaigns = Campaign::withTrashed()->where('team_id', $user->teams->first()->id)->orderBy('deleted_at', 'asc')->paginate();
        }
        return view('admin.v1.campaigns.index')->withCampaigns($campaigns);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.v1.campaigns.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreCampaignRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCampaignRequest $request)
    {
        $this->dispatch(new StoreCampaignJob($request->all()));
        return redirect()->route('admin.v1.campaigns.index')->withSuccess('Campaign added successfully');
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
            return redirect()->route('admin.v1.campaigns.index')->withSuccess('Access Restricted');
        }
        $campaign = Campaign::with(['members', 'addresses', 'addresses.city'])->withTrashed()->find($id);
        return view('admin.v1.campaigns.show')->withCampaign($campaign);
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
            return redirect()->route('admin.v1.campaigns.index')->withSuccess('Access Restricted');
        }
        $campaign = Campaign::with(['members', 'team', 'team.members', 'addresses', 'addresses.city'])->find($id);
        return view('admin.v1.campaigns.edit')
            ->withCampaign($campaign);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateCampaignRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCampaignRequest $request, $id)
    {
        $this->dispatch(new UpdateCampaignJob($id, $request->all()));
        return redirect()->route('admin.v1.campaigns.show', $id)->withSuccess('Campaign updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campaign = Campaign::withTrashed()->find($id);
        $campaign->trashed() ? $campaign->restore() : $campaign->delete();
        return redirect()->route('admin.v1.campaigns.index')->withSuccess('Campaign updated successfully');
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
            $campaigns = Campaign::withTrashed()->where('team_id', $user->teams->first()->id)->orderBy('deleted_at', 'asc')->whereId($id)->first();
                if(!$campaigns)
                {
                    return true;
                }
        }
    }
}
