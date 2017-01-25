<?php

namespace Whatsloan\Repositories\Attendances;

interface Contract
{


    /**
     * Get attendance history
     * @param  [type] $teamId [description]
     * @return [type]         [description]
     */
    public function getTeamHistory($month,$year,$user_uuid);




    /**
     * Checks whether the day is started
     * @param type $userId
     */
    public function isDayStarted($userId);
}
