<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Whatsloan\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Repositories\Leads\Contract as ILeads;
use Whatsloan\Repositories\Leads\Lead;
use Whatsloan\Repositories\Loans\Loan;
use Whatsloan\Http\Requests\Admin\V1\UpdateLeadRequest;
use Whatsloan\Http\Requests\Admin\V1\StoreLeadRequest;
use Whatsloan\Http\Requests\Admin\V1\IndexLeadRequest;
use Whatsloan\Jobs\UpdateLeadJob;
use Whatsloan\Jobs\StoreLeadAsAdminJob;
use Whatsloan\Jobs\StoreUserAttachmentsJob;
use Whatsloan\Jobs\SetCredentialsJob;
use Whatsloan\Repositories\Users\User;
use Carbon\Carbon;

class LeadsController extends Controller
{

    /**
     * @var leads
     */
    public $leads;

    /**
     * Controller constructor
     * @param ILeads $leads
     */
    public function __construct(ILeads $leads)
    {
        $this->leads = $leads;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexLeadRequest $request)
    {
        $leads = $this->leads->listAsAdmin();
        return view('admin.v1.leads.index')->withLeads($leads);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.v1.leads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLeadRequest $request)
    {
        $request->offsetSet('dob', Carbon::parse($request->settings['dob'])->format('Y-m-d'));
        $request->offsetSet('resident_status', @$request->settings['resident_status']);
        $request->offsetSet('profession', @$request->settings['profession']);
        $request->offsetSet('education', @$request->settings['education']);
        $request->offsetSet('marital_status', @$request->settings['marital_status']);
        $request->offsetSet('company', @$request->settings['company']);
        $request->offsetSet('net_income', sprintf('%0.2f',@$request->settings['net_income']));
        $request->offsetSet('pan', @$request->settings['pan']);
        $request->offsetSet('salary_bank', @$request->settings['salary_bank']);
        $request->offsetSet('skype', @$request->settings['skype']);
        $request->offsetSet('facetime', @$request->settings['facetime']);
        $request->offsetSet('contact_time', @$request->settings['contact_time']);
        $request->offsetSet('cibil_score', @$request->settings['cibil_score']);
        $request->offsetSet('cibil_status', @$request->settings['cibil_status']);

        $user = $this->dispatch(new StoreLeadAsAdminJob($request->except(['profile_picture','address_proof','id_proof'])));
        $this->dispatch(new  SetCredentialsJob($user->id));
        $request = $this->CheckAttachments($request,$user);
        $this->dispatch(new StoreUserAttachmentsJob($request->except(['profile_picture','address_proof','id_proof']), $user));
        return redirect()->route('admin.v1.leads.index')->withSuccess('Lead added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $assigned_to = false;
        $lead = Lead::with([
                    'loan',
                    'loan.type',
                    'loan.project',
                    'loan.project.builder',
                    'loan.agent',
                    'loan.agent.banks',
                    'userTrashed',
                    'referrals']);

            if (\Auth::user()->role != 'SUPER_ADMIN') {
                $lead = $lead->with(['userTrashed.loansTrashed' => function($q){
                        $q->whereIn('agent_id', teamMemberIdsAsAdmin(true));
                    }]);
            }

            $lead = $lead->withTrashed()
                ->find($id);
        if(!empty($lead->assigned_to)){
            $assigned_to = User::find($lead->assigned_to);
        }
        return view('admin.v1.leads.show')->withLead($lead)->withAssigned_user($assigned_to);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lead = Lead::with([
                    'loan',
                    'loan.type',
                    'loan.project',
                    'loan.project.builder',
                    'loan.agent',
                    'loan.agent.banks',
                    'user',
                    'referrals'])
                ->withTrashed()
                ->find($id);

        return view('admin.v1.leads.edit')->withLead($lead);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLeadRequest $request, $id)
    {
        $lead = Lead::with(['user'])->withTrashed()->find($id);
        $request = $this->CheckAttachments($request,$lead->user);
        $this->dispatch(new UpdateLeadJob($request->except(['profile_picture','address_proof','id_proof']), $id));
        return redirect()->route('admin.v1.leads.show', $id)->withSuccess("Lead updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {       
        $lead = Lead::with(['userTrashed'])->withTrashed()->findOrFail($id);
        if($lead->userTrashed->loans->count() > 0)
        {
            return redirect()->route('admin.v1.leads.index')->withSuccess('Leads conno\'t disabled with active loans.');    
        }
        $lead->userTrashed->trashed()? $lead->userTrashed->restore() : $lead->userTrashed->delete();
        return redirect()->route('admin.v1.leads.index')->withSuccess('Lead updated successfully');
    }

    private function CheckAttachments($request,$user){
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
        return $request;
    }

}
