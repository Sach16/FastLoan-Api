<?php

namespace Whatsloan\Http\Transformers\V1;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Loans\Loan;

class LoanTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = [
        'type',
        'agent',
        'loan_statuses',
        'customer',
        'user',
        'total_tat',
        'loan_status_tat',
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [
        'history',
        'tasks',
        'attachments',
        'team',
        //'total_tat',
    ];

    /**
     * @param Loan $loan
     * @return array
     */
    public function transform(Loan $loan)
    {

        return [
            'uuid'            => $loan->uuid,
            'amount'          => $loan->amount,
            'eligible_amount' => $loan->eligible_amount,
            'approved_amount' => $loan->approved_amount,
            'emi'             => $loan->emi,
            'appid'           => $loan->appid,
            'interest_rate'   => $loan->interest_rate,
            'applied_on'      => $loan->applied_on,
            'approval_date'   => $loan->approval_date,
            'emi_start_date'  => $loan->emi_start_date,
            'created_at'      => ($loan->loan_created_at) ? $loan->loan_created_at : $loan->created_at,
            'updated_at'      => $loan->updated_at,
        ];

    }

    /**
     * include loan customer
     * @param  Loan $loan
     * @return item
     */
    public function includeCustomer(Loan $loan)
    {
        if($loan->customer){
            return $this->item($loan->customer, new UserTransformer);
        }
    }

    /**
     * include loan customer
     * @param  Loan $loan
     * @return item
     */
    public function includeUser(Loan $loan)
    {
        if($loan->user){
            return $this->item($loan->user, new UserTransformer);
        }
    }

    /**
     * include loan agent
     * @param  Loan $loan
     * @return item
     */
    public function includeAgent(Loan $loan)
    {
        if($loan->agent){
            return $this->item($loan->agent, new UserTransformer);
        }
    }

    /**
     * include loan approved
     * @param  Customer $customer [description]
     * @return item
     */
    public function includeType(Loan $loan)
    {
        return $this->item($loan->type, new TypeTransformer);
    }

    /**
     * loan history
     * @param  Customer $customer [description]
     * @return item
     */
    public function includeHistory(Loan $loan)
    {
        return $this->collection($loan->history, new LoanHistoryTransformer);
    }

    /**
     * loan history
     * @param  Customer $customer [description]
     * @return item
     */
    public function includeTotalTat(Loan $loan)
    {
        if (count($loan->history) == 0) {
            $duration = 0;
        } else {
            $duration = (int) $loan->history->first()->updated_at->diffInDays(Carbon::now());
        }
        return $this->item($duration, new LoanTotalTatTransformer);
    }

    public function includeLoanStatusTat(Loan $loan)
    {
        $loanHistories = $loan->history()->get();
        if (count($loanHistories) == 0) {
            $status_tat[0]['status_tat'] = (int) 0;
            $status_tat[0]['status']     = (string) 'Lead';
            $status_tat[0]['key']        = (string) 'Lead';
        } else {
            $i = 0;
            foreach ($loanHistories as $loanHistory) {
                $dates[$i]['updated_at'] = $loanHistory->updated_at;
                $dates[$i]['status']     = $loanHistory->status;
                $i++;
            }

            $dates = array_reverse($dates);
            $j     = 0;
            foreach ($dates as $date) {
                if ($j == 0) {
                    $status[$j]['status_tat'] = (int) $date['updated_at']->diffInDays(Carbon::now());
                    $status[$j]['status']     = $date['status']['label'];
                    $updated_tat              = $date['updated_at'];
                    $status[$j]['key']        = $date['status']['key'];
                } else {
                    $status[$j]['status_tat'] = (int) $updated_tat->diffInDays($date['updated_at']);
                    $status[$j]['status']     = $date['status']['label'];
                    $updated_tat              = $date['updated_at'];
                    $status[$j]['key']        = $date['status']['key'];
                }
                $j++;
            }
            $status_tat = array_reverse($status);
        }

        for ($k = 0; $k < count($status_tat); $k++) {
            $disb = false;
            if (isset($status_tat[$k]['status'])) {
                $key = $k + 1;
                if (isset($status_tat[$key]['status'])) {
                    if (in_array($status_tat[$key]['key'], ['FIRST_DISB', 'PART_DISB', 'FINAL_DISB'])) {
                        $disb = true;
                    }
                    $next_is_disb  = true;
                    $next_disb_inc = 0;
                    if ($disb) {
                        for ($l = $key + 1; $l <= count($status_tat) && $next_is_disb; $l++) {
                            if (isset($status_tat[$l]['status']) && in_array($status_tat[$l]['key'], ['FIRST_DISB', 'PART_DISB', 'FINAL_DISB'])) {
                                $next_disb_inc = $l;
                            } else {
                                $next_is_disb = false;
                            }
                        }
                    }
                    $result[$k]['status'] = (in_array($status_tat[$k]['key'], ['FIRST_DISB', 'PART_DISB', 'FINAL_DISB']) ? 'Disbursement' : $status_tat[$k]['status']) . ' - ' . (in_array($status_tat[$key]['key'], ['FIRST_DISB', 'PART_DISB', 'FINAL_DISB']) ? 'Disbursement' : $status_tat[$key]['status']);
                } else {
                    $result[$k]['status'] = (in_array($status_tat[$k]['key'], ['FIRST_DISB', 'PART_DISB', 'FINAL_DISB']) ? 'Disbursement' : $status_tat[$k]['status']);
                }

            }

            if (isset($status_tat[$k]['status_tat'])) {
                if ($disb) {
                    if ($next_disb_inc == 0) {
                        $result[$k]['status_tat'] = $status_tat[$k]['status_tat'] + $status_tat[$key]['status_tat'];
                        $k                        = $k + 1;
                    } else {
                        $total = $status_tat[$k]['status_tat'];
                        for ($l = $key; $l <= $next_disb_inc; $l++) {
                            $total = $total + $status_tat[$l]['status_tat'];
                        }
                        $result[$k]['status_tat'] = $total;
                        $k                        = $l;
                    }

                } else {
                    $result[$k]['status_tat'] = $status_tat[$k]['status_tat'];
                }

            }
        }
        return $this->collection($result, new LoanStatusTatTransformer);
    }

    /**
     * Status of loan
     * @param Loan $loan
     */
    public function includeLoanStatuses(Loan $loan)
    {
        return $this->item($loan->loan_statuses, new LoanStatusTransformer);
    }

    /**
     * Status of loan
     * @param Loan $loan
     */
    public function includeTasks(Loan $loan)
    {
        return $this->collection($loan->tasks, new TaskTransformer);
    }

    /**
     * Attachment of Loan
     * @param Loan $loan
     */
    public function includeAttachments(Loan $loan)
    {
        return $this->collection($loan->attachments, new AttachmentTransformer);
    }

    public function includeTeam(Loan $loan)
    {
        return $this->item($loan->agent->teams->first(), new TeamTransformer);
    }

}
