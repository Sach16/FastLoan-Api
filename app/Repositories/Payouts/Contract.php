<?php

namespace Whatsloan\Repositories\Payouts;

interface Contract
{

    /**
     * Get a paginated list of all payouts
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15);

    /**
     * Update an existing payout
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function update(array $request, $id);

    /**
     * Store a new payout
     * 
     * @param array $request
     * @return mixed
     */
    public function store(array $request);

    /**
     * Get Single payout
     * 
     * @param integer $id
     * @return mixed
     */
    public function find($id);
}
