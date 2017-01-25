<?php

namespace Whatsloan\Http\Transformers\V1\Consumers;

use League\Fractal\TransformerAbstract;

use Whatsloan\Repositories\Banks\Bank;

class BankTransformer extends TransformerAbstract
{


    /**
     * @var array
     */
    protected $defaultIncludes = [
        
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [
        'address',
        'projects',
        'bankProjects',
    ];



    /**
     * @param $e
     * @return array
     */
    public function transform(Bank $bank)
    {

        return [
            'uuid'      => $bank->uuid,
            'name'      => $bank->name,
            'branch'    => $bank->branch,
            'ifsccode'  => $bank->ifsccode,
        ];
    }


    /**
     * Include address
     * @param  Bank $bank
     * @return
     */
    public function includeAddress(Bank $bank)
    {
        return $this->item($bank->addresses->first(), new AddressTransformer);
    }
    
    
    /**
     * Include projects
     * @param  Bank $bank
     * @return
     */
    public function includeProjects(Bank $bank)
    {
        return $this->collection($bank->projects, new ProjectTransformer);
    }
    
    /**
     * Include projects
     * @param  Bank $bank
     * @return
     */
    public function includeBankProjects(Bank $bank)
    {
        return $this->item($bank->pivot, new BanksProjectsTransformer);
    }
    
    
    
    
}
