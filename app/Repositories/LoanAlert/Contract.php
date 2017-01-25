<?php

namespace Whatsloan\Repositories\LoanAlert;

interface Contract
{

    /**
     * Get the loan alert values
     *
     * @param int $limit
     * @return mixed
     */
    public function show($userId);
    
    /**
     * Update the Loan alert
     *
     * @param $request
     * @param $uuid
     * @return mixed
     */
    public function update($request, $uuid);

    public function store ($request);
    
}
