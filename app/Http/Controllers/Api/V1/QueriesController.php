<?php

namespace Whatsloan\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Api\V1\StoreLsrQueryRequest;
use Whatsloan\Http\Requests\Api\V1\UpdateLsrQueryRequest;
use Whatsloan\Http\Transformers\V1\QueryTransformer;
use Whatsloan\Repositories\Queries\Contract as Queries;
use Whatsloan\Jobs\StoreQueryJob;
use Whatsloan\Jobs\UpdateQueryJob;
use Whatsloan\Http\Transformers\V1\ResourceCreated;
use Whatsloan\Http\Transformers\V1\ResourceUpdated;


class QueriesController extends Controller
{
    
    
    /**
     * @var $queries 
     */
    private $queries;

    /**
     * Controller constructor
     * @param Queries $queries
     */
    public function __construct(Queries $queries)
    {
        $this->queries = $queries;
    }
    
    
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
      $queries = $this->queries->paginate();
      return $this->transformCollection($queries,QueryTransformer::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(StoreLsrQueryRequest $request)
    {
        $query = $this->dispatch(new StoreQueryJob($request->all()));
        return $this->transformItem($query, ResourceCreated::class, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateLsrQueryRequest $request, $uuid)
    {
        $query = $this->dispatch(new UpdateQueryJob($uuid,$request->all()));
        return $this->transformItem($query, ResourceUpdated::class, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
