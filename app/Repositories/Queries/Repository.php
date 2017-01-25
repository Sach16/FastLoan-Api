<?php

namespace Whatsloan\Repositories\Queries;

use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Repositories\Users\User;

class Repository implements Contract
{

    /**
     *
     * @var Query
     */
    private $query;

    /**
     * Query repository constructor
     * @param Query $query
     */
    public function __construct(Query $query)
    {
        $this->query = $query;
    }

    /**
     *
     * @param type $limit
     */
    public function paginate($limit = 15)
    {
        return $this->query->with(['project', 'assignee'])->paginate($limit);
    }

    /**
     * Store LSR query
     * @param array $data
     */
    public function store($data)
    {

        $data['project_id']  = Project::whereUuid($data['project_uuid'])->first()->id;
        $data['assigned_to'] = User::whereUuid($data['assigned_to'])->first()->id;
        $data['uuid']        = uuid();

        $query = new Query($data);
        $query->save();

        return $query;
    }

    /**
     * Store LSR query
     * @param array $data
     */
    public function update($queryUuid, $data)
    {

        $data['project_id']  = Project::whereUuid($data['project_uuid'])->first()->id;
        $data['assigned_to'] = User::whereUuid($data['assigned_to'])->first()->id;

        $query = $this->query->whereUuid($queryUuid)->first();
        $query->update($data);

        return $query;
    }

    /**
     * @param $request
     * @param $queryId
     * @return mixed
     */
    public function updateAsAdmin($request, $queryId)
    {
        return $this->query->find($queryId)->update([
            'project_id' => $request['project_id'],
            'query'      => $request['query'],
            'start_date' => $request['start_date'],
            'end_date'   => $request['end_date'],
            'due_date'   => $request['end_date'],
            'status'     => $request['status'],
        ]);
    }

    /**
     * Store a query as admin
     *
     * @param array $request
     * @return mixed
     */
    public function storeAsAdmin(array $request)
    {
        return $this->query->create([
            'uuid'        => uuid(),
            'query'       => $request['query'],
            'project_id'  => $request['project_id'],
            'assigned_to' => 1,
            'start_date'  => $request['start_date'],
            'end_date'    => $request['end_date'],
            'due_date'    => $request['end_date'],
            'status'      => $request['status'],
        ]);
    }
}
