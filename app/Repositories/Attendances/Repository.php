<?php

namespace Whatsloan\Repositories\Attendances;

use Whatsloan\Repositories\Teams\Contract as Teams;
use Whatsloan\Repositories\Users\Contract as Users;
use Carbon\Carbon;
use DateTime;
use Whatsloan\Repositories\Attendances\Attendance;
use Whatsloan\Repositories\Calendars\Calendar;
use Whatsloan\Repositories\Users\User;

class Repository implements Contract
{

    /**
     * @var Lead
     */
    private $attendance;



     /**
     * @var Team
     */
    private $team;


    /**
     * @var [type]
     */
    private $user;



    /**
     * Attendances repository constructor
     * @param Attendance $attendance
     * @param Teams      $team
     * @param Users      $user
     */
    public function __construct(Attendance $attendance, Teams $team, Users $user)
    {
        $this->attendance = $attendance;
        $this->team = $team;
        $this->user = $user;
    }


    /**
     * Get attendance hostory of the team
     * @param  string $month
     * @param  string $year
     * @return Collection
     */
    public function getTeamHistory($month = "", $year = "", $user_uuid = "")
    {
        if ($month == "") {
            $month = date('n');
        }
        if ($year == "") {
            $year = date('o');
        }

        $authUser = \Auth::guard('api')->user();
        $userTeam = ($authUser->role == 'DSA_OWNER')
                ? $this->user->getDsaOwnerTeam($authUser->uuid)
                : $this->user->getDsaMemberTeam($authUser->uuid);
        if( $user_uuid == "" ){
          $memberIds = $userTeam->teams->first()->members->lists('id');
        }else{
          $memberIds = [User::Where('uuid',$user_uuid)->first()->id];
        }

        return  $this->getAttendaceSummary($memberIds, $year, $month);
    }


