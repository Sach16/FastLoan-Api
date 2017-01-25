<?php

namespace Whatsloan\Repositories\Payouts;

class Repository implements Contract
{

    /**
     * @var State
     */
    private $payout;

    /**
     * Repository constructor.
     * @param State $state
     */
    public function __construct(Payout $payout)
    {
        $this->payout = $payout;
    }

    /**
     * Get a paginated list of all states
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15)
    {
        return $this->payout->with(['builder', 'user'])->orderBy('updated_at', 'desc')->paginate($limit);
    }

    /**
     * Update an existing state
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function update(array $request, $id)
    {
        $state = $this->payout->withTrashed()->find($id);
        return $state->update($request);
    }

    /**
     * Store a new state
     *
     * @param array $request
     * @return mixed
     */
    public function store(array $request)
    {
        return $this->payout->create($request);
    }

    /**
     * Get Single payout
     * 
     * @param array $request
     * @return mixed
     */
    public function find($id)
    {
        return $this->payout->withTrashed()->find($id);
    }

}
