<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Banks\BankProject;


class BanksProjectsPivotTransformer extends TransformerAbstract
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
     * @param $e
     * @return array
     */
    public function transform($data)
    {
       
        return [
            'status'     => $data->status,
            'bank_id'    => $data->bank_id,
            'project_id' => $data->project_id,
            'agent_id'   => $data->agent_id,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at,
            'approved_date' => $data->approved_date,
        ];
    }
    
    
    
}
