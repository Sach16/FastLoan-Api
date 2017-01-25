<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Attendances\Attendance;

class AttendanceSummaryTransformer extends TransformerAbstract
{

     /**
     * @var array
     */
    protected $defaultIncludes = [
        // 'user'
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [

    ];


    /**
     * @param Attendance $attendance
     * @return array
     */
    public function transform($attendances)
    {
        return [
            'present' => $attendances['attendance'],
            'leave'   => $attendances['leaves'],
            'name'    => $attendances['first_name']." ".$attendances['last_name'],
            'uuid'    => $attendances['uuid'],
        ];
    }


     /**
     * @param Attendance $attendance
     * @return \League\Fractal\Resource\Collection
     */
    public function includeUser(Attendance $attendance)
    {
        return $this->item($attendance->user, new UserTransformer);
    }
}
