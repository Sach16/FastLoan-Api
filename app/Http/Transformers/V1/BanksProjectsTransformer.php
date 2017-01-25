<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Repositories\Banks\BankProject;

class BanksProjectsTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = [
        'bank',
        'project',
        'agent',
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * @param $e
     * @return array
     */
    public function transform(BankProject $data)
    {

        return [
            'status'        => $data->status,
            'bank_id'       => $data->bank_id,
            'project_id'    => $data->project_id,
            'agent_id'      => $data->agent_id,
            'approved_date' => $data->approved_date->timestamp <= 0 ? null : $data->approved_date,
            'created_at'    => $data->created_at,
            'updated_at'    => $data->updated_at,
        ];
    }

    /**
     * Include banks
     * @param \Whatsloan\Http\Transformers\V1\BankProject $bankProject
     * @return type
     */
    public function includeBank(BankProject $bankProject)
    {
        return $this->item($bankProject->bank, new BankTransformer());
    }

    /**
     * Include project
     * @param \Whatsloan\Http\Transformers\V1\BankProject $bankProject
     * @return type
     */
    public function includeProject(BankProject $bankProject)
    {
        return $this->item($bankProject->project, new ProjectTransformer());
    }

    /**
     * Include agent
     * @param \Whatsloan\Http\Transformers\V1\BankProject $bankProject
     * @return type
     */
    public function includeAgent(BankProject $bankProject)
    {
        return $this->item($bankProject->agent, new UserTransformer());
    }
}
