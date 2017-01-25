<?php

namespace Whatsloan\Http\Transformers\V1\Consumers;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Loans\Loan;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Banks\Bank;
use Carbon\Carbon;
class LoanTransformer extends TransformerAbstract
{
    
    
    /**
     * @var array
     */
    protected $defaultIncludes = [
        'bank',
        'type',
        'status',
        'agent',
        'documents'
//        'history',
        
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [
        'customer',
    ];
    
    
    /**
     * @param Loan $loan
     * @return array
     */
    public function transform(Loan $loan)
    {
        return [
            'uuid'           => $loan->uuid,
            'amount'         => $loan->amount,
            'eligible_amount'=> $loan->eligible_amount,
            'approved_amount'=> $loan->approved_amount,
            'interest_rate'  => $loan->interest_rate,
            'applied_on'     => $loan->applied_on,
            'approval_date'  => $loan->approval_date,
            'emi'            => $loan->emi,
            'emi_start_date' => $loan->emi_start_date,
            'appid'          => $loan->appid,
//            'documents'      => $loan->attachments(),
            'total_tat'      => $this->getTAT($loan),
        ];
    }


    /**
    * include  bank details
    * @param  Loan $loan 
    * @return item
    */
    public function includeBank(Loan $loan)
    {
        if($loan->bank != null){
            return $this->item($loan->bank, new BankTransformer);
        }
    }
    
    
    
    /**
    * include loan customer 
    * @param  Loan $loan 
    * @return item
    */
    public function includeCustomer(Loan $loan)
    {
        return $this->item($loan->customer, new UserTransformer);
    }
    
    
    
    /**
    * include loan agent 
    * @param  Loan $loan 
    * @return item
    */
    public function includeAgent(Loan $loan)
    {
        if(!empty($loan->agent)){
            return $this->item($loan->agent, new AgentTransformer);            
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
    * include user
    * @param  User $user [description]
    * @return item
    */
    public function includeUser()
    {
        return $this->item(\Auth::guard('api')->user(), new UserTransformer);
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
    
    public function getTAT($loan)
    {
        $leadCreatedDate ='';
        $leadToBankLogin ='';
        $bankLoginToSanction ='';
        $sanctionToDisbursal ='';
        foreach($loan->history as $loanHistory){
            if($loanHistory['status']->key == 'LEAD'){
               $leadCreatedDate = $loanHistory['created_at'];                
            }
            if($loanHistory['status']->key == 'BANK_LOGIN'){
                $leadToBankLogin = $loanHistory['updated_at'];
            }
            
            if($loanHistory['status']->key == 'SANCTION'){
                $bankLoginToSanction = $loanHistory['updated_at'];
            }
            if($loanHistory['status']->key == 'FIRST_DISB' ){
                $sanctionToDisbursal = $loanHistory['updated_at'];
            }
     
        }
        $leadBankLoginDays =0;
        $bankLoginToSanctionDays =0;
        $sanctionToDisbursalDays =0;
        
        if($leadCreatedDate !='' && $leadToBankLogin !='' ){
            $leadBankLoginDays = Carbon::parse($leadCreatedDate)->diffInDays(Carbon::parse($leadToBankLogin));
        }else if($leadCreatedDate !='' && $leadToBankLogin=='' ){
             $leadBankLoginDays = Carbon::parse($leadCreatedDate)->diffInDays(Carbon::now());
        }
        
        if($leadToBankLogin !='' && $bankLoginToSanction !=''){
            $bankLoginToSanctionDays = Carbon::parse($leadToBankLogin)->diffInDays(Carbon::parse($bankLoginToSanction));
        }else if($leadToBankLogin !='' && $bankLoginToSanction ==''){
            $bankLoginToSanctionDays = Carbon::parse($leadToBankLogin)->diffInDays(Carbon::now());
        }
        
        if($bankLoginToSanction !='' && $sanctionToDisbursal !=''){
            $sanctionToDisbursalDays = Carbon::parse($bankLoginToSanction)->diffInDays(Carbon::parse($sanctionToDisbursal));
        }elseif($bankLoginToSanction !='' && $sanctionToDisbursal ==''){
              $sanctionToDisbursalDays = Carbon::parse($bankLoginToSanction)->diffInDays(Carbon::now());
        }
        
        return [
            'LEAD_TO_BANK_LOGIN' =>$leadBankLoginDays,
            'BANK_LOGIN_TO_SANCTION' =>$bankLoginToSanctionDays,
            'SANCTION_TO_DISBURSAL' =>$sanctionToDisbursalDays,
        ];
        
    }
    
    
    /**
     * Status of loan 
     * @param Loan $loan
     */
    public function includeStatus(Loan $loan)
    {
        return $this->item($loan->status, new LoanStatusTransformer);
    }

     public function includeDocuments(Loan $loan)
    {
        return $this->collection($loan->attachments, new AttachmentTransformer);
    }
}
