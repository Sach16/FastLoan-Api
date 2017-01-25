<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\LoanHistories\LoanHistory;

class LoanTotalTatTransformer extends TransformerAbstract
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
    ];

    
    
    /**
     * Loan history transform
     * @param LoanHistory $loan
     * @return type
     */
    public function transform($duration)
    {
        return [
            'duration' => $duration,

        ];
    }

   
}
