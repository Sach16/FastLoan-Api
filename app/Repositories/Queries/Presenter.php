<?php

namespace Whatsloan\Repositories\Queries;

class Presenter extends \Laracasts\Presenter\Presenter
{

    /**
     * Present the start date
     *
     * @return mixed
     */
    public function startDate()
    {
        return $this->start_date->format('d-m-Y h:m A');
    }

    /**
     * Present the end date
     *
     * @return mixed
     */
    public function endDate()
    {
        return $this->end_date->format('d-m-Y h:m A');
    }

    /**
     * Present the due date
     *
     * @return mixed
     */
    public function dueDate()
    {
        return $this->due_date->format('d-m-Y');
    }

    /**22-06-2016 00:00 AM
     * Present the start date
     *
     * @return mixed
     */
    public function startDateInput()
    {
        return $this->start_date->format('d-m-Y h:m A');
    }

    /**
     * Present the end date
     *
     * @return mixed
     */
    public function endDateInput()
    {
        return $this->end_date->format('d-m-Y h:m A');
    }

    /**
     * Present the due date
     *
     * @return mixed
     */
    public function dueDateInput()
    {
        return $this->due_date->format('d-m-Y');
    }
}