<?php

namespace Whatsloan\Repositories\States;

class Repository implements Contract
{
    /**
     * @var State
     */
    private $state;

    /**
     * Repository constructor.
     * @param State $state
     */
    public function __construct(State $state)
    {
        $this->state = $state;
    }

    /**
     * Get a paginated list of all states
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15)
    {
        return $this->state->paginate($limit);
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
        $state = $this->state->find($id);
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
        return $this->state->create($request);
    }
}
