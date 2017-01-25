<?php

namespace Whatsloan\Repositories\Calendars;

class Repository implements Contract
{

    /**
     * @var Calendar
     */
    private $calendar;

    /**
     * Repository constructor.
     * @param Calendar $calendar
     */
    public function __construct(Calendar $calendar)
    {
        $this->calendar = $calendar;
    }


    /**
     * Update a calendar item
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function update(array $request, $id)
    {
        $calendar = $this->calendar->find($id);
        $calendar->update($request);

        return $calendar;
    }

    /**
     * Store a new  calendar item
     *
     * @param array $request
     * @param $teamId
     * @return mixed
     */
    public function store(array $request, $teamId)
    {
        return $this->calendar->create($request);
    }
}
