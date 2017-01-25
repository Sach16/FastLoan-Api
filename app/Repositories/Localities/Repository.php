<?php

namespace Whatsloan\Repositories\Localities;

class Repository implements Contract
{
    /**
     * @var State
     */
    private $locality;

    /**
     * Repository constructor.
     * @param Locality $locality
     */
    public function __construct(Locality $locality)
    {
        $this->locality = $locality;
    }

    /**
     * Get a paginated list of all localities
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15)
    {
        return $this->locality->paginate($limit);
    }

    /**
     * Update an existing locality
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function update(array $request, $id)
    {
        $locality = $this->locality->withTrashed()->find($id);
        return $locality->update($request);
    }

    /**
     * Store a new locality
     *
     * @param array $request
     * @return mixed
     */
    public function store(array $request)
    {
        return $this->locality->create($request);
    }
}
