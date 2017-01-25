<?php

namespace Whatsloan\Http\Transformers\V1;

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
            'uuid' => $loanStatus->uuid,
            'key' => $loanStatus->key,
            'label' => $loanStatus->label,
        ];
    }

}
