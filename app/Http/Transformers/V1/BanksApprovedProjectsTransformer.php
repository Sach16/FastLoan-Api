<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

use Whatsloan\Repositories\Banks\Bank;

class BanksApprovedProjectsTransformer extends TransformerAbstract
{


    /**
     * @param $e
     * @return array
     */
    public function transform($data)
    {
        return [
            'projects' => $data['projects'],
            'banks' => $data['banks'],
        ];
    }
}
