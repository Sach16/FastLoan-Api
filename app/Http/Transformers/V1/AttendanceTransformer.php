<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Attendances\Attendance;

class AttendanceTransformer extends TransformerAbstract
{


    /**
     * @param Attendance $attendance
     * @return array
     */
    public function transform(Attendance $attendance)
    {
        return [
            'uuid'       => $attendance->uuid,
            'start_time' => $attendance->start_time
        ];
    }
}
