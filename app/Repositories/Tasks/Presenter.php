<?php

namespace Whatsloan\Repositories\Tasks;

class Presenter extends \Laracasts\Presenter\Presenter
{

    /**
     * @return mixed
     */
    public function fromDate()
    {
        return $this->from->format('d-m-Y h:i A');
    }

    /**
     * @return mixed
     */
    public function toDate()
    {
        return $this->to->format('d-m-Y h:i A');
    }

    /**
     * @return mixed
     */
    public function fromInput()
    {
        return $this->from->format('d-m-Y H:i A');
    }

    /**
     * @return mixed
     */
    public function toInput()
    {
        return $this->to->format('d-m-Y H:i A');
    }
}
