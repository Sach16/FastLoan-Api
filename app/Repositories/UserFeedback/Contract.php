<?php

namespace Whatsloan\Repositories\UserFeedback;

interface Contract
{

    /**
     * Get a paginated list of 
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15);
}
