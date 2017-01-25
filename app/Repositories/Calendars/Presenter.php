<?php

namespace Whatsloan\Repositories\Calendars;

class Presenter extends \Laracasts\Presenter\Presenter
{

    /**
     * @return mixed
     */
    public function dateInput()
    {
        return $this->date->format('Y-m-d');
    }
}