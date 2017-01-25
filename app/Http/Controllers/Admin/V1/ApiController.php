<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Banks\BankProject;
use Whatsloan\Repositories\Builders\Builder;
use Whatsloan\Repositories\Cities\City;
use Whatsloan\Repositories\LoanStatuses\LoanStatus;
use Whatsloan\Repositories\Loans\Loan;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Repositories\Sources\Source;
use Whatsloan\Repositories\States\State;
use Whatsloan\Repositories\Teams\Team;
use Whatsloan\Repositories\Types\Type;
use Whatsloan\Repositories\Users\User;

class ApiController extends Controller
{

    /**
     * @return string
     */
    public function teamIndex()
    {
        if (\Auth::user()->role != 'SUPER_ADMIN') {
            $teams = Team::whereHas('members', function ($q) {
                $q->where('user_id', \Auth::user()->id);
            })->get();
        } else {
            $teams = Team::all();
        }
        $template = "<option value=''>Select Team</option>";
        foreach ($teams as $team) {
            $template .= "<option value=\"$team->id\" " . selectDropdown($team->id) . ">$team->name</option>";
        }
        return $template;
    }

    /**
     * Get the members of a team
     *
     * @param $id
     * @return string
     */
    public function teamShow($id)
    {
        $team     = Team::with('members')->find($id);
        $template = '';
        foreach ($team->members as $member) {
            $name = $member->present()->name;
            $template .= "<option value=\"$member->id\" " . selectMultiDropdown($member->id) . ">$name</option>";
        }

        return $template;
    }

    /**
     * Get all cities
     *
     * @return string
     */
    public function cities()
    {
        $cities = City::all();

        $template = "<option value=\"-1\" selected disabled>Select City</option>";
        foreach ($cities as $city) {
            $template .= "<option value=\"$city->id\" " . selectDropdown($city->id) . ">$city->name</option>";
        }
        return $template;
    }

    /**
     * Get team members of current user
     *
     * @return string
     */
    public function membersIndex()
    {
        $user = request()->user();

        if (\Auth::user()->role == 'SUPER_ADMIN') {
            $members = User::with('banks')->whereIn('role', ['DSA_MEMBER', 'DSA_OWNER'])->get();
        } else {
            $userTeam = User::with('teams', 'teams.members', 'banks')->whereId($user->id)->first();
            $members  = $userTeam->teams->first()->members;
        }

        $template = "<option value=\"-1\" selected disabled >Select member</option>";
        foreach ($members as $member) {
            if (!$member->banks->isEmpty()) {
                $name = $member->present()->name . ' - ' . $member->banks()->first()->name;
                $template .= "<option value=\"$member->id\" " . selectDropdown($member->id) . ">$name</option>";
            }
        }
        return $template;
    }

    /**
     * Get team dsa owner members of current user
     *
     * @return string
     */
    public function ownerMembersIndex()
    {
        $user = request()->user();

        if (\Auth::user()->role == 'SUPER_ADMIN') {
            $members = User::with('banks')->whereIn('role', ['DSA_OWNER'])->get();
        } else {
            $userTeam = User::with('teams', 'teams.members', 'banks')->whereId($user->id)->first();
            $members  = $userTeam->teams->first()->members;
        }

        $template = "<option value=\"-1\" selected disabled >Select member</option>";
        foreach ($members as $member) {
            if (!$member->banks->isEmpty()) {
                $name = $member->present()->name . ' - ' . $member->banks()->first()->name;
                $template .= "<option value=\"$member->id\" " . selectDropdown($member->id) . ">$name</option>";
            }
        }
        return $template;
    }

    /**
     * Get non members
     *
     * @return string
     */
    public function nonmembersIndex()
    {
        $request  = request()->all();
        $users    = User::whereRole('DSA_NONMEMBER');
        $template = "<option value='' disabled selected>Select member</option>";

        if (isset($request['owner'])) {
            $users->whereNotIn('id', [request()->all()['owner']]);
            $template = "";
        }
        $members = $users->get();

        foreach ($members as $member) {
            $template .= "<option value=\"$member->id\" " . selectDropdown($member->id) . ">{$member->present()->name}</option>";
        }
        if($members->count() <= 0){
            $template = "<option value='' disabled selected>No member available</option>";
        }

        //Adding team members
        if (isset($request['team']) && !empty($request['team']) && $request['team'] != 'undefined') {
            $team = Team::with('members')->find($request['team']);
            foreach ($team->members as $member) {
                if ($member->pivot->is_owner == 0) {
                    $template .= "<option value=\"$member->id\" selected disabled>{$member->present()->name}</option>";
                }
            }
        };
        return $template;
    }

