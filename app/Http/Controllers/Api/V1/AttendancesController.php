<?php

namespace Whatsloan\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Transformers\V1\AttendanceSummaryTransformer;
use Whatsloan\Http\Transformers\V1\AttendanceTransformer;
use Whatsloan\Http\Transformers\V1\CalendarTransformer;
use Whatsloan\Http\Transformers\V1\HolidayTransformer;
use Whatsloan\Http\Transformers\V1\ValidationError;
use Whatsloan\Repositories\Attendances\Contract as Attendances;
use Carbon\Carbon;

class AttendancesController extends Controller
{

    /**
     * @var attendances
     */
    private $attendances;

    /**
     * AttendancesController constructor
     * @param Attendances $attendances
     */
    public function __construct(Attendances $attendances)
    {
        $this->attendances = $attendances;
    }

    /**
     * index methos
     * @param  string $teamId [description]
     * @return [type]         [description]
     */
    public function index(Request $request)
    {
        $attendances = $this->attendances->getTeamHistory($request->get('month'), $request->get('year'), $request->get('user_uuid'));
        return $this->transformCollectionOnly($attendances, AttendanceSummaryTransformer::class);
    }

    /**
     * Monthly calendar
     * @param  [type] $uuid [description]
     * @return [type]       [description]
     */
    public function calendar($uuid, Request $request)
    {
        $attendances = $this->attendances->filter($uuid, $request->get('year'), $request->get('month'));
        return $this->transformCollectionOnly($attendances, CalendarTransformer::class);
    }
    
    /**
     * Monthly calendar
     * @param  [type] $uuid [description]
     * @return [type]       [description]
     */
    public function holidays()
    {
        $holidays = $this->attendances->holidays();
        return $this->transformCollection($holidays, HolidayTransformer::class);
    }

    /**
     * Mark Attendance
     */
    public function startDay()
    {
        if($this->attendances->isDayStarted(\Auth::guard('api')->user()->id)) {
            return $this->transformItem("Attendance already marked", ValidationError::class);
        }

        $attendace = $this->attendances->store([
            'uuid' => \Webpatser\Uuid\Uuid::generate()->string,
            'user_id' => \Auth::guard('api')->user()->id,
            'start_time' => Carbon::now()->toDateTimeString()
        ]);


       return $this->transformItem($attendace, AttendanceTransformer::class);
    }

}
