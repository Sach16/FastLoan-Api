<?php

namespace Whatsloan\Repositories\Calendars;

interface Contract
{

    /**
     * Store a new  calendar item
     * 
     * @param array $request
     * @param $teamId
     * @return mixed
     */
    public function store(array $request, $teamId);

    /**
     * Update a calendar item
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function update(array $request, $id);
}