    /**
     * @return string
     */
    public function buildersIndex()
    {
        $builders = Builder::all();
        $template = "<option value=\"-1\" selected disabled>Select builder</option>";
        foreach ($builders as $member) {
            $template .= "<option value=\"$member->id\" " . selectDropdown($member->id) . ">$member->name</option>";
        }
        return $template;
    }

    /**
     * Leas sources
     * @return type
     */
    public function sourcesIndex()
    {
        $sources = Source::all();

        $template = "<option value=\"-1\" selected disabled>Select source</option>";
        foreach ($sources as $source) {
            $template .= "<option value=\"$source->id\" data-key=\"$source->key\" " . selectDropdown($source->id) . ">$source->name</option>";
        }
        return $template;
    }

    /**
     * List of all loans
     */
    public function loansIndex()
    {
        $user = User::with(['teams', 'teams.members'])
            ->find(request()->user()->id);

        if ($user->role == 'DSA_OWNER') {
            // Get all team members
            $loans = Loan::with(['type', 'user'])
                ->whereIn('agent_id', $user->teams->first()->members->lists('id'))
                ->get();
        } else {
            $loans = Loan::with('type')->get();
        }

        $template = "<option value=\"-1\" disabled selected>Select loan</option>";
        foreach ($loans as $loan) {
            if ($loan->user) {
                $name = $loan->user->present()->name . " | " . $loan->user->phone . " | " . $loan->type->name . ' | Rs.' . $loan->amount;
                $template .= "<option value=\"$loan->id\">$name</option>";
            }
        }
        return $template;
    }

    /**
     * Loan type details
     * @return string
     */
    public function loanTypesIndex()
    {

        $types = Type::all();

        $template = "<option value=\"-1\" disabled selected>Select products</option>";
        foreach ($types as $type) {
            $template .= "<option value=\"$type->id\" data-key=\"$type->key\" " . selectDropdown($type->id) . ">$type->name</option>";
        }
        return $template;
    }

    /**
     *
     */
    public function projectsIndex()
    {
        $request = request()->all();
        if (isset($request['builder_id'])) {
            $projects = Project::where('builder_id', request()->all()['builder_id'])->get();
        } else {
            $projects = Project::all();
        }

        if ($projects->isEmpty()) {
            return "<option value=\"-1\">No projects available</option>";
        }

        $template = "<option value=\"-1\">Select projects</option>";
        foreach ($projects as $project) {
            $template .= "<option value=\"$project->id\" data-key=\"$project->key\" " . selectDropdown($project->id) . ">$project->name</option>";
        }

        return $template;
    }

    /**
     * Payout Project Refereral list
     */
    public function PayoutProjectsIndex()
    {
        $request = request()->all();

        $project_ids = Project::whereHas('payouts', function ($q) {})
            ->with('payouts', 'builder')
            ->lists('id');

        if (isset($request['builder_id'])) {
            $projects = Project::where('builder_id', request()->all()['builder_id'])->whereNotIn('id', $project_ids)->get();
        } else {
            $projects = collect([]);
        }

        $template = "<option value=\"-1\">Select projects</option>";
        foreach ($projects as $project) {
            $template .= "<option value=\"$project->id\" data-key=\"$project->key\" " . selectDropdown($project->id) . ">$project->name</option>";
        }

        return $template;
    }

    /**
     * Refereral list
     * @return string
     */
    public function referralsIndex()
    {
        if (authUser("web")->role == 'SUPER_ADMIN') {
            $referrals = User::whereRole('REFERRAL')->get();
        } else if (authUser("web")->role == 'DSA_OWNER') {
            $user = User::with(['teams', 'teams.referrals'])
                ->find(authUser("web")->id);
            $referrals = $user->teams->first()->referrals;
        }

        $template = "<option value=\"-1\">Select referral</option>";
        foreach ($referrals as $referral) {
            $template .= "<option value=\"$referral->id\" " . selectDropdown($referral->id) . " >$referral->first_name $referral->last_name</option>";
        }

        return $template;
    }

