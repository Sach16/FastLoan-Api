<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class HolidayTransformer extends TransformerAbstract
{

    /**
     * @param Source $source
     * @return array
     */
    public function transform($holidays)
    {
        return [
            'date' => $holidays['date'],            
        ];
    }
}
