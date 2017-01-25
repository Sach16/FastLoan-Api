<?php

namespace Whatsloan\Repositories\Designations;

class Repository implements Contract
{
    /**
     * @var Designation
     */
    private $designation;

    /**
     * Repository constructor.
     * @param Designation $designation
     */
    public function __construct(Designation $designation)
    {
        $this->designation = $designation;
    }

    /**
     * Get a paginated list of all designations
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15)
    {
        return $this->designation->paginate($limit);
    }

    /**
     * Update an existing designation
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function update(array $request, $id)
    {
        $designation = $this->designation->find($id);
        return $designation->update($request);
    }

    /**
     * Store a new designation
     *
     * @param array $request
     * @return mixed
     */
    public function store(array $request)
    {
        return $this->designation->create($request);
    }
}
