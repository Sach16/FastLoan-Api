<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;

class CalendarTransformer extends TransformerAbstract
{

    /**
     * @param Source $source
     * @return array
     */
    public function transform($calendar)
    {

        return [
            'isPresent' => $calendar['isPresent'],
            'day' => $calendar['day'],
        ];
    }
}