    /**
     * Get attendance history
     * @param  array $memberIds
     * @param  string $year
     * @param  string $month
     * @return Collection
     */
    public function getAttendaceSummary($memberIds, $year, $month)
    {
        $TotlalDaysInMonth = [];
        $presentDayForMonth = [];
        $holiday_list =[];
        $result = collect([]);
        if(Carbon::createFromDate($year, $month, 1)->isFuture())
          return $result;

        //Future dates are blocking for current month
        if(date("Y") == $year && date("m") == $month) {
            $daysInMonth = date("d");
            $PresentMonth = 1;
        }else{
            $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
            $PresentMonth = 0;
        }
        // to get holidays count based on date
        $from_date = $year."-".sprintf("%02d", $month)."-01";
        $to_date = $year."-".sprintf("%02d", $month)."-".$daysInMonth;

        $tempAttendancetable = \DB::raw("(SELECT user_id,count(*) as present_count FROM attendances where start_time LIKE '".$year."-".sprintf("%02d", $month)."%' GROUP BY user_id ) as attendancesTemp ") ;
        $users = User::with(['attendances' => function ($q) use ($year, $month) {
                            $q->whereYear('start_time', '=', $year)
                              ->whereMonth('start_time', '=', $month);
                    }])
                    ->join('team_user','users.id','=','team_user.user_id')
                    ->leftJoin($tempAttendancetable, 'attendancesTemp.user_id', '=', 'users.id')
                    ->whereIn('users.id', $memberIds)
                    ->orderByRaw("FIELD(users.role , 'DSA_OWNER', 'DSA_MEMBER') ASC")
                    ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.uuid')
                    ->select(\DB::raw('
                        users.first_name,
                        users.last_name,IFNULL(attendancesTemp.present_count,0) as attendance,
                        users.id as user_id,
                        users.uuid,
                        users.settings,
                        team_user.team_id'))
                    ->get();

                    if($daysInMonth != 0 ){
                        //if present month then send the list having one count less 
                        if($PresentMonth ==1){
                            --$daysInMonth;
                        }

                        //for all users 
                        foreach ($users as $user) {
                            if(isset($user->settings['DOJ'])){
                                $doj = Carbon::parse($user->settings['DOJ']);
                                //if present month calculate days count based on DOJ
                                foreach (range(1, $daysInMonth) as $key => $day) {
                                    $DateCountStart = Carbon::parse($day.'-'.$month.'-'.$year);                                
                                    if( $DateCountStart->gt($doj) || $DateCountStart->eq($doj) ){
                                        $TotlalDaysInMonth[] = date('Y-m-d',strtotime($year.'-'.$month.'-'.$day));
                                    }
                                }
                                $User_attendances = User::with(['attendances' => function ($q) use ($year, $month) {
                                                            $q->whereYear('start_time', '=', $year)
                                                            ->whereMonth('start_time', '=', $month);
                                                    }])->find($user->user_id);

                                foreach ($User_attendances->attendances as $presentDays){
                                    $presentDayForMonth[] = date('Y-m-d',strtotime($presentDays->start_time));
                                }
                                // get holidays count based on present days for the user
                                $cal_holidays = User::join('team_user','users.id','=','team_user.user_id')
                                                ->join('calendars','calendars.team_id','=','team_user.team_id')
                                                ->whereRaw("DATE(date) BETWEEN '".$from_date."' AND '".$to_date."' ")
                                                ->where('calendars.team_id',$user->team_id)
                                                ->where('team_user.user_id',$user->user_id)
                                                ->select('date as holidays_date')
                                                ->get();
                                                                
                                foreach ($cal_holidays as $cal_holiday) {
                                    $holiday_list =[];
                                    $tmp_date = date('Y-m-d',strtotime($cal_holiday->holidays_date));
                                    if(!in_array($tmp_date,$presentDayForMonth))
                                        $holiday_list[] = $tmp_date;
                                }
                                //get date when user on leave
                                $count_of_leave = array_diff($TotlalDaysInMonth,$presentDayForMonth);
                                // get the count of actual leave
                                $leave_count = count($count_of_leave) - count($holiday_list);

                                    $result->push([
                                        'leaves' => ($leave_count<0) ? 0 : $leave_count,
                                        'attendance' => $user->attendance,
                                        'first_name' => $user->first_name,
                                        'last_name' => $user->last_name,
                                        'user_id' => $user->id,
                                        'uuid' => $user->uuid,
                                    ]);
                                    $presentDayForMonth =[];
                                    $TotlalDaysInMonth = [];
                            }
                        }
                    }              
                    return $result;
    }


    /**
     * Filter attendeance of user by year and month
     * @param  string $userUuid
     * @param  integer $year
     * @param  integer $month
     * @return Collection
     */
    public function filter($userUuid, $year, $month)
    {

        if ($month == "") {
            $month = date('n');
        }
        if ($year == "") {
            $year = date('o');
        }

        $user = $this->user->find($userUuid);
        $doj = Carbon::parse($user->settings['DOJ']);
        $attendances = $this->attendance
                            ->where('user_id', '=', $user->id)
                            ->whereYear('start_time', '=', $year)
                            ->whereMonth('start_time', '=', $month)
                            ->get();

        $userPresentDays = [];
        $attendances->each(function (&$attendance, $key) use (&$userPresentDays) {
            $userPresentDays[] = Carbon::parse($attendance->start_time)->day;
        });

        $TeamHoliDays = [];
        $holidays = $this->holidays();
        $holidays->each(function (&$holiday, $key) use (&$TeamHoliDays) {
            $TeamHoliDays[] = Carbon::parse($holiday->date)->day;
        });
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        $attendanceForMonth = collect();

        $authUser = \Auth::guard('api')->user();
        //Future dates are blocking for current month
        if(date("Y") == $year && date("m") == $month) {
          $isCurrentDay = $daysInMonth = ($authUser->uuid == $userUuid  )
                                          ? (date("d") - 1)
                                          : date("d");
        }
        if($daysInMonth != 0){
          foreach (range(1, $daysInMonth) as $key => $day) {
            $DateCountStart = Carbon::parse($day.'-'.$month.'-'.$year);                                
            if( $DateCountStart->gt($doj) || $DateCountStart->eq($doj) ){

                if(isset($isCurrentDay) && ($daysInMonth == $day) ){

                    $isPresent = ($authUser->uuid == $userUuid  )
                                ?((in_array($day, $userPresentDays)) ? 1 : 0)
                                :2;
                }else{
                    $isPresent =(in_array($day, $userPresentDays)) ? 1 : 0;
                }
                    // if user not present and its holiday send 3
                    $isPresent = $isPresent == 0 ? (in_array($day, $TeamHoliDays)) ? 3 : 0 : $isPresent;

                    $attendanceForMonth->push([
                        'day'         => $day,
                        'isPresent'   => $isPresent,
                    ]);
            }

          }
          //list the future holidays
          sort($TeamHoliDays);
          $FutureHolidays = array_filter(
                $TeamHoliDays,
                function ($value) use($day) {
                    return ($value >= $day );
                }
            );
            if(!empty($FutureHolidays)){
                foreach($FutureHolidays as $holi_day){
                     if(date("Y") == $year && date("m") == $month) {
                        $isAuthUser = ($authUser->uuid == $userUuid  )
                                                        ? 1
                                                        : 0;
                    }
                    if(! (isset($isCurrentDay) && isset($isAuthUser) && $holi_day == date("d")) )
                    $attendanceForMonth->push([
                        'day'         => $holi_day,
                        'isPresent'   => 3,
                    ]);
                }
            }
            

        }
        return $attendanceForMonth;
    }

    public function holidays()
    {
        $year = request()->year;
        $month = request()->month;
        if ($month == "") {
            $month = date('n');
        }
        if ($year == "") {
            $year = date('o');
        }
        $authUser = \Auth::guard('api')->user();
        $team = $this->user->getTeamId($authUser->uuid);
        return $holidays = Calendar::where('team_id',$team->id)
                    ->whereYear('date', '=', $year)
                    ->whereMonth('date', '=', $month)
                    ->get();
        
    }

    /**
     * Store user attendance
     * @param array $data
     */
    public function store($data) {
        $attendance = new Attendance($data);

        if($attendance->save()) {
            return $attendance;
        }

        return false;
    }



    /**
     * Is day started
     * @param string $uuid
     * @param string $date
     * @return Item
     */
    public function isDayStarted($userId) {

        return $this->attendance
                    ->where('user_id',$userId)
                    ->whereYear('start_time','=',date('Y'))
                    ->whereMonth('start_time','=',date('m'))
                    ->whereDay('start_time','=',date('d'))
                    ->first();
    }
}
