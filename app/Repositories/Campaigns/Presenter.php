<?php

namespace Whatsloan\Repositories\Campaigns;

class Presenter extends \Laracasts\Presenter\Presenter
{

    /**
     * Present the campaign type
     *
     * @return mixed
     */
    public function ctype()
    {
        return ucfirst(str_replace('_', ' ', strtolower($this->type)));
    }

    /**
     * Present the from date
     *
     * @return mixed
     */
    public function fromDate()
    {
        return $this->from->format('d-m-Y h:i A');
    }

    /**
     * Present the to date
     *
     * @return mixed
     */
    public function toDate()
    {
        return $this->to->format('d-m-Y h:i A');
    }

    /**
     * Present the from date
     *
     * @return mixed
     */
    public function fromInput()
    {
        return $this->from->format('d-m-Y H:i A');
    }

    /**
     * Present the to date
     *
     * @return mixed
     */
    public function toInput()
    {
        return $this->to->format('d-m-Y H:i A');
    }
}