<?php

namespace Whatsloan\Repositories\Loans;

class Presenter extends \Laracasts\Presenter\Presenter
{

    /**
     * @return mixed
     */
    public function amountAsCurrency()
    {
        return money_format('%!i', $this->amount);
    }
}