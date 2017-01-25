<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Queries\Query;

class QueryTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = [
        'assignee'
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [
        'project'
    ];

    /**
     * @param $e
     * @return array
     */
    public function transform(Query $query)
    {
        return [
            'uuid' => $query->uuid,
            'query' => $query->query,
            'start_date' => $query->start_date,
            'end_date' => $query->end_date,
            'due_date' => $query->due_date,
            'status' => $query->status,
            'pending_with' => $query->pending_with,
            'raised_date' => $query->raised_date,
        ];
    }

    /**
     * Include project
     * @param Query $query
     * @return type
     */
    public function includeProject(Query $query)
    {
        return $this->item($query->project, new ProjectTransformer);
    }

    
    /**
     * Include Assignee
     * @param Query $query
     * @return type
     */
    public function includeAssignee(Query $query)
    {
        return $this->item($query->assignee, new UserTransformer);
    }

}
