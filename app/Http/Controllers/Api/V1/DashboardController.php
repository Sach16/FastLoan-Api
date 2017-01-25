<?php

namespace Whatsloan\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Transformers\V1\BuilderDashboardTransformer;
use Whatsloan\Http\Transformers\V1\ReferralDashboardTransformer;
use Whatsloan\Http\Transformers\V1\TeamDashboardTransformer;
use Whatsloan\Repositories\Attendances\Attendance;
use Whatsloan\Repositories\Leads\Lead;
use Whatsloan\Repositories\Users\Contract as Users;

class DashboardController extends Controller
{
    /**
     * @var Leads
     */
    private $users;

    /**
     * LeadsController constructor
     *
     * @param Leads $leads
     */
    public function __construct(Users $users)
    {
        $this->users = $users;
    }

    /**
     * Return the team dashboard
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function teams(Request $request)
    {
        $data               = [];
        $attendance         = Attendance::filters($request)->count();
        $data['attendance'] = ['count' => $attendance];

        $all = Lead::dashboardStats($request, $role = 'LEAD')->get();
        if ($all) {
            $data['leads'] = ['count' => $all->count('id'), 'amount' => sprintf('%0.2f', $all->sum('amount'))];
        } else {
            $data['leads'] = ['count' => 0, 'amount' => 0.00];
        }

        $logins = Lead::dashboardStats($request, $role = 'CONSUMER', $loan_status = 'LOGIN', ['OFFICE_LOGIN', 'BANK_LOGIN'])->get();
        if ($logins) {
            $data['logins'] = ['count' => $logins->count('id'), 'amount' => sprintf('%0.2f', $logins->sum('amount'))];
        } else {
            $data['logins'] = ['count' => 0, 'amount' => 0.00];
        }

        $disbursements = Lead::dashboardStats($request, $role = 'CONSUMER', $loan_status = 'DISB', ['FIRST_DISB', 'PART_DISB', 'FINAL_DISB'])->get();
        if ($disbursements) {
            $data['disbursements'] = ['count' => $disbursements->unique('id')->count('id'), 'amount' => sprintf('%0.2f', $disbursements->sum('disb_amount'))];
        } else {
            $data['disbursements'] = ['count' => 0, 'amount' => 0.00];
        }

        $sanctions = Lead::dashboardStats($request, $role = 'CONSUMER', $loan_status = 'SANCTION', ['SANCTION'])->get();
        if ($sanctions) {
            $data['sanctions'] = ['count' => $sanctions->count('id'), 'amount' => sprintf('%0.2f', $sanctions->sum('amount'))];
        } else {
            $data['sanctions'] = ['count' => 0, 'amount' => 0.00];
        }

        if (isset($request->member_uuid)) {
            $team                     = $this->users->getTeamId($request->member_uuid);
            $data['target']           = $team->pivot->target;
            $data['achieved']         = $team->pivot->achieved;
            $data['incentive_plan']   = $team->pivot->incentive_plan;
            $data['incentive_earned'] = $team->pivot->incentive_earned;
        } else {
            $data['target'] = $data['achieved'] = $data['incentive_plan'] = $data['incentive_earned'] = 0;
        }

        return $this->transformItem($data, TeamDashboardTransformer::class);
    }

    /**
     * Returns the Builder Dashboard
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function builders(Request $request)
    {
        $data = [];
        $all  = Lead::builderDashboardStats($request, $role = 'LEAD')->get();
        //dd($all);
        if ($all) {
            $data['leads'] = ['count' => $all->count('id'), 'amount' => sprintf('%0.2f', $all->sum('amount'))];
        } else {
            $data['leads'] = ['count' => 0, 'amount' => 0.00];
        }

        $logins = Lead::builderDashboardStats($request, $role = 'CONSUMER', $loan_status = 'LOGIN', ['OFFICE_LOGIN', 'BANK_LOGIN'])->get();

        if ($logins) {
            $data['logins'] = ['count' => $logins->count('id'), 'amount' => sprintf('%0.2f', $logins->sum('amount'))];
        } else {
            $data['logins'] = ['count' => 0, 'amount' => 0.00];
        }

        $first_disbursement = Lead::builderDashboardStats($request, $role = 'CONSUMER', $loan_status = 'DISB', ['FIRST_DISB'])->get();
        if ($first_disbursement) {
            $first_disbursement = $first_disbursement->filter(function ($item) {
                return $item->logout_count <= 0;
            });
            $data['first_disbursement'] = ['count' => $first_disbursement->count('id'), 'amount' => sprintf('%0.2f', $first_disbursement->sum('disb_amount'))];
        } else {
            $data['first_disbursement'] = ['count' => 0, 'amount' => 0.00];
        }

        $part_disbursement = Lead::builderDashboardStats($request, $role = 'CONSUMER', $loan_status = 'DISB', ['PART_DISB'])->get();
        if ($part_disbursement) {
            $part_disbursement = $part_disbursement->filter(function ($item) {
                return $item->logout_count <= 0;
            });
            $data['part_disbursement'] = ['count' => $part_disbursement->count('id'), 'amount' => sprintf('%0.2f', $part_disbursement->sum('disb_amount'))];
        } else {
            $data['part_disbursement'] = ['count' => 0, 'amount' => 0.00];
        }

        $final_disbursement = Lead::builderDashboardStats($request, $role = 'CONSUMER', $loan_status = 'DISB', ['FINAL_DISB'])->get();
        if ($final_disbursement) {
            $final_disbursement = $final_disbursement->filter(function ($item) {
                return $item->logout_count <= 0;
            });
            $data['final_disbursement'] = ['count' => $final_disbursement->count('id'), 'amount' => sprintf('%0.2f', $final_disbursement->sum('disb_amount'))];
        } else {
            $data['final_disbursement'] = ['count' => 0, 'amount' => 0.00];
        }

        return $this->transformItem($data, BuilderDashboardTransformer::class);
    }

    /**
     * Returns the Referral Dashboard
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function referrals(Request $request)
    {
        $data  = [];
        $leads = Lead::referralDashboardStats($request, $role = 'LEAD')->get();
        if ($leads) {
            $data['leads'] = ['count' => $leads->count('id'), 'amount' => sprintf('%0.2f', $leads->sum('amount')), 'payout' => sprintf('%0.2f', $leads->sum('payout'))];
        } else {
            $data['leads'] = ['count' => 0, 'amount' => 0.00, 'payout' => 0.00];
        }

        $logins = Lead::referralDashboardStats($request, $role = 'CONSUMER', $loan_status = 'LOGIN', ['OFFICE_LOGIN', 'BANK_LOGIN'])->get();

        if ($logins) {
            $data['logins'] = ['count' => $logins->count('id'), 'amount' => sprintf('%0.2f', $logins->sum('amount')), 'payout' => sprintf('%0.2f', $logins->sum('payout'))];
        } else {
            $data['logins'] = ['count' => 0, 'amount' => 0.00, 'payout' => 0.00];
        }

        $sanctions = Lead::referralDashboardStats($request, $role = 'CONSUMER', $loan_status = 'SANCTION', ['SANCTION'])->get();

        if ($sanctions) {
            $data['sanctions'] = ['count' => $sanctions->count('id'), 'amount' => sprintf('%0.2f', $sanctions->sum('amount')), 'payout' => sprintf('%0.2f', $sanctions->sum('payout'))];
        } else {
            $data['sanctions'] = ['count' => 0, 'amount' => 0.00, 'payout' => 0.00];
        }

        $disbursals = Lead::referralDashboardStats($request, $role = 'CONSUMER', $loan_status = 'DISB', ['FIRST_DISB', 'PART_DISB', 'FINAL_DISB'])->get();
        if ($disbursals) {
            $data['disbursals'] = ['count' => $disbursals->count('id'), 'amount' => sprintf('%0.2f', $disbursals->sum('amount')), 'payout' => sprintf('%0.2f', $disbursals->sum('payout'))];
        } else {
            $data['disbursals'] = ['count' => 0, 'amount' => 0.00, 'payout' => 0.00];
        }

        $total_payout                          = [$leads->sum('payout'), $logins->sum('payout'), $sanctions->sum('payout'), $disbursals->sum('payout')];
        $data['total_payout_earned']['amount'] = sprintf('%0.2f', array_sum($total_payout));

        $total_payout                 = [$leads->sum('total_paid'), $logins->sum('total_paid'), $sanctions->sum('total_paid'), $disbursals->sum('total_paid')];
        $data['total_paid']['amount'] = sprintf('%0.2f', array_sum($total_payout));

        $data['balance']['amount'] = sprintf('%0.2f', $data['total_payout_earned']['amount'] - $data['total_paid']['amount']);

        return $this->transformItem($data, ReferralDashboardTransformer::class);
    }
}
