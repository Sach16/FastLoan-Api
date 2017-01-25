<?php

namespace Whatsloan\Repositories\LoanAlert;

use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Types\Type;

class Repository implements Contract
{

    /**
     * @var City
     */
    private $loanalert;

    /**
     * City repository constructor
     * @param City $city
     */
    public function __construct(LoanAlert $loanalert )
    {
        $this->loanalert = $loanalert;
    }

    /**
     * Get a single city details
     * @param  string $uuid
     * @return Item
     */
    public function find($uuid)
    {
        return $this->loanalert->whereUuid($uuid)->firstOrFail();
    }

    /**
     * Get the loan alert
     *
     * @param int $limit
     * @return mixed
     */
    public function show($userId)
    {
        return $this->loanalert
                ->with(['banks','types'])
                ->whereuser_id($userId)
                ->first(); 
    }
    
    /**
     * Update the loan alert
     *
     * @param $request
     * @param $uuid
     * @return mixed
     */
    public function update($request, $uuid)
    {    
        $loanalert = $this->loanalert->whereUuid($uuid)->first();
        if($request->bank_uuid !=''){
            $loanalert->bank_id = Bank::whereUuid($request->bank_uuid)->first()->id;
        }
        if($request->type_uuid !=''){
            $loanalert->type_id = Type::whereUuid($request->type_uuid)->first()->id;
        }
        $loanalert->update($request->all());
        return $loanalert;
    }
    
    public function store ($request){
        $loanalert = new LoanAlert();
        
        $loanalert->uuid= uuid();
        $loanalert->user_id= \Auth::guard('api')->user()->id;
        $loanalert->loan_emi_amount = $request->loan_emi_amount ;
        $loanalert->interest_rate = $request->interest_rate ;
        $loanalert->due_date = $request->due_date ;
        $loanalert->balance_amount = $request->balance_amount ;
        $loanalert->emi_start_date = $request->emi_start_date ;
        $loanalert->emi =$request->emi ;
        $loanalert->take_over = $request->take_over ;
        $loanalert->part_pre_payement = $request->part_pre_payement ;
        $loanalert->type_of_property = $request->type_of_property ;
                
        if(isset($request->bank_uuid)){
            $loanalert->bank_id = Bank::where('uuid', $request->bank_uuid)->first()->id;
        }
        
        if(isset($request->type_uuid)){
            $loanalert->type_id =  Type::where('uuid', $request->type_uuid)->first()->id;;
        }
        
        $loanalert->save();
        
          return $this->loanalert
                ->with(['banks','types'])
                ->whereId($loanalert->id)
                ->first(); 
        
    }
}