    /**
     * Payout Refereral list
     * @return string
     */
    public function PayoutReferralsIndex()
    {
        $user_ids = User::whereHas('payouts', function ($q) {})
            ->with('payouts')
            ->lists('id');

        if (authUser("web")->role == 'SUPER_ADMIN') {
            $referrals = User::whereRole('REFERRAL')->whereNotIn('id', $user_ids)->get();
        } else if (authUser("web")->role == 'DSA_OWNER') {
            $user = User::with(['teams', 'teams.referrals' => function ($q) use ($user_ids) {
                $q->whereNotIn('referral_team.referral_id', $user_ids);
            }])->find(authUser("web")->id);
            $referrals = $user->teams->first()->referrals;
        }

        $template = "<option value=\"-1\">Select referral</option>";
        foreach ($referrals as $referral) {
            $template .= "<option value=\"$referral->id\" " . selectDropdown($referral->id) . " >$referral->first_name $referral->last_name</option>";
        }

        return $template;
    }

    /**
     * List the states
     * @return string
     */
    public function states()
    {
        $states   = State::all();
        $template = "<option selected disabled>Select State</option>";
        foreach ($states as $state) {
            $template .= "<option value=\"$state->id\" " . selectDropdown($state->id) . ">$state->name</option>";
        }
        return $template;
    }

    /**
     * Loan statuses
     */
    public function loanStatusesIndex()
    {

        $statuses = LoanStatus::all();
        $template = "<option selected disabled >Select Stage</option>";
        foreach ($statuses as $status) {
            $template .= "<option value=\"$status->id\" data-key=\"$status->key\" " . selectDropdown($status->id) . ">$status->label</option>";
        }
        return $template;
    }

    /**
     * Members bank
     * @return string
     */
    public function membersBank()
    {
        $requestData = request()->all();

        $user = User::with(['banks'])->whereId($requestData['user_id'])->first();
        if ($user->banks->count() > 0) {
            return $user->banks->first();
        }
        return "";
    }

    public function membersApprovalRequest()
    {
        $user = request()->user();

        $agentIds = BankProject::where('project_id', request()->all()['project_id'])->get()->lists('agent_id')->all();

        if (\Auth::user()->role == 'SUPER_ADMIN') {
            $members = User::whereIn('role', ['DSA_MEMBER', 'DSA_OWNER'])
                ->whereNotIn('id', $agentIds)->get();
        } else {
            $userTeam = User::with(['teams', 'teams.members' => function ($query) use ($agentIds) {
                $query->whereNotIn('id', $agentIds);
            }])->whereId($user->id)->first();
            $members = $userTeam->teams->first()->members;
        }

        if ($members->isEmpty()) {
            $template = "<option value=''>No members available</option>";
        } else {
            $template = "<option value=''>Select member</option>";
        }
        foreach ($members as $member) {
            $name = $member->present()->name;
            $template .= "<option value=\"$member->id\" " . selectDropdown($member->id) . ">$name</option>";
        }
        return $template;
    }

    /**
     * bank lists
     * @return mixed
     */
    public function bankIndex()
    {
        $banks = Bank::with(['addresses.city'])
            ->whereHas('addresses', function ($query) {
                if (isset(request()->city_id) && request()->city_id != 'null') {
                    $query->where('city_id', City::whereId(request()->city_id)->first()->id);
                }
            })
            ->get();

        if ($banks->isEmpty()) {
            $template = "<option value=''>No banks available</option>";
        } else {
            $template = "<option value=''>Select bank</option>";
        }
        foreach ($banks as $bank) {
            $template .= "<option value=\"$bank->id\" " . selectDropdown($bank->id) . ">$bank->name</option>";
        }
        return $template;
    }

    /**
     * bank Members
     * @return string
     */
    public function bankMembers()
    {
        $requestData = request()->all();

        $bank = Bank::with(['users' => function ($query) {
            $query->whereIn('role', ['DSA_MEMBER', 'DSA_OWNER']);
        }])->whereId($requestData['bank_id'])->get();
        $template = "<option value=\"-1\">Select member</option>";
        foreach ($bank->first()->users as $member) {
            $name = $member->present()->name;
            $template .= "<option value=\"$member->id\" " . selectDropdown($member->id) . ">$name</option>";
        }
        return $template;
    }

}
