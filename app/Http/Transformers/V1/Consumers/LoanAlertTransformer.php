<?php

namespace Whatsloan\Http\Transformers\V1\Consumers;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\LoanAlert\LoanAlert;

class LoanAlertTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = [
        'bank',
        'type'
        ];

    /**
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * @param User $user
     * @return array
     */
    public function transform(LoanAlert $loanAlert)
    {
        return [
            'uuid'       => $loanAlert->uuid,
            'loan_emi_amount' => $loanAlert->loan_emi_amount,
            'due_date'  => $loanAlert->due_date,
            'interest_rate'      => $loanAlert->interest_rate,
            'balance_amount'      => $loanAlert->balance_amount,
            'emi_start_date'       => $loanAlert->emi_start_date,
            'emi'      => $loanAlert->emi,
            'take_over' => $loanAlert->take_over,
            'part_pre_payement' => $loanAlert->part_pre_payement,
            'type_of_property' => $loanAlert->type_of_property,
            'created_at' => $loanAlert->created_at,
            'updated_at' => $loanAlert->updated_at,
        ];
    }

    
    /**
    * include  bank details
    * @param   
    * @return item
    */
    public function includeBank(LoanAlert $loanAlert)
    {
        return $this->item($loanAlert->banks, new BankTransformer);
      
    }
    
    public function includeType(LoanAlert $loanAlert)
    {
         return $this->item($loanAlert->types, new TypeTransformer);
    }
}
