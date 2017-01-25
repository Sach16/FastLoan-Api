<?php

namespace Whatsloan\Http\Transformers\V1\Consumers;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\LoanStatuses\LoanStatus;

class LoanStatusTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * Loan status transformer
     * @param LoanStatus $loanStatus
     * @return array
     */
    public function transform(LoanStatus $loanStatus)
    {
        return [
            'key' => $loanStatus->key,
            'label' => $loanStatus->label,
            'consolidated_status'   => $this->consolidatedStatus($loanStatus['key']),
        ];
    }
    public function consolidatedStatus($loanStatus){
        if($loanStatus == 'LEAD' || $loanStatus== 'FOLLOW_UP' || $loanStatus== 'OFFICE_LOGIN'  ){
            return 'LEAD';
        }else if($loanStatus== 'BANK_LOGIN' ){
            return 'BANK LOGIN';
        }
        else if($loanStatus== 'SANCTION'){
            return 'SANCTION';
        }
        else if($loanStatus== 'FIRST_DISB' || $loanStatus== 'PART_DISB'|| $loanStatus== 'FINAL_DISB' ){
            return 'DISBURSEMENT';
        }else{
            return $loanStatus;
        }
    }
}
